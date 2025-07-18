<?php
session_start();

if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../admin/login.php");
    exit;
}

$id = $_POST['id'];
$nome = $_POST['nome'];
$preco = $_POST['preco'];

if (!isset($_SESSION['carrinho'][$id])) {
    $_SESSION['carrinho'][$id] = [
        'nome' => $nome,
        'preco' => $preco,
        'quantidade' => 1
    ];
} else {
    $_SESSION['carrinho'][$id]['quantidade']++;
}
header("Location: index.php");
exit();
