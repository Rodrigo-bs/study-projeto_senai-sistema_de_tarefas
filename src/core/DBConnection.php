<?php

    namespace Src\Core;

    use PDO;

    class DBConnection {
        private const DBNAME = NAME_DB;
        private const HOST = HOST_DB;
        private const PORT = PORT_DB;
        private const USER = USER_DB;
        private const PASSWORD = PASSWORD_DB;

        protected $conn;
        protected $table;
        
        public function __construct($table) {
            $this->table = $table;
            $this->makeConnection();
        }

        private function makeConnection(): void {
            try {
                $pdo = new PDO('pgsql:host='. SELF::HOST . ';port=' . SELF::PORT . ';dbname='. SELF::DBNAME, SELF::USER, SELF::PASSWORD);
                $pdo->setAttribute(PDO::ERRMODE_EXCEPTION, PDO::ERRMODE_WARNING);
                $this->conn = $pdo;
            } catch(PDOException $e) {
                die('Erro na conexão com o banco de dados');
            }
        }

        protected function execute($sql, $values = []) {
            try {
                $mysql = $this->conn->prepare($sql);
                $mysql->execute($values);
                return $mysql;
            } catch(PDOException $e) {
                die('Erro na comunicação com o banco de dados, erro: ' . $e->getMessage());
            }
        }

        protected function selectAll($columns = ['*']) {
            $sql = "SELECT ".implode(',', $columns)." FROM $this->table";
            return $this->execute($sql)->fetchAll();
        }

        protected function selectBy($where, $columns = ['*'], $limit = null, $order = null, $offset = null) {
            $whereColumn = array_keys($where);
            $values = array_values($where);

            $order = $order == null ? '' : ' ORDER BY ' . $order['column'] . ' ' . $order['order'];
            $limit = $limit == null ? '' : ' LIMIT ' . $limit;
            $offset = $offset == null || $limit == null ? '' : ' OFFSET ' . $offset;

            $sql = "SELECT ".implode(',', $columns)." FROM $this->table WHERE " . implode('=? AND ', $whereColumn). '=?' . $order . $limit . $offset;
            return $this->execute($sql, $values)->fetchAll();
        }

        protected function insert($values) {
            $columns = array_keys($values);            
            $values = array_values($values);
            $placeholders = array_pad([], count($values), '?');

            $sql = "INSERT INTO $this->table (".implode(',', $columns).") VALUES (".implode(',', $placeholders).")";

            return $this->execute($sql, $values);
        }

        protected function update($values, $where) {
            $columns = array_keys($values);      
            $values = array_values($values);
            $placeholders = array_pad([], count($values), '?');

            $whereColumns = array_keys($where);
            $whereValues = array_values($where);
            
            foreach ($whereValues as $whereValue) {
                array_push($values, $whereValue);
            }

            $sql = "UPDATE $this->table SET ".implode('=?,', $columns)."=? WHERE ". implode('=? AND ', $whereColumns)."=?";
            return $this->execute($sql, $values);
        }

        protected function delete($where) {
            $whereColumn = array_keys($where);
            $values = array_values($where);

            $sql = "DELETE FROM $this->table WHERE " . implode('=? AND ', $whereColumn) .'=?';
            return  $this->execute($sql, $values);
        }
    }