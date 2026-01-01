<?php

require_once __DIR__ . '/../classes/FeedbackController.php';

$nota = (int)($_POST['nota'] ?? 0);
$idUser = (int)($_POST['idUser'] ?? 0);
$idCardapio = (int)($_POST['idCardapio'] ?? 0);

$ctrl = new FeedbackController();
$response = $ctrl->sendFeedback($nota, $idUser, $idCardapio);

if ($response['status']) {
    echo json_encode(['status'=> 'success']); exit();
} else {
    echo json_encode(['status'=> 'error']); exit();
}
