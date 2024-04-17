<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "beauty";

if (isset($_POST["send"])) {
    if (isset($_POST["email"])) {
        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ds.perera.test@gmail.com';
        $mail->Password = 'ycfdgqfhinumrzjx';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // Email content
        $mail->setFrom('centos-migrate@hsenid.com');
        $mail->addAddress('darshana.saluka.pc@gmail.com'); // Additional recipient
        $mail->isHTML(true);
        $mail->Subject = "Saukya Contact Us form - " . $_POST["name"];
        $mail->Body = "Name: " . $_POST["name"] . "<br>"
            . "Phone Number: " . $_POST["phone-number"] . "<br>"
            . "Email: " . $_POST["email"] . "<br>"
            . "Message: " . $_POST["message"];

        try {
            // Send email
            $mail->send();

            // Send text email
            $textMail = new PHPMailer(true);
            $textMail->isSMTP();
            $textMail->Host = 'smtp.gmail.com';
            $textMail->SMTPAuth = true;
            $textMail->Username = 'ds.perera.test@gmail.com';
            $textMail->Password = 'ycfdgqfhinumrzjx';
            $textMail->SMTPSecure = 'ssl';
            $textMail->Port = 465;
            $textMail->setFrom('centos-migrate@hsenid.com');
            $textMail->addAddress($_POST["email"]);
            // $mail->addAddress($_POST["email"]);
            $textMail->Subject = "Thank you for contacting us";
            $textMail->Body = "Dear " . $_POST["name"] . ",\n\nThank you for contacting us. We will get back to you soon.\n\nBest regards,\nThe Saukya Team";

            $textMail->send();

            // Store form data in MySQL database
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("INSERT INTO beautyRequests (name, phone_number, email, message) VALUES (:name, :phone_number, :email, :message)");
            $stmt->bindParam(':name', $_POST["name"]);
            $stmt->bindParam(':phone_number', $_POST["phone-number"]);
            $stmt->bindParam(':email', $_POST["email"]);
            $stmt->bindParam(':message', $_POST["message"]);
            $stmt->execute();

            // Additional functions
            // Function 1: Log email sending
            logEmailSending($_POST["name"], $_POST["email"]);

            // Function 2: Send a notification
            sendNotification($_POST["name"], $_POST["email"]);

            echo "<script>
            document.location.href = 'Thankyou.html';
            </script>";
        } catch (Exception $e) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
    } else {
        echo "Email field not found in the form submission.";
    }
}

// Function to log email sending
function logEmailSending($name, $email)
{
    // Implement your logging logic here
    // For example, you can insert a log entry into a database or write to a log file
    // This function will be called after the email is sent successfully
}

// Function to send a notification
function sendNotification($name, $email)
{
    // Implement your notification logic here
    // For example, you can send a notification to the admin or another email address
    // This function will be called after the email is sent successfully
}
