<?php
require_once("../checa_login.php");

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

require_once("../conexao.php");

if(isset($_POST['prefixo']) && isset($_POST['posfixo'])) {
  $idVeiculo = $_POST['id'];
  $prefixo = $_POST['prefixo'];
  $posfixo = $_POST['posfixo'];
  $placa = $_POST['placa'];
  $marca = $_POST['marca'];
  $modelo = $_POST['modelo'];
  $setor = $_POST['setor'];

  $sql = "UPDATE veiculo SET 
          prefixo = '$prefixo', 
          posfixo = '$posfixo', 
          placa = '$placa', 
          marca = '$marca',
          modelo = '$modelo',
          idSetor = $setor
          WHERE id = $idVeiculo";
  var_dump($sql);
  $conn->query($sql);
  header("Location: dados.php?id=$idVeiculo");
  exit;
}

if(!isset($_GET['id'])) header("Location: dashboard.php");

$idVeiculo = $_GET['id'];

$sql = "SELECT * FROM veiculo as v WHERE v.id = $idVeiculo";
$result = $conn->query($sql);
$veiculo = $result->fetch_assoc();

$sql = "SELECT c.* FROM compartimento as c
        LEFT JOIN veiculo as v ON v.id = c.idVeiculo
        WHERE v.id = $idVeiculo";
$compartimentos = $conn->query($sql);

$sql = "SELECT * FROM setor as s";
$setores = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <?php require_once("../header.php") ?>
  <a class="button back-button" href="dados.php?id=<?= $veiculo['id'] ?>">Dados</a>
  <section>
    <h1 class="title">Alterar: <?= $veiculo['prefixo']."-".$veiculo['posfixo'] ?></h1>
    <main>
      <form method="post">
        <input name="id" value="<?= $idVeiculo ?>" hidden/>
        <label>Código</label>
        <div class="input-group">
          <input class="input" name="prefixo" value="<?= $veiculo['prefixo'] ?>" maxlength="10" placeholder="Prefixo" required/>
          <input class="input" name="posfixo" value="<?= $veiculo['posfixo'] ?>" maxlength="10" placeholder="Posfixo" required/>
        </div>
        <label>Placa</label>
        <input class="input" name="placa" value="<?= $veiculo['placa'] ?>" maxlength="10" required/>
        <label>Marca/Modelo</label>
        <div class="input-group">
          <input class="input" name="marca" value="<?= $veiculo['marca'] ?>" placeholder="Marca" maxlength="50" required/>
          <input class="input" name="modelo" value="<?= $veiculo['modelo'] ?>" placeholder="Modelo" maxlength="50" required/>
        </div>
        <label>Setor</label>
        <select class="input select" id="setor" name="setor">
          <?php foreach ($setores as $s) { ?>
            <option value="<?= $s['id'] ?>" <?php if($s['id'] == $veiculo['idSetor']) echo "selected"; ?>><?= $s['nome'] ?></option>
          <?php } ?>
        </select>
        <label></label>
        <input type="submit" value="Salvar" class="button">
      </form>
      <a class="button" href="confirmar.php?id=<?= $veiculo['id'] ?>">Desativar</a>
    </main>
  </section>
</body>
</html>