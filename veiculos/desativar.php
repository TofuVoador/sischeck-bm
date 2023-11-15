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

//desativa materiais no veículo
//desativa compartimentos
//desativa veiculo

#header("Location: index.php");
?>