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

$idSetor = $_GET["id"];

//busca o nome do setor
$sql = "SELECT * FROM setor as s WHERE s.id = $idSetor";
$result = $conn->query($sql);
$setor = $result->fetch_assoc();

//busca o nome do setor
$sql = "SELECT v.* FROM veiculo as v WHERE v.idSetor = $idSetor";
$veiculos = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <?php require_once("../header.php") ?>
  <a class="button back-button" href="index.php">Setores</a>
  <section>
    <h1 class="title">Desativar <?= $setor['nome'] ?>?</h1>
    <main>
      <h1>Os seguintes veículos serão desativados:</h1>
      <ul>
        <?php foreach($veiculos as $v) { ?> 
          <li><?= $v['prefixo'].'-'.$v['posfixo'] ?></li>
        <?php } ?>
      </ul>
      <a class="button" href="desativar.php?id=<?= $setor['id'] ?>"  onclick="return confirm('Tem certeza de que deseja desativar?')">Confirmar<a>
    </main>
  </section>
</body>
</html>