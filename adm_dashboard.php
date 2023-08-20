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
<head>
  <meta charset="utf-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1, shrink-to-fit=no"
  />
  <title>SisCarga BM</title>
  <link rel="stylesheet" href="styles.css" />
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
      <img src="/assets/siscarga.png" alt="siscarga logo"/>
      <h1 class="title">Siscarga BM</h1>
    </div>
    <h2 class="welcome">Bem vindo, <?= $usuario['nome'] ?>! </h2>
  </header>
  <section>
    <main>
      <a href="./veiculos">Veículos<a>
      <a href="./materiais">Materiais<a>
      <a href="./notificacoes">Notificações<a>
    </main>
  </section>
</body>
</html>