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

//verifica se há informações do formulário
if(isset($_GET['mat']) && isset($_GET['qtd'])) {
  $idMaterial = $_GET['mat'];
  $qtd = $_GET['qtd'];
  $sql = "INSERT INTO materiais_no_veiculo (quantidade, idMaterial, idCompartimento)
          values ($qtd, $idMaterial, $idCompartimento)";
  $conn->query($sql);

  header("Location: dados.php?id=$idMaterial");
}

//seleciona os dados do material
$sql = "SELECT * FROM compartimento where id = $idCompartimento";
$result = $conn->query($sql);
$compartimento = $result->fetch_assoc();

//seleciona todos os materiais que não estão no compartimento
$sql = "SELECT *
        FROM material as m
        WHERE NOT EXISTS (
            SELECT 1
            FROM materiais_no_veiculo AS mnv
            WHERE mnv.idMaterial = m.id AND mnv.idCompartimento = $idCompartimento
        )";
$materiais = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <?php require_once("../header.php") ?>
  <a class="button back-button" href="dados.php?id=<?= $compartimento['idVeiculo'] ?>">Veículo de <?= $compartimento['nome'] ?></a>
  <section>
    <main>
      <form>
        <input name="id" id="id" value="<?= $compartimento['id'] ?>" hidden/>
        <label>Quantidade:</label>
        <input class="input" type="number" name="qtd" id="qtd" value="1" min="1" required/>
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
  </section>
</body>
</html>