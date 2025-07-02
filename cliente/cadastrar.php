<?php

session_start();
require_once '../config/db.php';

$erro = '';
$suceso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $sobrenome = trim($_POST['sobrenome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if (empty($nome) || empty($sobrenome) || empty($email) || empty($senha)) {
        die("Preencha todos os campos.");
    } else {
        $verif = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $verif->bind_param('s', $email);
        $verif->execute();
        $verif->store_result();

        if ($verif->num_rows()) {
            $erro = ("Email ja cadastrado.");
        } else {
            $senhaCripto = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO usuarios(nome, sobrenome, email, senha, tipo) VALUES (?,?,?,?,'cliente')");
            $stmt->bind_param('ssss', $nome, $sobrenome, $email, $senhaCripto);

            if ($stmt->execute()) {
                header("Location: index.php");
                exit();
            } else {
                echo "Erro ao cadastrar usuário.";
            }
            $stmt->close();
            $verif->close();
        }
    }
}


?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Cadatrar usuario</title>
</head>

<body>
    <h2>Cadastrar</h2>

    <form method="POST">
        <label>Nome:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>Sobrenome:</label><br>
        <input type="text" name="sobrenome" required><br><br>

        <label>Email:</label><br>
        <input type="text" name="email" required><br><br>

        <label>Senha:</label><br>
        <input type="password" name="senha" required><br><br>

        <button type="submit">Cadastrar</button>
    </form>

    <p><a href="login.php">Já tem conta? Faça login</a></p>
</body>

</html>