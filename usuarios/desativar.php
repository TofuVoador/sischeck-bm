<?php
require_once("../checa_login.php");

if($usuario['tipo'] != 'administrador') {
  header("Location: ../dashboard.php");
  exit();
}

if(!isset($_GET["id"])) {
  header("Location: ../dashboard.php");
  exit;
}

$idUsuario = $_GET["id"];

if(!is_numeric($idUsuario)) {
  echo "ID não é um número válido";
  exit;
}

require_once("../conexao.php");

$sql = "SELECT * FROM usuario WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if($user['tipo'] == "administrador") {
  $sql = "SELECT COUNT(*) AS num_administradores FROM usuario WHERE tipo = 'administrador' AND status = 'ativo'";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $result = $stmt->get_result();
  $rowCheckAdmin = $result->fetch_assoc();
  $numAdministradores = $rowCheckAdmin['num_administradores'];

  if($numAdministradores <= 1) {
    ?><div class="alert-notif"> Não é possível inativar este usuário pois é o único administrador. Defina mais um administrador para poder inativar este. </div> <?php
    exit;
  }
}

$sql = "UPDATE usuario SET status = 'inativo' where id = $idUsuario";
$conn->query($sql);

header("Location: index.php");
exit;
?>