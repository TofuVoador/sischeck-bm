<?php
require_once("../checa_login.php");

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

$idCompartimento = $_GET["id"];

require_once("../conexao.php");

//busca o compartimento
$sql = "SELECT * from compartimento where id = $idCompartimento";
$result = $conn->query($sql);
$compartimento = $result->fetch_assoc();
$idVeiculo = $compartimento['idVeiculo'];

//desativa todos os mnvs do compartimento
$sql = "UPDATE materiais_no_veiculo SET status = 'inativo' WHERE idCompartimento = $idCompartimento and status = 'ativo'";
$conn->query($sql);

//desativa o compartimento
$sql = "UPDATE compartimento SET status = 'inativo' WHERE id = $idCompartimento";
$conn->query($sql);

header("Location: dados.php?id=$idVeiculo");
exit;
?>
