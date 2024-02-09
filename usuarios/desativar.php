<?php
require_once("../checa_login.php");

if($usuario['tipo'] != 'administrador') {
  header("Location: ../dashboard.php");
  exit();
}

$idUsuario = $_GET["id"];

if(!is_numeric($idUsuario)) {
  echo "ID não é um número válido";
  exit;
}

require_once("../conexao.php");

$sql = "UPDATE usuario SET status = 'inativo' where id = $idUsuario";
$conn->query($sql);

header("Location: index.php");
exit;
?>