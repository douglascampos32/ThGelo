<?php
session_start();

// Verifica se o usuário está autenticado
if (isset($_SESSION['username'])) {
    header("Location: orders.php"); // Redireciona para a página de pedidos
    exit();
} else {
    header("Location: login.php"); // Redireciona para a página de login
    exit();
}
