<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Consulta para obter o inventário
$query = "SELECT * FROM inventory";
$result = mysqli_query($conn, $query);
$inventory = mysqli_fetch_assoc($result);
?>
<link rel="stylesheet" type="text/css" href="styles.css">

<nav>
    <a href="add_order.php">Adicionar Pedido</a>
    <a href="orders.php">Pedidos</a>
    <a href="add_inventory.php">Adicionar Estoque</a>
    <a href="logout.php">Logout</a>
</nav>

<table>
    <tr>
        <th>Sabor</th>
        <th>Quantidade</th>
    </tr>
    <tr>
        <td>Coco</td>
        <td><?= $inventory['coco'] ?></td>
    </tr>
    <tr>
        <td>Maracujá</td>
        <td><?= $inventory['maracuja'] ?></td>
    </tr>
    <tr>
        <td>Melancia</td>
        <td><?= $inventory['melancia'] ?></td>
    </tr>
    <tr>
        <td>Maçã Verde</td>
        <td><?= $inventory['maca_verde'] ?></td>
    </tr>
    <tr>
        <td>Morango</td>
        <td><?= $inventory['morango'] ?></td>
    </tr>
</table>

<?php
// Fechar a conexão
mysqli_close($conn);
?>
