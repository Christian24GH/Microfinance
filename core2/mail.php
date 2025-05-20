<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientIds = $_POST['client_ids'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $messageTemplate = $_POST['message'] ?? '';
    $attachment = $_FILES['attachment'] ?? null;

    $clientIdsArray = array_filter(array_map('trim', explode(',', $clientIds)), 'is_numeric');

    if (empty($clientIdsArray)) {
        die("Invalid or empty client IDs provided.");
    }

    require_once 'config/db.php';

    $placeholders = implode(',', array_fill(0, count($clientIdsArray), '?'));
    $sql = "SELECT client_id, first_name, middle_name, last_name, email, city, province FROM client_info WHERE client_id IN ($placeholders)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $types = str_repeat('i', count($clientIdsArray));
    $stmt->bind_param($types, ...$clientIdsArray);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'trulend2025@gmail.com';
            $mail->Password = 'hbzwztbrvcczeevo';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];

            $mail->setFrom('trulend2025@gmail.com', 'Tru-Lend');
            $mail->isHTML(true);
            $mail->Subject = $subject;

            if ($attachment && !empty($attachment['tmp_name'])) {
                $mail->addAttachment($attachment['tmp_name'], $attachment['name']);
            }

            while ($row = $result->fetch_assoc()) {
                $recipientEmail = $row['email'];
                $fullName = $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'];
                $city = $row['city'];
                $province = $row['province'];

                $personalizedMessage = str_replace(
                    ['{{first_name}}', '{{middle_name}}', '{{last_name}}', '{{full_name}}', '{{city}}', '{{province}}'],
                    [$row['first_name'], $row['middle_name'], $row['last_name'], $fullName, $city, $province],
                    $messageTemplate
                );

                try {
                    $mail->clearAddresses();
                    $mail->addAddress($recipientEmail);
                    $mail->Body = nl2br(htmlspecialchars($personalizedMessage));
                    $mail->AltBody = strip_tags($personalizedMessage);
                    $mail->send();

              $logSql = "INSERT INTO email_logs (recipient_email, subject, message, status, send_date) VALUES (?, ?, ?, 'SENT', NOW())";
$logStmt = $conn->prepare($logSql);
if ($logStmt) {
    $logStmt->bind_param('sss', $recipientEmail, $subject, $personalizedMessage);
    $logStmt->execute();
}

                } catch (Exception $e) {
                    error_log("Email to {$recipientEmail} failed: {$mail->ErrorInfo}");
                }
            }

            echo "<script>alert('Emails sent successfully!'); window.history.back();</script>";
        } catch (Exception $e) {
            echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('No selected users found.'); window.history.back();</script>";
    }

} else {
    echo "Invalid request.";
}
?>
