<?php

require_once __DIR__ . '/../classes/CardapioController.php';

$cardapio = json_decode($_POST['cardapio'] ?? '[]', true);
$controller = new CardapioController();
$response = $controller->salvarCardapioSemana($cardapio);

if ($response['status']) {
    echo json_encode(['status' => 'success', 'message' => $response['message']]); exit();
} else {
    echo json_encode(['status' => 'error', 'message' => $response['message']]); exit();
}
