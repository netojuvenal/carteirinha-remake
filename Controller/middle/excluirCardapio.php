<?php

require_once __DIR__ . '/../classes/CardapioController.php';

$ctrl = new CardapioController();
$response = $ctrl->excluirCardapio();

if ($response['status']) {
    echo json_encode(['status' => 'success', 'message' => $response['message']]); exit();
} else {
    echo json_encode(['status' => 'error', 'message' => $response['message'] ?? 'Erro desconhecido ao excluir cardápio.']); exit();
}
