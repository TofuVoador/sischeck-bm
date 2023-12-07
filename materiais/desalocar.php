<?php
require_once("../checa_login.php");

// Verificar se o usuário é adm
if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

// Verificar se há id ou qtd
if(!isset($_POST["id"])) { 
  header("Location: ../dashboard.php");
  exit;
}

$idMNV = $_POST["id"];

require_once("../conexao.php");

$sql = "UPDATE materiais_no_veiculo SET status = 'inativo', quantidade = 0 WHERE id = $idMNV";
$conn->query($sql);

header("Location: index.php");
?>