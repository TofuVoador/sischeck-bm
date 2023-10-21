<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../index.php");
  exit();
}

require_once("../conexao.php");

$sql = "SELECT ch.id, ch.data_check, ch.observacao, 
        m.descricao, mnv.quantidade, v.prefixo, v.posfixo, u.nome as 'verificador'
        FROM materiais_no_veiculo as mnv
        LEFT JOIN (
            SELECT idMateriais_no_veiculo, MAX(data_check) as max_data
            FROM check_mnv
            GROUP BY idMateriais_no_veiculo
        ) as max_ch ON mnv.id = max_ch.idMateriais_no_veiculo
        LEFT JOIN check_mnv as ch ON mnv.id = ch.idMateriais_no_Veiculo AND ch.data_check = max_ch.max_data
        LEFT JOIN usuario as u on u.id = ch.idVerificador
        LEFT JOIN material as m ON m.id = mnv.idMaterial
        LEFT JOIN compartimento as c ON c.id = mnv.idCompartimento
        LEFT JOIN veiculo as v ON v.id = c.idVeiculo
        WHERE mnv.status = 'ativo' AND ch.resolvido = 0
        ORDER BY m.id";
$notificacoes = $conn->query($sql);

?>
<!DOCTYPE html>
<html>
<?php require_once("./head.html") ?>
<body>
  <?php require_once("header.php") ?>
  <a class="button back-button" href="../dashboard.php">Menu</a>
  <section>
    <h1 class="title">Notificações</h1>
    <main>
      <?php foreach ($notificacoes as $notif) { ?>
        <div class="card">
          <h1><?= $notif['descricao'] ?></h1>
          <h1><?= $notif['prefixo'] . "-" . $notif['posfixo'] ?></h1>
          <p><?= $notif['observacao'] ?></p>
          <p>Quantidade Padrão: <?= $notif['quantidade'] ?></p>
          <p>Verificador: <?= $notif['verificador'] ?></p>
          <p>
            <a class="button" href="resolver.php?id=<?= $notif['id'] ?>">Resolver</a>
          </p>
        </div>
      <?php } ?>
    </main>
  </section>
</body>
</html>