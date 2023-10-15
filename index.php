<?php
if(isset($_POST['password']) && isset($_POST['user'])) {
  require_once("conexao.php");

  // Obter os dados do formulário
  $login = $_POST['user'];
  $senha = $_POST['password'];

  // Consulta SQL para verificar as credenciais and retrieve user type
  $sql = "SELECT * FROM usuario WHERE login = '$login' AND senha = '$senha'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
      // Fetch user data
      $user = $result->fetch_assoc();
      
      // Store user type in session
      session_start();
      $_SESSION['usuario'] = $user;
      
      // Redirect to appropriate dashboard based on user type
      header("Location: dashboard.php");
  } else { 
      echo "Nome de usuário ou senha incorretos.";
  }

  $conn->close();
}
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
  <body>
    <header>
      <div class="logo">
        <img src="assets/SismatIcon.png" alt="sismat logo"/>
        <h1 class="title">Sismat BM</h1>
      </div>
    </header>
    <section>
      <main>
          <img class="sgbi-photo" src="./assets/6sgbi.png" alt="brasão 6sgbi" />
          <form method="POST">
            <input type="text" class="input" placeholder="USUÁRIO" name="user"/>
            <input type="password" class="input" placeholder="SENHA" name="password"/> 
            <input type="submit" class="button" value="Entrar">
          </form>
          <a>ESQUECI MINHA SENHA</a>
      </main>
    </section>
  </body>
</html>