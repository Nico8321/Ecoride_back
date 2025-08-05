<?php
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail
{
    private $mail;
    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->config();
    }
    private function config()
    {
        // Configuration SMTP Gmail
        $this->mail->isSMTP();
        $this->mail->Host       = 'smtp.gmail.com';
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = $_ENV['EMAIL'];         // ← Ton adresse Gmail
        $this->mail->Password   = $_ENV['PASSWORD_MAIL']; // ← Le mot de passe d'application
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port       = 587;
        $this->mail->setFrom($_ENV['EMAIL'], 'EcoRide');
    }
    public  function envoyer($toEmail, $toName, $sujet, $contenu)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($toEmail, $toName);
            $this->mail->isHTML(true);
            $this->mail->Subject = trim($sujet);;
            $this->mail->Body = $contenu;
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erreur envoi mail : {$this->mail->ErrorInfo}");
            return false;
        }
    }
}
