<?php

namespace App\Helpers;

class Database
{
    private $pdo;

    public function __construct()
    {
        $host = getenv('DB_HOST') ?: 'db';
        $dbname = getenv('DB_NAME') ?: 'ecof_db';
        $username = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASS') ?: 'root';

        $this->pdo = new \PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    }

    public function getUsers()
    {
        $stmt = $this->pdo->query('SELECT name, email FROM users');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function insertUser($name, $email)
    {
        $stmt = $this->pdo->prepare('INSERT INTO users (name, email) VALUES (:name, :email)');
        $stmt->execute(['name' => $name, 'email' => $email]);
    }

    public function insertSensorReading($sensor_uuid, $temperature)
    {
        $stmt = $this->pdo->prepare('INSERT INTO sensor_readings (sensor_uuid, temperature) VALUES (:sensor_uuid, :temperature)');
        $stmt->execute(['sensor_uuid' => $sensor_uuid, 'temperature' => $temperature]);
    }
}
