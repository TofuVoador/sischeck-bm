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

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
}

require_once("../conexao.php");

if(isset($_POST['login']) || isset($_POST['senha'])) {
  $login = $_POST['login'];
  $senha = $_POST['senha'];
  $nome = $_POST['nome'];

  $sql = "INSERT INTO usuario (login, senha, nome)
          VALUES ('$login', '$senha', '$nome')";
  $conn->query($sql);

  header("Location: index.php");
}

$sql = "SELECT * FROM setor";
$setores = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<?php require_once("./head.html") ?>
<body>
<?php require_once("../header.php") ?>
  <a class="button back-button" href="index.php">Usuários</a>
  <section>
    <h1 class="title">Cadastro de Usuário</h1>
    <main>
      <form method="post">
        <label>Nome</label>
        <input class="input" name="nome"/>
        <label>Login</label>
        <input class="input" name="login"/>
        <label>Senha</label>
        <input type="password" class="input" name="senha"/>
        <label>Confirme a Senha</label>
        <input type="password" class="input" name="confirma-senha"/>
        <label></label>
        <input type="submit" value="Cadastrar" class="button">
      </form>
    </main>
  </section>
</body>
</html>