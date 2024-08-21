<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash da senha
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Inserção no banco de dados
    $query = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $hashed_password);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: login.php"); // Redireciona para a página de login
        exit();
    } else {
        echo "Erro ao cadastrar usuário.";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
<link rel="stylesheet" type="text/css" href="styles.css">

<form method="POST" action="">
    <input type="text" name="username" placeholder="Nome de Usuário" required>
    <input type="password" name="password" placeholder="Senha" required>
    <button type="submit">Cadastrar</button>
</form>
