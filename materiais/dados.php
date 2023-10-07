<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario']) || !isset($_GET["id"])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../index.php");
  exit();
}

$idMaterial = $_GET["id"];

require_once("../conexao.php");

$sql = "SELECT * FROM material where id = $idMaterial";
$result = $conn->query($sql);
$material = $result->fetch_assoc();

$sql = "SELECT mnv.quantidade, c.nome as 'compartimento', 
        v.prefixo as 'v_pref', v.posfixo as 'v_posf', ch.data_check as 'verificado'
        FROM materiais_no_veiculo as mnv
        LEFT JOIN material as m on m.id = mnv.idMaterial
        LEFT JOIN compartimento as c on c.id = mnv.idCompartimento
        LEFT JOIN veiculo as v on v.id = c.idVeiculo
        LEFT JOIN check_mnv as ch on ch.idMateriais_no_veiculo
        WHERE m.id = $idMaterial AND mnv.status = 'ativo'
        ORDER BY c.ordem_verificacao";
        
$alocacoes = $conn->query($sql);
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
    <a href="index.php">Materiais</a>
  </div>
  <section>
    <main>
      <h1><?= $material['descricao'] ?></h1>
      <div>Patrimônio: <?= ($material['patrimonio'] != '') ? $material['patrimonio'] : '-' ?></div>
      <div>Origem: <?= ($material['origem_patrimonio'] != '') ? $material['origem_patrimonio'] : '-' ?></div>
      <div>Status: <?= $material['status'] ?></div>
    </main>
    <div>
      <table>
        <thead>
          <tr>
            <th>Quantidade</th>
            <th>Compartimento</th>
            <th>Veículo</th>
            <th>Última Verificação</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($alocacoes as $aloc) { ?>
            <tr>
              <td><?= $aloc['quantidade'] ?></td>
              <td><?= $aloc['compartimento'] ?></td>
              <td><?= $aloc['v_pref'] . "-" . $aloc['v_posf'] ?></td>
              <td><?= $aloc['verificado'] != null ? $aloc['verificado'] : 'Novo!' ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
      <div>Almoxarifado: <?= $material['quantidade'] ?></div>
    </div>
  </section>
</body>
</html>