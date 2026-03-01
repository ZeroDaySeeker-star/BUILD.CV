<?php
// BUILD.CV - Global Configuration
// Load environment variables from .env file
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            if (!isset($_ENV[$key])) {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
}

// Application Configuration
define('APP_NAME', $_ENV['APP_NAME'] ?? 'BUILD.CV');
define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost/BUILD.CV');
define('APP_VERSION', $_ENV['APP_VERSION'] ?? '1.0.0');
define('APP_ENV', $_ENV['APP_ENV'] ?? 'production');
define('DEBUG', filter_var($_ENV['DEBUG'] ?? 'false', FILTER_VALIDATE_BOOLEAN));

// Error Reporting & Logging Setup
if (DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
    
    // Log errors to a safe file
    $logFile = __DIR__ . '/../storage/logs/error.log';
    if (!is_dir(dirname($logFile))) {
        mkdir(dirname($logFile), 0755, true);
    }
    ini_set('log_errors', 1);
    ini_set('error_log', $logFile);
}

// AI Configuration
define('AI_PROVIDER', $_ENV['AI_PROVIDER'] ?? 'openai'); // 'openai', 'gemini', or 'grok'
define('OPENAI_API_KEY', $_ENV['OPENAI_API_KEY'] ?? '');
define('GEMINI_API_KEY', $_ENV['GEMINI_API_KEY'] ?? '');
define('GROK_API_KEY', $_ENV['GROK_API_KEY'] ?? '');

// Select API key based on provider
$apiKeyMap = [
    'openai' => OPENAI_API_KEY,
    'gemini' => GEMINI_API_KEY,
    'grok' => GROK_API_KEY,
];
define('AI_API_KEY', $apiKeyMap[AI_PROVIDER] ?? '');

// AI API URLs
define('OPENAI_API_URL', 'https://api.openai.com/v1/chat/completions');
define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent');
define('GROK_API_URL', 'https://api.x.ai/v1/chat/completions');

// File Upload Settings
define('UPLOAD_DIR', __DIR__ . '/../assets/uploads/');
define('UPLOAD_URL', APP_URL . '/assets/uploads/');
define('MAX_FILE_SIZE', (int)($_ENV['MAX_FILE_SIZE'] ?? 5242880)); // 5MB default
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);

// Session Settings
define('SESSION_LIFETIME', (int)($_ENV['SESSION_LIFETIME'] ?? 2592000)); // 30 days default

// Pagination
define('ITEMS_PER_PAGE', (int)($_ENV['ITEMS_PER_PAGE'] ?? 10));

// Plan Limits
define('FREE_PLAN_CV_TEMPLATES', ['minimal', 'professional', 'modern', 'compact']);
define('FREE_PLAN_PORTFOLIO_TEMPLATES', ['portfolio_minimal', 'portfolio_developer', 'dark', 'gallery']);

// Error Reporting (set to 0 in production)
if (APP_ENV === 'production') {
    error_reporting(0);
    ini_set('display_errors', 0);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', DEBUG ? 1 : 0);
}

// Helper function to get AI provider API URL
function getAiApiUrl() {
    return match(AI_PROVIDER) {
        'openai' => OPENAI_API_URL,
        'gemini' => GEMINI_API_URL,
        'grok' => GROK_API_URL,
        default => OPENAI_API_URL,
    };
}

// Helper function to verify SSL based on environment
function shouldVerifySSL() {
    return APP_ENV !== 'development' && APP_ENV !== 'local';
}

// ==========================================
// CSRF Protection Helpers
// ==========================================
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken($token) {
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        return false;
    }
    return true;
}

function csrfField() {
    $token = generateCsrfToken();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}

function verifyApiCsrf() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        return true;
    }
    
    $token = '';
    // Check $_SERVER first (PHP standard CGI way)
    if (isset($_SERVER['HTTP_X_CSRF_TOKEN'])) {
        $token = $_SERVER['HTTP_X_CSRF_TOKEN'];
    } elseif (function_exists('getallheaders')) {
        $headers = getallheaders();
        foreach ($headers as $key => $value) {
            if (strtolower($key) === 'x-csrf-token') {
                $token = $value;
                break;
            }
        }
    }
    
    if (!verifyCsrfToken($token)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Jeton de sécurité CSRF invalide (API).']);
        exit;
    }
    return true;
}

// Auto-load classes
$classDir = __DIR__ . '/../classes/';
if (is_dir($classDir)) {
    foreach (glob($classDir . '*.php') as $classFile) {
        require_once $classFile;
    }
}

// Start session securely
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => SESSION_LIFETIME,
        'path' => '/',
        'domain' => '', 
        'secure' => (APP_ENV === 'production'), // True in production (HTTPS)
        'httponly' => true, // Prevents JavaScript access to session cookie
        'samesite' => 'Lax' // Protects against CSRF
    ]);
    session_start();
}

/**
 * Robustly parses basic Markdown (bold, italic, lists, newlines) into HTML.
 * Used for formatting AI-generated text or user inputs consistently across the platform.
 */
function parse_markdown_to_html($text) {
    if (empty($text)) return '';
    
    // Decode HTML entities in case the text was previously saved with htmlspecialchars
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    
    // Normalize newlines
    $text = str_replace("\r\n", "\n", $text);
    
    // Bold: **text** - preventing cross-line matching by omitting 's' modifier
    $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
    // Italic: *text* (must not have space after opening * or before closing *)
    $text = preg_replace('/(?<!\*)\*(?!\s)(.*?)(?<!\s)\*(?!\*)/', '<em>$1</em>', $text);
    
    // Lists processing
    $lines = explode("\n", $text);
    $inList = false;
    $htmlLines = [];
    
    foreach ($lines as $line) {
        // Match bullet points: *, -, or + at the start of the line or after spaces
        if (preg_match('/^\s*(?:[\*\-\+])\s+(.+)$/', $line, $matches)) {
            if (!$inList) {
                $htmlLines[] = "<ul style='margin-top:4px; margin-bottom:4px; padding-left:18px;'>";
                $inList = true;
            }
            $htmlLines[] = "<li style='margin-bottom:3px;'>" . trim($matches[1]) . "</li>";
        } else {
            if ($inList && trim(strip_tags($line)) !== '') {
                $htmlLines[] = "</ul>";
                $inList = false;
            }
            // Only add the line if it's not empty or we aren't in a list
            if (trim($line) !== '' || !$inList) {
                $htmlLines[] = $line;
            }
        }
    }
    if ($inList) {
        $htmlLines[] = "</ul>";
    }
    
    // Instead of imploding with newlines (which nl2br catches), we implode with a space if it's a list item
    // Actually, implode with \n is fine, but we'll modify the nl2br step.
    $html = implode("\n", $htmlLines);
    
    // Convert remaining newlines to <br> that are NOT inside <ul> or <li>
    // We achieve this safely by splitting the HTML tags and only modifying text nodes
    $tokens = preg_split('/(<[^>]*>)/', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
    $result = '';
    $inBlockTag = false;
    
    foreach ($tokens as $token) {
        if (preg_match('/^<(ul|li|ol|p|div|h[1-6]).*>$/i', $token)) {
            $inBlockTag = true;
            $result .= $token;
        } elseif (preg_match('/^<\/(ul|li|ol|p|div|h[1-6])>$/i', $token)) {
            $inBlockTag = false;
            $result .= $token;
        } elseif (preg_match('/^<.*>$/', $token)) {
            $result .= $token;
        } else {
            if (!$inBlockTag) {
                // If it's a newline directly following a </li>, don't apply nl2br
                // We'll just trim it to remove the \n, or use nl2br depending
                $token = nl2br($token);
            }
            $result .= $token;
        }
    }
    
    // Final cleanup for <br /> directly after </li> or before </ul>
    $result = preg_replace('/<\/li>\s*<br\s*\/?>/i', '</li>', $result);
    $result = preg_replace('/<ul[^>]*>\s*<br\s*\/?>/i', '<ul>', $result);
    $result = preg_replace('/<br\s*\/?>\s*<\/ul>/i', '</ul>', $result);
    
    return trim($result);
}
