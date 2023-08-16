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
<?php require("./header.html") ?>
  <body>
    <header>
      <img src="/assets/siscarga.png" alt="siscarga logo"/>
      <h1 class="title">Siscarga BM</h1>
      <h2 class="title">Bem vindo, <?= $usuario['nome'] ?>! </h2>
    </header>
    <section>
      <main>
      </main>
    </section>
  </body>
</html>