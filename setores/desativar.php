<?php
require_once("../checa_login.php");

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
}

$idSetor = $_GET["id"];

require_once("../conexao.php");

$sql = "UPDATE veiculo SET status = 'inativo' where idSetor = $idSetor";
$conn->query($sql);

$sql = "UPDATE setor SET status = 'inativo' where id = $idSetor";
$conn->query($sql);

header("Location: index.php");
?>