<?php
session_start();
$carrinho = $_SESSION['carrinho'] ?? [];
$total = 0;
?>

<h2>Carrinho de compras</h2>
<table border="1" cellpadding="5">
    <tr>
        <th>Produto</th>
        <th>Preço</th>
        <th>Quantidade</th>
        <th>Subtotal</th>
    </tr>
    <?php foreach ($carrinho as $item):
        $subtotal = $item['preco'] * $item['quantidade'];
        $total += $subtotal;
    ?>
        <tr>
            <td><?= $item['nome'] ?></td>
            <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
            <td><?= $item['quantidade'] ?></td>
            <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<p><strong>Total: R$ <?= number_format($total, 2, ',', '.') ?> </strong></p>
<p>
    <a href="esvaziar_carrinho.php"><button>Esvaziar Carrinho</button></a>
    <a href="finalizar_compra.php"><button>Finalizar Compra</button></a>
</p>