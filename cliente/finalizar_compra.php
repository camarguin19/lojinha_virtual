<?php
session_start();
require_once '../config/db.php';


if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
    header("Location: ../admin/login.php");
    exit;
}

$carrinho = $_SESSION['carrinho'] ?? [];

if (empty($_SESSION['carrinho'])) {
    echo "Carrinho vazio. Não é possível finalizar a compra.";
    echo '<br><a href="index.php">Voltar para a loja</a>';
    exit;
}

$erros = [];

foreach ($carrinho as $id => $item) {
    $stmt = $conn->prepare("SELECT estoque FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $produto = $resultado->fetch_assoc();

    if (!$produto) {
        $erros[] = "Produto com ID $id não encontrado.";
    }
    $quantidade_disponivel = $produto['estoque'];
    $quantidade_comprada = $item['quantidade'];

    if ($quantidade_comprada > $quantidade_disponivel) {
        $erros[] = "Estoque insuficiente para o produto '{$item['nome']}' (ID: $id). Estoque atual: {$produto['estoque']}.";
    }
    $stmt->close();
}

if (!empty($erros)) {
    echo "<h3>Erro ao finalizar compra: </h3><ul>";
    foreach ($erros as $erro) {
        echo "<li>" . htmlspecialchars($erro) . "</li>";
    }
    echo "</ul> <a href='ver_carrinho.php'>Voltar para o carrinho</a>";
    exit;
}
foreach ($carrinho as $id => $item) {
    $quantidade_comprada = $item['quantidade'];

    $stmt = $conn->prepare("UPDATE produtos SET estoque = estoque - ? WHERE id = ?");
    $stmt->bind_param("ii", $quantidade_comprada, $id);
    $stmt->execute();
    $stmt->close();
}
$usuario_id = $_SESSION['usuario']['id'];

foreach ($_SESSION['carrinho'] as $produtos_id => $item) {
    $quantidade = $item['quantidade'];
    $preco_unitario = $item['preco'];

    $stmt = $conn->prepare("INSERT INTO historico_compras (usuarios_id, produtos_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $usuario_id, $produtos_id, $quantidade, $preco_unitario);
    $stmt->execute();
    $stmt->close();
}
unset($_SESSION['carrinho']);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Compra Finalizada</title>
</head>

<body>
    <h2>✅ Compra finalizada com sucesso!</h2>
    <p>O estoque foi atualizado com base nos itens adquiridos.</p>
    <a href="index.php">Voltar para a loja</a>
</body>

</html>