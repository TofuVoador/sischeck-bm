<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "siscarga-bm";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$sql = "SELECT c.*, m.*, v.* FROM compartimento as c LEFT JOIN material as m ON m.id = c.idMateiral LEFT JOIN veiculo as v ON v.id = c.idVeiculo";
$veiculos = $conn->query($sql);
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