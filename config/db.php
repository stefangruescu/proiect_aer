<?php
$host = '127.0.0.1';
$port = '8889'; // Portul MAMP
$db   = 'air_quality';
$user = 'root';
$pass = 'root';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexiune eșuată: " . $e->getMessage());
}