<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario']) || !isset($_GET['id'])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../index.php");
  exit();
}

$idMaterial = $_GET['id'];

require_once("../conexao.php");

$sql = "SELECT * FROM materiais WHERE id = $idMaterial";
$material = $conn->query($sql);

$sql = "SELECT c.* FROM compartimento as c
        LEFT JOIN veiculo as v ON v.id = c.idVeiculo
        WHERE v.id = $idVeiculo";
$compartimentos = $conn->query($sql);

var_dump($compartimentos)
?>
<!DOCTYPE html>
<html>
<?php require_once("./head.html") ?>
<body>
  <?php require_once("header.php") ?>
  <a class="button back-button" href="../dashboard.php">Menu</a>
  <section>
  <h1 class="title">Alterar: <?= $veiculo['prefixo']."-".$veiculo['posfixo'] ?></h1>
    <main>
      <form>
        <label class="switch">
          <input type="checkbox" class="toggle-switch" 
          name="materials[<?=$mat['id_mnv']?>][check]"
          <?php if($veiculo['status'] == 'ativo') echo 'checked';?>>
          <span class="slider round"></span>
        </label>
        <div class="input-group">
          <input name="veiculo['prefixo']" value="<?= $veiculo['prefixo'] ?>"/>
          <input name="veiculo['posfixo']" value="<?= $veiculo['posfixo'] ?>"/>
        </div>
        <input name="veiculo['placa']" value="<?= $veiculo['placa'] ?>"/>
        <div class="input-group">
          <input name="veiculo['marca']" value="<?= $veiculo['marca'] ?>"/>
          <input name="veiculo['modelo']" value="<?= $veiculo['modelo'] ?>"/>
        </div>
        <input name="veiculo['renavan']" value="<?= $veiculo['renavan'] ?>"/>
      </form>
    </main>
  </section>
</body>
</html>