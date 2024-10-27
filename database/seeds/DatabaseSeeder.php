<?php

namespace Database\Seeds;

use App\Helpers\Database;

class DatabaseSeeder
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function run()
    {
        $this->seedUsers();
        $this->seedSensorReadings();
    }

    private function seedUsers()
    {
        $users = [
            ['name' => 'Alice', 'email' => 'alice@example.com'],
            ['name' => 'Bob', 'email' => 'bob@example.com'],
        ];

        foreach ($users as $user) {
            $this->db->insertUser($user['name'], $user['email']);
        }
    }

    private function seedSensorReadings()
    {
        $sensorReadings = [
            ['sensor_uuid' => '123e4567-e89b-12d3-a456-426614174000', 'temperature' => 22.50],
            ['sensor_uuid' => '123e4567-e89b-12d3-a456-426614174001', 'temperature' => 25.00],
        ];

        foreach ($sensorReadings as $reading) {
            $this->db->insertSensorReading($reading['sensor_uuid'], $reading['temperature']);
        }
    }
}
