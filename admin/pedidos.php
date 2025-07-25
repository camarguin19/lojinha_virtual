<?php

session_start();
require_once '../config/db.php';

if (!isset($_SESSION['admin_logado'])) {
    header('Location: login.php');
    exit;
}
$sql = "SELECT h.*,
               u.nome AS nome_usuario,
               u.sobrenome as sobrenome_usuario,
               u.email,
               p.nome as nome_produto
        FROM historico_compras h
        JOIN usuarios u on h.usuarios_id = u.id
        JOIN produtos p on h.produtos_id = p.id
        ORDER BY h.data_compra DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Pedidos Realizados</title>
    <link rel="stylesheet" href="estilos_admin.css">
</head>

<body>
    <h2>📦 Pedidos Realizados</h2>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Nome do usuário</th>
                <th>Email</th>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Preço Unitário</th>
                <th>Data da Compra</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nome_usuario']) . ' ' . htmlspecialchars($row['sobrenome_usuario']) ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= htmlspecialchars($row['nome_produto']) ?></td>
                    <td><?= $row['quantidade'] ?></td>
                    <td>R$ <?= number_format($row['preco_unitario'], 2, ',', '.') ?></td>
                    <td><?= date('d/m/Y', strtotime($row['data_compra'])) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <br>
    <a href="principal_adm.php">🔙 Voltar ao Painel</a>
</body>

</html>