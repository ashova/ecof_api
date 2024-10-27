<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Database\Seeds\DatabaseSeeder;

$seeder = new DatabaseSeeder();
$seeder->run();
echo "Database seeded successfully!";


