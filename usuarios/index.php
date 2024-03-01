<?php
require_once("../checa_login.php");

if($usuario['tipo'] != 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

require_once("../conexao.php");

$sql = "SELECT *
        FROM usuario
        where status = 'ativo'
        order by nome";
$usuarios = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <?php require_once("../header.php") ?>
  <a class="button back-button" href="../dashboard.php">Menu</a>
  <section>
    <a class="button novo-button" href="cadastrar.php">Novo</a>
    <h1 class="title">Todos os Usuários</h1>
    <main>
      <input class="input" id="search" onkeyup="filterUsuario()" placeholder="Pesquisar pelo usuario...">
      <script>
        function filterUsuario() {
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
      <div class="list">
        <?php foreach ($usuarios as $u) { ?>
          <div class="card">
            <h1 class="card-header"><?= $u['nome'] ?> (<?= $u['login'] ?>)</h1>
            <p class="card-action">
              <a class="button" href="alterar.php?id=<?=$u['id']?>">Alterar</a>
            </p>
            <p>
              <a class="alert-button" href="desativar.php?id=<?=$u['id']?>" onclick="return confirm('Tem certeza de que deseja desativar?')">Desativar</a>
            </p>
          </div>
        <?php } ?>
      </div>
    </main>
  </section>
</body>
</html>