<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Helpers\Database;

$db = new Database();
$users = $db->getUsers();

echo "<h1>Users List</h1><ul>";
foreach ($users as $user) {
    echo "<li>{$user['name']} ({$user['email']})</li>";
}
echo "</ul>";
