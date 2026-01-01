<?php

require_once __DIR__ . '/../classes/AuthController.php';

$matricula = $_POST['matricula'] ?? '';
$password  = $_POST['password'] ?? '';

$auth = new AuthController();
$response = $auth->login($matricula, $password);

if ($response['status']) {
    echo json_encode(['status' => 'success', 'message' => $response['message']]); exit();
} else {
    echo json_encode(['status' => 'error', 'message' => $response['message']]); exit();
}
