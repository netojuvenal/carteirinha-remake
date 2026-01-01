<?php

require_once __DIR__ . '/../classes/RelatorioController.php';
$controller = new RelatorioController();

$inicio = $_POST['inicio'] ?? '';
$fim = $_POST['fim'] ?? '';
echo json_encode($controller->getCardapioByInterval($inicio, $fim)['data'] ?? $controller->getCardapioByInterval($inicio, $fim));
