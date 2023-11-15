<?php
require_once("../checa_login.php");

// Verificar se o usuário é adm
if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

// Verificar se há id ou qtd
if(!isset($_GET["id"]) || !isset($_GET["qtd"])) { 
  header("Location: ../dashboard.php");
  exit;
}

$idMNV = $_POST["id"];
$qtd = $_POST["qtd"];

require_once("../conexao.php");

$sql = "SELECT mnv.* FROM materiais_no_veiculo as mnv where mnv.id = $idMNV";
$result = $conn->query($sql);
$mnv = $result->fetch_assoc();

$novaQuantidade = $mnv['quantidade'] - $qtd;

//se ficar vazio, desativa o mnv
if ($novaQuantidade == 0) {
  $sql = "UPDATE materiais_no_veiculo SET status = 'inativo', quantidade = 0 WHERE id = $idMNV";
} else {
  $sql = "UPDATE materiais_no_veiculo SET quantidade = $novaQuantidade WHERE id = $idMNV";
}
$conn->query($sql);

$sql = "UPDATE material SET quantidade = quantidade + $qtd WHERE id = {$mnv['idMaterial']}";

$conn->query($sql);

header("Location: dados.php?id={$mnv['idMaterial']}");
?>