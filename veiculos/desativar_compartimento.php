<?php
require_once("../checa_login.php");

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
}

$idCompartimento = $_GET["id"];

require_once("../conexao.php");

$sql = "UPDATE materiais_no_veiculo SET status = 'desativado' WHERE idCompartimento = $idCompartimento";
$conn->query($sql);

$sql = "UPDATE compartimento SET status = 'desativado' WHERE id = $idCompartimento";
$conn->query($sql);

header("Location: dados.php?id=$idVeiculo");
?>