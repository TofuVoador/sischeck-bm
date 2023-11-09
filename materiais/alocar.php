<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario']) || !isset($_GET["id"])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
}

$idMaterial = $_GET["id"];

require_once("../conexao.php");

if(isset($_GET['comp']) && isset($_GET['qtd'])) {
  $idCompartimento = $_GET['comp'];
  $qtd = $_GET['qtd'];
  $sql = "INSERT INTO materiais_no_veiculo (quantidade, idMaterial, idCompartimento)
          values ($qtd, $idMaterial, $idCompartimento)";
  $conn->query($sql);

  $sql = "UPDATE material SET quantidade = quantidade - $qtd where id = $idMaterial";
  $conn->query($sql);

  
}

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
        ORDER BY v.prefixo, v.posfixo, c.nome";
$compartimentos = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <?php require_once("../header.php") ?>
  <a class="button back-button" href="dados.php?id=<?= $material['id'] ?>">Dados de <?= $material['descricao'] ?></a>
  <section>
    <main>
      <form>
        <input name="id" id="id" value="<?= $material['id'] ?>" hidden/>
        <label>Quantidade:</label>
        <input class="input" type="number" name="qtd" id="qtd" value="1" min="1" max="<?= $material['quantidade'] ?>"/>
        <label>Compartimento:</label>
        <input class="input" list="compartimentos" id="comp" name="comp" placeholder="Digite o Prefixo Veículo"/>
        <datalist id="compartimentos">
          <?php foreach ($compartimentos as $c) { ?>
            <option value="<?= $c['id'] ?>"><?= $c['prefixo'].'-'.$c['posfixo'].": ".$c['nome'] ?></option>
          <?php } ?>
        </datalist>
        <input type="submit" value="Alocar" class="button">
      </form>
    </main>
  </section>
</body>
</html>