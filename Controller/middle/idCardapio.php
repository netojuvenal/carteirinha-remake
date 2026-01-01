<?php

require_once __DIR__ . '/../classes/CardapioController.php';

$dia = $_POST['diaSemana'] ?? '';
$ctrl = new CardapioController();
$res = $ctrl->getIdCardapio($dia);

if ($res !== null) {
    echo $res;
}
