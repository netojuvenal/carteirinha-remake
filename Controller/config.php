<?php

$protocol = 'http://';
$host = 'localhost';
$projectFolder = 'carteirinha-remake'; 
$base = $protocol . $host . '/' . $projectFolder;

define('BASE_URL', $base);
define('PATH', 'Location: ' . $base);

define('WPATH', $base);
define('MENU', $base . '/View/cardapio.php');
define('LANDPAGE', $base . '/View/landpage.php');
define('LANDPAGEADM', $base . '/View/painel-administrador.php');
define('LOGOUT', $base . '/View/logout.php');
define('LOGIN', $base . '/View/login.php');
define('ABOUT', $base . '/View/sobre.php');
define('CONTATO', $base . '/View/entre-em-contato.php');
define('PROFILE', $base . '/View/perfil.php');
define('QRCODEIMG', $base . '/View/assets/qr-code.png');
define('QRCODE', $base . '/View/qr-code.php');
define('QRCODREAD', $base . '/View/qr-code-estudante.php');
define('PROFILEPIC', $base . '/View/assets/perfilPadrao.jpeg');

define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'carteirinha23');
define('DB_USER', 'root');
define('DB_PASS', '');

date_default_timezone_set('America/Sao_Paulo');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}