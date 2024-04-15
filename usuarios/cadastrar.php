<?php
require_once("../checa_login.php");

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

require_once("../conexao.php");

// Verifica se há informações do formulário
if(isset($_POST['login'], $_POST['nome'], $_POST['tipo'])) {
  $login = $_POST['login'];
  $tipo = $_POST['tipo'];
  $senha = $login;
  $nome = $_POST['nome'];

  //Confirma que não há usuários com este login
  $sql = "SELECT * FROM usuario WHERE login = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $login);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows === 0) {
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    // Preparar a consulta SQL
    $sql = "INSERT INTO usuario (login, tipo, senha, nome) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $login, $tipo, $senhaHash, $nome);

    // Executar a consulta SQL preparada
    $stmt->execute();

    header("Location: index.php");
    exit;
  } else {
    ?><div class="alert-notif"> Este Login já está em uso. </div> <?php
  }
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
      <form method="post">
        <label>Nome</label>
        <input class="input" name="nome" maxlength="50" required/>
        <label>Login</label>
        <input class="input" name="login" maxlength="50" required/>
        <label>Tipo</label>
        <select class="input select" name="tipo" required>
          <option value="administrador">Administrador</option>
          <option value="verificador">Verificador</option>
        </select>
        <label></label>
        <input type="submit" value="Cadastrar" class="button submit">
      </form>
    </main>
  </section>
</body>
</html>