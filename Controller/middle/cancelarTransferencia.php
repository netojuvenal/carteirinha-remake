<?php

require_once __DIR__ . '/../classes/NotificationController.php';

$idDest = (int)($_POST['idDestinatario'] ?? 0);
$controller = new NotificationController();
$response = $controller->cancelarTransferencia($idDest);

if (is_array($response)) {
    // controller retorna array padrão
    echo json_encode(['status' => $response['status'] ? 'success' : 'error', 'message' => $response['message']]);
    exit();
}

// retro-compatibilidade: se boolean
echo json_encode(['status' => $response ? 'success' : 'error']);
exit();
