<?php
session_start();

if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../admin/login.php");
    exit;
}

require_once '../config/db.php';

$id = $_POST['id'];
$nome = $_POST['nome'];
$preco = $_POST['preco'];

$sql = "SELECT estoque FROM produtos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$produto = $result->fetch_assoc();

if (!$produto) {
    $_SESSION['mensagem'] = ['tipo' => 'erro', 'texto' => 'Produto não encontrado!'];
    header("Location: index.php");
    exit;
}

$estoque_disponivel = $produto['estoque'];
$quantidade_no_carrinho = $_SESSION['carrinho'][$id]['quantidade'] ?? 0;

if ($quantidade_no_carrinho >= $estoque_disponivel) {
    $_SESSION['mensagem'] = ['tipo' => 'erro', 'texto' => 'Estoque insuficiente para adicionar mais deste produto.'];
    header("Location: index.php");
    exit;
}

if (!isset($_SESSION['carrinho'][$id])) {
    $_SESSION['carrinho'][$id] = [
        'nome' => $nome,
        'preco' => $preco,
        'quantidade' => 1
    ];
    $_SESSION['mensagem'] = ['tipo' => 'successo', 'texto' => 'Produto adicionado ao carrinho!'];
} else {
    $_SESSION['carrinho'][$id]['quantidade']++;
    $_SESSION['mensagem'] = ['tipo' => 'info', 'texto' => 'Produto já está no carrinho, quantidade atualizada!'];
}
header("Location: index.php");
exit();
