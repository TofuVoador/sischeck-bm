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

if(isset($_GET['c'])) {
  $idCompartimento = $_GET['c'];
  $sql = "INSERT INTO materiais_no_veiculo ()";
  $result = $conn->query($sql);
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
        WHERE mnv.idMaterial <> $idMaterial
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
        <input id="mat" value="<?= $material['id'] ?>" hidden/>
        <label>Quantidade:</label>
        <input class="input" type="number" id="qtd" value="1" min="1" max="<?= $material['quantidade'] ?>"/>
        <label>Código do Compartimento:</label>
        <input class="input" list="compartimentos" id="comp" name="comp"/>
        <datalist id="compartimentos">
          <?php foreach ($compartimentos as $c) { ?>
            <option value="<?= $c['id'] ?>"><?= $c['prefixo'].'-'.$c['posfixo'].": ".$c['nome'] ?></option>
          <?php } ?>
        </datalist>
      </form>
    </main>
  </section>
</body>
</html>