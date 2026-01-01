<?php
require_once __DIR__ . '/../config.php';

class HomeController
{
    public function index()
    {
        header('Location: ' . BASE_URL . "/View/landpage.php");
        exit();
    }

    public function indexAdm()
    {
        header('Location: ' . BASE_URL . "/View/painel-administrador.php");
        exit();
    }
}