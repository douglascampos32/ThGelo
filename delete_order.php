<?php
session_start();
include 'db.php'; // Conexão com o banco de dados

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Verifica se o ID do pedido foi passado via URL
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // Verifica se o formulário de confirmação foi enviado
    if (isset($_POST['confirm']) && $_POST['confirm'] == 'yes') {
        // Excluir o pedido do banco de dados
        $query_delete = "DELETE FROM orders WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query_delete);
        mysqli_stmt_bind_param($stmt, "i", $order_id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: orders.php");
            exit();
        } else {
            echo "Erro ao excluir o pedido.";
        }
    } elseif (isset($_POST['confirm']) && $_POST['confirm'] == 'no') {
        // Se o usuário escolher não, redireciona de volta para a lista de pedidos
        header("Location: orders.php");
        exit();
    }
} else {
    header("Location: orders.php");
    exit();
}
?>

<link rel="stylesheet" type="text/css" href="styles.css">
<h2>Confirmar Exclusão</h2>
<p>Você tem certeza que deseja excluir este pedido?</p>

<form method="POST" action="">
    <button type="submit" name="confirm" value="yes">Sim, excluir</button>
    <button type="submit" name="confirm" value="no">Não, voltar</button>
</form>


