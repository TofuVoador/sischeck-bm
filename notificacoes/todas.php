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
        FROM check_mnv as ch
        LEFT JOIN (
          SELECT MAX(ch1.data_check) as max_data_check, mnv.id as mnv_id
          FROM check_mnv as ch1
          LEFT JOIN materiais_no_veiculo as mnv ON mnv.id = ch1.idMateriais_no_Veiculo
          WHERE mnv.status = 'ativo'
          GROUP BY mnv.id
        ) as ultima_verificacao ON ch.data_check = ultima_verificacao.max_data_check
        LEFT JOIN materiais_no_veiculo as mnv ON mnv.id = ch.idMateriais_no_Veiculo
        LEFT JOIN usuario as u ON u.id = ch.idVerificador
        LEFT JOIN material as m ON m.id = mnv.idMaterial
        LEFT JOIN compartimento as c ON c.id = mnv.idCompartimento
        LEFT JOIN veiculo as v ON v.id = c.idVeiculo
        WHERE mnv.status = 'ativo' AND ch.resolvido = 0
          AND ch.data_check = ultima_verificacao.max_data_check
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
