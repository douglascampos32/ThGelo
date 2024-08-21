<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliente = $_POST['cliente'];
    $coco_qntd = $_POST['coco_qntd'];
    $maracuja_qntd = $_POST['maracuja_qntd'];
    $melancia_qntd = $_POST['melancia_qntd'];
    $maca_verde_qntd = $_POST['maca_verde_qntd'];
    $morango_qntd = $_POST['morango_qntd'];
    
    $obs = $_POST['obs'];
    $data_entrega = $_POST['data_entrega'];

     // Calcular o valor total do pedido
     $quantidade_total = $coco_qntd + $maracuja_qntd + $melancia_qntd + $maca_verde_qntd + $morango_qntd;
     $valor_total = $quantidade_total * $valor_unitario;

    // Verifica estoque
    $query_estoque = "SELECT * FROM inventory";
    $result_estoque = mysqli_query($conn, $query_estoque);
    $estoque = mysqli_fetch_assoc($result_estoque);

    $status = "liberado";
    if ($estoque['coco'] < $coco_qntd || $estoque['maracuja'] < $maracuja_qntd || 
        $estoque['melancia'] < $melancia_qntd || $estoque['maca_verde'] < $maca_verde_qntd || 
        $estoque['morango'] < $morango_qntd) {
        $status = "producao";
    }

    // Prepara e executa a inserção do pedido
    $query = "INSERT INTO orders (cliente, coco_qntd, maracuja_qntd, melancia_qntd, maca_verde_qntd, morango_qntd, obs, data_entrega, status) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssssssss", $cliente, $coco_qntd, $maracuja_qntd, $melancia_qntd, $maca_verde_qntd, $morango_qntd, $obs, $data_entrega, $status);
    $result = mysqli_stmt_execute($stmt);


    

    if ($result) {
        if ($status == "liberado") {
            // Atualizar estoque
            $query_update_estoque = "UPDATE inventory SET coco = coco - ?, maracuja = maracuja - ?, melancia = melancia - ?, maca_verde = maca_verde - ?, morango = morango - ?";
            $stmt_update = mysqli_prepare($conn, $query_update_estoque);
            mysqli_stmt_bind_param($stmt_update, "sssss", $coco_qntd, $maracuja_qntd, $melancia_qntd, $maca_verde_qntd, $morango_qntd);
            mysqli_stmt_execute($stmt_update);
            mysqli_stmt_close($stmt_update);
        }
        header("Location: orders.php");
        exit();
    } else {
        echo "Erro ao adicionar pedido: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
<link rel="stylesheet" type="text/css" href="styles.css">

<form method="POST" action="">
    <input type="text" name="cliente" placeholder="Nome do Cliente" required>
    
    <label>Quantidade de Sabores:</label>
    <div>
        <input type="number" name="coco_qntd" placeholder="Quantidade Coco" required>
    </div>
    <div>
        <input type="number" name="maracuja_qntd" placeholder="Quantidade Maracujá" required>
    </div>
    <div>
        <input type="number" name="melancia_qntd" placeholder="Quantidade Melancia" required>
    </div>
    <div>
        <input type="number" name="maca_verde_qntd" placeholder="Quantidade Maçã Verde" required>
    </div>
    <div>
        <input type="number" name="morango_qntd" placeholder="Quantidade Morango" required>
    </div>



    <textarea name="obs" placeholder="Observações"></textarea>
    <input type="date" name="data_entrega" required>
    <button type="submit">Adicionar Pedido</button>
    <a href="orders.php" class="back-button">Voltar</a>

</form>
