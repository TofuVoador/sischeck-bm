<?php
require_once("../checa_login.php");

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

require_once("../conexao.php");

//verifica se há informações do formulário
if(isset($_POST['tipo']) && isset($_POST['nome']) && isset($_POST['login']) && isset($_POST['id'])) {
  $id = $_POST['id'];
  $tipo = $_POST['tipo'];
  $nome = $_POST['nome'];
  $login = $_POST['login'];

  require_once("../conexao.php");

  // Preparar a consulta SQL
  $sql = "UPDATE usuario SET nome = ?, login = ?, tipo = ? WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('sssi', $nome, $login, $tipo, $id);
  $stmt->execute();
}

if(!isset($_GET['id'])) {
  header("Location: ../dashboard.php");
  exit;
}

$idUsuario = $_GET['id'];

if(!is_numeric($idUsuario)) {
  echo "ID não é um número válido";
  exit;
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
      <form method="post">
        <input name="id" value="<?= $usuario['id'] ?>" hidden/>
        <label>Nome</label>
        <input class="input" name="nome" value="<?= $usuario['nome'] ?>" maxlength="50" required/>
        <label>Login</label>
        <input class="input" name="login" value="<?= $usuario['login'] ?>" maxlength="50" required/>
        <label>Tipo</label>
        <select class="input select" name="tipo" required>
          <option value="administrador" <?php if($usuario['tipo'] == 'administrador') echo 'selected'; ?>>Administrador</option>
          <option value="verificador" <?php if($usuario['tipo'] == 'verificador') echo 'selected'; ?>>Verificador</option>
        </select>
        <input type="submit" value="Salvar" class="button submit">
      </form>
    </main>
  </section>
</body>
</html>
