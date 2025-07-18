<?php
session_start();

unset($_SESSION['carrinho']);

header("Location: index.php");
exit;
