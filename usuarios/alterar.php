<?php
require_once("../checa_login.php");

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
}

require_once("../conexao.php");

if(!isset($_GET['id'])) {
  header("Location: ../dashboard.php");
}

$idUsuario = $_GET['id'];

//verifica se há informações do formulário
if(isset($_GET['tipo'])) {
  $id = $_GET['id'];
  $tipo = $_GET['tipo'];
  $nome = $_GET['nome'];
  $login = $_GET['login'];

  $sql = "ALTER usuario SET nome = '$nome', login = '$login', tipo = '$tipo' WHERE id = $id";
  $conn->query($sql);
}

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
      <form>
        <input name="id" value="<?= $usuario['id'] ?>" hidden/>
        <label>Nome</label>
        <input class="input" name="nome" value="<?= $usuario['nome'] ?>" required/>
        <label>Login</label>
        <input class="input" name="login" value="<?= $usuario['login'] ?>" required/>
        <label>Tipo</label>
        <select class="input select" name="tipo" required>
          <option value="administrador">Administrador</option>
          <option value="verificador">Verificador</option>
        </select>
        <input type="submit" value="Cadastrar" class="button submit">
      </form>
    </main>
  </section>
</body>
</html>