<?php
require_once("../checa_login.php");

// Verificar se o usuário é adm
if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

require_once("../conexao.php");

$sql = "SELECT m.* FROM material as m 
        WHERE m.status = 'ativo'
        ORDER BY m.id";
$materiais = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<?php require_once("./head.html") ?>
<body>
  <?php require_once("../header.php") ?>
  <a class="button back-button" href="../dashboard.php">Menu</a>
  <section>
    <a class="button novo-button" href="cadastrar.php">Novo</a>
    <h1 class="title">Todos os Materiais</h1>
    <main>
      <input class="input" id="search" onkeyup="filterMaterial()" placeholder="Pesquisar pelo material...">
      <script>
        function filterMaterial() {
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
        <?php foreach ($materiais as $mat) { ?>
          <div class="card">
            <h1 class="card-header"><?php echo $mat['descricao'] ?></h1>
            <p>
              <a class="button" href="dados.php?id=<?=$mat['id']?>">Abrir</a>
            </p>
          </div>
        <?php } ?>
      </div>
    </main>
  </section>
</body>
</html>