<?php
require_once("../checa_login.php");

if(!isset($_POST['user']) || !isset($_POST['nome']) || !isset($_POST['novasenha'])) {
  header("Location: ../dashboard.php");
  exit;
}

$idUsuario = $_POST['user'];
$nome = $_POST['nome'];
$novaSenha = $_POST['novasenha'];
$senha = $_POST['senha'];

if($senha == $usuario['senha']) {
  header("Location: ../dashboard.php");
  exit;
}

$sql = "UPDATE usuario SET 
        nome = '$nome', 
        senha = '$novaSenha'
        WHERE id = $idUsuario";
$conn->query($sql);

//Busca pelo usuário
$sql = "SELECT * FROM usuario WHERE id = $idUsuario";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
  $usuario = $result->fetch_assoc();

  $_SESSION['usuario'] = $usuario;

  header("Location: ../dashboard.php");
  exit;
} else {
  echo "Erro ao buscar dados atualizados do usuário.";
}
?>