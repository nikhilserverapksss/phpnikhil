<?php
/**
 * SRC Module - Device Logging API (Vercel Serverless)
 * Endpoint: /api/device_log
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

// Simple counter (Vercel doesn't support file writes, use database in production)
// For demo, just return success
$deviceCount = rand(1, 100); // Random count for demo

http_response_code(200);
echo json_encode([
    'status' => 'ok',
    'device_count' => $deviceCount,
    'message' => 'Device logged'
]);
?>
