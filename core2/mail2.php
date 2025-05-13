<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientIds = $_POST['client_ids'] ?? ''; // Comma-separated list of selected client IDs
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    $attachment = $_FILES['attachment'] ?? null;  // Handle file attachment

    // Split client IDs and retrieve emails
    $clientIdsArray = explode(',', $clientIds);

    // Fetch emails from the database for the selected client IDs
    require_once 'config/db.php'; // Connect to DB

    // Prepare the SQL query to fetch emails for the selected client IDs
    $placeholders = implode(',', array_fill(0, count($clientIdsArray), '?'));
    $sql = "SELECT email FROM client_references WHERE client_ref_id IN ($placeholders)";
    $stmt = $conn->prepare($sql);

    // Bind the client IDs to the query
    $stmt->bind_param(str_repeat('i', count($clientIdsArray)), ...$clientIdsArray);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'trulendcomms2025@gmail.com'; // Your Gmail address
            $mail->Password = 'ojjqzlryiurpxlfg';  // Your app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Disable SSL certificate verification (for debugging purposes)
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            // Sender's email address
            $mail->setFrom('trulendcomms2025@gmail.com', 'Tru-Lend');
            $mail->isHTML(true); // Set email format to HTML

            // Email content
            $mail->Subject = $subject;
            $mail->Body = nl2br(htmlspecialchars($message));
            $mail->AltBody = strip_tags($message);

            // Attach image if available
            if ($attachment && !empty($attachment['tmp_name'])) {
                $mail->addAttachment($attachment['tmp_name'], $attachment['name']);
            }

            // Loop through all emails and send the email to each
            while ($row = $result->fetch_assoc()) {
                $recipientEmail = $row['email'];

                // Set recipient for each email
                $mail->clearAddresses();  // Clear any previously added addresses
                $mail->addAddress($recipientEmail);

                // Send email to the current recipient
                if ($mail->send()) {
                    // Log the email send into the database
                    $logSql = "INSERT INTO email_logs (recipient_email, subject, message, status) VALUES (?, ?, ?, 'SENT')";
                    $logStmt = $conn->prepare($logSql);
                    $logStmt->bind_param('sss', $recipientEmail, $subject, $message);
                    $logStmt->execute();
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
