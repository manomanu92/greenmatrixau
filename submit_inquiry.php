<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /');
    exit;
}

function clean_input($value) {
    return trim(strip_tags($value));
}

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

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'info@greenmatrixsolar.au';
    $mail->Password = 'YOUR_HOSTINGER_SMTP_PASSWORD';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    $mail->setFrom('info@greenmatrixsolar.au', 'Green Matrix Solar Pty Ltd');
    $mail->addAddress('info@greenmatrixsolar.au');
    $mail->addReplyTo($emailAddress, $firstName . ' ' . $lastName);

    $mail->Subject = 'New Solar Enquiry from ' . $firstName . ' ' . $lastName;
    $mail->Body = "New Solar Enquiry\n" .
        "==================\n\n" .
        "First Name: {$firstName}\n" .
        "Last Name: {$lastName}\n" .
        "Email Address: {$emailAddress}\n" .
        "Company / Organisation: " . ($companyName !== '' ? $companyName : 'Not provided') . "\n" .
        "Product Interest: " . ($productInterest !== '' ? $productInterest : 'Not specified') . "\n\n" .
        "Message:\n" . ($message !== '' ? $message : 'No message provided') . "\n";

    $mail->send();
    echo 'Thank you. Your enquiry has been sent successfully.';
} catch (Exception $e) {
    http_response_code(500);
    echo 'There was a problem sending your enquiry. Please email info@greenmatrixsolar.au directly.';
}
