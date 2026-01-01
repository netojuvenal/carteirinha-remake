<?php

require_once __DIR__ . '/../classes/AdmController.php';

$hora = $_POST['hora'] ?? '';
$adm = new AdmController();
$response = $adm->editarHorario($hora);

$encodedMessage = base64_encode($response['message'] ?? '');

if ($response['status']) {
    header("Location: ../../View/painel-administrador.php?status=success&message=" . urlencode($encodedMessage));
    exit();
} else {
    header("Location: ../../View/editar-horario.php?status=error&message=" . urlencode($encodedMessage));
    exit();
}
