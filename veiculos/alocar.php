<?php 
require_once("../checa_login.php");

// Verificar se o usuário é adm
if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

if(!isset($_POST['mat']) || !isset($_POST['id'])) {
  header("Location: ../dashboard.php");
  exit;
}

$idMaterial = $_POST['mat'];
$idCompartimento = $_POST['id'];
$qtd = $_POST['qtd'];
$obs = $_POST['obs'];

require_once("../conexao.php");

// Preparar a consulta SQL base
$sql = "INSERT INTO materiais_no_veiculo (quantidade, observacao, idMaterial, idCompartimento)
        VALUES (?, ?, ?, ?)";

// Preparar e executar a consulta SQL
$stmt = $conn->prepare($sql);

// Verificar se a quantidade está vazia e vincular null se for o caso
if(empty($qtd)) {
  $qtd = null;
  $stmt->bind_param('isii', $qtd, $obs, $idMaterial, $idCompartimento);
} else {
  $stmt->bind_param('dsii', $qtd, $obs, $idMaterial, $idCompartimento);
}

$stmt->execute();

header("Location: alocacoes.php?id=$idCompartimento");
exit;
?>
