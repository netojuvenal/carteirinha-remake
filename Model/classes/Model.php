<?php

class Model
{
    /** @var mysqli */
    public $conn;

    public function __construct()
    {
        // espera que exista $conn (requisição de Model/connect.php pelo bootstrap)
        if (!isset($conn)) {
            // tenta carregar automaticamente se possível
            $possible = __DIR__ . '/../connect.php';
            if (file_exists($possible)) {
                require_once $possible;
            }
        }

        global $conn;
        $this->conn = $conn;
    }

    /**
     * Inferir tipos para bind_param a partir dos valores em $params.
     * i => integer, d => double, s => string, b => blob
     */
    protected function inferTypes(array $params): string
    {
        $types = '';
        foreach ($params as $p) {
            if (is_int($p)) {
                $types .= 'i';
            } elseif (is_float($p) || is_double($p)) {
                $types .= 'd';
            } elseif (is_null($p)) {
                // NULL: trata como string para bind (mysqli aceita)
                $types .= 's';
            } else {
                $types .= 's';
            }
        }
        return $types;
    }

    /**
     * Prepara statement, faz bind dos parâmetros (se houver) e executa.
     * Retorna o stmt ou false em erro.
     */
    protected function prepareAndExecute(string $query, array $params = [])
    {
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            error_log("Prepare falhou: (" . $this->conn->errno . ") " . $this->conn->error . " | SQL: $query");
            return false;
        }

        if (!empty($params)) {
            $types = $this->inferTypes($params);

            // bind_param exige referências
            $refs = [];
            foreach ($params as $key => $value) {
                $refs[$key] = &$params[$key];
            }
            array_unshift($refs, $types);

            // usar call_user_func_array para compatibilidade
            if (!call_user_func_array([$stmt, 'bind_param'], $refs)) {
                error_log("bind_param falhou: (" . $stmt->errno . ") " . $stmt->error . " | SQL: $query");
                $stmt->close();
                return false;
            }
        }

        if (!$stmt->execute()) {
            error_log("Execute falhou: (" . $stmt->errno . ") " . $stmt->error . " | SQL: $query");
            $stmt->close();
            return false;
        }

        return $stmt;
    }

    /**
     * Executa SELECT e retorna array associativo (ou array vazio).
     * Mantém assinaturas antigas para compatibilidade.
     *
     * @param string $query
     * @param array $params
     * @param string $types Ignorado: tipos inferidos automaticamente se não vazio
     * @return array|false
     */
    protected function executeQuery($query, $params = [], $types = "")
    {
        // Força strings vazias para SQL SELECT; se a query não for SELECT, ainda lidamos (será retornado [])
        $stmt = $this->prepareAndExecute($query, $params);
        if ($stmt === false) {
            return false;
        }

        // Só SELECT retorna resultados
        $trim = ltrim($query);
        if (stripos($trim, 'SELECT') === 0 || stripos($trim, 'SHOW') === 0) {
            $result = $stmt->get_result();
            if ($result === false) {
                // consulta sem result set 
                $stmt->close();
                return [];
            }
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            $result->free();
            $stmt->close();
            return $rows ?: [];
        }

        // Se chegou aqui e não é SELECT, tratamos como update/insert/delete
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected;
    }

    /**
     * Executa INSERT/UPDATE/DELETE. Retorna true em caso de execução bem sucedida, false em erro.
     * Mantém compatibilidade com assinatura antiga.
     */
    protected function executeUpdate($query, $params = [], $types = "")
    {
        $stmt = $this->prepareAndExecute($query, $params);
        if ($stmt === false) {
            return false;
        }

        // Execução ok
        $stmt->close();
        return true;
    }

    /**
     * Executa INSERT e retorna o id inserido (ou false em erro).
     */
    protected function executeInsertAndGetId($query, $params = [], $types = "")
    {
        $stmt = $this->prepareAndExecute($query, $params);
        if ($stmt === false) {
            return false;
        }
        $insertId = $stmt->insert_id;
        $stmt->close();
        return $insertId !== 0 ? $insertId : $this->conn->insert_id;
    }

    /**
     * Funções públicas de conveniência (encapsulamento)
     */
    public function select(string $query, array $params = [])
    {
        return $this->executeQuery($query, $params);
    }

    public function execute(string $query, array $params = [])
    {
        return $this->executeUpdate($query, $params);
    }

    public function insertAndGetId(string $query, array $params = [])
    {
        return $this->executeInsertAndGetId($query, $params);
    }
}
