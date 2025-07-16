<?php
require_once '../config/auth.php';
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], ($_POST['acao']))) {
    $id = intval($_POST['id']);
    $acao = $_POST['acao'];

    if (in_array($acao, ['admin', 'cliente'])) {
        $stmt = $conn->prepare("UPDATE usuarios SET tipo = ? WHERE id = ?");
        $stmt->bind_param("si", $acao, $id);
        if ($stmt->execute());
    }
}

$usuarios = [];
$result = $conn->query("SELECT id, nome,sobrenome, email, tipo FROM usuarios ORDER BY nome");

if ($result) {
    $usuarios = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<h2>Gerenciar Usuários</h2>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Tipo</th>
        <th>Ação</th>
    </tr>
    <?php foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?= $usuario['id'] ?></td>
            <td><?= htmlspecialchars($usuario['nome'] . ' ' . $usuario['sobrenome']) ?></td>
            <td><?= htmlspecialchars($usuario['email']) ?></td>
            <td><?= $usuario['tipo'] ?></td>
            <td>
                <?php if ($usuario['tipo'] === 'cliente'): ?>
                    <form method="POST" style="display:inline">
                        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                        <button type="submit" name="acao" value="admin">Tornar Admin</button>
                    </form>
                <?php elseif ($usuario['tipo'] === 'admin' && $usuario['id'] != $_SESSION['admin_usuario']['id']): ?>
                    <form method="POST" style="display:inline">
                        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                        <button type="submit" name="acao" value="cliente">Rebaixar</button>
                    </form>
                <?php else: ?>

                    <em>Você</em>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<p><a href="principal_adm.php">← Voltar para o Painel</a></p>