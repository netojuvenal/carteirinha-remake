<?php

session_start();
require_once __DIR__ . '/../classes/CardapioController.php';

$idJustificativa = 0;
$justificativa = $_POST['justificativa'] ?? '';
$idUser = (int)($_POST['idUser'] ?? 0);
$diaDaSemana = $_POST['diaDaSemana'] ?? '';

if ($justificativa === "outro") {
    $idJustificativa = 4;
    $justificativa = $_POST["outro"] ?? '';
} else {
    $idJustificativa = match ($justificativa) {
        "contra-turno" => 1,
        "transporte"   => 2,
        "projeto"      => 3,
        default        => null,
    };
}

$controller = new CardapioController();
$response = $controller->processarReserva($idUser, $idJustificativa, $justificativa, $diaDaSemana);

if ($response['status']) {
    // enviar e-mail de confirmação 
    $sent = $controller->sendEmailLunch($_SESSION['email'] ?? '', $_SESSION['name'] ?? '');
    if (!$sent) {
        header("Location: ../../View/cardapio.php?agendamento=emailerror");
        exit();
    }
    header("Location: ../../View/cardapio.php?agendamento=success");
    exit();
} else {
    header("Location: ../../View/cardapio.php?agendamento=error");
    exit();
}
