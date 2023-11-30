<?php
require_once("../checa_login.php");

// Verificar se o usuário é adm
if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

// Verificar se há id
if(!isset($_GET["id"])) {
  header("Location: ../dashboard.php");
  exit;
}

require_once("../conexao.php");

$idVeiculo = $_GET["id"];

//busca os dados do veículo
$sql = "SELECT * FROM veiculo as v WHERE v.id = $idVeiculo";
$result = $conn->query($sql);
$veiculo = $result->fetch_assoc();

//busca todos os compartimentos e materiais
$sql = "SELECT c.id, COUNT(mnv.idCompartimento) AS 'materiais'
        FROM compartimento AS c
        LEFT JOIN materiais_no_veiculo AS mnv ON mnv.idCompartimento = c.id
        LEFT JOIN veiculo AS v ON v.id = c.idVeiculo
        WHERE v.id = $idVeiculo and mnv.status = 'ativo' 
        GROUP BY c.id";
$compartimentos = $conn->query($sql);
$compartimentosCount = $compartimentos->num_rows;

$materiaisCount = 0;
foreach ($compartimentos as $comp) {
  $materiaisCount += $comp['materiais'];
}
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <?php require_once("../header.php") ?>
  <a class="button back-button" href="dados.php?id=<?= $veiculo['id'] ?>">Dados</a>
  <section>
    <h1 class="title">Desativar <?= $veiculo['prefixo']."-".$veiculo['posfixo'] ?>?</h1>
    <main>
      <h1><?= $compartimentosCount ?> compartimentos serão desativados</h1>
      <h1><?= $materiaisCount ?> materiais serão removidos</h1>
      <a class="button" href="desativar.php?id=<?= $veiculo['id'] ?>"  onclick="return confirm('Tem certeza de que deseja desativar?')">Confirmar<a>
    </main>
  </section>
</body>
</html>