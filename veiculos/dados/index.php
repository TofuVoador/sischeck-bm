<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario']) || !isset($_GET["v"])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

require_once("./conexao.php");

$idVeiculo = $_GET["v"];

$sql = "SELECT v.* FROM veiculo where id = $idVeiculo";
$result = $conn->query($sql);
$veiculo = $result->fetch_assoc();

$sql = "SELECT c.*, m.* FROM compartimento as c LEFT JOIN material as m ON m.id = c.idMaterial LEFT JOIN veiculo as v ON v.id = c.idVeiculo where v.id = $idVeiculo";
$result = $conn->query($sql);
$itensNoVeiculo = $result->fetch_assoc();

?>
<!DOCTYPE html>
<html>
<body>
  <?php require("../header.html") ?>
  <section>
    <img src="/assets/siscarga.png" alt="siscarga logo"/>
      <h1 class="title">Todos os Veículos</h1>
      <table>
        <thead>
          <tr>
            <th>Identificador</th>
            <th>Placa</th>
            <th>Marca/Modelo</th>
            <th>Setor</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($veiculos as $veiculo) { ?>
            <tr>
              <td><?php echo ($veiculo['prefixo'] . '-' . $veiculo['posfixo']) ?></td>
              <td><?php echo $veiculo['placa'] ?></td>
              <td><?php echo ($veiculo['marca'] . " " . $veiculo['modelo']) ?></td>
              <td><?php echo $veiculo['setor_nome'] ?></td>
              <td><?php echo $veiculo['status'] ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </header>
  </section>
</body>
</html>