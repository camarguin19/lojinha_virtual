<?php

require_once '../config/auth.php';
require_once '../config/db.php';


if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID do produto não informado');
}

$id = (int)$_GET['id'];

$sql = "DELETE FROM produtos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    header('Location: gerenciar_produtos.php');
    exit;
} else {
    die('Erro ao excluir produto: ' . $stmt->error);
}
