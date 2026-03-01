<?php
/**
 * Profile Guard - Empêche la création de plusieurs profils par utilisateur (1:1)
 * Vérifie et crée le profil automatiquement à la première connexion
 */

class ProfileGuard {
    
    /**
     * Vérifie et crée le profil s'il n'existe pas (appelé après register/login)
     */
    public static function ensureProfileExists($userId) {
        $profile = db()->fetchOne('SELECT id FROM profiles WHERE user_id = ?', [$userId]);
        
        if ($profile) {
            return true; // Profil existe déjà
        }
        
        // Créer le profil automatiquement
        try {
            $user = db()->fetchOne('SELECT * FROM users WHERE id = ?', [$userId]);
            if (!$user) return false;
            
            db()->query(
                'INSERT INTO profiles (user_id, full_name, email, created_at, updated_at)
                 VALUES (?, ?, ?, NOW(), NOW())',
                [$userId, $user['full_name'], $user['email']]
            );
            
            AuditLog::log($userId, 'profile_auto_created', 'profile', null);
            
            return true;
        } catch (Exception $e) {
            if (DEBUG) error_log('Profile creation error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Empêche la création manuelle d'un deuxième profil
     */
    public static function blockMultipleProfiles($userId) {
        $profile = db()->fetchOne('SELECT id FROM profiles WHERE user_id = ?', [$userId]);
        
        if ($profile) {
            return [
                'success' => false,
                'error' => 'Vous avez déjà un profil. Un utilisateur ne peut avoir qu\'un seul profil personnel.',
                'profile_id' => $profile['id']
            ];
        }
        
        return ['success' => true];
    }
    
    /**
     * Crée une souscription au plan gratuit par défaut
     */
    public static function ensureSubscription($userId) {
        $subscription = db()->fetchOne(
            'SELECT * FROM subscriptions WHERE user_id = ?',
            [$userId]
        );
        
        if ($subscription) {
            return true; // Souscription existe
        }
        
        // Récupérer le plan gratuit
        $freePlan = db()->fetchOne('SELECT id FROM plans WHERE name = "free"');
        if (!$freePlan) {
            if (DEBUG) error_log('Free plan not found');
            return false;
        }
        
        // Créer la souscription
        try {
            db()->query(
                'INSERT INTO subscriptions (user_id, plan_id, status, start_date, auto_renew)
                 VALUES (?, ?, "active", NOW(), 1)',
                [$userId, $freePlan['id']]
            );
            
            AuditLog::log($userId, 'subscription_created', 'subscription', $freePlan['id']);
            
            return true;
        } catch (Exception $e) {
            if (DEBUG) error_log('Subscription creation error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Hook appelé après l'enregistrement
     */
    public static function onUserRegistered($userId) {
        self::ensureProfileExists($userId);
        self::ensureSubscription($userId);
    }
    
    /**
     * Hook appelé après la connexion
     */
    public static function onUserLogin($userId) {
        self::ensureProfileExists($userId);
        self::ensureSubscription($userId);
    }
}
