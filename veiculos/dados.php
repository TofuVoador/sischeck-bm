<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario']) || !isset($_GET["id"])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$idVeiculo = $_GET["id"];

require_once("../conexao.php");

$sql = "SELECT * FROM veiculo where id = $idVeiculo";
$result = $conn->query($sql);
$veiculo = $result->fetch_assoc();

$sql = "SELECT mnv.quantidade, m.descricao, c.nome as 'compartimento', ch.data_check as 'verificado'
        FROM materiais_no_veiculo as mnv
        LEFT JOIN material as m on m.id = mnv.idMaterial
        LEFT JOIN compartimento as c on c.id = mnv.idCompartimento
        LEFT JOIN veiculo as v on v.id = c.idVeiculo
        LEFT JOIN check_mnv as ch on ch.idMateriais_no_veiculo
        WHERE v.id = $idVeiculo AND mnv.status = 'ativo'
        ORDER BY c.ordem_verificacao, ch.data_check";
        
$materiaisNoVeiculo = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <header>
    <div class="logo">
      <img src="../assets/SismatIcon.png" alt="sismat logo"/>
      <h1 class="title">Sismat BM</h1>
    </div>
    <h2 class="welcome">Bem vindo, <?= $usuario['nome'] ?>! </h2>
  </header>
  <div class="back-button">
    <a href="index.php">Veículos</a>
  </div>
  <section>
    <main>
      <h1><?php echo $veiculo['prefixo'] . "-" . $veiculo['posfixo'] ?></h1>
      <div>Placa: <?= $veiculo['placa'] ?></div>
      <div>Marca/Modelo: <?php echo $veiculo['marca'] . "/" . $veiculo['modelo'] ?></div>
      <div>Renavan: <?= $veiculo['renavan'] ?></div>
      <div>Status: <?= $veiculo['status'] ?></div>
      <a href="./verificar.php?id=<?=$veiculo['id']?>">Verificar</a>
    </main>
    <div>
      <?php $last_compartimento = null;     
      foreach ($materiaisNoVeiculo as $mat) { 
        if ($mat['compartimento'] !== $last_compartimento) {
          echo "<h1>".$mat['compartimento']."</h1>";
          $last_compartimento = $mat['compartimento'];
        }
        ?>
        <div class="row">
          <h2><?= $mat['descricao'] ?></h2>
          <div>
            <t>Quantidade: <?= $mat['quantidade'] ?></t>
            <t>Última Verificação: <?= $mat['verificado'] != null ? date('H:i - d/m/Y', strtotime($mat['verificado'])) : 'Novo!' ?></t>
          </div>
        </div>
      <?php } ?>
    </div>
  </section>
</body>
</html>