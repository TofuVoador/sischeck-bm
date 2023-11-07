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
}

$idMaterial = $_GET["id"];

require_once("../conexao.php");

$sql = "UPDATE materiais_no_veiculo SET status = 'inativo' where idMaterial = $idMaterial";
$result = $conn->query($sql);

$sql = "UPDATE material SET status = 'inativo' where id = $idMaterial";
$result = $conn->query($sql);

header("Location: index.php");
?>