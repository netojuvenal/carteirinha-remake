<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../../Model/classes/AuthModel.php';
require_once __DIR__ . '/../config.php';

class AuthController extends Controller
{
    private $model;
    private $homeController;

    public function __construct()
    {
        $this->model = new AuthModel();
        require_once __DIR__ . '/HomeController.php';
        $this->homeController = new HomeController();
    }

    /**
     * Redireciona para login ou para as home pages conforme a sessão.
     */
    public function isLoggedIn()
    {
        if (!$this->usuarioLogado()) {
            $this->showLogin();
        } else {
            $cat = $_SESSION['category'] ?? $_SESSION['categoria'] ?? '';
            if ($cat === 'adm') {
                $this->homeController->indexAdm();
            } else {
                $this->homeController->index();
            }
        }
    }

    public function showLogin()
    {
        header(PATH . '/View/login.php');
        exit();
    }

    /**
     * login($user, $pass)
     * - Recebe matrícula e senha
     * - Retorna array ['status' => bool, 'message' => string]
     */
    public function login($user, $pass)
    {
        if (empty($user) || empty($pass)) {
            return ['status' => false, 'message' => 'Preencha matrícula e senha'];
        }

        if (!$this->model->login($user, $pass)) {
            return ['status' => false, 'message' => 'Credenciais inválidas'];
        }

        $dados = $this->model->getDataByMatricula($user);
        if ($dados === false) {
            return ['status' => false, 'message' => 'Erro ao obter dados do usuário'];
        }

        // criar sessão uniforme
        $_SESSION['id'] = $dados['id'];
        $_SESSION['name'] = $dados['nome'];
        $_SESSION['email'] = $dados['email'];
        $_SESSION['telefone'] = $dados['telefone'];
        $_SESSION['enrollment'] = $dados['matricula'];
        $_SESSION['matricula'] = $dados['matricula'];
        $_SESSION['category'] = $dados['categoria'];
        $_SESSION['categoria'] = $dados['categoria'];
        $_SESSION['logged_in'] = true;

        return ['status' => true, 'message' => 'sucesso'];
    }

    public function logout()
    {
        session_destroy();
        header(PATH);
        exit();
    }
}
