<?php

namespace App\Models;

use App\Helpers\Database;

class SensorReading
{
    private Database $db;

    /**
     * Initializes the Database instance for database interactions.
     */
    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Insert a new sensor reading into the database.
     *
     * @param string $sensor_uuid
     * @param float $temperature
     * @throws \Exception
     */
    public function insert(string $sensor_uuid, float $temperature): void
    {
        if ($this->sensorUuidExists($sensor_uuid)) {
            throw new \Exception('Sensor UUID already exists', 422);
        }

        $this->db->insert('sensor_readings', [
            'sensor_uuid' => $sensor_uuid,
            'temperature' => $temperature,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Check if a sensor UUID already exists in the database.
     *
     * @param string $sensor_uuid
     * @return bool
     */
    public function sensorUuidExists(string $sensor_uuid): bool
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM sensor_readings WHERE sensor_uuid = ?', [$sensor_uuid]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Get the average temperature for the past specified number of days.
     *
     * @param int $days
     * @return float|null
     */
    public static function getAverageTemperature(int $days): ?float
    {
        $db = new Database();
        $stmt = $db->query(
            "SELECT AVG(temperature) as average FROM sensor_readings WHERE created_at >= NOW() - INTERVAL :days DAY",
            [':days' => $days]
        );
        $result = $db->fetch($stmt);

        return $result ? (float)$result['average'] : null;
    }

    /**
     * Get the average temperature for a specific sensor.
     *
     * @param string $sensor_uuid
     * @return float|null
     */
    public static function getAverageTemperatureBySensor(string $sensor_uuid): ?float
    {
        $db = new Database();
        $stmt = $db->query(
            "SELECT AVG(temperature) as average FROM sensor_readings WHERE sensor_uuid = :sensor_uuid",
            [':sensor_uuid' => $sensor_uuid]
        );
        $result = $db->fetch($stmt);

        return $result ? (float)$result['average'] : null;
    }
}
