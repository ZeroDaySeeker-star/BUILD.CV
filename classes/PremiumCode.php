<?php
/**
 * Class PremiumCode
 * Gère la génération, validation et utilisation des codes Premium
 */
class PremiumCode {
    
    // Antibruteforce settings
    const MAX_ATTEMPTS = 5;
    const ATTEMPT_WINDOW_MINUTES = 15;

    /**
     * Générer un code unique aléatoire
     */
    private static function generateRandomString($length = 12) {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // No I, O, 0, 1 for readability
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }
        // Format: XXXX-XXXX-XXXX
        $parts = str_split($code, 4);
        return 'PREM-' . implode('-', $parts);
    }

    /**
     * Génère un ou plusieurs codes
     * 
     * @param int $planId L'ID du plan associé au code
     * @param int $durationMonths La durée de l'abonnement en mois (1 = 30 jours par défaut)
     * @param int $maxUses Nombre maximum d'utilisations par code (1 par défaut)
     * @param string|null $expiresAt Date d'expiration du code (opt)
     * @param int $count Nombre de codes à générer (1 par défaut)
     * @return array La liste des codes générés
     */
    public static function generate($planId, $durationMonths = 1, $maxUses = 1, $expiresAt = null, $count = 1) {
        $codes = [];
        $createdAt = date('Y-m-d H:i:s');
        
        $sql = "INSERT INTO premium_codes (code, plan_id, duration_months, max_uses, expires_at, created_at, status) 
                VALUES (?, ?, ?, ?, ?, ?, 'active')";
        
        for ($i = 0; $i < $count; $i++) {
            $code = self::generateRandomString();
            
            try {
                db()->query($sql, [
                    $code,
                    $planId,
                    $durationMonths,
                    $maxUses,
                    $expiresAt,
                    $createdAt
                ]);
                $codes[] = $code;
            } catch (PDOException $e) {
                // If unique constraint fails (very rare), retry this iteration
                if ($e->getCode() == 23000) {
                    $i--; 
                } else {
                    throw $e;
                }
            }
        }
        
        return $codes;
    }

    /**
     * Check if IP/User is blocked due to bruteforce
     */
    public static function isBlocked($ipAddress, $userId = null) {
        $timeLimit = date('Y-m-d H:i:s', strtotime('-' . self::ATTEMPT_WINDOW_MINUTES . ' minutes'));
        
        $sql = "SELECT COUNT(*) as attempts FROM code_attempts WHERE ip_address = ? AND attempt_time > ?";
        $params = [$ipAddress, $timeLimit];
        
        if ($userId) {
            $sql .= " OR (user_id = ? AND attempt_time > ?)";
            $params[] = $userId;
            $params[] = $timeLimit;
        }
        
        $result = db()->fetchOne($sql, $params);
        return $result && $result['attempts'] >= self::MAX_ATTEMPTS;
    }

    /**
     * Record a failed attempt
     */
    public static function recordAttempt($ipAddress, $userId = null) {
        db()->query("INSERT INTO code_attempts (ip_address, user_id) VALUES (?, ?)", [$ipAddress, $userId]);
    }

    /**
     * Validates a code without redeeming it
     * 
     * @return array [success => bool, error => string|null, code_data => array|null]
     */
    public static function validate($code) {
        $codeData = db()->fetchOne("SELECT * FROM premium_codes WHERE code = ?", [$code]);
        
        if (!$codeData) {
            return ['success' => false, 'error' => 'Code invalide.'];
        }
        
        if ($codeData['status'] !== 'active') {
            return ['success' => false, 'error' => 'Ce code est inactif ou a déjà été utilisé.'];
        }
        
        if ($codeData['used_count'] >= $codeData['max_uses']) {
            return ['success' => false, 'error' => 'Ce code a atteint sa limite d\'utilisation.'];
        }
        
        if ($codeData['expires_at'] && strtotime($codeData['expires_at']) < time()) {
            return ['success' => false, 'error' => 'Ce code a expiré.'];
        }
        
        return ['success' => true, 'code_data' => $codeData];
    }

    /**
     * Redeem a code for a user
     */
    public static function redeem($userId, $code, $ipAddress = '') {
        // Anti-Bruteforce check
        if (self::isBlocked($ipAddress, $userId)) {
            return [
                'success' => false, 
                'error' => 'Trop de tentatives échouées. Veuillez réessayer dans quelques minutes.'
            ];
        }

        // Validate code properties
        $validation = self::validate($code);
        if (!$validation['success']) {
            self::recordAttempt($ipAddress, $userId);
            return $validation;
        }

        $codeData = $validation['code_data'];

        // Check if user already used this specific code (if max_uses > 1, prevent same user double-dip)
        $alreadyUsed = db()->fetchOne("SELECT id FROM code_usages WHERE code_id = ? AND user_id = ?", [$codeData['id'], $userId]);
        if ($alreadyUsed) {
            return ['success' => false, 'error' => 'Vous avez déjà utilisé ce code.'];
        }

        try {
            // Begin transaction
            $conn = db()->getConnection();
            $conn->beginTransaction();

            // 1. Calculate dates
            $plan = db()->fetchOne("SELECT * FROM plans WHERE id = ?", [$codeData['plan_id']]);
            if (!$plan) {
                throw new Exception("Plan introuvable pour ce code.");
            }

            // Current subscription
            $sub = db()->fetchOne("SELECT * FROM subscriptions WHERE user_id = ?", [$userId]);
            
            $now = time();
            $startDate = date('Y-m-d H:i:s');
            $durationDays = $codeData['duration_months'] * 30; // 30 days per month
            
            // If user already has an active subscription, extend from its end date OR from now if it's expired
            if ($sub && $sub['status'] === 'active' && strtotime($sub['end_date']) > $now) {
                $endDate = date('Y-m-d H:i:s', strtotime($sub['end_date'] . " + {$durationDays} days"));
                
                // Keep the best plan if stacking on a different plan? Let's just update to the code's plan
                db()->query("UPDATE subscriptions SET plan_id = ?, status = 'active', end_date = ? WHERE id = ?", 
                    [$codeData['plan_id'], $endDate, $sub['id']]);
            } else {
                $endDate = date('Y-m-d H:i:s', strtotime(" + {$durationDays} days"));
                if ($sub) {
                    db()->query("UPDATE subscriptions SET plan_id = ?, status = 'active', start_date = ?, end_date = ? WHERE id = ?", 
                        [$codeData['plan_id'], $startDate, $endDate, $sub['id']]);
                } else {
                    db()->query("INSERT INTO subscriptions (user_id, plan_id, status, start_date, end_date) VALUES (?, ?, 'active', ?, ?)", 
                        [$userId, $codeData['plan_id'], $startDate, $endDate]);
                }
            }

            // 2. Log usage
            db()->query("INSERT INTO code_usages (code_id, user_id, ip_address) VALUES (?, ?, ?)", 
                [$codeData['id'], $userId, $ipAddress]);

            // 3. Update code counts
            $newCount = $codeData['used_count'] + 1;
            $newStatus = ($newCount >= $codeData['max_uses']) ? 'used' : 'active';
            
            db()->query("UPDATE premium_codes SET used_count = ?, status = ? WHERE id = ?", 
                [$newCount, $newStatus, $codeData['id']]);
            
            // 4. Update memory session plan if applicable
            $_SESSION['plan'] = $plan['name'];
            $_SESSION['plan_level'] = $plan['position'];
            
            // Clear attempts on success
            db()->query("DELETE FROM code_attempts WHERE ip_address = ? OR user_id = ?", [$ipAddress, $userId]);

            $conn->commit();

            return [
                'success' => true, 
                'message' => 'Code validé avec succès ! Votre abonnement ' . htmlspecialchars($plan['display_name']) . ' a été activé pour ' . $durationDays . ' jours.'
            ];

        } catch (Exception $e) {
            if (isset($conn)) {
                $conn->rollBack();
            }
            if (DEBUG) error_log("Redeem error: " . $e->getMessage());
            return ['success' => false, 'error' => 'Une erreur est survenue lors de l\'activation: ' . $e->getMessage()];
        }
    }
}
