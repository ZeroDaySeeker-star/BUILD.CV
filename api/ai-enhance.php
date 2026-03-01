<?php
// API : AI-Powered Text Enhancement (OpenAI, Gemini, or Grok)
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
verifyApiCsrf();

header('Content-Type: application/json');

// ============================================================================
// 1. AUTHENTICATION & INPUT VALIDATION
// ============================================================================

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Non autorisé']);
    exit;
}

// Parse JSON input
$body = json_decode(file_get_contents('php://input'), true);
if (!is_array($body)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Requête invalide']);
    exit;
}

// Validate and sanitize input
$text  = trim($body['text'] ?? '');
$type  = trim($body['type'] ?? 'summary');
$targetLang = trim($body['target_lang'] ?? 'English');

// Block AI features for Free users (plan_level < 2)
if (($_SESSION['plan_level'] ?? 1) < 2) {
    http_response_code(403);
    echo json_encode(['error' => 'Les fonctionnalités IA sont réservées aux abonnements Premium. Mettez à niveau votre compte !']);
    exit;
}

// Special Premium Check (Plan Level 3) for advanced tools
$premiumOnlyTypes = ['ats_optimizer', 'translate', 'interview'];
if (in_array($type, $premiumOnlyTypes) && ($_SESSION['plan_level'] ?? 1) < 3) {
    http_response_code(403);
    echo json_encode(['error' => 'Cet outil est réservé au mode Premium uniquement.']);
    exit;
}

// Input validation
if (empty($text)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Aucun texte fourni']);
    exit;
}

if (strlen($text) > 2000) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Texte trop long (max 2000 caractères)']);
    exit;
}

// Validate type against whitelist
$validTypes = ['summary', 'experience', 'project', 'cover_letter', 'analysis', 'ats_optimizer', 'translate', 'tone_switch', 'interview'];
if (!in_array($type, $validTypes, true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Type invalide']);
    exit;
}

// ============================================================================
// 2. RATE LIMITING
// ============================================================================

$today = date('Y-m-d');
$sessionKey = "ai_calls_{$today}";
$dailyCallCount = $_SESSION[$sessionKey] ?? 0;
$maxCallsPerDay = 50;

if ($dailyCallCount >= $maxCallsPerDay) {
    http_response_code(429);
    echo json_encode([
        'success' => false,
        'error' => "Limite quotidienne atteinte ($maxCallsPerDay appels/jour). Réessayez demain."
    ]);
    exit;
}

// Increment call counter
$_SESSION[$sessionKey] = $dailyCallCount + 1;

// ============================================================================
// 3. CONTEXT DATA FETCHING (For Cover Letters & Analysis)
// ============================================================================

$userContext = "";
if (in_array($type, ['cover_letter', 'analysis', 'ats_optimizer'])) {
    $profile = db()->fetchOne("SELECT * FROM profiles WHERE user_id = ?", [$userId]);
    $exp = db()->fetchAll("SELECT * FROM experience WHERE user_id = ? ORDER BY start_date DESC", [$userId]);
    $edu = db()->fetchAll("SELECT * FROM education WHERE user_id = ?", [$userId]);
    $skills = db()->fetchAll("SELECT * FROM skills WHERE user_id = ?", [$userId]);
    
    $userContext = "USER PROFILE:\n";
    $userContext .= "Name: " . ($profile['full_name'] ?? 'N/A') . "\n";
    $userContext .= "Title: " . ($profile['title'] ?? 'N/A') . "\n";
    $userContext .= "Summary: " . ($profile['summary'] ?? 'N/A') . "\n\n";
    
    $userContext .= "EXPERIENCE:\n";
    foreach($exp as $e) $userContext .= "- {$e['position']} at {$e['company']}: {$e['description']}\n";
    
    $userContext .= "\nSKILLS:\n";
    foreach($skills as $s) $userContext .= "- {$s['skill_name']} ({$s['level']})\n";
}

// ============================================================================
// 4. PROMPT TEMPLATES
// ============================================================================

$prompts = [
    'summary'    => "You are a professional CV writer. Rewrite the following summary as a compelling, concise professional summary (2-3 sentences), highlighting key strengths. You MUST answer in French. RÉPONDEZ EXCLUSIVEMENT EN FRANÇAIS. Return ONLY the enhanced text.\n\nOriginal: $text",
    'experience' => "You are a professional CV writer. Expand the following work experience description into 2-4 professional bullet points or sentences using action verbs and specific details. You MUST answer in French. RÉPONDEZ EXCLUSIVEMENT EN FRANÇAIS. Return ONLY the enhanced text.\n\nOriginal: $text",
    'project'    => "You are a professional CV writer. Rewrite the following project description to be more compelling and professional (2-3 sentences), highlighting the purpose, technologies, and impact. You MUST answer in French. RÉPONDEZ EXCLUSIVEMENT EN FRANÇAIS. Return ONLY the enhanced text.\n\nOriginal: $text",
    'cover_letter' => "You are an expert career coach. Using the provided USER PROFILE and the JOB DESCRIPTION below, write a highly professional, persuasive cover letter in French. The tone should be confident and tailored to the job. RÉPONDEZ EXCLUSIVEMENT EN FRANÇAIS. Return ONLY the letter content.\n\n$userContext\n\nJOB DESCRIPTION:\n$text",
    'analysis'   => "You are an ATS (Applicant Tracking System) expert. Compare the USER PROFILE and the JOB DESCRIPTION below. identify: 1) Match Score (0-100), 2) Top 3 matching skills, 3) Top 3 missing keywords. Format the output professionally in French. RÉPONDEZ EXCLUSIVEMENT EN FRANÇAIS.\n\n$userContext\n\nJOB DESCRIPTION:\n$text",
    'ats_optimizer' => "You are an ATS Keyword Optimizer. Compare the USER PROFILE and the JOB DESCRIPTION below. List the EXACT keywords and professional terms from the job description that are MISSING or UNDER-REPRESENTED in the USER PROFILE. Provide a list of 5-10 keywords with a short explanation of where to add them. RÉPONDEZ EXCLUSIVEMENT EN FRANÇAIS.\n\n$userContext\n\nJOB DESCRIPTION:\n$text",
    'translate'  => "Act as a professional translator specializing in recruitment. Translate the following text into $targetLang, while maintaining professional industry terminology. Return ONLY the translated text.\n\nText: $text",
    'tone_switch' => "Rewrite the following professional text in a choice of these tones: [Startup/Modern, Executive/Formal, Academic]. Choose the most appropriate one if not specified or adapt the input. Return ONLY the rewritten text in French.\n\nText: $text",
    'interview'  => "Based on the following professional profile/experience, generate 5 challenging interview questions that a recruiter might ask. Provide a brief tip for each on how to answer. Return ONLY the questions and tips in French.\n\nProfile: $text",
];

$prompt = $prompts[$type] ?? $prompts['summary'];

// ============================================================================
// 5. API KEY & PROVIDER VALIDATION
// ============================================================================

if (empty(AI_API_KEY)) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Clé API non configurée pour le provider: ' . AI_PROVIDER
    ]);
    exit;
}

$enhanced = '';

// ============================================================================
// 6. CALL APPROPRIATE AI PROVIDER
// ============================================================================

if (AI_PROVIDER === 'grok') {
    // === GROK API (xAI) ===
    $url = GROK_API_URL;
    $payload = json_encode([
        'model'      => 'grok-2-1212',
        'messages'   => [['role' => 'user', 'content' => $prompt]],
        'max_tokens' => 800,
    ]);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . AI_API_KEY,
        ],
        CURLOPT_TIMEOUT        => 45,
        CURLOPT_SSL_VERIFYPEER => shouldVerifySSL(),
        CURLOPT_SSL_VERIFYHOST => shouldVerifySSL() ? 2 : 0,
    ]);

    $response  = curl_exec($ch);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Erreur réseau Grok: ' . $curlError]);
        exit;
    }

    $json = json_decode($response, true);
    if (isset($json['error'])) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Erreur Grok: ' . ($json['error']['message'] ?? $json['error'] ?? 'Inconnue')]);
        exit;
    }
    $enhanced = $json['choices'][0]['message']['content'] ?? '';


} elseif (AI_PROVIDER === 'gemini') {
    // === GEMINI API ===
    $url = GEMINI_API_URL . '?key=' . AI_API_KEY;
    $payload = json_encode([
        'contents' => [['parts' => [['text' => $prompt]]]]
    ]);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_TIMEOUT        => 45,
        CURLOPT_SSL_VERIFYPEER => shouldVerifySSL(),
        CURLOPT_SSL_VERIFYHOST => shouldVerifySSL() ? 2 : 0,
    ]);

    $response  = curl_exec($ch);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Erreur réseau Gemini: ' . $curlError]);
        exit;
    }

    $json = json_decode($response, true);
    if (!$json || isset($json['error'])) {
        $msg = $json['error']['message'] ?? 'Erreur inconnue de l\'API Gemini';
        $code = $json['error']['code'] ?? 500;
        
        // Détection du dépassement de quota (Google)
        if (strpos($msg, 'quota') !== false || strpos($msg, 'limit') !== false || $code == 429) {
            $msg = "Désolé, le quota quotidien de BUILD.CV pour l'IA est temporairement atteint. Réessayez dans quelques instants ou demain !";
        }
        
        // Specific helpful message for 404 Model Not Found
        if ($code == 404) {
            $msg = "Le modèle de langage (" . AI_PROVIDER . ") n'est pas disponible ou est mal configuré. Contactez l'administrateur.";
        }
        
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => "$msg", 'raw' => $json]);
        exit;
    }
    
    // Support multiple Gemini response formats (sometimes it's an array, sometimes an object)
    $candidates = $json['candidates'] ?? [];
    if (!empty($candidates)) {
        $enhanced = $candidates[0]['content']['parts'][0]['text'] ?? '';
    } else {
        $enhanced = $json['parts'][0]['text'] ?? '';
    }


} else {
    // === OPENAI API (default) ===
    $payload = json_encode([
        'model'      => 'gpt-4o-mini',
        'messages'   => [['role' => 'user', 'content' => $prompt]],
        'max_tokens' => 800,
    ]);

    $ch = curl_init(OPENAI_API_URL);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . AI_API_KEY,
        ],
        CURLOPT_TIMEOUT        => 45,
        CURLOPT_SSL_VERIFYPEER => shouldVerifySSL(),
        CURLOPT_SSL_VERIFYHOST => shouldVerifySSL() ? 2 : 0,
    ]);

    $response  = curl_exec($ch);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Erreur réseau OpenAI: ' . $curlError]);
        exit;
    }

    $json = json_decode($response, true);
    if (isset($json['error'])) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Erreur OpenAI: ' . ($json['error']['message'] ?? 'Inconnue')]);
        exit;
    }
    $enhanced = $json['choices'][0]['message']['content'] ?? '';
}

// ============================================================================
// 7. VALIDATE RESPONSE & RETURN
// ============================================================================

if (empty($enhanced)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Aucune réponse générée par l\'IA. Réessayez.']);
    exit;
}

// Success response
header('X-Calls-Remaining: ' . ($maxCallsPerDay - $_SESSION[$sessionKey]));
echo json_encode([
    'success' => true,
    'enhanced' => trim($enhanced),
    'provider' => AI_PROVIDER,
    'callsRemaining' => $maxCallsPerDay - $_SESSION[$sessionKey]
]);
