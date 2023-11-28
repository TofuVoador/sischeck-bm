<?php
require_once("../checa_login.php");

// Verificar se o usuário é adm
if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

// Verificar se há id
if(!isset($_GET["id"])) header("Location: ../dashboard.php");

$idCompartimento = $_GET["id"];

require_once("../conexao.php");

//seleciona os dados do material
$sql = "SELECT * FROM compartimento where id = $idCompartimento";
$result = $conn->query($sql);
$compartimento = $result->fetch_assoc();

//verifica se há informações do formulário
if(isset($_GET['mat'])) {
  $idMaterial = $_GET['mat'];
  $qtd = $_GET['qtd'];

  if($qtd == '') $qtd = 'null';

  $sql = "INSERT INTO materiais_no_veiculo (quantidade, idMaterial, idCompartimento)
  values ($qtd, $idMaterial, $idCompartimento)";

  $conn->query($sql);
}

//seleciona todos os materiais que não estão no compartimento
$sql = "SELECT *
        FROM material as m
        WHERE NOT EXISTS (
            SELECT 1
            FROM materiais_no_veiculo AS mnv
            WHERE mnv.idMaterial = m.id AND mnv.idCompartimento = $idCompartimento
        )";
$materiais = $conn->query($sql);

$sql = "SELECT mnv.*, m.id, m.descricao
        FROM materiais_no_veiculo as mnv
        LEFT JOIN material as m on m.id = mnv.idMaterial
        WHERE mnv.idCompartimento = $idCompartimento";
$mnv = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <?php require_once("../header.php") ?>
  <a class="button back-button" href="dados.php?id=<?= $compartimento['idVeiculo'] ?>">Veículo de <?= $compartimento['nome'] ?></a>
  <section>
    <h1>Alocar em <?= $compartimento['nome'] ?></h1>
    <main>
      <form>
        <input name="id" id="id" value="<?= $compartimento['id'] ?>" hidden/>
        <label>Quantidade:</label>
        <input class="input" type="number" name="qtd" id="qtd" min="1"/>
        <label>Material:</label>
        <input class="input" list="materiais" id="mat" name="mat" placeholder="Descrição do Material" required/>
        <datalist id="materiais">
          <?php foreach ($materiais as $m) { ?>
            <option value="<?= $m['id'] ?>"><?= $m['descricao'] ?></option>
          <?php } ?>
        </datalist>
        <input type="submit" value="Alocar" class="button">
      </form>
    </main>
    <div class="secondary-section">
      <h1>Materias em <?= $compartimento['nome'] ?></h1>
      <?php foreach ($mnv as $material) { ?>
        <div class="card">
          <h1><?= $material['descricao'] ?></h1>
          <p>Quantidade: <?= $material['quantidade'] != null ? $material['quantidade'] : 'Indefinida' ?></p>
        </div>
      <?php } ?>
    </div>
  </section>
</body>
</html>