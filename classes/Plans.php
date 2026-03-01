<?php
/**
 * Plans Management - SaaS Plan Limits Management
 * Gère les plans d'abonnement et les limites de création
 */

class Plans {
    
    /**
     * Récupère le plan actuel d'un utilisateur
     */
    public static function getUserPlan($userId) {
        if (!$userId) return null;
        
        $result = db()->fetchOne(
            'SELECT p.* FROM plans p
             JOIN subscriptions s ON s.plan_id = p.id
             WHERE s.user_id = ? AND s.status = "active"
             ORDER BY s.start_date DESC LIMIT 1',
            [$userId]
        );
        
        return $result ?? self::getDefaultPlan();
    }
    
    /**
     * Récupère le plan par défaut (gratuit)
     */
    public static function getDefaultPlan() {
        return db()->fetchOne(
            'SELECT * FROM plans WHERE name = ? LIMIT 1',
            ['free']
        );
    }
    
    /**
     * Récupère un plan par ID
     */
    public static function getPlanById($planId) {
        return db()->fetchOne(
            'SELECT * FROM plans WHERE id = ? AND is_active = 1',
            [$planId]
        );
    }
    
    /**
     * Récupère un plan par nom
     */
    public static function getPlanByName($name) {
        return db()->fetchOne(
            'SELECT * FROM plans WHERE name = ? AND is_active = 1',
            [$name]
        );
    }
    
    /**
     * Récupère tous les plans actifs
     */
    public static function getAllPlans() {
        return db()->fetchAll(
            'SELECT * FROM plans WHERE is_active = 1 ORDER BY position ASC'
        );
    }
    
    /**
     * Vérifie si l'utilisateur a atteint la limite de CV
     */
    public static function hasReachedCvLimit($userId) {
        // Single-CV architecture: always returns false (each user has exactly one CV)
        return false;
    }
    
    /**
     * Vérifie si l'utilisateur a atteint la limite de portfolios
     */
    public static function hasReachedPortfolioLimit($userId) {
        // Single-portfolio architecture: always returns false (each user has exactly one portfolio)
        return false;
    }
    
    /**
     * Récupère les limites actuelles de l'utilisateur
     */
    public static function getUserLimits($userId) {
        $plan = self::getUserPlan($userId);
        if (!$plan) return [
            'plan_name' => 'free',
            'plan_display_name' => 'Gratuit',
            'is_unlimited' => false,
        ];
        
        return [
            'plan_name'         => $plan['name'] ?? 'free',
            'plan_display_name' => $plan['display_name'] ?? ucfirst($plan['name'] ?? 'Gratuit'),
            'is_unlimited'      => (bool)($plan['is_unlimited'] ?? false),
        ];
    }
    
    /**
     * Crée une nouvelle souscription pour un utilisateur
     */
    public static function createSubscription($userId, $planId, $startDate = null) {
        $plan = self::getPlanById($planId);
        if (!$plan) return false;
        
        try {
            db()->query(
                'INSERT INTO subscriptions (user_id, plan_id, status, start_date, auto_renew)
                 VALUES (?, ?, ?, ?, 1)
                 ON DUPLICATE KEY UPDATE plan_id = ?, status = "active", start_date = NOW()',
                [$userId, $planId, 'active', $startDate ?? date('Y-m-d H:i:s'), $planId]
            );
            
            // Log action
            AuditLog::log($userId, 'subscription_created', 'plan', $planId, [
                'plan_name' => $plan['name'],
                'period' => 'monthly'
            ]);
            
            return true;
        } catch (Exception $e) {
            if (DEBUG) error_log('Subscription error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupère les templates disponibles pour un plan
     */
    public static function getAvailableTemplates($userId, $templateType = null) {
        // This function is deprecated - templates are now managed via dashboard/templates.php
        // Return empty array to avoid DB crashes on non-existent 'templates' table
        return [];
    }
}

/**
 * Audit Log - Traçabilité des actions
 */
class AuditLog {
    
    public static function log($userId, $action, $entityType, $entityId, $details = []) {
        try {
            db()->query(
                'INSERT INTO audit_logs (user_id, action, entity_type, entity_id, details, ip_address, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, NOW())',
                [
                    $userId,
                    $action,
                    $entityType,
                    $entityId,
                    json_encode($details),
                    $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]
            );
        } catch (Exception $e) {
            if (DEBUG) error_log('Audit log error: ' . $e->getMessage());
        }
    }
    
    public static function getUserLogs($userId, $limit = 50) {
        return db()->fetchAll(
            'SELECT * FROM audit_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT ?',
            [$userId, $limit]
        );
    }
}
