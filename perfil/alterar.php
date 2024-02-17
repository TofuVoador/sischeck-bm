<?php
require_once("../checa_login.php");

if(!isset($_POST['user']) || !isset($_POST['nome'])) {
  header("Location: ../dashboard.php");
  exit;
}

$idUsuario = $_POST['user'];
$nome = $_POST['nome'];
$novaSenha = $_POST['novasenha'];
$confirmarNovaSenha = $_POST['confirmarnovasenha'];
$senhaAtual = $_POST['senha'];

if(!password_verify($senhaAtual, $usuario['senha'])) {
  echo "Senha Incorreta";
  exit;
}

require_once("../conexao.php");

if(!empty($novaSenha)) {

  if($novaSenha != $confirmarNovaSenha) {
    echo "Nova senha não confere";
    exit;
  }

  $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

  // Preparar a consulta SQL com parâmetros preparados
  $sql = "UPDATE usuario SET nome = ?, senha = ? WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ssi', $nome, $novaSenhaHash, $idUsuario);
} else {
  $sql = "UPDATE usuario SET nome = ? WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('si', $nome, $idUsuario);
}

// Executar a consulta SQL preparada
$stmt->execute();

$sql = "SELECT * FROM usuario WHERE id = $idUsuario";
$result = $conn->query($sql);

if ($result) {
  // Atualiza a sessão
  $user = $result->fetch_assoc();
  $_SESSION['usuario'] = $user;

  header("Location: index.php");
  exit;
} else {
  echo "Erro ao buscar dados atualizados do usuário.";
  exit;
}
?>
