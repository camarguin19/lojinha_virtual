<?php
require_once '../config/db.php';
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../admin/login.php");
    exit;
}
$usuario_id = $_SESSION['usuario']['id'];

$sql = "SELECT h.*, p.nome AS produtos_nome
        FROM historico_compras h
        JOIN produtos p ON h.produtos_id = p.id
        WHERE h.usuarios_id = ?
        ORDER BY h.data_compra DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Meus Pedidos</h2>
<table border="1">
    <tr>
        <th>Produto</th>
        <th>Quantidade</th>
        <th>Preço Unitário</th>
        <th>Data da Compra</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['produtos_nome']) ?></td>
            <td><?= $row['quantidade'] ?></td>
            <td>R$ <?= number_format($row['preco_unitario'], 2, ',', '.') ?></td>
            <td><?= date('d/m/Y', strtotime($row['data_compra'])) ?></td>
        </tr>
    <?php endwhile; ?>
</table>