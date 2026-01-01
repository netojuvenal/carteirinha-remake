<?php

require_once __DIR__ . '/Controller.php';

class ContatoController extends Controller
{
    public function sendEmailContact($content, $title)
    {
        require_once(__DIR__ . "/../../libs/phpmailer/vendor/autoload.php");

        if (session_status() === PHP_SESSION_NONE) { session_start(); }

        $senderEmail = 'refeicao@leds.net.br';
        $senderPassword = '';
        $recipientName = 'Refeição IFBA Seabra';
        $senderName = $_SESSION['name'] ?? 'Usuário';
        $host = 'email-ssl.com.br';

        $body = '
        <!DOCTYPE html>
        <html>
        <p style="color:black;">
            Olá, Você recebeu uma nova mensagem do formulário de contato.<br><br>
            Nome: '. htmlspecialchars($senderName) .' <br>
            Email: '. htmlspecialchars($_SESSION['email'] ?? '') .' <br>
            Telefone: '. htmlspecialchars($_SESSION['telefone'] ?? '') .'<br>
            Matricula: '. htmlspecialchars($_SESSION['enrollment'] ?? '') .' <br>
            <br>
            '. htmlspecialchars($content) .'
        </p>
        </html>
        ';

        try {
            $mail = new PHPMailer();
            $mail->setLanguage('pt_br', '../libs/phpmailer/vendor/phpmailer/phpmailer/language/');
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->Host = $host;
            $mail->SMTPAuth = true;
            $mail->Port = 465;
            $mail->Username = $senderEmail;
            $mail->setFrom($senderEmail, $senderName);
            $mail->Password = $senderPassword;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->addAddress($senderEmail, $recipientName);
            $mail->isHTML(true);
            $mail->Subject = $title;
            $mail->Body = $body;
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
