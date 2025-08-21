<?php

require_once __DIR__ . '/../models/mail.php';


class MailController
{

    public function envoyerRefus($pseudo, $email, $dateDepart)
    {

        ob_start();
        require __DIR__ . '/../utils/models_mail/mail_refus.php';
        $contenu = ob_get_clean();
        $mail = new Mail;
        $envoi = $mail->envoyer($email, $pseudo, "ECORIDE - Réservation refusée", $contenu);

        return $envoi;
    }
    public function envoyerAcceptation($pseudo, $email, $dateDepart)
    {
        ob_start();
        require __DIR__ . '/../utils/models_mail/mail_acceptation.php';
        $contenu = ob_get_clean();
        $mail = new Mail();
        $envoi = $mail->envoyer($email, $pseudo, "ECORIDE - Réservation acceptée", $contenu);
        return $envoi;
    }
    public function envoyerAnnulation($pseudo, $email, $dateDepart, $conducteurPseudo)
    {

        ob_start();
        require __DIR__ . '/../utils/models_mail/mail_annulation.php';
        $contenu = ob_get_clean();
        $mail = new Mail;
        $envoi = $mail->envoyer($email, $pseudo, "ECORIDE - Covoiturage annulé", $contenu);
        return $envoi;
    }
    public function envoyerFeedback($pseudo, $email)
    {

        ob_start();
        require __DIR__ . '/../utils/models_mail/mail_feedback.php';
        $contenu = ob_get_clean();
        $mail = new Mail;
        $envoi = $mail->envoyer($email, $pseudo, "ECORIDE - Covoiturage terminé", $contenu);
        return $envoi;
    }
}
