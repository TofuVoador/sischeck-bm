<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

if($_SESSION['usuario']['status'] != 'ativo') {
  header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <header>
    <div class="logo">
      <img src="assets/CargaCheckBM_Logo.png" alt="cargacheck logo"/>
      <h1 class="logo-name">CargaCheck BM</h1>
    </div>
    <h2 class="welcome">Bem vindo, <?= $usuario['nome'] ?>! </h2>
  </header>
  <section>
    <main>
      <div class="list">
        <a class="button" href="./veiculos">Veículos<a>
        <?php if ($usuario['tipo'] == 'administrador') { ?>
          <a class="button" href="./materiais">Materiais<a>
          <a class="button" href="./setores">Setores<a>
          <a class="button" href="./notificacoes">Notificações<a>
        <?php } ?>
      </div>
    </main>
  </section>
</body>
</html>