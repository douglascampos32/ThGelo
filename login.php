<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta no banco de dados
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verifica a senha usando password_verify
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            header("Location: orders.php"); // Redireciona para a página de pedidos
            exit();
        } else {
            echo "Usuário ou senha inválidos.";
        }
    } else {
        echo "Usuário ou senha inválidos.";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
<link rel="stylesheet" type="text/css" href="styles.css">

<form id="login-form" method="POST" action="">
    <input type="text" name="username" placeholder="Nome de Usuário" required>
    <input type="password" name="password" placeholder="Senha" required>
    <button type="submit">Login</button>
    
    <?php if (isset($error_message)): ?>
        <p class="error"><?= $error_message ?></p>
    <?php endif; ?>
</form>
