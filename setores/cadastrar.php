<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario']) || !isset($_GET["nome"])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
}

$nome = $_GET["nome"];

require_once("../conexao.php");

$sql = "INSERT INTO setor (nome) values ('$nome')";

$conn->query($sql);

header("Location: index.php");
?>