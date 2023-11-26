<?php
require_once("../checa_login.php");

$idVeiculo = $_GET["id"];

require_once("../conexao.php");

$sql = "SELECT * FROM veiculo where id = $idVeiculo";
$result = $conn->query($sql);
$veiculo = $result->fetch_assoc();

$sql = "SELECT * FROM compartimento where idVeiculo = $idVeiculo";
$compartimentos = $conn->query($sql);

function getMateriais($idCompartimento) {
  require("../conexao.php");

  //busca todos os materiais do compartimento solicitado
  $sql = "SELECT mnv.quantidade, m.descricao, ch.data_check as 'verificado', ch.ok, ch.observacao, ch.resolvido
        FROM materiais_no_veiculo as mnv
        LEFT JOIN material as m on m.id = mnv.idMaterial
        LEFT JOIN compartimento as c on c.id = mnv.idCompartimento
        LEFT JOIN veiculo as v on v.id = c.idVeiculo
        LEFT JOIN (
            SELECT idMateriais_no_veiculo, MAX(data_check) as max_data
            FROM check_mnv
            GROUP BY idMateriais_no_veiculo
        ) as max_ch ON mnv.id = max_ch.idMateriais_no_veiculo 
        LEFT JOIN check_mnv as ch on ch.idMateriais_no_veiculo = mnv.id AND ch.data_check = max_ch.max_data
        WHERE c.id = $idCompartimento AND mnv.status = 'ativo'
        ORDER BY m.descricao, ch.data_check";

  $materiaisNoVeiculo = $conn->query($sql);
  return $materiaisNoVeiculo;
}
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
  </section>
  <section>
    <h1>Compartimentos de <?php echo $veiculo['prefixo'] . "-" . $veiculo['posfixo'] ?></h1>
    <?php $last_compartimento = null;     
    foreach ($compartimentos as $c) { ?>
      <div class="secondary-section">
        <h1><?= $c['nome'] ?></h1>
        <a class="button" href="desativar_compartimento.php?id=<?= $c['id'] ?>">Desativar Compartimento</a>
        <a class="button" href="alocar.php?id=<?= $c['id'] ?>">Alocar um Material</a>
        <?php $mnv = getMateriais($c['id']);
        foreach ($mnv as $material) { ?>
          <div class="card">
            <h1><?= $material['descricao'] ?></h1>
            <p>Quantidade: <?= $material['quantidade'] ?></p>
            <p>Status: <?= $material['verificado'] != null ? 
                          ($material['ok'] == 0 && $material['resolvido'] == 0 ? $material['observacao'] : "Ok") : 
                          "-" ?></p>
            <p>Verificado: <?= $material['verificado'] != null ? date('H:i - d/m/Y', strtotime($material['verificado'])) : 'Novo!' ?></p>
          </div>
        <?php } ?>
      </div>
    <?php } ?>
  </section>
</body>
</html>