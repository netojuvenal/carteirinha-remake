<?php
// Model/classes/Model.php - VERSÃO CORRIGIDA

class Model
{
    /** @var mysqli */
    protected $conn;

    public function __construct()
    {
        $this->connect();
    }

    /**
     * Estabelece conexão com o banco de dados
     */
    protected function connect()
    {
        // Se já está conectado, não faz nada
        if ($this->conn instanceof mysqli && $this->conn->ping()) {
            return;
        }

        try {
            // Carrega configurações
            require_once __DIR__ . '/../../Controller/config.php';
            
            // Cria conexão
            $this->conn = new mysqli(
                DB_HOST, 
                DB_USER, 
                DB_PASS, 
                DB_NAME, 
                DB_PORT
            );

            // Verifica erros
            if ($this->conn->connect_errno) {
                throw new Exception(
                    "Erro ao conectar ao MySQL: (" . $this->conn->connect_errno . ") " . 
                    $this->conn->connect_error
                );
            }

            // Define charset
            $this->conn->set_charset('utf8mb4');

        } catch (Exception $e) {
            error_log("Erro de conexão MySQL: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Inferir tipos para bind_param
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
                $types .= 's';
            } else {
                $types .= 's';
            }
        }
        return $types;
    }

    /**
     * Prepara statement, faz bind dos parâmetros e executa
     */
    protected function prepareAndExecute(string $query, array $params = [])
    {
        // Garante conexão
        $this->connect();
        
        if ($this->conn === null) {
            throw new Exception("Conexão com banco de dados não estabelecida.");
        }

        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            error_log("Prepare falhou: (" . $this->conn->errno . ") " . $this->conn->error . " | SQL: $query");
            throw new Exception("Erro ao preparar consulta SQL.");
        }

        if (!empty($params)) {
            $types = $this->inferTypes($params);
            $bindParams = array_merge([$types], $params);
            
            // Cria referências para bind_param
            $refs = [];
            foreach ($bindParams as $key => $value) {
                $refs[$key] = &$bindParams[$key];
            }
            
            if (!call_user_func_array([$stmt, 'bind_param'], $refs)) {
                error_log("bind_param falhou: (" . $stmt->errno . ") " . $stmt->error);
                $stmt->close();
                throw new Exception("Erro ao vincular parâmetros SQL.");
            }
        }

        if (!$stmt->execute()) {
            error_log("Execute falhou: (" . $stmt->errno . ") " . $stmt->error . " | SQL: $query");
            $stmt->close();
            throw new Exception("Erro ao executar consulta SQL.");
        }

        return $stmt;
    }

    /**
     * Executa SELECT e retorna array associativo
     */
    protected function executeQuery($query, $params = [], $types = "")
    {
        try {
            $stmt = $this->prepareAndExecute($query, $params);
            
            // Verifica se é SELECT ou SHOW
            $trimmedQuery = ltrim($query);
            if (stripos($trimmedQuery, 'SELECT') === 0 || stripos($trimmedQuery, 'SHOW') === 0) {
                $result = $stmt->get_result();
                if ($result === false) {
                    $stmt->close();
                    return [];
                }
                $rows = $result->fetch_all(MYSQLI_ASSOC);
                $result->free();
                $stmt->close();
                return $rows ?: [];
            }

            // Para outras queries, retorna affected rows
            $affected = $stmt->affected_rows;
            $stmt->close();
            return $affected;

        } catch (Exception $e) {
            error_log("Erro executeQuery: " . $e->getMessage() . " | SQL: $query");
            return false;
        }
    }

    /**
     * Executa INSERT/UPDATE/DELETE
     */
    protected function executeUpdate($query, $params = [], $types = "")
    {
        try {
            $stmt = $this->prepareAndExecute($query, $params);
            $success = ($stmt->affected_rows >= 0);
            $stmt->close();
            return $success;
        } catch (Exception $e) {
            error_log("Erro executeUpdate: " . $e->getMessage() . " | SQL: $query");
            return false;
        }
    }

    /**
     * Executa INSERT e retorna ID
     */
    protected function executeInsertAndGetId($query, $params = [], $types = "")
    {
        try {
            $stmt = $this->prepareAndExecute($query, $params);
            $insertId = $stmt->insert_id;
            $stmt->close();
            return $insertId !== 0 ? $insertId : $this->conn->insert_id;
        } catch (Exception $e) {
            error_log("Erro executeInsertAndGetId: " . $e->getMessage() . " | SQL: $query");
            return false;
        }
    }

    /**
     * Métodos públicos de conveniência
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

    /**
     * Fecha conexão
     */
    public function __destruct()
    {
        if ($this->conn instanceof mysqli) {
            $this->conn->close();
        }
    }
}