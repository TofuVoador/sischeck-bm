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

$idMaterial = $_GET["id"];

if(!is_numeric($idMaterial)) {
  echo "ID não é um número válido";
  exit;
}

require_once("../conexao.php");

$sql = "UPDATE materiais_no_veiculo SET status = 'inativo' where idMaterial = $idMaterial";
$result = $conn->query($sql);

$sql = "UPDATE material SET status = 'inativo' where id = $idMaterial";
$result = $conn->query($sql);

header("Location: index.php");
exit;
?>