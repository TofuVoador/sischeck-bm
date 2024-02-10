<?php
require_once("../checa_login.php");

// Verificar se o usuário é adm
if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
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

require_once("../conexao.php");

$sql = "SELECT * FROM material where id = $idMaterial";
$result = $conn->query($sql);
$material = $result->fetch_assoc();

//busca cada alocação com a data da última verificação
$sql = "SELECT mnv.*, m.descricao, c.nome as 'compartimento', v.prefixo as 'v_pref', v.posfixo as 'v_posf', 
        ch.ok, ch.observacao as 'ch_obs', ch.resolvido, ch.data_check, u.nome as 'verificador'
        FROM materiais_no_veiculo as mnv
        LEFT JOIN material as m on m.id = mnv.idMaterial
        LEFT JOIN compartimento as c on c.id = mnv.idCompartimento
        LEFT JOIN veiculo as v on v.id = c.idVeiculo
        LEFT JOIN (
              SELECT idMateriais_no_veiculo, MAX(data_check) as max_data
              FROM check_mnv
              GROUP BY idMateriais_no_veiculo
          ) as max_ch ON mnv.id = max_ch.idMateriais_no_veiculo
          LEFT JOIN check_mnv as ch ON 
            ch.idMateriais_no_veiculo = mnv.id AND ch.data_check = max_ch.max_data
          LEFT JOIN usuario as u on u.id = ch.idVerificador
        WHERE mnv.idMaterial = $idMaterial and mnv.status = 'ativo'
        group by mnv.id";

$alocacoes = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <?php require_once("../header.php") ?>
  <a class="button back-button" href="index.php">Materiais</a>
  <section>
    <main>
      <h1><?= $material['descricao'] ?></h1>
      <a class="button" href="alocar.php?id=<?= $idMaterial ?>">Alocar</a>
      <a class="button" href="alterar.php?id=<?= $idMaterial ?>">Alterar</a>
      <a class="button" href="desativar.php?id=<?= $material['id'] ?>" onclick="return confirm('Tem certeza de que deseja desativar?')">
        Desativar
      </a>
    </main>
    <div class="secondary-section">
      <h2>Alocações:</h2>
      <?php foreach ($alocacoes as $aloc) { ?>
        <div class="card">
          <h1><?= $aloc['compartimento'] ?> de <?= $aloc['v_pref'] . "-" . $aloc['v_posf'] ?></h1>  
          <p <?php if($aloc['ok'] == false && $aloc['resolvido'] == false) echo 'style="color: red"'; ?>>Status: <?= ($aloc['ok'] == true) ? 'Ok' : $aloc['ch_obs'].($aloc['resolvido'] == true ? ' (Resolvido)' : '') ?></p>
          <p><?= $aloc['data_check'] != null ? $aloc['verificador']." | ".date('H:i | d/m/Y', strtotime($aloc['data_check'])) : 'Novo!' ?></p>
          <p>Quantidade: <?php echo ($aloc['quantidade'] != '') ? $aloc['quantidade'] : 'indefinida' ?></p>
          <p>
            <a class="button" href="desalocar.php?id=<?=$aloc['id']?>">Desalocar</a>
          </p>
        </div>
      <?php } ?>
    </div>
  </section>
</body>
</html>
