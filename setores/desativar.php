<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario']) || !isset($_GET["id"])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../index.php");
  exit();
}

$idSetor = $_GET["id"];

require_once("../conexao.php");

$sql = "UPDATE veiculo SET status = 'inativo' where idSetor = $idSetor";
$result = $conn->query($sql);

$sql = "UPDATE setor SET status = 'inativo' where id = $idSetor";
$result = $conn->query($sql);

header("Location: index.php");
?>