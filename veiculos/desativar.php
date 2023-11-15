<?php
require_once("../checa_login.php");

// Verificar se o usuário é adm
if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

// Verificar se há id
if(!isset($_GET["id"])) {
  header("Location: ../dashboard.php");
  exit;
}

$idVeiculo = $_GET["id"];

require_once("../conexao.php");

//busca todos os compartimentos
$sql = "SELECT * from compartimento where idVeiculo = $idVeiculo";
$compartimentos = $conn->query($sql);

foreach($compartimentos as $comp) {
  $idCompartimento = $comp['id'];

  //busca todos os materiais_no_veiculo
  $sql = "SELECT * from materiais_no_veiculo where idCompartimento = $idCompartimento and status = 'ativo'";
  $mnvs = $conn->query($sql);

  foreach($mnvs as $mnv) {
    //adciona no almoxarifado
    $idMaterial = $mnv['idMaterial'];
    $qtd = $mnv['quantidade'];
    $sql = "UPDATE material SET quantidade = quantidade + $qtd WHERE id = $idMaterial";
    $conn->query($sql);
  }

  //desativa todos os mnvs do compartimento
  $sql = "UPDATE materiais_no_veiculo SET status = 'inativo' WHERE idCompartimento = $idCompartimento and status = 'ativo'";
  $conn->query($sql);

  //desativa o compartimento
  $sql = "UPDATE compartimento SET status = 'inativo' WHERE id = $idCompartimento";
  $conn->query($sql);
}

//desativa o veiculo
$sql = "UPDATE veiculo SET status = 'inativo' WHERE id = $idVeiculo";
$conn->query($sql);

#header("Location: index.php");
?>