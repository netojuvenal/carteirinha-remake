<?php

require_once __DIR__ . '/../classes/CardapioController.php';

$idUser = (int)($_POST['idUser'] ?? 0);
$motivo = $_POST['motivo'] ?? '';

$controller = new CardapioController();
$response = $controller->cancelarReserva($idUser, $motivo);

if ($response['status']) {
    echo json_encode(['status' => 'success', 'message' => $response['message']]); exit();
} else {
    echo json_encode(['status' => 'error', 'message' => $response['message']]); exit();
}
