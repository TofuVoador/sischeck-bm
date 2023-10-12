<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario']) || !isset($_GET["id"])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$idVeiculo = $_GET['id'];

require_once("../conexao.php");

$sql = "SELECT * FROM veiculo as v WHERE v.id = $idVeiculo";
$veiculo = $conn->query($sql);

$sql = "SELECT c.* FROM compartimento as c
        LEFT JOIN veiculo as v ON v.id = c.idVeiculo
        WHERE v.id = $idVeiculo";
$compartimentos = $conn->query($sql);

var_dump($compartimentos)
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <?php require_once("header.php") ?>
  <div class="button back-button">
    <a href="index.php">Alterar <?php echo $veiculo['prefixo'] . "-" . $veiculo['posfixo'] ?></a>
  </div>
  <section>
    <main>

    </main>
  </section>
</body>
</html>