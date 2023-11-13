<?php
require_once("../checa_login.php");

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
}

require_once("../conexao.php");

//verifica se há informações do formulário
if(isset($_POST['login']) || isset($_POST['senha'])) {
  $login = $_POST['login'];
  $tipo = $_POST['tipo'];
  $senha = $_POST['senha'];
  $confirmaSenha = $_POST['confirma-senha'];
  $nome = $_POST['nome'];

  //confirmação de senha
  if($senha == $confirmaSenha) {
    $sql = "INSERT INTO usuario (login, tipo, senha, nome)
      VALUES ('$login', '$tipo', '$senha', '$nome')";
    $conn->query($sql);
    
    header("Location: index.php");
  } else {
    echo "SENHA NÃO CONFERE!";
  }
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
        <input class="input" name="nome" required/>
        <label>Login</label>
        <input class="input" name="login" required/>
        <label>Tipo</label>
        <select class="input select" name="tipo" required>
          <option value="administrador">Administrador</option>
          <option value="verificador">Verificador</option>
        </select>
        <label>Senha</label>
        <input type="password" class="input" name="senha" required/>
        <label>Confirme a Senha</label>
        <input type="password" class="input" name="confirma-senha" required/>
        <label></label>
        <input type="submit" value="Cadastrar" class="button">
      </form>
    </main>
  </section>
</body>
</html>