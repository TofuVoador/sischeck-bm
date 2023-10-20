<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

require_once("../conexao.php");

$sql = "SELECT s.*, (SELECT COUNT(v.id) FROM veiculo as v where v.idSetor = s.id) as veiculos FROM setor as s order by s.nome";
$setores = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <?php require_once("header.php") ?>
  <a class="button back-button" href="../dashboard.php">Menu</a>
  <section>
    <a class="button novo-button" href="novo.php">Novo</a>
    <h1 class="title">Todos os Setores</h1>
    <main>
      <?php foreach ($setores as $setor) { ?>
        <div class="card">
          <h1 class="card-header"><?= $setor['nome'] ?></h1>
          <p>Veículos: <?php echo $setor['veiculos'] ?></p>
          <p class="card-action">
            <a class="button" href="dados.php?id=<?=$setor['id']?>">Abrir</a>
          </p>
        </div>
      <?php } ?>
    </main>
  </section>
</body>
</html>