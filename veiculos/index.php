<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

require_once("../conexao.php");

$sql = "SELECT v.*, s.nome as setor_nome FROM veiculo as v LEFT JOIN setor as s ON s.id = v.idSetor";
$veiculos = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <?php require_once("header.php") ?>
  <div class="button back-button">
    <a href="../dashboard.php">Menu</a>
  </div>
  <section>
    <h1 class="title">Todos os Veículos</h1>
    <main>
      <?php foreach ($veiculos as $veiculo) { ?>
        <div class="card">
          <h1 class="card-header"><?php echo ($veiculo['prefixo'] . '-' . $veiculo['posfixo']) ?></h1>
          <p>Placa: <?php echo $veiculo['placa'] ?></p>
          <p>Marca: <?php echo ($veiculo['marca'] . " " . $veiculo['modelo']) ?></p>
          <p>Setor: <?php echo $veiculo['setor_nome'] ?></p>
          <p class="card-action">
            <a class="button" href="dados.php?id=<?=$veiculo['id']?>">Abrir</a>
            <a class="button" href="verificar.php?id=<?=$veiculo['id']?>">Verificar</a>
          </p>
        </div>
      <?php } ?>
    </main>
  </section>
</body>
</html>