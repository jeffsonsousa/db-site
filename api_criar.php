<?php
require __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');

// Exemplo com POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "ok" => false,
        "erro" => "Use método POST"
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Vamos aceitar JSON no corpo da requisição
$raw = file_get_contents('php://input');
$dados = json_decode($raw, true);

$nome  = trim($dados['nome']  ?? '');
$preco = (float)($dados['preco'] ?? 0);

// Validação bem básica
if ($nome === '' || $preco <= 0) {
    http_response_code(422);
    echo json_encode([
        "ok" => false,
        "erro" => "Informe nome e preço válidos"
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $sql = "INSERT INTO produtos (nome, preco) VALUES (:n, :p)";
    $stmt = db()->prepare($sql);
    $stmt->execute([
        ':n' => $nome,
        ':p' => $preco
    ]);

    $novoId = db()->lastInsertId();

    echo json_encode([
        "ok" => true,
        "mensagem" => "Produto cadastrado",
        "id" => $novoId
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "ok" => false,
        "erro" => "Erro ao inserir produto",
        "detalhe" => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
