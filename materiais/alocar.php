<?php
require_once("../checa_login.php");

// Verificar se o usuário é adm
if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

require_once("../conexao.php");

//verifica se há informações do formulário
if(isset($_POST['comp']) && isset($_POST['id'])) {
  $idMaterial = $_POST['id'];
  $idCompartimento = $_POST['comp'];
  $qtd = $_POST['qtd'];
  $obs = $_POST['obs'];

  $sql = "INSERT INTO materiais_no_veiculo (quantidade, observacao, idMaterial, idCompartimento)
          values ($qtd, $obs, $idMaterial, $idCompartimento)";
  $conn->query($sql);

  header("Location: dados.php?id=$idMaterial");
  exit;
}

// Verificar se há id
if(!isset($_GET["id"])) header("Location: ../dashboard.php");

$idMaterial = $_GET["id"];

//seleciona os dados do material
$sql = "SELECT * FROM material where id = $idMaterial";
$result = $conn->query($sql);
$material = $result->fetch_assoc();

//seleciona todos os compartimentos que não possuem o material
$sql = "SELECT c.*, v.prefixo, v.posfixo
        FROM compartimento as c
        LEFT JOIN veiculo as v on v.id = c.idVeiculo
        LEFT JOIN materiais_no_veiculo as mnv on mnv.idCompartimento = c.id
        WHERE NOT EXISTS (
          SELECT 1
          FROM materiais_no_veiculo AS m
          WHERE m.idMaterial = $idMaterial AND m.idCompartimento = c.id
        )
        GROUP BY c.id
        ORDER BY v.prefixo, v.posfixo, c.nome";
$compartimentos = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <?php require_once("../header.php") ?>
  <a class="button back-button" href="dados.php?id=<?= $material['id'] ?>">Dados</a>
  <section>
    <main>
      <form method="post">
        <input name="id" id="id" value="<?= $material['id'] ?>" hidden/>
        <label>Quantidade (Opcional):</label>
        <input class="input" type="number" name="qtd" id="qtd" min="1"/>
        <label>Compartimento:</label>
        <input class="input" list="compartimentos" id="comp" name="comp" placeholder="Digite o Prefixo Veículo" required/>
        <datalist id="compartimentos">
          <?php foreach ($compartimentos as $c) { ?>
            <option value="<?= $c['id'] ?>"><?= $c['prefixo'].'-'.$c['posfixo'].": ".$c['nome'] ?></option>
          <?php } ?>
        </datalist>
        <label>Observação (Opcional):</label>
        <input class="input" type="text" name="obs" id="obs" placeholder="Patrimônio e outras especificações"/>
        <input type="submit" value="Alocar" class="button submit">
      </form>
    </main>
  </section>
</body>
</html>