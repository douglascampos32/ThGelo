<?php
$conn = mysqli_connect(
    "localhost", // Host do MySQL
    "root",      // Nome de usuário do MySQL
    "",          // Senha do MySQL (deixe vazio se não houver)
    "sistema_pedidos" // Nome do banco de dados
);

if (!$conn) {
    die("Erro de conexão com o banco de dados: " . mysqli_connect_error());
} else {
    echo "";
}

?>


