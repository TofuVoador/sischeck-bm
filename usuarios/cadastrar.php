<?php
require_once("../checa_login.php");

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

require_once("../conexao.php");

// Verifica se há informações do formulário
if(isset($_POST['login'], $_POST['senha'], $_POST['confirma-senha'], $_POST['nome'], $_POST['tipo'])) {
  $login = $_POST['login'];
  $tipo = $_POST['tipo'];
  $senha = $_POST['senha'];
  $confirmaSenha = $_POST['confirma-senha'];
  $nome = $_POST['nome'];

  // Confirmação de senha
  if($senha == $confirmaSenha) {
    require_once("../conexao.php");

    // Preparar a consulta SQL
    $sql = "INSERT INTO usuario (login, tipo, senha, nome) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $login, $tipo, $senha, $nome);

    // Executar a consulta SQL preparada
    $stmt->execute();

    header("Location: index.php");
    exit;
  } else {
    echo "SENHA NÃO CONFERE!";
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
        <label>Senha</label>
        <input type="password" class="input" name="senha" maxlength="250" required/>
        <label>Confirme a Senha</label>
        <input type="password" class="input" name="confirma-senha" maxlength="250" required/>
        <label></label>
        <input type="submit" value="Cadastrar" class="button submit">
      </form>
    </main>
  </section>
</body>
</html>