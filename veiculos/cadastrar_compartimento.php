<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario']) || !isset($_GET["nome"]) || !isset($_GET["idVeiculo"])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

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