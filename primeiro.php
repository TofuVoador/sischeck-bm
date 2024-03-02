<?php
require_once("conexao.php");

$sql = "SELECT count(id) as 'count' FROM usuario WHERE status = 'ativo'";
$result = $conn->query($sql);
$logins = $result->fetch_assoc();

if($logins['count'] > 0) {
  header("Location: ./");
  exit;
}

// Verifica se há informações do formulário
if(isset($_POST['login'], $_POST['senha'], $_POST['confirma-senha'], $_POST['nome'])) {
  $login = $_POST['login'];
  $tipo = 'administrador';
  $senha = $_POST['senha'];
  $confirmaSenha = $_POST['confirma-senha'];
  $nome = $_POST['nome'];

  // Confirmação de senha
  if($senha == $confirmaSenha) {
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Preparar a consulta SQL
    $sql = "INSERT INTO usuario (login, tipo, senha, nome) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $login, $tipo, $senhaHash, $nome);

    // Executar a consulta SQL preparada
    $stmt->execute();

    header("Location: ./");
    exit;
  } else {
    echo "SENHA NÃO CONFERE!";
    exit;
  }
}
?>
<!DOCTYPE html>
<html>
<?php require_once("./head.html") ?>
<body>
  <section>
    <h1 class="title">Cadastro de Primeiro Usuário</h1>
    <main>
      <form method="post">
        <label>Nome</label>
        <input class="input" name="nome" maxlength="50" required/>
        <label>Login</label>
        <input class="input" name="login" maxlength="50" required/>
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
