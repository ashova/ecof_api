<?php

namespace App\Helpers;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
    private PDO $pdo;

    /**
     * Initializes the PDO instance for database interactions.
     */
    public function __construct()
    {
        $host = getenv('DB_HOST') ?: 'db';
        $dbname = getenv('DB_NAME') ?: 'ecof_db';
        $username = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASS') ?: 'root';

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log('Database connection error: ' . $e->getMessage());
            throw new \RuntimeException('Database connection error.');
        }
    }

    /**
     * Execute a SQL query with parameters.
     *
     * @param string $sql
     * @param array $params
     * @return PDOStatement
     * @throws PDOException
     */
    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Fetch all results from a statement as an associative array.
     *
     * @param \PDOStatement $stmt
     * @return array
     */
    public function fetchAll(\PDOStatement $stmt): array
    {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Fetch a single result from a statement as an associative array.
     *
     * @param \PDOStatement $stmt
     * @return array|null
     */
    public function fetch(\PDOStatement $stmt): ?array
    {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Insert data into a specified table.
     *
     * @param string $table
     * @param array $data
     * @throws PDOException
     */
    public function insert(string $table, array $data): void
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $stmt = $this->pdo->prepare("INSERT INTO $table ($columns) VALUES ($placeholders)");
        $stmt->execute(array_values($data));
    }
}
