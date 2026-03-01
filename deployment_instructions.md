# 🚀 Guide de Déploiement Hostinger - BUILD.CV

Voici les instructions précises pour déployer votre plateforme sur Hostinger. Le code a été audité, nettoyé et sécurisé.

## 📦 Fichiers prêts pour le déploiement
J'ai généré deux fichiers spécifiques très importants pour Hostinger :
1. `hostinger_export.sql` : La base de données vierge (sans fausses données, sans utilisateurs tests) mais avec la structure exacte et les offres Premium/Standard créées par défaut.
2. `config/config.production.php` : Un modèle pré-configuré des éléments de base.

## 🛠 Instructions Exactes de Mise en Ligne

### Étape 1 : Base de Données (MySQL)
1. Connectez-vous à votre panel Hostinger (hPanel).
2. Allez dans **Bases de données > Bases de données MySQL** et créez une nouvelle base (ex: `buildcv_db`) avec un utilisateur associé et un mot de passe très fort.
3. Allez dans **phpMyAdmin** et cliquez sur votre nouvelle base de données.
4. Cliquez sur **Importer**, sélectionnez le fichier `hostinger_export.sql` et cliquez sur "Go". Votre base est prête.

### Étape 2 : Fichiers
1. Dans Hostinger, allez dans **Gestionnaire de fichiers**.
2. Ouvrez le dossier `public_html`.
3. Supprimez les fichiers inutiles locaux avant d'envoyer (`.gemini/`, `.git/`, `README.md`, `tests/` etc..).
4. Uploadez **tout le contenu de votre dossier BUILD.CV** directement dans `public_html/`. (Ne le mettez pas dans un sous-dossier BUILD.CV, mettez les fichiers à la racine).

### Étape 3 : Configuration
1. Dans Hostinger (Gestionnaire de fichiers), allez dans `public_html/config/`.
2. Renommez le fichier `config.production.php` en `config.php`.
3. Ouvrez `config.php` et modifiez les lignes suivantes :
   - `APP_URL` : Mettez votre nom de domaine (ex: `https://votre-domaine.com`).
   - `DB_NAME`, `DB_USER`, `DB_PASS` : Mettez les infos de l'étape 1.
   - `ADMIN_PASSWORD` : Changez le mot de passe "admin123" par un mot de passe très complexe.
   - `GEMINI_API_KEY` : Remettez votre clé d'API Google Gemini.

### Étape 4 : Serveur et PHP
1. Dans Hostinger, allez dans **Avancé > Configuration PHP**.
2. Assurez-vous que la **Version PHP est sur 8.1 ou 8.2**.
3. Assurez-vous que les extensions `pdo_mysql`, `curl`, et `mbstring` sont cochées.

---

## ✅ Liste des problèmes corrigés pendant l'audit technique
- **Sécurité CSRF** : Ajout de jetons de sécurité sur tous les formulaires restants (`dashboard/settings.php` et `dashboard/portfolio.php`) pour éviter les piratages de session.
- **Injections SQL** : Le wrapper PDO `db()->query()` et `db()->fetchAll()` protège bien toute l'application. Les requêtes ont été vérifiées.
- **Performances** : Création d'un fichier `.htaccess` surpuissant :
  - *Compression GZIP* activée (chargements plus rapides des CSS/JS).
  - *Cache navigateur* activé pour forcer le stockage des images et du code dans les navigateurs des visiteurs (1 an).
- **Sécurité Serveur** : Le `.htaccess` bloque désormais l'accès direct aux fichiers de configuration (`config.php`, `.env`, `schema.sql`) et masque la navigation dans les dossiers.

## ⚠️ Points à surveiller / Recommandations post-déploiement
- **Certificat SSL** : Mettez bien votre site en "Forcer HTTPS" dans les réglages Hostinger une fois le domaine lié. Le fichier `.htaccess` contient déjà la ligne de code (commentée) que vous pouvez activer plus tard.
- **Sauvegardes (Backups)** : La base de données stockera les portefeuilles des utilisateurs. Activez les sauvegardes automatiques hebdomadaires sur Hostinger.

Le site est maintenant **professionnel, propre, sécurisé et prêt pour un lancement réel**. Félicitations ! 🎉
