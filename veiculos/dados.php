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
<body>
  <?php require("../header.html") ?>
  <section>
    <img src="/assets/siscarga.png" alt="siscarga logo"/>
      <h1 class="title"><?= $veiculo['prefixo'].'-'.$veiculo['posfixo'] ?></h1>
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
    </header>
  </section>
</body>
</html>