<?php
require __DIR__ . '/db.php';

// busca todos os produtos
$stmt = db()->query("SELECT id, nome, preco FROM produtos ORDER BY id");
$produtos = $stmt->fetchAll();
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Lista de Produtos</title>
    <style>
        body { font-family: sans-serif; max-width: 600px; margin: 40px auto; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #999; padding: 8px; text-align: left; }
        th { background: #eee; }
        .topo { display: flex; justify-content: space-between; align-items: baseline; }
        a { text-decoration: none; color: #0066cc; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="topo">
        <h1>Produtos</h1>
        <a href="novo.php">[ + novo produto ]</a>
    </div>

    <?php if (count($produtos) === 0): ?>
        <p>Nenhum produto cadastrado ainda.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Preço (R$)</th>
            </tr>
            <?php foreach ($produtos as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['nome']) ?></td>
                    <td><?= number_format($p['preco'], 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
