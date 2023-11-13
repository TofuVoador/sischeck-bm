<?php
require_once("../checa_login.php");

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
}

$nome = $_GET["nome"];

require_once("../conexao.php");

$sql = "INSERT INTO setor (nome) values ('$nome')";

$conn->query($sql);

header("Location: index.php");
?>