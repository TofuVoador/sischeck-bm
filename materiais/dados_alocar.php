<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario']) || !isset($_GET["id"]) || !isset($_GET['c'])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
}

$idMaterial = $_GET["id"];
$idCompartimento = $_GET['c'];

require_once("../conexao.php");

$sql = "SELECT * FROM material where id = $idMaterial";
$result = $conn->query($sql);
$material = $result->fetch_assoc();



$sql = "SELECT * FROM materiais_no_veiculo where idMaterial = $idMaterial";
$result = $conn->query($sql);
$mnv = $result->fetch_assoc();

$sql = "SELECT c.*, v.prefixo, v.posfixo
        FROM compartimento as c
        LEFT JOIN veiculo as v on v.id = c.idVeiculo
        LEFT JOIN materiais_no_veiculo as mnv on mnv.idCompartimento = c.id
        WHERE mnv.idMaterial <> $idMaterial
        ORDER BY v.prefixo, v.posfixo, c.nome";
$compartimentos = $conn->query($sql);

if(isset("qtd")) {
  $sql = "INSERT INTO materiais_no_veiculo ()";
  $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <?php require_once("../header.php") ?>
  <a class="button back-button" href="dados.php?id=<?= $material['id'] ?>">Dados de <?= $material['descricao'] ?></a>
  <section>
    <main>
      <h1>Escolha onde alocar:</h1>
      <?php 
        $current = null;
        foreach ($compartimentos as $c) { ?>
        <?php if($c['prefixo'].'-'.$c['posfixo'] != $current) {
          echo "<h1>".$c['prefixo'].'-'.$c['posfixo']."</h1>";
          $current = $c['prefixo'].'-'.$c['posfixo'];
        }
        ?>
        <a class="button" href="dados_alocar.php?id=<?= $material['id'] ?>&c=<?= $c['id'] ?>"><?= $c['nome'] ?></a>
      <?php } ?>
    </main>
  </section>
</body>
</html>