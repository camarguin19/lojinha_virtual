<?php
session_start();

if (!isset($_SESSION['usuario_logado'])) {
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
    $stmt->bind_param($id, "i");
    $stmt->execute();
    $resultado = $stmt->get_result();
    $produtos = $resultado->fetch_assoc();

    if (!$produtos) {
        $erros[] = "Produto com ID $id não encontrado.";
    } elseif ($produtos['estoque'] < $item['quantidade']) {
        $erros[] = "Estoque insuficiente para o produto '{$item['nome']}' (ID: $id). Estoque atual: {$produtos['estoque']}.";
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
    $stmt = $conn->prepare("SELECT estoque FROM produtos WHERE id = ?");
    $stmt->bind_param($id, "i");
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