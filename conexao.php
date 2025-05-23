<?php
$host = "localhost";
$port = "5432";
$dbname = "banco";
$user = "postgres"; // ou outro usuário que você configurou
$password = "91354385"; // substitua pela senha do usuário PostgreSQL

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    // Configura o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
    exit;
}
?>
