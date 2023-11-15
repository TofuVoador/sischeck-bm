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
      <?php foreach ($materiais as $mat) { ?>
        <div class="card">
          <h1><?php echo $mat['descricao'] ?></h1>
          <p><?php echo $mat['origem_patrimonio'] . '-' . $mat['patrimonio'] ?></p>
          <p>
            <a class="button" href="dados.php?id=<?=$mat['id']?>">Abrir</a>
          </p>
        </div>
      <?php } ?>
    </main>
  </section>
</body>
</html>