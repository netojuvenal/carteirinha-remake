<?php

require_once __DIR__ . '/../classes/FeedbackController.php';
$controller = new FeedbackController();

$idUser = (int)($_POST["idUser"] ?? 0);
echo json_encode($controller->getUserFeedback($idUser)['data'] ?? $controller->getUserFeedback($idUser));
