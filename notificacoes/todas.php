<?php
require_once("../checa_login.php");

// Verificar se o usuário é adm
if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

require_once("../conexao.php");

//busca todos as verificações com problema que ainda não foram resolvidads
$sql = "SELECT ch.id, ch.data_check, ch.observacao, 
        m.descricao, mnv.quantidade, v.prefixo, v.posfixo, u.nome as 'verificador'
        FROM materiais_no_veiculo as mnv
        LEFT JOIN (
            SELECT idMateriais_no_veiculo, MAX(data_check) as max_data
            FROM check_mnv
            GROUP BY idMateriais_no_veiculo
        ) as max_ch ON mnv.id = max_ch.idMateriais_no_veiculo
        LEFT JOIN check_mnv as ch ON mnv.id = ch.idMateriais_no_Veiculo AND ch.data_check = max_ch.max_data
        LEFT JOIN usuario as u on u.id = ch.idVerificador
        LEFT JOIN material as m ON m.id = mnv.idMaterial
        LEFT JOIN compartimento as c ON c.id = mnv.idCompartimento
        LEFT JOIN veiculo as v ON v.id = c.idVeiculo
        WHERE mnv.status = 'ativo'
        ORDER BY ch.data_check, m.id";
$notificacoes = $conn->query($sql);

?>
<!DOCTYPE html>
<html>
<?php require_once("./head.html") ?>
<body>
  <?php require_once("../header.php") ?>
  <a class="button back-button" href="index.php">Notificações</a>
  <section>
    <h1 class="title">Todas as Notificações</h1>
    <main>
      <?php if ($notificacoes->num_rows == 0) { ?>
        <h1>Ainda não há notificações...</h1>
      <?php } else {
      foreach ($notificacoes as $notif) { ?>
        <div class="card">
          <p><?php echo date('H:i - d/m/Y', strtotime($notif['data_check'])) ?>
          <h1><?= $notif['descricao'] ?></h1>
          <h2><?= $notif['prefixo'] . "-" . $notif['posfixo'] ?></h2>
          <p><?= $notif['observacao'] ?></p>
          <p>Quantidade Padrão: <?= $notif['quantidade'] ?></p>
          <p>Verificador: <?= $notif['verificador'] ?></p>
        </div>
      <?php }
      } ?>
    </main>
  </section>
</body>
</html>