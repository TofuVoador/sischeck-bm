<?php
require_once("../checa_login.php");

// Verificar se o usuário é adm
if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

require_once("../conexao.php");

//busca todos as verificações com problema que ainda não foram resolvidads
$sql = "SELECT ch.id, ch.data_check, ch.observacao, ch.resolvido,
        m.descricao, mnv.quantidade, c.nome as 'compartimento', v.prefixo, v.posfixo, u.nome as 'verificador'
        FROM check_mnv as ch
        LEFT JOIN materiais_no_veiculo as mnv ON mnv.id = ch.idMateriais_no_Veiculo
        LEFT JOIN usuario as u ON u.id = ch.idVerificador
        LEFT JOIN material as m ON m.id = mnv.idMaterial
        LEFT JOIN compartimento as c ON c.id = mnv.idCompartimento
        LEFT JOIN veiculo as v ON v.id = c.idVeiculo
        WHERE mnv.status = 'ativo' AND ch.ok = 0
        ORDER BY ch.data_check desc, m.id";
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
    <input class="input" id="search" onkeyup="filterNotif()" placeholder="Pesquisar pelo material...">
      <script>
        function filterNotif() {
          var input = document.getElementById('search');
          var filter = input.value.toUpperCase();
          var cards = document.querySelectorAll('.card');

          cards.forEach(function(card) {
            var title = card.querySelector('.card-header');
            var txt = title.textContent || title.innerHTML;
            if(txt.toUpperCase().indexOf(filter) > -1) {
              card.style.display = '';
            } else {
              card.style.display = 'none';
            }
          });
        }
      </script>
      <?php if ($notificacoes->num_rows == 0) { ?>
        <h1>Ainda não há notificações...</h1>
      <?php } else { ?>
        <div class="list">
        <?php foreach ($notificacoes as $notif) { ?>
          <div class="card">
            <p><?php echo date('H:i - d/m/Y', strtotime($notif['data_check'])) . ($notif['resolvido'] == '1' ? ' (Resolvido)' : '') ?></p>
            <p><?= $notif['compartimento']?> de <?= $notif['prefixo'] . "-" . $notif['posfixo'] ?></p>
            <h1 class="card-header"><?= $notif['descricao'] ?></h1>
            <p><?= $notif['observacao'] ?></p>
            <p>Quantidade Padrão: <?php echo ($notif['quantidade'] != '') ? $notif['quantidade'] : 'Indefinida' ?></p>
            <p>Verificador: <?= $notif['verificador'] ?></p>
          </div>
        <?php } ?>
        </div>
      <?php
      } ?>
    </main>
  </section>
</body>
</html>
