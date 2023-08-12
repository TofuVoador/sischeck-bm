<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

// Exibir informações do usuário logado
echo "Bem-vindo, " . $usuario['nome'] . "!";
?>