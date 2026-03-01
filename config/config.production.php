<?php
// BUILD.CV - Hostinger Production Configuration Template
// Renommez ce fichier en "config.php" ou copiez ces valeurs dans votre .env sur Hostinger

// 1. URL DE L'APPLICATION (Très important pour les assets et redirections)
// Remplacez par votre vrai nom de domaine (avec https)
define('APP_URL', 'https://votre-domaine.com');
define('APP_NAME', 'BUILD.CV');
define('APP_ENV', 'production');
define('DEBUG', false); // Désactiver le mode debug en production !

// 2. CONFIGURATION DE LA BASE DE DONNÉES HOSTINGER
// Remplacez ces valeurs par celles fournies dans votre panel Hostinger (Bases de données MySQL)
$_ENV['DB_HOST'] = 'localhost'; // Souvent localhost sur Hostinger
$_ENV['DB_NAME'] = 'u123456789_buildcv'; // Nom de la base de données Hostinger
$_ENV['DB_USER'] = 'u123456789_user'; // Utilisateur de la base de données
$_ENV['DB_PASS'] = 'VotreMotDePasseComplexe123!'; // Mot de passe de la base de données

// 3. MOT DE PASSE ADMINISTRATION
$_ENV['ADMIN_PASSWORD'] = 'MotDePasseAdminTresSecurise';

// 4. API GEMINI
$_ENV['AI_PROVIDER'] = 'gemini';
$_ENV['GEMINI_API_KEY'] = 'VOTRE_CLÉ_API_GEMINI_ICI';

// === NE PAS MODIFIER LA LIGNE CI-DESSOUS ===
// Cette ligne charge la configuration de base de l'application
require_once __DIR__ . '/config.base.php';
