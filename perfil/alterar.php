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

if($senha != $usuario['senha']) {
  header("Location: ../dashboard.php");
  exit;
}

require_once("../conexao.php");

// Preparar a consulta SQL com parâmetros preparados
$sql = "UPDATE usuario SET nome = ?, senha = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ssi', $nome, $novaSenha, $idUsuario);

// Executar a consulta SQL preparada
$stmt->execute();

// Busca pelo usuário atualizado
$sql = "SELECT * FROM usuario WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $idUsuario);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
  // Atualiza a sessão
  $_SESSION['usuario'] = $result;

  header("Location: index.php");
  exit;
} else {
  echo "Erro ao buscar dados atualizados do usuário.";
}
?>
