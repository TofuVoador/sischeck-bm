<?php
if(isset($_POST['password']) && isset($_POST['user'])) {
  require_once("conexao.php");

  // Obter os dados do formulário
  $login = $_POST['user'];
  $senha = $_POST['password'];

  // Consulta SQL para verificar as credenciais
  $sql = "SELECT * FROM usuario WHERE login = ? AND senha = ?";
  $stmt = $conn->prepare($sql);

  if($stmt) {
    //Bind dos parâmetros
    $stmt->bind_param('ss', $login, $senha);
    $stmt->execute();
    $result = $stmt->get_result();

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
  } else {
    echo "Erro na preparação do SQL.";
  }

  $stmt->close();
  $conn->close();
}
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
  <body>
    <header>
      <div class="logo">
        <img src="assets/SisCheck-BM.png" alt="cargacheck logo"/>
        <h1 class="title">SisCheck-BM</h1>
      </div>
    </header>
    <section>
      <main>
        <img class="photo" src="./assets/SisCheck-BM.png" alt="cargacheck logo" />
        <form method="POST">
          <input type="text" class="input" placeholder="USUÁRIO" name="user"/>
          <input type="password" class="input" placeholder="SENHA" name="password"/> 
          <input type="submit" class="button" value="Entrar">
        </form>
      </main>
    </section>
    <footer>
      Desenvolvido por Gustavo A. Kumagai
    </footer>
  </body>
</html>
