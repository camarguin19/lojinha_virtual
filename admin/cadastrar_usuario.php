<?php
session_start();
require_once '../config/db.php';

$sucesso = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $sobrenome = trim($_POST['sobrenome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $tipo = 'cliente';

    if (empty($nome) || empty($sobrenome) || empty($email) || empty($senha) || !in_array($tipo, ['admin', 'cliente'])) {
        $erro = "Preencha todos os campos corretamente.";
    } else {
        $verif = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $verif->bind_param('s', $email);
        $verif->execute();
        $verif->store_result();

        if ($verif->num_rows()) {
            $erro = "Email já cadastrado.";
        } else {
            $senhaCripto = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('INSERT INTO usuarios(nome, sobrenome, email, senha, tipo) VALUES (?, ?, ?, ?, ?)');
            $stmt->bind_param('sssss', $nome, $sobrenome, $email, $senhaCripto, $tipo);

            if ($stmt->execute()) {
                $sucesso = "Usuário cadastrado com sucesso.";
            } else {
                $erro = "Erro ao cadastrar usuário.";
            }

            $stmt->close();
        }

        $verif->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
</head>

<body>
    <h2>Cadastro de Usuário</h2>

    <?php if (!empty($erro)): ?>
        <p style="color: red;"><?= $erro ?></p>
    <?php elseif (!empty($sucesso)): ?>
        <p style="color: green;"><?= $sucesso ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Nome:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>Sobrenome:</label><br>
        <input type="text" name="sobrenome" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Senha:</label><br>
        <input type="password" name="senha" required><br><br>
        <button type="submit">Cadastrar</button>
    </form>

    <br><a href="login.php">Voltar para o login</a>
</body>

</html>