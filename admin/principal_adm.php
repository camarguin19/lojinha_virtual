<?php
require_once '../config/auth.php';

$adminNome = $_SESSION['admin_usuario']['nome'] ?? 'Administrador';
$adminEmail = $_SESSION['admin_usuario']['email'] ?? '';
?>

<h1>Bem-vindo, <?= htmlspecialchars($adminNome) ?>!</h1>
<button type="button" onclick="window.location.href='gerenciar_usuarios.php'">Gerenciar Usuarios</button>