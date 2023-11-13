<?php
require_once("../checa_login.php");

// Verificar se o usuário é adm
if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
}

// Verificar se há id
if(!isset($_GET["id"])) header("Location: ../dashboard.php");

$idMaterial = $_GET["id"];

require_once("../conexao.php");

$sql = "UPDATE materiais_no_veiculo SET status = 'inativo' where idMaterial = $idMaterial";
$result = $conn->query($sql);

$sql = "UPDATE material SET status = 'inativo' where id = $idMaterial";
$result = $conn->query($sql);

header("Location: index.php");
?>