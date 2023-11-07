<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

if($_SESSION['usuario']['status'] != 'ativo') {
  header("Location: ../index.php");
  exit();
}

$usuario = $_SESSION['usuario'];

if($usuario['tipo'] != 'administrador') {
  header("Location: ../dashboard.php");
}

require_once("../conexao.php");

$sql = "SELECT *
        FROM usuario
        where status = 'ativo'
        order by nome";
$usuarios = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <?php require_once("../header.php") ?>
  <a class="button back-button" href="../dashboard.php">Menu</a>
  <section>
    <a class="button novo-button" href="cadastrar.php">Novo</a>
    <h1 class="title">Todos os Usuários</h1>
    <main>
      <?php foreach ($usuarios as $u) { ?>
        <div class="card">
          <h1 class="card-header"><?= $u['nome'] ?></h1>
          <p class="card-action">
            <a class="button" href="desativar.php?id=<?=$u['id']?>" onclick="return confirm('Tem certeza de que deseja desativar?')">Desativar</a>
          </p>
        </div>
      <?php } ?>
    </main>
  </section>
</body>
</html>