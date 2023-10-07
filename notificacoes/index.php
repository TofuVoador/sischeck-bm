<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

$sql = "SELECT ch.data_check, ch.observacao, 
        m.descricao, mnv.quantidade, v.prefixo, v.posfixo, u.nome
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
    <h1 class="title">Notificações</h1>
    <main>
      
    </main>
  </section>
</body>
</html>