<?php

require_once __DIR__ . '/../classes/CardapioController.php';

$nome = $_POST['nome'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$gluten = (int)($_POST['gluten'] ?? 0);
$lactose = (int)($_POST['lactose'] ?? 0);

$controller = new CardapioController();
$response = $controller->criarTag($nome, $tipo, $gluten, $lactose);

if ($response['status']) {
    echo json_encode(['status' => 'success', 'message' => $response['message']]); exit();
} else {
    echo json_encode(['status' => 'error', 'message' => $response['message']]); exit();
}
