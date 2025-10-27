<?php
function db() {
    static $pdo;

    if ($pdo) {
        return $pdo;
    }
    $host = 'localhost';
    $banco = 'loja';
    $usuario = 'phpuser';  // ajuste para o seu usuário
    $senha = '1234';        // ajuste para sua senha

    $dsn = "mysql:host=$host;dbname=$banco;charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $usuario, $senha, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,     // lança exceção em erro
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // retorna array associativo
        ]);
    } catch (PDOException $e) {
        // Se der erro aqui, mostra uma mensagem amigável
        die("Erro ao conectar ao banco: " . $e->getMessage());
    }

    return $pdo;
}
