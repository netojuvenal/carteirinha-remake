<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Controller
{
    protected function resposta(bool $status, string $mensagem = '', $dados = null): array
    {
        return [
            'status'  => $status,
            'message' => $mensagem,
            'data'    => $dados
        ];
    }

    protected function usuarioLogado(): bool
    {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    protected function usuarioAdm(): bool
    {
        return $this->usuarioLogado() && (($_SESSION['category'] ?? $_SESSION['categoria'] ?? '') === 'adm');
    }
}
