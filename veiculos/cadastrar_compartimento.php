<?php
require_once("../checa_login.php");

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
}

$nome = $_GET["nome"];
$idVeiculo = $_GET["idVeiculo"];

require_once("../conexao.php");

$sql = "INSERT INTO compartimento (nome, idVeiculo) values ('$nome', $idVeiculo)";

$conn->query($sql);

header("Location: dados.php?id=$idVeiculo");
?>