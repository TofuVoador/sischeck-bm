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

$sql = "SELECT * FROM veiculo WHERE id = $idVeiculo";
$veiculo = $conn->query($sql);

$sql = "SELECT mnv.id, mnv.quantidade, ch.estado, 
        m.descricao, c.nome as 'nome_compartimento' FROM materiais_no_veiculo as mnv 
        LEFT JOIN material as m on m.id = mnv.idMaterial
        LEFT JOIN check_mnv as ch ON ch.idMateriais_no_veiculo = mnv.id
        LEFT JOIN compartimento as c on c.id = mnv.idCompartimento
        LEFT JOIN veiculo as v on v.id = c.idVeiculo
        WHERE v.id = $idVeiculo
        ORDER BY c.nome, m.descricao";
$mnv = $conn->query($sql);

if ($veiculo->num_rows > 0) {
  $veiculo = $veiculo->fetch_assoc();
} else {
  header("Location: ../dashboard.php");
}
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
    <a href="index.php">Voltar</a>
  </div>
  <section>
    <h1 class="title">Checagem de <?= $veiculo['prefixo'] . '-' . $veiculo['posfixo']?></h1>
    <main>
      <form>
        <table>
          <thead>
            <tr>
              <th>Checagem</th>
              <th>Quantidade</th>
              <th>Descrição</th>
              <th>Compartimento</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($mnv as $mat) { ?>
              <tr>
                <td><label class="switch">
                  <input type="checkbox">
                  <span class="slider round"></span>
                </label></td>
                <td><?= $mat['quantidade'] ?></td>
                <td><?= $mat['descricao'] ?></td>
                <td><?= $mat['nome_compartimento']?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
    </main>
  </section>
</body>
</html>