<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /');
    exit;
}

function clean_input($value) {
    return trim(strip_tags($value));
}

$to = 'info@greenmatrixsolar.au';
$firstName = clean_input($_POST['firstName'] ?? '');
$lastName = clean_input($_POST['lastName'] ?? '');
$emailAddress = clean_input($_POST['emailAddress'] ?? '');
$companyName = clean_input($_POST['companyName'] ?? '');
$productInterest = clean_input($_POST['productInterest'] ?? '');
$message = clean_input($_POST['message'] ?? '');

if ($firstName === '' || $lastName === '' || $emailAddress === '') {
    http_response_code(400);
    echo 'Please complete the required fields.';
    exit;
}

if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo 'Please enter a valid email address.';
    exit;
}

$subject = 'New Solar Enquiry from ' . $firstName . ' ' . $lastName;

$body = "New Solar Enquiry\n";
$body .= "==================\n\n";
$body .= "First Name: {$firstName}\n";
$body .= "Last Name: {$lastName}\n";
$body .= "Email Address: {$emailAddress}\n";
$body .= "Company / Organisation: " . ($companyName !== '' ? $companyName : 'Not provided') . "\n";
$body .= "Product Interest: " . ($productInterest !== '' ? $productInterest : 'Not specified') . "\n\n";
$body .= "Message:\n" . ($message !== '' ? $message : 'No message provided') . "\n";

$headers = array(
    'From: Green Matrix Solar Pty Ltd <info@greenmatrixsolar.au>',
    'Reply-To: ' . $emailAddress,
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'X-Mailer: PHP/' . phpversion()
);

if (mail($to, $subject, $body, implode("\r\n", $headers))) {
    echo 'Thank you. Your enquiry has been sent successfully.';
} else {
    http_response_code(500);
    echo 'There was a problem sending your enquiry. Please email info@greenmatrixsolar.au directly.';
}
