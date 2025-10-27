<?php
require __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $stmt = db()->query("SELECT id, nome, preco FROM produtos ORDER BY id");
    $produtos = $stmt->fetchAll();

    echo json_encode([
        "ok" => true,
        "dados" => $produtos
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "ok" => false,
        "erro" => "Erro ao consultar produtos",
        "detalhe" => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
