<?php
require_once("../checa_login.php");

require_once("../conexao.php");

$data_inicio = date('Y-m-d');;
$data_fim = date('Y-m-d', strtotime('-7 days'));
if(isset($_POST['ini']) && isset($_POST['fim'])) {

  if (strtotime($data_inicio) === false || strtotime($data_fim) === false) {
    ?><div class="alert-notif"> Datas fornecidas não são válidas. </div> <?php
    exit; // Ou qualquer tratamento de erro adequado
  } else {
    $data_inicio = $_POST['ini'];
    $data_fim = $_POST['fim'];
  }
}

//busca todos os veículos e a data da última checagem
$sql = "SELECT cmnv.data_check, v.prefixo, v.posfixo, u.nome as 'verificador',
        SUM(CASE WHEN cmnv.ok = 0 THEN 1 ELSE 0 END) AS 'problemas',
        GROUP_CONCAT(CASE WHEN ok = 0 THEN m.descricao ELSE NULL END) AS 'materiais_com_problema' FROM check_mnv as cmnv
        LEFT JOIN usuario as u ON cmnv.idVerificador = u.id
        LEFT JOIN materiais_no_veiculo as mnv ON cmnv.idMateriais_no_veiculo = mnv.id
        LEFT JOIN material as m ON mnv.idMaterial = m.id
        LEFT JOIN compartimento as c ON mnv.idCompartimento = c.id
        LEFT JOIN veiculo as v ON c.idVeiculo = v.id
        WHERE cmnv.data_check BETWEEN '$data_inicio' AND '$data_fim'
        GROUP BY cmnv.data_check
        ORDER BY DATE(cmnv.data_check) DESC, v.id";
$checagens = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<?php require_once("./head.html") ?>
<body>
  <?php require_once("../header.php") ?>
  <a class="button back-button" href="../dashboard.php">Menu</a>
  <section>
    <h1 class="title">Checagens Entre <?= date('d/m/Y', strtotime($data_inicio)) ?> e <?= date('d/m/Y', strtotime($data_fim))?></h1>
    <main>
      <form method="post">
        <label>De: <input type="date" class="input" name="ini" placeholder="Data início" required/></label>
        <label>Até: <input type="date" class="input" name="fim" placeholder="Data final" required/></label>
        <input type="submit" value="Buscar" class="button">
      </form>
      <?php if ($checagens->num_rows == 0) { ?>
        <h1>Nenhuma Checagem Nos Entre <?= date('d/m/Y', strtotime($data_inicio)) ?> e <?= date('d/m/Y', strtotime($data_fim))?></h1> 
      <?php } else { ?>
      <div class="list">
        <?php foreach ($checagens as $ch) { ?>
          <div class="card">
            <p><?php echo $ch['verificador']." | ".date('H:i | d/m/Y', strtotime($ch['data_check']))?>
            <h1><?= $ch['prefixo'] . "-" . $ch['posfixo'] ?></h1>
            <p><?= $ch['problemas'] ?> reporte(s)</p>
            <p><?= $ch['materiais_com_problema'] ?></p>
          </div>
        <?php } ?>
      </div>
      <?php } ?>
    </main>
  </section>
</body>
</html>
