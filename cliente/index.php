<?php

require_once '../config/db.php';
session_start();

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
    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <?php if (!isset($_SESSION['usuario_logado'])): ?>
                <a href="../admin/login.php"><button>Login</button></a>
            <?php else: ?>
                <a href="../admin/logout.php"><button>Sair</button></a>
            <?php endif; ?>
        </div>

        <div>
            <a href="ver_carrinho.php"><button>🛒 Ver Carrinho</button></a>
        </div>
    </div>


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
                    <td>
                        <form method="POST" action="adicionar_carrinho.php">
                            <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                            <input type="hidden" name="nome" value="<?= $produto['nome'] ?>">
                            <input type="hidden" name="preco" value="<?= $produto['preco'] ?>">
                            <button type="submit">🛒</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>