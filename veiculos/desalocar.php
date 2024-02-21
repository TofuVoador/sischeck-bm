<?php
require_once("../checa_login.php");

// Verificar se o usuário é adm
if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

// Verificar se há id ou qtd
if(!isset($_GET["id"])) { 
  header("Location: ../dashboard.php");
  exit;
}

$idMNV = $_GET["id"];

if(!is_numeric($idMNV)) {
  echo "ID não é um número válido";
  exit;
}

require_once("../conexao.php");

$sql = "UPDATE materiais_no_veiculo SET status = 'inativo' WHERE id = $idMNV";
$conn->query($sql);

$sql = "SELECT c.id as 'idCompartimento' from materiais_no_veiculo as mnv
        LEFT JOIN compartimento as c on c.id = mnv.idCompartimento
        WHERE mnv.id = $idMNV";
$result = $conn->query($sql);
$idCompartimento = $result->fetch_assoc()['idCompartimento'];

header("Location: dados.php?id=$idCompartimento");
?>
