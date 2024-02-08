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

$idSetor = $_GET["id"];

require_once("../conexao.php");

//busca todos veiculos
$sql = "SELECT * from veiculo where idSetor = $idSetor";
$veiculos = $conn->query($sql);

foreach($veiculos as $vei) {
  $idVeiculo = $vei['id'];

  //busca todos os compartimentos
  $sql = "SELECT * from compartimento where idVeiculo = $idVeiculo";
  $compartimentos = $conn->query($sql);

  foreach($compartimentos as $comp) {
    $idCompartimento = $comp['id'];

    //busca todos os materiais_no_veiculo
    $sql = "SELECT * from materiais_no_veiculo where idCompartimento = $idCompartimento and status = 'ativo'";
    $mnvs = $conn->query($sql);

    //desativa todos os mnvs do compartimento
    $sql = "UPDATE materiais_no_veiculo SET status = 'inativo' WHERE idCompartimento = $idCompartimento";
    $conn->query($sql);

    //desativa o compartimento
    $sql = "UPDATE compartimento SET status = 'inativo' WHERE id = $idCompartimento";
    $conn->query($sql);
  }

  //desativa o veiculo
  $sql = "UPDATE veiculo SET status = 'inativo' WHERE id = $idVeiculo";
  $conn->query($sql);
}

//desativa o setor
$sql = "UPDATE setor SET status = 'inativo' WHERE id = $idSetor";
$conn->query($sql);

header("Location: index.php");
?>
