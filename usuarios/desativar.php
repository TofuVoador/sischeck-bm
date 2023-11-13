<?php
require_once("../checa_login.php");

if($usuario['tipo'] != 'administrador') {
  header("Location: ../dashboard.php");
  exit();
}

$idUsuario = $_GET["id"];

require_once("../conexao.php");

$sql = "UPDATE usuario SET status = 'inativo' where id = $idUsuario";
$conn->query($sql);

header("Location: index.php");
?>