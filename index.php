<?php
// --- CONFIGURAÇÕES DE ERRO ---
error_reporting(E_ALL);
ini_set('display_errors', '1'); // Mudar para 1 para debug

$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
$cookieParams = [
    'lifetime' => 0,
    'path'     => '/',
    'domain'   => $_SERVER['HTTP_HOST'] ?? '',
    'secure'   => $secure,
    'httponly' => true,
    'samesite' => 'Lax'
];

session_set_cookie_params($cookieParams);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    // CORREÇÃO: Caminho correto para config.php
    require_once __DIR__ . '/Controller/config.php';
    
    // CORREÇÃO: Caminho correto para connect.php
    require_once __DIR__ . '/Model/connect.php';
    
    // CORREÇÃO: Caminho correto para AuthController
    require_once __DIR__ . '/Controller/classes/AuthController.php';
    
    $authController = new AuthController();
    $authController->isLoggedIn();
    
} catch (Throwable $e) {
    error_log("Erro no fluxo de autenticação: " . $e->getMessage());
    http_resonse_code(500);
    echo "<h1>Erro interno</h1>";
    echo "<p>Contate o administrador.</p>";
    echo "<pre>Erro: " . htmlspecialchars($e->getMessage()) . "</pre>";
    exit();
}