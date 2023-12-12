<?php
require_once("../checa_login.php");

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

if(isset($_POST['nome'])) {
  $nome = $_GET["nome"];

  require_once("../conexao.php");

  $sql = "INSERT INTO setor (nome) values ('$nome')";

  $conn->query($sql);

  header("Location: index.php");
  exit;
}
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
      <form method="post" action="cadastrar.php">
        <label>Novo Setor</label>
        <input class="input" name="nome" placeholder="Nome do Setor" required/>
        <input type="submit" class="button" value="Cadastrar"/>
      </form>
    </main>
  </section>
</body>
</html>