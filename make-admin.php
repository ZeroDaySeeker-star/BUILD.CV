<?php
// Script temporaire pour donner les droits d'administration au premier utilisateur
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

try {
    // 1. Ajouter la colonne is_admin si elle n'existe pas
    try {
        db()->query("ALTER TABLE users ADD COLUMN is_admin TINYINT(1) DEFAULT 0");
        echo "Colonne is_admin ajoutée avec succès.<br>";
    } catch (Exception $e) {
        // La colonne existe probablement déjà, on ignore l'erreur
        echo "La colonne is_admin existe déjà.<br>";
    }

    if (!isset($_SESSION['user_id'])) {
        die("Vous devez être connecté à votre compte BUILD.CV pour pouvoir vous donner les droits d'administrateur. Veuillez d'abord vous connecter.");
    }
    
    $userId = $_SESSION['user_id'];

    // 2. Donner les droits à l'utilisateur actuel
    db()->query("UPDATE users SET is_admin = 1 WHERE id = ?", [$userId]);
    
    // 3. Mettre à jour la session
    $_SESSION['is_admin'] = true;
    
    echo "<h2>Succès !</h2>";
    echo "Votre compte est maintenant Administrateur.<br>";
    echo "<a href='" . APP_URL . "/admin/generate-code.php'>=> Aller au panneau Admin</a>";
    
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
// N'oubliez pas de supprimer ce fichier après usage !
?>
