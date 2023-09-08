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
    <h1 class="title">Todos os Veículos</h1>
    <main>
      <table>
        <thead>
          <tr>
            <th>Identificador</th>
            <th>Placa</th>
            <th>Marca/Modelo</th>
            <th>Setor</th>
            <th>Status</th>
            <th>Ação</th>
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
              <td>
                <a href="dados.php?id=<?=$veiculo['id']?>">Abrir</a>
                <a href="verificar.php?id=<?=$veiculo['id']?>">Verificar</a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </main>
  </section>
</body>
</html>