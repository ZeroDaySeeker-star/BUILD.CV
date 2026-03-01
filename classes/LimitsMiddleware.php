<?php
/**
 * Middleware de vérification des limites et accès
 * Vérifications obligatoires avant toute opération de création
 */

class LimitsMiddleware {
    
    /**
     * Vérifie les limites de création de CV
     */
    public static function checkCvCreation() {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            return [
                'success' => false,
                'error' => 'Non authentifié',
                'requires_login' => true
            ];
        }
        
        $userId = $_SESSION['user_id'];
        
        // Vérification 1: Profil existe
        $profile = db()->fetchOne('SELECT id FROM profiles WHERE user_id = ?', [$userId]);
        if (!$profile) {
            http_response_code(403);
            return [
                'success' => false,
                'error' => 'Vous devez d\'abord créer un profil'
            ];
        }
        
        // Vérification 2: Souscription active
        $subscription = db()->fetchOne(
            'SELECT s.* FROM subscriptions s WHERE s.user_id = ? AND s.status = "active"',
            [$userId]
        );
        if (!$subscription) {
            http_response_code(402);
            return [
                'success' => false,
                'error' => 'Aucun plan actif',
                'requires_subscription' => true
            ];
        }
        
        // Vérification 3: Limite atteinte
        if (Plans::hasReachedCvLimit($userId)) {
            http_response_code(402);
            return [
                'success' => false,
                'error' => 'Vous avez atteint la limite de CV de votre plan',
                'limits' => Plans::getUserLimits($userId),
                'requires_upgrade' => true
            ];
        }
        
        return ['success' => true];
    }
    
    /**
     * Vérifie les limites de création de portfolio
     */
    public static function checkPortfolioCreation() {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            return [
                'success' => false,
                'error' => 'Non authentifié',
                'requires_login' => true
            ];
        }
        
        $userId = $_SESSION['user_id'];
        
        // Vérification 1: Profil existe
        $profile = db()->fetchOne('SELECT id FROM profiles WHERE user_id = ?', [$userId]);
        if (!$profile) {
            http_response_code(403);
            return [
                'success' => false,
                'error' => 'Vous devez d\'abord créer un profil'
            ];
        }
        
        // Vérification 2: Souscription active
        $subscription = db()->fetchOne(
            'SELECT s.* FROM subscriptions s WHERE s.user_id = ? AND s.status = "active"',
            [$userId]
        );
        if (!$subscription) {
            http_response_code(402);
            return [
                'success' => false,
                'error' => 'Aucun plan actif',
                'requires_subscription' => true
            ];
        }
        
        // Vérification 3: Limite atteinte
        if (Plans::hasReachedPortfolioLimit($userId)) {
            http_response_code(402);
            return [
                'success' => false,
                'error' => 'Vous avez atteint la limite de portfolios de votre plan',
                'limits' => Plans::getUserLimits($userId),
                'requires_upgrade' => true
            ];
        }
        
        return ['success' => true];
    }
    
    /**
     * Vérifie l'accès à un template
     */
    public static function checkTemplateAccess($userId, $templateId) {
        $template = db()->fetchOne('SELECT * FROM templates WHERE id = ?', [$templateId]);
        if (!$template) {
            http_response_code(404);
            return [
                'success' => false,
                'error' => 'Template non trouvé'
            ];
        }
        
        $plan = Plans::getUserPlan($userId);
        if (!$plan) {
            http_response_code(403);
            return [
                'success' => false,
                'error' => 'Aucun plan associé'
            ];
        }
        
        // Vérifier l'accès selon le plan
        if ($template['plan_required'] !== 'free') {
            if ($template['plan_required'] === 'premium' && $plan['name'] !== 'premium') {
                http_response_code(403);
                return [
                    'success' => false,
                    'error' => 'Ce template n\'est disponible que pour le plan Premium',
                    'requires_upgrade' => true
                ];
            }
            if ($template['plan_required'] === 'standard' && !in_array($plan['name'], ['standard', 'premium'])) {
                http_response_code(403);
                return [
                    'success' => false,
                    'error' => 'Ce template nécessite le plan Standard ou supérieur',
                    'requires_upgrade' => true
                ];
            }
        }
        
        return ['success' => true, 'template' => $template];
    }
    
    /**
     * Vérifie que l'utilisateur est propriétaire d'une ressource
     */
    public static function checkOwnership($userId, $resourceType, $resourceId) {
        $table = null;
        $userColumn = 'user_id';
        
        switch ($resourceType) {
            case 'cv':
                $table = 'cvs';
                break;
            case 'portfolio':
                $table = 'portfolios';
                break;
            default:
                return false;
        }
        
        if (!$table) return false;
        
        $resource = db()->fetchOne(
            "SELECT id FROM $table WHERE id = ? AND $userColumn = ?",
            [$resourceId, $userId]
        );
        
        return (bool)$resource;
    }
}
