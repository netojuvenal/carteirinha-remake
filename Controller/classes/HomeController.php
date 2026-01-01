<?php

require_once __DIR__ . '/../config.php';

class HomeController
{
    // Abstrai redirecionamentos de home
    public function index()
    {
        header(PATH . "/View/landpage.php");
        exit();
    }

    public function indexAdm()
    {
        header(PATH . "/View/painel-administrador.php");
        exit();
    }
}
