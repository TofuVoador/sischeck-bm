<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario']) || !isset($_GET['id'])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$idMaterial = $_GET['id'];

require_once("../conexao.php");

$sql = "SELECT * FROM materiais WHERE id = $idMaterial";
$material = $conn->query($sql);

$sql = "SELECT c.* FROM compartimento as c
        LEFT JOIN veiculo as v ON v.id = c.idVeiculo
        WHERE v.id = $idVeiculo";
$compartimentos = $conn->query($sql);

var_dump($compartimentos)
?>
<!DOCTYPE html>
<html>
<?php require_once("./head.html") ?>
<body>
  <header>
    <div class="logo">
      <img src="../assets/SismatIcon.png" alt="sismat logo"/>
      <h1 class="title">Sismat BM</h1>
    </div>
    <h2 class="welcome">Bem vindo, <?= $usuario['nome'] ?>! </h2>
  </header>
  <div class="back-button">
    <a href="../dashboard.php">Menu</a>
  </div>
  <section>
    <h1 class="title">Alterar: <?= $veiculo['prefixo']."-".$veiculo['posfixo'] ?></h1>
    <main>

    </main>
  </section>
</body>
</html>