<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario'])) {
  header("Location: ./");
  exit();
}

$usuario = $_SESSION['usuario'];

// Verificar se o usuário está ativo
if($_SESSION['usuario']['status'] != 'ativo') {
  header("Location: index.php");
  exit();
}
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <header>
    <div class="logo">
      <img src="assets/SisCheck-BM.png" alt="cargacheck logo"/>
      <h1 class="logo-name">SisCheck-BM</h1>
    </div>
    <div>
      <a class="logout" href="logout.php">Logout</a>
      <a class="welcome" href="perfil/index.php?id=<?= $usuario['id'] ?>">Bem vindo(a), <?= $usuario['nome'] ?>!</a>
    </div>
  </header>
  <section>
    <main>
      <a class="button" href="./veiculos">Veículos<a>
      <a class="button" href="./checagens">Checagens<a>
      <?php if ($usuario['tipo'] == 'administrador') { 
          require_once("conexao.php");

          // Consulta para contar o número de problemas não resolvidos
          $sql = "SELECT ch.id FROM check_mnv as ch
                  LEFT JOIN (
                    SELECT MAX(ch1.data_check) as max_data_check, mnv.id as mnv_id
                    FROM check_mnv as ch1
                    LEFT JOIN materiais_no_veiculo as mnv ON mnv.id = ch1.idMateriais_no_Veiculo
                    WHERE mnv.status = 'ativo'
                    GROUP BY mnv.id
                  ) as ultima_verificacao ON ch.data_check = ultima_verificacao.max_data_check
                  LEFT JOIN materiais_no_veiculo as mnv ON mnv.id = ch.idMateriais_no_Veiculo
                  WHERE mnv.status = 'ativo' AND ch.resolvido = 0
                    AND ch.data_check = ultima_verificacao.max_data_check
                  GROUP BY mnv.id
                  ORDER BY ch.data_check";

          $result = $conn->query($sql);
          $quantidadeNotif = $result->num_rows; ?>
        <a class="button" href="./materiais">Materiais<a>
        <a class="button" href="./setores">Setores<a>
        <a class="button" href="./notificacoes"><?php echo $quantidadeNotif != 0 ? '('.$quantidadeNotif.')' : '' ?> Notificações<a>
        <a class="button" href="./usuarios">Usuários<a>
      <?php } ?>
    </main>
  </section>
  <footer>
    Desenvolvido por Gustavo A. Kumagai
  </footer>
</body>
</html>
