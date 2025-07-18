<?php
session_start();
require_once '../config/db.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param('s', $usuario);
        $stmt->execute();

        $result = $stmt->get_result();
        $dadosUsuario = $result->fetch_assoc();

        if ($dadosUsuario && password_verify($senha, $dadosUsuario['senha'])) {
            $_SESSION['usuario_logado'] = true;
            $_SESSION['usuario'] = [
                'id' => $dadosUsuario['id'],
                'nome' => $dadosUsuario['nome'],
                'sobrenome' => $dadosUsuario['sobrenome'],
                'email' => $dadosUsuario['email'],
                'tipo' => $dadosUsuario['tipo']
            ];

            if ($dadosUsuario['tipo'] === 'admin') {
                $_SESSION['admin_logado'] = true;
                $_SESSION['admin_usuario'] = $_SESSION['usuario'];
                header('Location: principal_adm.php');
            } else {
                header('Location: ../cliente/index.php');
            }
            exit();
        } else {
            $erro = 'Usuário ou senha inválidos.';
        }

        $stmt->close();
    } else {
        $erro = 'Erro na preparação da consulta.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
</head>

<body>
    <h2>Login</h2>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($erro)): ?>
        <p style="color: red;"><?= $erro ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Usuário:</label><br>
        <input type="text" name="usuario" required><br><br>

        <label>Senha:</label><br>
        <input type="password" name="senha" required><br><br>

        <button type="submit">Entrar</button>
        <button type="button" onclick="window.location.href='cadastrar_usuario.php'">Cadastrar</button>
    </form>
    <p><a href="../cliente/index.php"><button>🏠</button></a></p>
</body>

</html>