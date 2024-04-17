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

try {
    // Create a new PDO connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL query to select all records from the beautyRequests table
    $stmt = $conn->query("SELECT * FROM beautyRequests");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Function to send email and store the request
function sendEmail($data) {
    global $conn;

    try {
        // Store the request in the sendRequest table
        $stmt = $conn->prepare("INSERT INTO sendRequest (name, phone_number, email, message) VALUES (:name, :phone_number, :email, :message)");
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':phone_number', $data['phone_number']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':message', $data['message']);
        $stmt->execute();

        // Send email
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
        $mail->addAddress($data['email']);
        $mail->isHTML(true);
        $mail->Subject = "Confirmation";
        $mail->Body = "Dear Customer,\n\nThank you for your interest in our services.\n\nBest regards,\nThe Beauty Team";

        $mail->send();
        echo "<script>alert('Email sent successfully');</script>";
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sendEmail"])) {
    $data = [
        'name' => $_POST["name"],
        'phone_number' => $_POST["phone-number"],
        'email' => $_POST["email"],
        'message' => $_POST["message"]
    ];
    sendEmail($data);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Beauty Requests</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Beauty Requests</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Phone Number</th>
            <th>Email</th>
            <th>Message</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['message']); ?></td>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="name" value="<?php echo $row['name']; ?>">
                        <input type="hidden" name="phone-number" value="<?php echo $row['phone_number']; ?>">
                        <input type="hidden" name="email" value="<?php echo $row['email']; ?>">
                        <input type="hidden" name="message" value="<?php echo $row['message']; ?>">
                        <button type="submit" name="sendEmail">Send Email</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>

