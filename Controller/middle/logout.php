<?php

require_once __DIR__ . '/../classes/AuthController.php';
require_once __DIR__ . '/../config.php';

$auth = new AuthController();
$auth->logout();
header(PATH);
exit();
