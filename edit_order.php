<?php
session_start();
include 'db.php'; // Conexão com o banco de dados

// Verificar se o usuário está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Verificar se o ID do pedido foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID do pedido não fornecido.";
    exit();
}

// Obter o ID do pedido
$order_id = $_GET['id'];

// Buscar os detalhes do pedido
$query = "SELECT * FROM orders WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$order = mysqli_fetch_assoc($result);

// Verificar se o pedido foi encontrado
if (!$order) {
    echo "Pedido não encontrado.";
    exit();
}

// Atualizar o pedido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliente = $_POST['cliente'];
    $coco_qntd = $_POST['coco_qntd'];
    $maracuja_qntd = $_POST['maracuja_qntd'];
    $melancia_qntd = $_POST['melancia_qntd'];
    $maca_verde_qntd = $_POST['maca_verde_qntd'];
    $morango_qntd = $_POST['morango_qntd'];
    $obs = $_POST['obs'];
    $data_entrega = $_POST['data_entrega'];

    $query_update = "UPDATE orders 
                     SET cliente = ?, coco_qntd = ?, maracuja_qntd = ?, melancia_qntd = ?, maca_verde_qntd = ?, morango_qntd = ?, obs = ?, data_entrega = ?
                     WHERE id = ?";
    $stmt_update = mysqli_prepare($conn, $query_update);
    mysqli_stmt_bind_param($stmt_update, "siiiiissi", $cliente, $coco_qntd, $maracuja_qntd, $melancia_qntd, $maca_verde_qntd, $morango_qntd, $obs, $data_entrega, $order_id);

    if (mysqli_stmt_execute($stmt_update)) {
        header("Location: orders.php"); // Redireciona para a página de pedidos
        exit();
    } else {
        echo "Erro ao atualizar pedido.";
    }

    mysqli_stmt_close($stmt_update);
}
mysqli_close($conn);
?>

<link rel="stylesheet" type="text/css" href="styles.css">
<form method="POST" action="">
    <input type="text" name="cliente" value="<?= htmlspecialchars($order['cliente']) ?>" placeholder="Nome do Cliente" required>
    <input type="number" name="coco_qntd" value="<?= htmlspecialchars($order['coco_qntd']) ?>" placeholder="Quantidade Coco" required>
    <input type="number" name="maracuja_qntd" value="<?= htmlspecialchars($order['maracuja_qntd']) ?>" placeholder="Quantidade Maracujá" required>
    <input type="number" name="melancia_qntd" value="<?= htmlspecialchars($order['melancia_qntd']) ?>" placeholder="Quantidade Melancia" required>
    <input type="number" name="maca_verde_qntd" value="<?= htmlspecialchars($order['maca_verde_qntd']) ?>" placeholder="Quantidade Maçã Verde" required>
    <input type="number" name="morango_qntd" value="<?= htmlspecialchars($order['morango_qntd']) ?>" placeholder="Quantidade Morango" required>
    <textarea name="obs" placeholder="Observações"><?= htmlspecialchars($order['obs']) ?></textarea>
    <input type="date" name="data_entrega" value="<?= htmlspecialchars($order['data_entrega']) ?>" required>
    <button type="submit">Atualizar Pedido</button>
</form>
