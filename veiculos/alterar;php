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
        ORDER BY c.ordem_verificacao";
        
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
      <table>
        <thead>
          <tr>
            <th>Compartimento</th>
            <th>Descrição</th>
            <th>Quantidade</th>
            <th>Última Verificação</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($materiaisNoVeiculo as $mat) { ?>
            <tr>
              <td><?= $mat['compartimento'] ?></td>
              <td><?= $mat['descricao'] ?></td>
              <td><?= $mat['quantidade'] ?></td>
              <td><?= $mat['verificado'] != null ? $mat['verificado'] : 'Novo!' ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </section>
</body>
</html>