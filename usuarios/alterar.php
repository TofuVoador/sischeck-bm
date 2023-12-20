<?php
require_once("../checa_login.php");

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
}

require_once("../conexao.php");

//verifica se há informações do formulário
if(isset($_POST['tipo']) && isset($_POST['nome']) && isset($_POST['login'])) {
  $id = $_POST['id'];
  $tipo = $_POST['tipo'];
  $nome = $_POST['nome'];
  $login = $_POST['login'];

  $sql = "ALTER usuario SET nome = '$nome', login = '$login', tipo = '$tipo' WHERE id = $id";
  $conn->query($sql);
}

if(!isset($_GET['id'])) {
  header("Location: ../dashboard.php");
}

$idUsuario = $_GET['id'];

$sql = "SELECT * FROM usuario where id = $idUsuario";
$result = $conn->query($sql);
$usuario = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<?php require_once("./head.html") ?>
<body>
<?php require_once("../header.php") ?>
  <a class="button back-button" href="index.php">Usuários</a>
  <section>
    <h1 class="title">Alterar <?= $usuario['nome'] ?></h1>
    <main>
      <form method="post">
        <input name="id" value="<?= $usuario['id'] ?>" hidden/>
        <label>Nome</label>
        <input class="input" name="nome" value="<?= $usuario['nome'] ?>" required/>
        <label>Login</label>
        <input class="input" name="login" value="<?= $usuario['login'] ?>" required/>
        <label>Tipo</label>
        <select class="input select" name="tipo" required>
          <option value="administrador" <?php if($usuario['tipo'] == 'administrador') echo 'selected'; ?>>Administrador</option>
          <option value="verificador" <?php if($usuario['tipo'] == 'verificador') echo 'selected'; ?>>Verificador</option>
        </select>
        <input type="submit" value="Cadastrar" class="button submit">
      </form>
    </main>
  </section>
</body>
</html>
