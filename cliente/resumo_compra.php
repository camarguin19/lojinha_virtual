<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['usuario_logado'])) {
    header('Location: ../admin/login.php');
    exit;
}
$carrinho = $_SESSION['carrinho'] ?? [];

if (empty($carrinho)) {
    echo ("Carrinho vazio");
    echo '<br><a href="index.php">Voltar para a loja</a>';
    exit;
}
$total = 0;

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Resumo da Compra</title>
    <link rel="stylesheet" href="../public/assets/css/resumo_compra.css">
</head>

<body>
    <h2>Resumo da Compra</h2>
    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Qtd</th>
                <th>Preço Unitário</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($carrinho as $item):
                $sbubtotal = $item['quantidade'] * $item['preco'];
                $total += $sbubtotal;
            ?>
                <tr>
                    <td><?= htmlspecialchars($item['nome']) ?></td>
                    <td><?= $item['quantidade'] ?></td>
                    <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($sbubtotal, 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td>Total:</td>
                <td>R$ <?= number_format($total, 2, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>
    <div class="botoes">
        <a href="ver_carrinho.php"><button>🔙 voltar ao carrinho</button></a>

        <form action="finalizar_compra.php" method="POST">
            <button type="submit">Concluir Compra</button>
        </form>
    </div>
</body>

</html>