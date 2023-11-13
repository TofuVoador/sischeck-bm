<?php
require_once("../checa_login.php");

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
}

$idVeiculo = $_GET['id'];

require_once("../conexao.php");

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
        <label>Código</label>
        <div class="input-group">
          <input class="input" name="veiculo['prefixo']" value="<?= $veiculo['prefixo'] ?>" placeholder="Prefixo" required/>
          <input class="input" name="veiculo['posfixo']" value="<?= $veiculo['posfixo'] ?>" placeholder="Posfixo" required/>
        </div>
        <label>Placa</label>
        <input class="input" name="veiculo['placa']" value="<?= $veiculo['placa'] ?>" required/>
        <label>Marca/Modelo</label>
        <div class="input-group">
          <input class="input" name="veiculo['marca']" value="<?= $veiculo['marca'] ?>" placeholder="Marca" required/>
          <input class="input" name="veiculo['modelo']" value="<?= $veiculo['modelo'] ?>" placeholder="Modelo" required/>
        </div>
        <label>Setor</label>
        <select class="input select" id="setor">
          <?php foreach ($setores as $s) { ?>
            <option value="<?php $s['id'] ?>" <?php if($s['id'] == $veiculo['idSetor']) echo "selected"; ?>><?= $s['nome'] ?></option>
          <?php } ?>
        </select>
        <label></label>
        <input type="submit" value="Salvar" class="button">
      </form>
      <a class="button" href="arquivar.php?id=<?= $veiculo['id'] ?>" onclick="return confirm('Tem certeza de que deseja arquivar?')">Arquivar</a>
    </main>
  </section>
</body>
</html>