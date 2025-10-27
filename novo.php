<?php
require __DIR__ . '/db.php';

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome'] ?? '');
    $preco = trim($_POST['preco'] ?? '');

    // validação básica
    if ($nome === '' || $preco === '' || !is_numeric($preco)) {
        $mensagem = '<p style="color:red">Preencha nome e preço válidos.</p>';
    } else {
        try {
            $sql = "INSERT INTO produtos (nome, preco) VALUES (:n, :p)";
            $stmt = db()->prepare($sql);
            $stmt->execute([
                ':n' => $nome,
                ':p' => $preco
            ]);

            // mensagem de sucesso
            $mensagem = '<p style="color:green">Produto "' . htmlspecialchars($nome) . '" cadastrado com sucesso!</p>';

        } catch (PDOException $e) {
            $mensagem = '<p style="color:red">Erro ao inserir: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Novo Produto</title>
    <style>
        body { font-family: sans-serif; max-width: 600px; margin: 40px auto; }
        label { display: block; margin-bottom: 10px; }
        input[type=text], input[type=number] {
            padding: 6px;
            width: 100%;
            max-width: 300px;
        }
        button {
            padding: 8px 16px;
            cursor: pointer;
        }
        a { text-decoration: none; color: #0066cc; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>Novo Produto</h1>

    <?= $mensagem ?>

    <form method="post">
        <label>
            Nome:
            <input type="text" name="nome" required placeholder="Mouse Gamer">
        </label>

        <label>
            Preço:
            <input type="number" name="preco" step="0.01" required placeholder="199.90">
        </label>

        <button type="submit">Salvar</button>
    </form>

    <p><a href="index.php">&larr; Voltar para a lista</a></p>
</body>
</html>
