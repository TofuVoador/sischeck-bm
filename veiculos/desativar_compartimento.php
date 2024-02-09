<?php
require_once("../checa_login.php");

// Verifica se o usuário é adm
if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

// Verifica se o parâmetro "id" foi passado pela URL
if(!isset($_GET["id"])) {
  // Se não foi passado, redireciona de volta para a página de dados
  header("Location: dados.php");
  exit;
}

$idCompartimento = $_GET["id"];

if(!is_numeric($idCompartimento)) {
  echo "ID não é um número válido";
  exit;
}

require_once("../conexao.php");

// Preparar a consulta SQL para buscar o compartimento
$sql = "SELECT * FROM compartimento WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $idCompartimento);
$stmt->execute();
$compartimento = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$compartimento) {
  // Se o compartimento não for encontrado, redireciona de volta para a página de dados
  header("Location: dados.php");
  exit;
}

$idVeiculo = $compartimento['idVeiculo'];

// Preparar e executar a consulta SQL para desativar todos os mnvs do compartimento
$sql = "UPDATE materiais_no_veiculo SET status = 'inativo' WHERE idCompartimento = ? AND status = 'ativo'";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $idCompartimento);
$stmt->execute();

// Preparar e executar a consulta SQL para desativar o compartimento
$sql = "UPDATE compartimento SET status = 'inativo' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $idCompartimento);
$stmt->execute();

// Redirecionar para a página de dados do veículo
header("Location: dados.php?id=$idVeiculo");
exit;
?>