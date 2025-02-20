<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
</head>
<body>
<form action="register.php" method="post">
    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" required><br>
    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" required><br>
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br>
    <label for="offer">Receive Offers:</label>
    <input type="checkbox" id="offer" name="offer"><br>
    <button type="submit">Register</button>
</form>
</body>
</html>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $offer = isset($_POST['offer']) ? 1 : 0;
    $email_confirm = 0;

    require_once '../DoNotShare/db.php';

    try {
        $pdo = $GLOBALS['conn'];
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insertion des données dans la base de données
        $stmt = $pdo->prepare('INSERT INTO users (firstname, lastname, username, email, password, email_confirm, offer) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$firstname, $lastname, $username, $email, $password, $email_confirm, $offer]);

        // Envoi de l'e-mail de confirmation
        $mail = new PHPMailer(true);

        try {
            // Configuration du serveur SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com'; // Remplacez par votre serveur SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@example.com'; // Remplacez par votre adresse e-mail
            $mail->Password = 'your_password'; // Remplacez par votre mot de passe
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Destinataires
            $mail->setFrom('your_email@example.com', 'Your Name');
            $mail->addAddress($email);

            // Contenu de l'e-mail
            $mail->isHTML(true);
            $mail->Subject = 'Confirmation d\'inscription';
            $mail->Body = 'Merci de vous être inscrit. Veuillez cliquer sur le lien suivant pour confirmer votre inscription : <a href="https://example.com/confirm.php?email=' . urlencode($email) . '">Confirmer l\'inscription</a>';

            $mail->send();
            echo 'Un e-mail de confirmation a été envoyé.';
        } catch (Exception $e) {
            echo "L'e-mail n'a pas pu être envoyé. Erreur: {$mail->ErrorInfo}";
        }
    } catch (PDOException $e) {
        echo "Erreur de connexion à la base de données: " . $e->getMessage();
    }
}
?>