<?php

namespace App\Services;

use App\Models\SensorReading;

class SensorService
{
    /**
     * Saves sensor data by inserting a reading for the specified UUID and temperature.
     *
     * @param string $sensor_uuid
     * @param float $temperature
     * @throws \Exception
     */
    public function saveSensorData(string $sensor_uuid, float $temperature): void
    {
        try {
            $sensorReading = new SensorReading();
            $sensorReading->insert($sensor_uuid, $temperature);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Retrieves the average temperature over a specified number of days.
     *
     * @param int $days
     * @return float|null
     */
    public function getAverageTemperature(int $days): ?float
    {
        return SensorReading::getAverageTemperature($days);
    }

    /**
     * Retrieves the average temperature for a specific sensor UUID.
     *
     * @param string $sensor_uuid
     * @return float|null
     */
    public function getAverageTemperatureBySensor(string $sensor_uuid): ?float
    {
        return SensorReading::getAverageTemperatureBySensor($sensor_uuid);
    }
}
