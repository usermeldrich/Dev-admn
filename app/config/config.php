<?php
define('BASE_URL', 'http://dev-admn.local');

define('DB_HOST', 'localhost');
define('DB_NAME', 'reserva');
define('DB_USER', 'postgres');
define('DB_PASS', '*1052sonicX');

try {
    $pdo = new PDO("pgsql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>