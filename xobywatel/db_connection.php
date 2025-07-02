<?php
$host = 'localhost';
$db = 'jabcok7345';
$user = 'xobywatel';
$pass = 'FSZ#1wQ]v3h6Np4G';

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    echo 'Błąd połączenia: ' . $e->getMessage();
    exit();
}
