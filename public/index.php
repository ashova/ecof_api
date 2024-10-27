<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\SensorController;

$controller = new SensorController();

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch (true) {
    case preg_match('/^\/api\/push$/', $requestUri) && $requestMethod === 'POST':
        $controller->pushReading();
        break;

    case preg_match('/^\/api\/average$/', $requestUri) && $requestMethod === 'GET':
        $controller->getAverageTemperature();
        break;

    case preg_match('/^\/api\/sensor\/([a-f0-9\-]{36})$/', $requestUri, $matches)
        && $requestMethod === 'GET':
        $sensor_uuid = $matches[1];
        $controller->getSensorAverage($sensor_uuid);
        break;

    default:
        header('HTTP/1.1 404 Not Found');
        echo json_encode(['error' => 'Not Found']);
        break;
}
