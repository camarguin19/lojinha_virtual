<?php
require_once '../config/auth.php';
require_once '../config/db.php';


$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $preco = $_POST['preco'] ?? 0;
    $estoque = $_POST['estoque'] ?? 0;
    $categoria_id = $_POST['categoria_id'] ?? null;

    if ($nome && $preco && $estoque && $categoria_id) {
        $stmt = $conn->prepare("INSERT INTO produtos (nome, descricao, preco, estoque, categoria_id) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Erro na preparação da consulta: " . $conn->error);
        }

        $stmt->bind_param("ssdii", $nome, $descricao, $preco, $estoque, $categoria_id);

        if ($stmt->execute()) {
            $mensagem = 'Produto adicionado com sucesso!';
        } else {
            $erro = 'Erro ao adicionar produto: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        $erro = 'Erro na preparação da consulta: ' . $conn->error;
    }
}

$categorias = [];
$res = $conn->query("SELECT id, nome FROM categorias");
if ($res && $res->num_rows > 0) {
    while ($cat = $res->fetch_assoc()) {
        $categorias[] = $cat;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Adicionar Produto</title>
</head>

<body>
    <h1>Adicionar Produto</h1>
    <p><a href="gerenciar_produtos.php">← Voltar</a></p>

    <?php if ($erro): ?>
        <p style="color: red;"><?= $erro ?></p>
    <?php endif; ?>
    <?php if ($mensagem): ?>
        <p style="color: green;"><?= $mensagem ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Nome:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>Descrição:</label><br>
        <textarea name="descricao"></textarea><br><br>

        <label>Preço:</label><br>
        <input type="number" name="preco" step="0.01" required><br><br>

        <label>Estoque:</label><br>
        <input type="number" name="estoque" required><br><br>

        <label>Categoria:</label><br>
        <select name="categoria_id" required>
            <option value="">Selecione uma categoria...</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nome']) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <button type="submit">Cadastrar</button>
    </form>
</body>

</html>