<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

require_once("../conexao.php");

$sql = "SELECT m.id, m.descricao, m.patrimonio, m.origem_patrimonio,
        mnv.quantidade, v.prefixo, v.posfixo, v.id as 'idVeiculo' FROM material as m 
        LEFT JOIN materiais_no_veiculo as mnv ON mnv.idMaterial = m.id 
        LEFT JOIN compartimento as c ON c.id = mnv.idCompartimento
        LEFT JOIN veiculo as v ON v.id = c.idVeiculo
        WHERE mnv.status = 'ativo'
        ORDER BY m.id";
$materiais = $conn->query($sql);
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
    <h1 class="title">Todos os Materiais</h1>
    <main>
      <table>
        <thead>
          <tr>
            <th>Material</th>
            <th>Patrimônio</th>
            <th>Veículo</th>
            <th>Ação</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($materiais as $mat) { ?>
            <tr>
              <td><?php echo $mat['descricao'] ?></td>
              <td><?php echo $mat['origem_patrimonio'] . '-' . $mat['patrimonio'] ?></td>
              <td><a href="../veiculos/dados.php?id=<?=$mat['idVeiculo']?>"><?php echo ($mat['prefixo'] . '-' . $mat['posfixo']) ?></a></td>
              <td><a href="dados.php?id=<?=$mat['id']?>">Abrir</a></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </main>
  </section>
</body>
</html>