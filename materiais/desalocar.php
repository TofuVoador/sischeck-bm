<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario']) || !isset($_POST["id"]) || !isset($_POST["qtd"])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit();
}

$idMNV = $_POST["id"];
$qtd = $_POST["qtd"];

require_once("../conexao.php");

$sql = "SELECT mnv.* FROM materiais_no_veiculo as mnv where mnv.id = $idMNV";
$result = $conn->query($sql);
$mnv = $result->fetch_assoc();

$novaQuantidade = $mnv['quantidade'] - $qtd;

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