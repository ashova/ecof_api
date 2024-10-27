<?php

namespace App\Controllers;

use App\Services\SensorService;

class SensorController
{
    private SensorService $sensorService;

    public function __construct()
    {
        $this->sensorService = new SensorService();
    }

    /**
     * Push a sensor reading and save it to the database.
     *
     * @return array
     *@throws \Exception.
     */
    public function pushReading(): array
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (isset($input['reading']['sensor_uuid'], $input['reading']['temperature'])) {
            $sensor_uuid = $input['reading']['sensor_uuid'];
            $temperature = $input['reading']['temperature'];

            if (!$this->isValidSensorUuid($sensor_uuid)) {
                return $this->respondWithError(400, 'Invalid sensor UUID format');
            }

            if (!$this->isValidTemperature($temperature)) {
                return $this->respondWithError(
                    400,
                    'Temperature must be a decimal value between -10 and +80'
                );
            }

            try {
                $this->sensorService->saveSensorData($sensor_uuid, $temperature);
                return $this->respondWithSuccess('Reading saved');
            } catch (\Exception $e) {
                error_log('Failed to save reading: ' . $e->getMessage());
                return $this->respondWithError($e->getCode(), $e->getMessage());
            }
        }

        return $this->respondWithError(400, 'Invalid input');
    }

    /**
     * Validate the format of the sensor UUID.
     *
     * @param string $sensor_uuid
     * @return bool
     */
    private function isValidSensorUuid(string $sensor_uuid): bool
    {
        return preg_match('/^[a-f0-9\-]{36}$/', $sensor_uuid);
    }

    /**
     * Validate the temperature value.
     *
     * @param mixed $temperature
     * @return bool
     */
    private function isValidTemperature($temperature): bool
    {
        if (is_numeric($temperature)) {
            $temperature = (float)$temperature;
            return $temperature >= -10 && $temperature <= 80;
        }
        return false;
    }

    /**
     * Retrieve the average temperature over a specified number of days.
     *
     * @return array
     */
    public function getAverageTemperature(): array
    {
        $days = isset($_GET['days']) ? (int)$_GET['days'] : 1;

        try {
            $average = $this->sensorService->getAverageTemperature($days);

            if ($average !== null) {
                return $this->respondWithSuccess(sprintf('Average temperature: %s', $average));
            } else {
                return $this->respondWithError(404, 'No readings found for the specified period');
            }
        } catch (\Exception $e) {
            error_log('Failed to retrieve average temperature: ' . $e->getMessage());
            return $this->respondWithError(
                500,
                'An error occurred while retrieving the average temperature'
            );
        }
    }

    /**
     * Retrieve the average temperature for a specific sensor.
     *
     * @param string $sensor_uuid The UUID of the sensor.
     * @return array
     */
    public function getSensorAverage(string $sensor_uuid): array
    {
        try {
            $average = $this->sensorService->getAverageTemperatureBySensor($sensor_uuid);

            if ($average !== null) {
                return $this->respondWithSuccess(sprintf('%s, %s', $sensor_uuid, $average));
            } else {
                return $this->respondWithError(
                    404,
                    'No sensor readings found for the specified sensor'
                );
            }
        } catch (\Exception $e) {
            error_log('Failed to retrieve average temperature for sensor: ' . $e->getMessage());
            return $this->respondWithError(
                500,
                'An error occurred while retrieving the average temperature for the sensor'
            );
        }
    }

    /**
     * Respond with an error message in JSON format.
     *
     * @param int $statusCode The HTTP status code to send.
     * @param string $message The error message.
     * @return array
     */
    private function respondWithError(int $statusCode, string $message): array
    {
        http_response_code($statusCode);
        $response = ['code' => $statusCode, 'error' => $message];
        echo json_encode($response);
        return $response;
    }

    /**
     * Respond with success message in JSON format.
     *
     * @param string $message The error message.
     * @return array
     */
    private function respondWithSuccess(string $message): array
    {
        $response = ['message' => $message];
        echo json_encode($response);
        return $response;
    }
}
