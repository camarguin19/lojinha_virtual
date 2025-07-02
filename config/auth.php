<?php
session_start();

if (
    !isset($_SESSION['admin_logado']) ||
    !isset($_SESSION['admin_usuario']) ||
    $_SESSION['admin_usuario']['tipo'] !== 'admin'
) {
    header('Location: login.php');
    exit();
}
