<?php
require_once("../checa_login.php");

// Verificar se o usuário é adm
if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

// Verificar se há id ou qtd
if(!isset($_GET["id"])) { 
  header("Location: ../dashboard.php");
  exit;
}

$idMNV = $_GET["id"];

require_once("../conexao.php");

$sql = "SELECT idMaterial FROM materiais_no_veiculo WHERE id = $idMNV";
$result = $conn->query($sql);
$idMaterial = $result->fetch_assoc()['idMaterial'];

$sql = "UPDATE materiais_no_veiculo SET status = 'inativo', quantidade = 0 WHERE id = $idMNV";
$conn->query($sql);

header("Location: dados.php?id=$idMaterial");
?>