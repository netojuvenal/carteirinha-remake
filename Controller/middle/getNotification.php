<?php

require_once __DIR__ . '/../classes/NotificationController.php';

$idUser = (int)($_POST['idUser'] ?? 0);
$idNot = isset($_POST['idNotificacao']) ? (int)$_POST['idNotificacao'] : null;

$ctrl = new NotificationController();
$response = $ctrl->getNotification($idUser, $idNot);

if ($response['status']) {
    echo json_encode(['status'=> 'success', 'array' => $response['data']]); exit();
} else {
    echo json_encode(['status'=> 'error', 'message' => $response['message']]); exit();
}
