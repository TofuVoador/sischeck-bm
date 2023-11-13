<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

// Verificar se o usuário está ativo
if($_SESSION['usuario']['status'] != 'ativo') {
  header("Location: ../index.php");
    exit();
}
?>