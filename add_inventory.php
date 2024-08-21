<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $coco = $_POST['coco'];
    $maracuja = $_POST['maracuja'];
    $melancia = $_POST['melancia'];
    $maca_verde = $_POST['maca_verde'];
    $morango = $_POST['morango'];

    // Consulta SQL para atualizar o estoque
    $query = "UPDATE inventory 
              SET coco = coco + ?, maracuja = maracuja + ?, melancia = melancia + ?, 
                  maca_verde = maca_verde + ?, morango = morango + ?";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iiiii", $coco, $maracuja, $melancia, $maca_verde, $morango);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: inventory.php");
        exit();
    } else {
        echo "Erro ao adicionar estoque.";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
<link rel="stylesheet" type="text/css" href="styles.css">

<form method="POST" action="">
    <input type="number" name="coco" placeholder="Adicionar Coco" required>
    <input type="number" name="maracuja" placeholder="Adicionar Maracujá" required>
    <input type="number" name="melancia" placeholder="Adicionar Melancia" required>
    <input type="number" name="maca_verde" placeholder="Adicionar Maçã Verde" required>
    <input type="number" name="morango" placeholder="Adicionar Morango" required>
    <button type="submit">Adicionar Estoque</button>
    <a href="orders.php" class="back-button">Voltar</a>
</form>
