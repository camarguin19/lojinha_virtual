<?php
require_once '../config/auth.php';
require_once '../config/db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID do produto não informado.');
}

$id = (int)$_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $preco = str_replace(',', '.', $_POST['preco']);
    $estoque = (int)$_POST['estoque'];
    $categoria_id = (int)$_POST['categoria_id'];
    if (empty($nome) || !is_numeric($preco) || $estoque < 0 || $categoria_id <= 0) {
        die('Preencha os campos corretamente.');
    }

    $sqlUpdate = "UPDATE produtos SET nome = ?, preco = ?, estoque = ?, categoria_id = ? WHERE id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param('sdiii', $nome, $preco, $estoque, $categoria_id, $id);
    $stmtUpdate->execute();

    header('Location: ../admin/gerenciar_produtos.php');
    exit;
}

$sql = "SELECT * FROM produtos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('Produto não encontrado.');
}
$produto = $result->fetch_assoc();

$sqlCategorias = "SELECT id, nome FROM categorias";
$resultCat = $conn->query($sqlCategorias);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
</head>

<body>
    <h1>Editar Produto</h1>

    <form action="" method="POST">
        <label>Nome:<br>
            <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>
        </label><br><br>

        <label>Preço:<br>
            <input type="text" name="preco" value="<?= number_format($produto['preco'], 2, ',', '.') ?>" required>
        </label><br><br>

        <label>Estoque:<br>
            <input type="number" name="estoque" value="<?= $produto['estoque'] ?>" required min="0">
        </label><br><br>

        <label>Categoria:<br>
            <select name="categoria_id" required>
                <option value="">Selecione uma categoria</option>
                <?php while ($categoria = $resultCat->fetch_assoc()): ?>
                    <option value="<?= $categoria['id'] ?>" <?= ($categoria['id'] == $produto['categoria_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($categoria['nome']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </label><br><br>

        <button type="submit">Salvar Alterações</button>
    </form>

    <p><a href="gerenciar_produtos.php">← Voltar para a lista</a></p>
</body>

</html>