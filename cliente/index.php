<?php

require_once '../config/db.php';

$sql = "SELECT p.*, c.nome AS categoria_nome
        FROM produtos p
        LEFT JOIN categorias c ON p.categoria_id = c.id";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro na banco: " . $conn->error);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../public/assets/css/style.css">
    <title>Loja Virtual - Produtos</title>
</head>

<body>
    <h1>Lista de Produtos</h1>

    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th>Categoria</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($produto = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($produto['nome']) ?></td>
                    <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                    <td><?= $produto['estoque'] ?></td>
                    <td><?= $produto['categoria_nome'] ?? 'Não definida' ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>