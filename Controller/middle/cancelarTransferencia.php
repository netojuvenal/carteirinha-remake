<?php

require_once __DIR__ . '/../classes/NotificationController.php';

$idDest = (int)($_POST['idDestinatario'] ?? 0);
$controller = new NotificationController();
$response = $controller->cancelarTransferencia($idDest);

if (is_array($response)) {
    echo json_encode(['status' => $response['status'] ? 'success' : 'error', 'message' => $response['message']]);
    exit();
}

echo json_encode(['status' => $response ? 'success' : 'error']);
exit();