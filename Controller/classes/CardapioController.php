<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../../Model/classes/CardapioModel.php';

class CardapioController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new CardapioModel();
    }

    // Métodos públicos usados pelos middle
    public function getCardapio()
    {
        return $this->model->getCardapio();
    }

    public function getTime()
    {
        return $this->model->getTime();
    }

    public function hasRefeicao($idUser, $current_day)
    {
        return $this->model->hasRefeicao($idUser, $current_day);
    }

    public function hasTransferencia($idUser)
    {
        return $this->model->transferenciaIsActive($idUser);
    }

    public function getRefeicaoById($idUser)
    {
        return $this->model->getRefeicaoById($idUser);
    }

    public function getCardapioById($idCardapio)
    {
        return $this->model->getCardapioById($idCardapio);
    }

    public function getIdCardapio($diaDaSemana)
    {
        return $this->model->getIdCardapio($diaDaSemana);
    }

    // processarReserva usado por agendarAlmoco.php 
    public function processarReserva($idUser, $idJustificativa, $justificativa, $diaDaSemana)
    {
        date_default_timezone_set('America/Sao_Paulo');
        $idCardapio = $this->model->getIdCardapio($diaDaSemana);
        if ($idCardapio === null) {
            return ['status' => false, 'message' => 'Cardápio não encontrado para o dia'];
        }

        $statusRef = 1;
        $dataSolicitacao = date("Y-m-d");
        $horaSolicitacao = date("H:i:s");

        $result = $this->model->setMeal($idUser, $idCardapio, $statusRef, $idJustificativa, $dataSolicitacao, $horaSolicitacao, $justificativa);

        if ($result) {
            return ['status' => true, 'message' => 'Refeição agendada com sucesso!'];
        } else {
            return ['status'=> false, 'message'=> 'Houve um problema ao agendar a sua refeição. Por favor, tente novamente mais tarde!'];
        }
    }

    public function cancelarReserva($idUser, $motivo)
    {
        if ($this->model->isActive($idUser)) {
            if ($this->model->cancelarReserva($idUser, $motivo)) {
                return ['status' => true, 'message' => 'Reserva cancelada com sucesso'];
            } else {
                return ['status' => false, 'message' => 'Falha ao cancelar a reserva'];
            }
        } else {
            return ['status' => false, 'message' => 'Reserva não encontrada ou já foi cancelada'];
        }
    }

    public function excluirCardapio()
    {
        if ($this->model->excluirCardapio()) {
            return ['status' => true, 'message' => 'Cardápio excluído com sucesso!'];
        } else {
            return ['status' => false, 'message' => 'Falha ao excluir cardápio!'];
        }
    }

    // Métodos auxiliares que o middle usa
    public function getIdByMatricula($matricula)
    {
        return $this->model->getIdByMatricula($matricula);
    }

    public function getTagsCardapio()
    {
        return $this->model->getTagsCardapio();
    }

    public function criarTag($tagName, $restricoes, $gluten, $lactose)
    {
        return $this->model->criarTag($tagName, $restricoes, $gluten, $lactose);
    }

    public function salvarCardapioSemana($dados)
    {
        return $this->model->salvarCardapioSemana($dados);
    }

    public function getRefeicoesConfirmadas()
    {
        return $this->model->getRefeicoesConfirmadas();
    }

    public function sendEmailLunch($recipientEmail, $recipientName)
    {
        require_once( __DIR__ . "/../../libs/phpmailer/vendor/autoload.php");

        date_default_timezone_set('America/Sao_Paulo');
        $currente_day = date('d/m/Y');

        $senderEmail = 'refeicao@leds.net.br';
        $senderName = 'Refeição IFBA Seabra';
        $senderPassword = '';
        $title = 'Indicação de alimentação no campus - IFBA Seabra';
        $host = 'email-ssl.com.br';
        
        $content = '
        <!DOCTYPE html>
        <html>
        <p style="color:black;">
            Olá,<br><br>
            Sua confirmação para o almoço no IFBA Campus Seabra para a data de '. $currente_day .' foi realizada com sucesso! <br><br>
            Em caso de dúvidas ou para relatar algum problema, envie um novo e-mail para este endereço.<br><br>
            Atenciosamente,<br>
            Suporte Almoço IFBA Seabra
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
            $mail->addAddress($recipientEmail, $recipientName);
            $mail->isHTML(true);
            $mail->Subject = $title;
            $mail->Body = $content;
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
