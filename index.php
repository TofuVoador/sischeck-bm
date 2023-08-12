<?php
if(isset($_POST['password']) && isset($_POST['user'])) {
  // Conectar ao banco de dados
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "siscarga-bm";

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
      die("Erro na conexão: " . $conn->connect_error);
  }

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
      if ($_SESSION['usuario']['tipo'] === 'administrador') {
          header("Location: pages/adm_dashboard.php");
      } else {
          header("Location: pages/verificador_dashboard.php");
      }
  } else {
      echo "Nome de usuário ou senha incorretos.";
  }

  $conn->close();
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="styles.css"/>
  </head>
  <body>
    <header>
      <img src="/assets/siscarga.png" alt="siscarga logo"/>
      <h1 class="title">Siscarga BM</h1>
    </header>
    <section>
      <main>
          <img class="sgbi-photo" src="./assets/6sgbi.png" alt="brasão 6sgbi" />
          <form method="POST">
            <input type="text" class="input-user" placeholder="USUÁRIO" name="user"/>
            <input type="password" class="input-password" placeholder="SENHA" name="password"/> 
            <input type="submit" value="Entrar">
          </form>
          <a class="button-forgot">ESQUECI MINHA SENHA</a>
      </main>
    </section>
  </body>
</html>