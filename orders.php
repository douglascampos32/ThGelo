<?php
session_start();
include 'db.php'; // Conexão com o banco de dados

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Função para atualizar o status dos pedidos e dar baixa no estoque
function atualizar_status_pedidos($conn) {
    // Buscar o estoque atual
    $query_estoque = "SELECT * FROM inventory";
    $result_estoque = mysqli_query($conn, $query_estoque);
    $estoque = mysqli_fetch_assoc($result_estoque);

    // Buscar todos os pedidos
    $query_pedidos = "SELECT * FROM orders";
    $result_pedidos = mysqli_query($conn, $query_pedidos);

    while ($pedido = mysqli_fetch_assoc($result_pedidos)) {
        $novo_status = "liberado";

        // Verificar se há estoque suficiente para liberar o pedido
        if ($estoque['coco'] < $pedido['coco_qntd'] || 
            $estoque['maracuja'] < $pedido['maracuja_qntd'] || 
            $estoque['melancia'] < $pedido['melancia_qntd'] || 
            $estoque['maca_verde'] < $pedido['maca_verde_qntd'] || 
            $estoque['morango'] < $pedido['morango_qntd']) {
            $novo_status = "producao";
        }

        // Se o status foi alterado de "producao" para "liberado", dar baixa no estoque
        if ($pedido['status'] === 'producao' && $novo_status === 'liberado') {
            $query_update_estoque = "UPDATE inventory SET 
                coco = coco - ?, 
                maracuja = maracuja - ?, 
                melancia = melancia - ?, 
                maca_verde = maca_verde - ?, 
                morango = morango - ?";
            $stmt_update_estoque = mysqli_prepare($conn, $query_update_estoque);
            mysqli_stmt_bind_param($stmt_update_estoque, "iiiii", 
                $pedido['coco_qntd'], 
                $pedido['maracuja_qntd'], 
                $pedido['melancia_qntd'], 
                $pedido['maca_verde_qntd'], 
                $pedido['morango_qntd']);
            mysqli_stmt_execute($stmt_update_estoque);

            // Marcar o pedido como "processado" para não descontar novamente
            $query_marcar_processado = "UPDATE orders SET status = 'liberado' WHERE id = ?";
            $stmt_marcar_processado = mysqli_prepare($conn, $query_marcar_processado);
            mysqli_stmt_bind_param($stmt_marcar_processado, "i", $pedido['id']);
            mysqli_stmt_execute($stmt_marcar_processado);
        }
    }
}

// Chama a função para atualizar o status e dar baixa no estoque
atualizar_status_pedidos($conn);

// Buscar todos os pedidos para exibição
$query = "SELECT * FROM orders ORDER BY data_entrega ASC";
$result = mysqli_query($conn, $query);
?>
<link rel="stylesheet" type="text/css" href="styles.css">
<nav>
    <a href="add_order.php">Adicionar Pedido</a>
    <a href="add_inventory.php">Adicionar Estoque</a>
    <a href="inventory.php">Estoque</a>
    <a href="logout.php">Logout</a>
</nav>

<table>
    <tr>
        <th>N°</th>
        <th>Cliente</th>
        <th>Sabores e Quantidades</th>
        <th>Data de Entrega</th>
        <th>Valor do Pedido</th>
        <th>Observações</th>
        <th>Status</th>
        <th>Ações</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
    <td class="<?= $row['status'] == 'liberado' ? 'status-liberado' : 'status-producao' ?>">#<?= $row['id'] ?></td>
        
        <td><?= htmlspecialchars($row['cliente']) ?></td>
        <td>
            Coco: <?= htmlspecialchars($row['coco_qntd']) ?>, 
            Maracujá: <?= htmlspecialchars($row['maracuja_qntd']) ?>, 
            Melancia: <?= htmlspecialchars($row['melancia_qntd']) ?>, 
            Maçã Verde: <?= htmlspecialchars($row['maca_verde_qntd']) ?>, 
            Morango: <?= htmlspecialchars($row['morango_qntd']) ?>
        </td>
        <td><?= date('d/m/Y', strtotime($row['data_entrega'])) ?></td>
        <td><?= number_format(calcular_valor_pedido($row), 2, ',', '.') ?> R$</td>
        <td><?= htmlspecialchars($row['obs']) ?></td>
        <td><?= htmlspecialchars($row['status']) ?></td>
        <td>
            <a href="edit_order.php?id=<?= htmlspecialchars($row['id']) ?>">Editar</a> | 
            <a href="delete_order.php?id=<?= htmlspecialchars($row['id']) ?>">Excluir</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php
// Função para calcular o valor do pedido
function calcular_valor_pedido($order) {
    $valor_coco = 1.00;
    $valor_maracuja = 1.00;
    $valor_melancia = 1.00;
    $valor_maca_verde = 1.00;
    $valor_morango = 1.00;

    return $order['coco_qntd'] * $valor_coco +
           $order['maracuja_qntd'] * $valor_maracuja +
           $order['melancia_qntd'] * $valor_melancia +
           $order['maca_verde_qntd'] * $valor_maca_verde +
           $order['morango_qntd'] * $valor_morango;
}
?>
