<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Method not allowed.']);
    exit;
}

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
if (!$email) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

$to = 'info@dscpls.co';
$subject = 'ATELIER — New newsletter signup';
$safeEmail = str_replace(["\r", "\n"], '', $email);
$body = "A new visitor joined the ATELIER newsletter.\n\nEmail: {$safeEmail}\n\nSubmitted: " . gmdate('Y-m-d H:i:s') . " UTC\n";
$host = isset($_SERVER['HTTP_HOST']) ? preg_replace('/[^A-Za-z0-9.-]/', '', $_SERVER['HTTP_HOST']) : 'localhost';
$fromDomain = ($host && $host !== 'localhost') ? $host : 'dscpls.co';
$headers = [
    'From: ATELIER Website <newsletter@' . $fromDomain . '>',
    'Reply-To: ' . $safeEmail,
    'Content-Type: text/plain; charset=UTF-8',
    'X-Mailer: PHP/' . phpversion()
];

$sent = @mail($to, $subject, $body, implode("\r\n", $headers));
if (!$sent) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Signup could not be sent. Please try again.']);
    exit;
}

echo json_encode(['ok' => true]);
