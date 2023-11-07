<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario']) || !isset($_GET['id'])) {
    header("Location: ../index.php");
    exit();
}

if($_SESSION['usuario']['status'] != 'ativo') {
  header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
}

$idChecagem = $_GET['id'];

require_once("../conexao.php");

$sql = "ALTER check_mnv SET resolvido = 1 where id = $idChecagem";

var_dump($sql);

#$conn->query($sql);

#header("Location: index.php");
?>