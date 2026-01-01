<?php

require_once __DIR__ . '/../classes/FeedbackController.php';
$controller = new FeedbackController();

$idCardapio = (int)($_POST["idCardapio"] ?? 0);
$response = $controller->getDiaByID($idCardapio);
echo json_encode($response['data'] ?? $response);
