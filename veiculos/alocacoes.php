<?php
require_once("../checa_login.php");

// Verificar se o usuário é adm
if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

require_once("../conexao.php");

// Verificar se há id
if(!isset($_GET["id"])) {
  header("Location: ../dashboard.php");
  exit;
}

$idCompartimento = $_GET["id"];

if(!is_numeric($idCompartimento)) {
  echo "ID não é um número válido";
  exit;
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
            WHERE mnv.idMaterial = m.id AND mnv.idCompartimento = $idCompartimento AND mnv.status = 'ativo'
        )";
$materiais = $conn->query($sql);

$sql = "SELECT mnv.*, m.descricao, ch.ok, ch.observacao as 'ch_obs', ch.resolvido, ch.data_check
        FROM materiais_no_veiculo as mnv
        LEFT JOIN material as m on m.id = mnv.idMaterial
        LEFT JOIN (
              SELECT idMateriais_no_veiculo, MAX(data_check) as max_data
              FROM check_mnv
              GROUP BY idMateriais_no_veiculo
          ) as max_ch ON mnv.id = max_ch.idMateriais_no_veiculo
          LEFT JOIN check_mnv as ch ON 
            ch.idMateriais_no_veiculo = mnv.id AND ch.data_check = max_ch.max_data
        WHERE mnv.idCompartimento = $idCompartimento and mnv.status = 'ativo'
        group by mnv.id";
$mnv = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <?php require_once("../header.php") ?>
  <a class="button back-button" href="dados.php?id=<?= $compartimento['idVeiculo'] ?>">Veículo</a>
  <section>
    <main>
      <h1>Nova Alocação em <?= $compartimento['nome'] ?></h1>
      <form method="post" action="alocar.php">
        <input name="id" id="id" value="<?= $compartimento['id'] ?>" hidden/>
        <label>Quantidade (Opcional):</label>
        <input class="input" type="number" name="qtd" id="qtd" min="1" max="99"/>
        <label>Material:</label>
        <input class="input" list="materiais" id="mat" name="mat" placeholder="Descrição do Material" required/>
        <datalist id="materiais">
          <?php foreach ($materiais as $m) { ?>
            <option value="<?= $m['id'] ?>"><?= $m['descricao'] ?></option>
          <?php } ?>
        </datalist>
        <label>Observação (Opcional):</label>
        <input class="input" type="text" name="obs" id="obs" maxlength="250" placeholder="Patrimônio e outras especificações"/>
        <input type="submit" value="Alocar" class="button">
      </form>
    </main>
    <div class="secondary-section">
      <h1>Materias em <?= $compartimento['nome'] ?></h1>
      <?php foreach ($mnv as $material) { ?>
        <div class="card">
          <h1><?= $material['descricao'] ?></h1>
          <p>Status: <?= ($material['ok'] != '0' && $material['resolvido'] != '0') ? 'Ok' : $material['ch_obs'] ?></p>
          <p>Verificado: <?= $material['data_check'] != null ? date('H:i - d/m/Y', strtotime($material['data_check'])) : 'Novo!' ?></p>
          <p>Quantidade: <?= $material['quantidade'] != null ? $material['quantidade'] : 'Indefinida' ?></p>
          <p>Observação: <?= $material['observacao'] != null ? $material['observacao'] : '-' ?></p>
          <p>
            <a class="button" href="desalocar.php?id=<?= $material['id'] ?>">Remover</a>
          </p>
        </div>
      <?php } ?>
    </div>
  </section>
</body>
</html>
