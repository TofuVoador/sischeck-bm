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

        if($user['status'] == 'ativo') {
          session_start();
          $_SESSION['usuario'] = $user;
  
          header("Location: dashboard.php");
          exit;
        } else { 
          ?><div class="alert-notif"> Usuário Inativado. </div> <?php ;
        }
      } else { 
        ?><div class="alert-notif"> Nome de usuário ou senha incorretos. </div> <?php ;
      }
    } else { 
      ?><div class="alert-notif"> Nome de usuário ou senha incorretos. </div> <?php ;
    }
  } else {
    ?><div class="alert-notif"> Erro na preparação do SQL. </div> <?php
    exit;
  }

  $stmt->close();
}

$sql = "SELECT count(id) as 'count' FROM usuario WHERE status = 'ativo'";
$result = $conn->query($sql);
$logins = $result->fetch_assoc();
$conn->close();

if($logins['count'] == 0) {
  header("Location: primeiro.php");
  exit;
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
          <input type="text" maxlength="50" class="input" placeholder="USUÁRIO" name="user"/>
          <input type="password" maxlength="250" class="input" placeholder="SENHA" name="password"/> 
          <input type="submit" class="button" value="Entrar">
        </form>
      </main>
    </section>
    <footer>
      Desenvolvido por Gustavo A. Kumagai
    </footer>
  </body>
</html>
