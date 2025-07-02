<?php
require_once '../config/auth.php';
require_once '../config/db.php';

$sql = "SELECT p.*, c.nome AS categoria_nome 
        FROM produtos p 
        LEFT JOIN categorias c ON p.categoria_id = c.id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Gerenciar Produtos</title>
</head>

<body>
    <h1>Gerenciar Produtos</h1>

    <p><a href="adicionar_produto.php">➕ Adicionar novo produto</a></p>

    <?php
    if (isset($_GET['excluido']) && $_GET['excluido'] == 1): ?>
        <p style="color: green;">✅ Produto excluído com sucesso!</p>
    <?php endif; ?>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th>Categorias</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($produto = $result->fetch_assoc()): ?>
                    <tr>

                        <td><?= htmlspecialchars($produto['nome']) ?></td>
                        <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                        <td><?= $produto['estoque'] ?></td>
                        <td><?= htmlspecialchars($produto['categoria_nome'] ?? 'Não definida') ?></td>
                        <td>
                            <a href="editar_produto.php?id=<?= $produto['id'] ?>">Editar</a> |
                            <a href="excluir_produto.php?id=<?= $produto['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Nenhum produto cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>