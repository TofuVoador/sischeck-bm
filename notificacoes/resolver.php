<?php
require_once("../checa_login.php");

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
}

$idChecagem = $_GET['id'];

require_once("../conexao.php");

$sql = "UPDATE check_mnv SET resolvido = 1 where id = $idChecagem";

$conn->query($sql);

header("Location: index.php");
?>