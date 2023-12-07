<?php 
require_once("../checa_login.php");

// Verificar se o usuário é adm
if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

if(!isset($_POST['mat']) || !isset($_POST['id'])) {
  header("Location: ../dashboard.php");
  exit;
}

$idMaterial = $_POST['mat'];
$idCompartimento = $_POST['id'];
$qtd = $_POST['qtd'];
$obs = $_POST['obs'];

require_once("../conexao.php");

$sql = "INSERT INTO materiais_no_veiculo (quantidade, observacao, idMaterial, idCompartimento)
values ($qtd, $obs, $idMaterial, $idCompartimento)";

$conn->query($sql);

header("Location: alocacoes.php?id=$idCompartimento");
exit;
?>