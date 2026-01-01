<?php

require_once __DIR__ . '/../classes/NotificationController.php';

$idDest = (int)($_POST['idDestinatario'] ?? 0);
$controller = new NotificationController();
$response = $controller->aceitarRefeicao($idDest);

if ($response['status']) {
    echo json_encode(['status' => 'success', 'message' => $response['message']]); exit();
} else {
    echo json_encode(['status' => 'error', 'message' => $response['message']]); exit();
}
