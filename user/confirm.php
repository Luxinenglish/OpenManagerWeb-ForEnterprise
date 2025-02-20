<?php
require 'vendor/autoload.php';
require_once '../DoNotShare/db.php';

if (isset($_GET['email'])) {
    $email = urldecode($_GET['email']);

    try {
        $pdo = new PDO(DB_SERVER, DB_USERNAME, DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Update the email_confirm field to 1 for the given email
        $stmt = $pdo->prepare('UPDATE users SET email_confirm = 1 WHERE email = ?');
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            echo 'Your email has been confirmed successfully.';
        } else {
            echo 'Invalid confirmation link or email already confirmed.';
        }
    } catch (PDOException $e) {
        echo "Database connection error: " . $e->getMessage();
    }
} else {
    echo 'No email provided for confirmation.';
}
?>