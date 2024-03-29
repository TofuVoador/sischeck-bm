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

  if($qtd == '') $qtd = null;

  // Preparar a consulta SQL base
  $sql = "INSERT INTO materiais_no_veiculo (quantidade, idMaterial, idCompartimento, observacao) VALUES (?, ?, ?, ?)";

  // Preparar e executar a declaração SQL
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('iiis', $qtd, $idMaterial, $idCompartimento, $obs);
  $stmt->execute();

  // Redirecionar para a página de dados
  header("Location: dados.php?id=$idMaterial");
  exit;
}

// Verificar se há id
if(!isset($_GET["id"])) {
  header("Location: ../dashboard.php");
  exit;
}

$idMaterial = $_GET["id"];

if(!is_numeric($idMaterial)) {
  echo "ID não é um número válido";
  exit;
}

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
          WHERE m.idMaterial = $idMaterial AND m.idCompartimento = c.id AND m.status = 'ativo'
        ) and c.status = 'ativo'
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
    <h1>Alocar <?= $material['descricao'] ?></h1>
    <main>
      <form method="post">
        <input name="id" id="id" value="<?= $material['id'] ?>" hidden/>
        <label>Quantidade (Opcional):</label>
        <input class="input" type="number" name="qtd" id="qtd" min="1" max="99"/>
        <label>Compartimento:</label>
        <input class="input" list="compartimentos" id="comp" name="comp" placeholder="Digite o Prefixo Veículo" required/>
        <datalist id="compartimentos">
          <?php foreach ($compartimentos as $c) { ?>
            <option value="<?= $c['id'] ?>"><?= $c['prefixo'].'-'.$c['posfixo'].": ".$c['nome'] ?></option>
          <?php } ?>
        </datalist>
        <label>Observação (Opcional):</label>
        <input class="input" type="text" name="obs" id="obs" maxlength="250" placeholder="Patrimônio e outras especificações"/>
        <input type="submit" value="Alocar" class="button submit">
      </form>
    </main>
  </section>
</body>
</html>
