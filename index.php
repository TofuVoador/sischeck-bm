<?php
require_once("conexao.php");

if(isset($_POST['password']) && isset($_POST['user'])) {
  // Obter os dados do formulário
  $login = $_POST['user'];
  $senha = $_POST['password'];
  
  // Consulta SQL para verificar as credenciais
  $sql = "SELECT * FROM usuario WHERE login = ?";
  $stmt = $conn->prepare($sql);

  if($stmt) {
    $stmt->bind_param('s', $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if(password_verify($senha, $user['senha'])) {
          session_start();
          $_SESSION['usuario'] = $user;

          header("Location: dashboard.php");
        } else { 
          echo "Nome de usuário ou senha incorretos.";
          exit;
      }
    } else { 
      echo "Nome de usuário ou senha incorretos.";
      exit;
    }
  } else {
    echo "Erro na preparação do SQL.";
    exit;
  }

  $stmt->close();
  $conn->close();
}

$sql = "SELECT count(id) as 'count' FROM usuario";
$result = $conn->query($sql);
$logins = $result->fetch_assoc();
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
          <input type="text" maxlength="50" class="input" placeholder="USUÁRIO" name="user"/>
          <input type="password" maxlength="250" class="input" placeholder="SENHA" name="password"/> 
          <input type="submit" class="button" value="Entrar">
        </form>
        <?php if($logins['count'] == 0) { ?>
          <a class="button" href="primeiro.php">Primeiro Usuário</a>
        <?php } ?>
      </main>
    </section>
    <footer>
      Desenvolvido por Gustavo A. Kumagai
    </footer>
  </body>
</html>
