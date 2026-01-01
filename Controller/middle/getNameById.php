<?php

require_once __DIR__ . '/../classes/RelatorioController.php';
$controller = new RelatorioController();

$id = (int)($_POST['id'] ?? 0);
echo json_encode($controller->getNameById($id)['data'] ?? $controller->getNameById($id));
