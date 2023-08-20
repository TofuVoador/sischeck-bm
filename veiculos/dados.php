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
<head>
  <meta charset="utf-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1, shrink-to-fit=no"
  />
  <title>SisCarga BM</title>
  <link rel="stylesheet" href="../styles.css" />
  <link
    rel="icon"
    href="assets/siscarga-bm.png"
    type="image/png"
    sizes="16x16"
  />
  <link
    rel="shortcut icon"
    href="assets/siscarga-bm.png"
    type="image/png"
    sizes="16x16"
  />
</head>
<body>
  <header>
    <div class="logo">
      <img src="../assets/siscarga.png" alt="siscarga logo"/>
      <h1 class="title">Siscarga BM</h1>
    </div>
    <h2 class="welcome">Bem vindo, <?= $usuario['nome'] ?>! </h2>
  </header>
  <section>
    <main>
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
    </main>
  </section>
</body>
</html>