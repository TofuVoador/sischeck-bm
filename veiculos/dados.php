<?php
require_once("../checa_login.php");

$idVeiculo = $_GET["id"];

require_once("../conexao.php");

$sql = "SELECT * FROM veiculo where id = $idVeiculo";
$result = $conn->query($sql);
$veiculo = $result->fetch_assoc();

$sql = "SELECT c.*, count(mnv.id) as 'materiais' FROM compartimento as c
        LEFT JOIN materiais_no_veiculo as mnv on mnv.idCompartimento = c.id
        WHERE c.idVeiculo = $idVeiculo and c.status = 'ativo'
        GROUP BY c.id";
$compartimentos = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <?php require_once("../header.php") ?>
  <a class="button back-button" href="index.php">Veículos</a>
  <section>
    <main>
      <h1><?php echo $veiculo['prefixo'] . "-" . $veiculo['posfixo'] ?></h1>
      <div>Placa: <?= $veiculo['placa'] ?></div>
      <div>Marca/Modelo: <?php echo $veiculo['marca'] . " " . $veiculo['modelo'] ?></div>
      <div>Status: <?= $veiculo['status'] ?></div>
      <a class="button" href="./alterar.php?id=<?=$veiculo['id']?>">Alterar</a>
      <a class="button" href="./verificar.php?id=<?=$veiculo['id']?>">Verificar</a>
    </main>
    <div>
      <form action="cadastrar_compartimento.php">
        <label>Novo Compartimento</label>
        <input class="input" name="idVeiculo" value="<?= $veiculo['id'] ?>" hidden/>
        <input class="input" name="nome" placeholder="Nome" required/>
        <input type="submit" value="Cadastrar" class="button">
      </form>
    </div>
    <div class="secondary-section">
      <h1>Compartimentos</h1>
      <?php $last_compartimento = null;     
      foreach ($compartimentos as $c) { ?>
        <div class="card">
          <h1><?= $c['nome'] ?></h1>
          <p><?= $c['materiais'] ?> materiais alocados</p>
          <p>
            <a class="button" href="desativar_compartimento.php?id=<?= $c['id'] ?>">Desativar</a>
            <a class="button" href="alocacoes.php?id=<?= $c['id'] ?>">Alocações</a>
          </p>
        </div>
      <?php } ?>
    </div>
  </section>
</body>
</html>
