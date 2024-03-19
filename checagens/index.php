<?php
require_once("../checa_login.php");

require_once("../conexao.php");

$time = 7;
if(isset($_GET['t']) && is_numeric($_GET['t'])) {
  $time = $_GET['t'];
  if($time > 365) {
    $time = 365;
    ?><div class="alert-notif"> Não é possível buscar checagens anteriores a um ano. </div> <?php ;
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
        WHERE cmnv.data_check >= CURDATE() - INTERVAL $time DAY
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
    <h1 class="title">Checagens Nos Últimos <?= $time ?> dias</h1>
    <form>
      <input type="number" class="input" name="t" placeholder="Limite da Busca"/>
      <input type="submit" value="Buscar" class="button">
    </form>
    <main>
      <?php if ($checagens->num_rows == 0) { ?>
        <h1>Nenhuma Checagem Nos Últimos <?= $time ?> dias...</h1>
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
