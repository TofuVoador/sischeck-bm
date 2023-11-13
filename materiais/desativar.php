<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

// Verificar se o usuário é adm
if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
}

// Verificar se há id
if(!isset($_GET["id"])) header("Location: ../dashboard.php");

$idMaterial = $_GET["id"];

require_once("../conexao.php");

$sql = "UPDATE materiais_no_veiculo SET status = 'inativo' where idMaterial = $idMaterial";
$result = $conn->query($sql);

$sql = "UPDATE material SET status = 'inativo' where id = $idMaterial";
$result = $conn->query($sql);

header("Location: index.php");
?>