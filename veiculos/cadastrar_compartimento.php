<?php
require_once("../checa_login.php");

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

if(!isset($_GET["nome"], $_GET["idVeiculo"])) {
  header("Location: ../dashboard.php");
  exit;
}

$nome = $_GET["nome"];
$idVeiculo = $_GET["idVeiculo"];

if(!is_numeric($idVeiculo)) {
  echo "ID não é um número válido";
  exit;
}

require_once("../conexao.php");

// Preparar a consulta SQL
$sql = "INSERT INTO compartimento (nome, idVeiculo) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('si', $nome, $idVeiculo);

$stmt->execute();

header("Location: dados.php?id=$idVeiculo");
exit;
?>
