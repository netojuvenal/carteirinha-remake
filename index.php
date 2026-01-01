<?php


// --- CONFIGURAÇÕES DE ERRO ---
// Em desenvolvimento, para depurar troque '0' por '1' temporariamente.
// Em produção deixe '0' e verifique logs via error_log().
error_reporting(E_ALL);
ini_set('display_errors', '0');


$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
$cookieParams = [
    'lifetime' => 0,                             // expira quando o navegador fecha
    'path'     => '/',                           // disponível em todo o site
    'domain'   => $_SERVER['HTTP_HOST'] ?? '',   // domínio atual
    'secure'   => $secure,                       // só envia em HTTPS (se disponível)
    'httponly' => true,                          // impede acesso por JavaScript
    'samesite' => 'Lax'                          // ajuda a mitigar CSRF simples
];


session_set_cookie_params($cookieParams);


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


try {

    require_once __DIR__ . '/Controller/config.php';


    require_once __DIR__ . '/Model/connect.php';


    require_once __DIR__ . '/Controller/classes/AuthController.php';
} catch (Throwable $e) {

    error_log("Falha ao carregar arquivos essenciais: " . $e->getMessage());
    http_response_code(500);
    echo "Erro ao iniciar a aplicação. Verifique os logs do servidor.";
    exit();
}

try {
    $authController = new AuthController();
    $authController->isLoggedIn();


} catch (Throwable $e) {

    error_log("Erro no fluxo de autenticação: " . $e->getMessage());
    http_response_code(500);
    echo "Erro interno. Contate o administrador.";
    exit();
}
