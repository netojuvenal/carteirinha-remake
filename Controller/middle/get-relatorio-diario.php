<?php

require_once __DIR__ . '/../classes/RelatorioController.php';
$controller = new RelatorioController();

$date = $_POST["date"] ?? '';
echo json_encode($controller->getRelatorioFaltas($date)['data'] ?? $controller->getRelatorioFaltas($date));
