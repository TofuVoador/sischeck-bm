<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario'])) {
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
      <img src="assets/SismatIcon.png" alt="sismat logo"/>
      <h1 class="logo-name">Sismat BM</h1>
    </div>
    <h2 class="welcome">Bem vindo, <?= $usuario['nome'] ?>! </h2>
  </header>
  <section>
    <main>
      <a href="./veiculos">Veículos<a>
      <?php if ($usuario['tipo'] == 'administrador') { ?>
        <a href="./materiais">Materiais<a>
        <a href="./notificacoes">Notificações<a>
      <?php } ?>
    </main>
  </section>
</body>
</html>