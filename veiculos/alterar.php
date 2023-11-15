<?php
require_once("../checa_login.php");

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

$idVeiculo = $_GET['id'];

require_once("../conexao.php");

if(isset($_GET['prefixo']) && isset($_GET['posfixo'])) {
  $prefixo = $_GET['prefixo'];
  $posfixo = $_GET['posfixo'];
  $placa = $_GET['placa'];
  $marca = $_GET['marca'];
  $modelo = $_GET['modelo'];
  $setor = $_GET['setor'];

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
  <a class="button back-button" href="dados.php?id=<?= $veiculo['id'] ?>">Dados de <?= $veiculo['prefixo']."-".$veiculo['posfixo'] ?></a>
  <section>
    <h1 class="title">Alterar: <?= $veiculo['prefixo']."-".$veiculo['posfixo'] ?></h1>
    <main>
      <form>
        <input name="id" value="<?= $idVeiculo ?>" hidden/>
        <label>Código</label>
        <div class="input-group">
          <input class="input" name="prefixo" value="<?= $veiculo['prefixo'] ?>" placeholder="Prefixo" required/>
          <input class="input" name="posfixo" value="<?= $veiculo['posfixo'] ?>" placeholder="Posfixo" required/>
        </div>
        <label>Placa</label>
        <input class="input" name="placa" value="<?= $veiculo['placa'] ?>" required/>
        <label>Marca/Modelo</label>
        <div class="input-group">
          <input class="input" name="marca" value="<?= $veiculo['marca'] ?>" placeholder="Marca" required/>
          <input class="input" name="modelo" value="<?= $veiculo['modelo'] ?>" placeholder="Modelo" required/>
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