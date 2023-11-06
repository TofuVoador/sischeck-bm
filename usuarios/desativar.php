<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario']) || !isset($_GET["id"])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit();
}

$idUsuario = $_GET["id"];

require_once("../conexao.php");

$sql = "UPDATE usuario SET status = 'inativo' where id = $idUsuario";
$conn->query($sql);

header("Location: index.php");
?>