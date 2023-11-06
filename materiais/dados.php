<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario']) || !isset($_GET["id"])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../index.php");
  exit();
}

$idMaterial = $_GET["id"];

require_once("../conexao.php");

$sql = "SELECT * FROM material where id = $idMaterial";
$result = $conn->query($sql);
$material = $result->fetch_assoc();

$sql = "SELECT mnv.id, mnv.quantidade, c.nome as 'compartimento', 
        v.prefixo as 'v_pref', v.posfixo as 'v_posf', ch.data_check as 'verificado'
        FROM materiais_no_veiculo as mnv
        LEFT JOIN material as m on m.id = mnv.idMaterial
        LEFT JOIN compartimento as c on c.id = mnv.idCompartimento
        LEFT JOIN veiculo as v on v.id = c.idVeiculo
        LEFT JOIN (
            SELECT idMateriais_no_veiculo, MAX(data_check) as max_data
            FROM check_mnv
            GROUP BY idMateriais_no_veiculo
        ) as max_ch ON mnv.id = max_ch.idMateriais_no_veiculo
        LEFT JOIN check_mnv as ch on ch.idMateriais_no_veiculo AND ch.data_check = max_ch.max_data
        WHERE m.id = $idMaterial AND mnv.status = 'ativo'
        ORDER BY c.ordem_verificacao";
       
$alocacoes = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <?php require_once("header.php") ?>
  <a class="button back-button" href="index.php">Materiais</a>
  <section>
    <main>
      <h1><?= $material['descricao'] ?></h1>
      <div>Patrimônio: <?= ($material['patrimonio'] != '') ? $material['patrimonio'] : '-' ?></div>
      <div>Origem: <?= ($material['origem_patrimonio'] != '') ? $material['origem_patrimonio'] : '-' ?></div>
      <div>Status: <?= $material['status'] ?></div>
      <p>Almoxarifado: <?= $material['quantidade'] ?></p>
      <a class="button" href="alocar.php?id=<?= $idMaterial ?>">Alocar</a>
      <a class="button" href="alterar.php?id=<?= $idMaterial ?>">Alterar</a>
    </main>
    <div>
      <h2>Alocações:</h2>
      <?php foreach ($alocacoes as $aloc) { ?>
        <div class="card">
          <h1><?= $aloc['compartimento'] ?> de <?= $aloc['v_pref'] . "-" . $aloc['v_posf'] ?></h1>  
          <p>Quantidade: <?= $aloc['quantidade'] ?></p>
          <p>Verificado: <?= $aloc['verificado'] != null ? date('H:i - d/m/Y', strtotime($aloc['verificado'])) : 'Novo!' ?></p>
          <form method="post" action="desalocar.php">
            <h2>Retirar:</h2>
            <input id="id" name="id" type="number" value="<?=$aloc['id']?>" hidden/>
            <input id="qtd" type="number" class="input" min="1" max="<?= $aloc['quantidade'] ?>" name="qtd" placeholder="Quantidade" required>
            <input type="submit" class="button" value="Retirar"/>
          </form>
        </div>
      <?php } ?>
    </div>
  </section>
</body>
</html>