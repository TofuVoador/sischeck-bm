<?php
require_once("../checa_login.php");

if(isset($_POST['user'])) {
  $idUsuario = $_POST['user'];
  $nome = $_POST['nome'];
  $novaSenha = $_POST['novasenha'];

  $senha = $_POST['senha'];

  if($senha == $usuario['senha']) {
    header("Location: ../dashboard.php");
    exit;
  }

  $sql = "UPDATE usuario SET 
          nome = '$nome', 
          senha = '$novaSenha'
          WHERE id = $idUsuario";
  $conn->query($sql);

  //Busca pelo usuário
  $sql = "SELECT * FROM usuario WHERE id = $idUsuario";
  $result = $conn->query($sql);

  if ($result && $result->num_rows > 0) {
    $usuario = $result->fetch_assoc();

    $_SESSION['usuario'] = $usuario;

    header("Location: ../dashboard.php");
    exit;
  } else {
    echo "Erro ao buscar dados atualizados do usuário.";
  }
}
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <?php require_once("../header.php") ?>  
  <a class="button back-button" href="../dashboard.php">Menu</a>
  <section>
    <h1 class="title">Meu Perfil</h1>
    <main>
      <form method="post">
        <input name="user" value="<?= $usuario['id'] ?>" hidden/>
        <label>Login</label>
        <input class="input" name="login" placeholder="Nome" value="<?= $usuario['login'] ?>" readonly/>
        <label>Nome</label>
        <input class="input" name="nome" placeholder="Nome" value="<?= $usuario['nome'] ?>" required/>
        <label>Nova Senha</label>
        <input class="input" type="password" name="novasenha" placeholder="Nova Senha" value="<?= $usuario['senha'] ?>" required/>
        <label>Senha</label>
        <input class="input" type="password" name="senha" placeholder="Senha" required/>
        <input type="submit" class="button" value="Salvar"/>
      </form>
    </main>
  </section>
</body>
</html>