<?php
require_once("../checa_login.php");

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

$nome = $_GET["nome"];
$idCompartimento = $_GET["comp"];
$idVeiculo = $_GET["idVeiculo"];

if($idCompartimento == "") $idCompartimento = "null";

require_once("../conexao.php");

$sql = "INSERT INTO compartimento (nome, idVeiculo, idCompartimento) values ('$nome', $idVeiculo, $idCompartimento)";

$conn->query($sql);

header("Location: dados.php?id=$idVeiculo");
exit;
?>