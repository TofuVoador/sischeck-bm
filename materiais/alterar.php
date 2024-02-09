<?php
require_once("../checa_login.php");

// Verificar se o usuário é adm
if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

require_once("../conexao.php");

//verifica se há informações do formulário
if(isset($_POST['desc']) && isset($_POST['id'])) {
  $descricao = $_POST['desc'];
  $id = $_POST['id'];

  $sql = "UPDATE material SET 
          descricao = '$descricao'
          WHERE id = $id";
  $conn->query($sql);
  var_dump($sql);
  header("Location: dados.php?id=$id");
  exit;
}


// Verificar se há id
if(!isset($_GET["id"])) {
  header("Location: ../dashboard.php");
  exit;
}

$idMaterial = $_GET['id'];

if(!is_numeric($idMaterial)) {
  echo "ID não é um número válido";
  exit;
}

$sql = "SELECT * FROM material WHERE id = $idMaterial";
$result = $conn->query($sql);
$material = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<?php require_once("./head.html") ?>
<body>
  <?php require_once("../header.php") ?>
  <a class="button back-button" href="dados.php?id=<?= $material['id'] ?>">Dados</a>
  <section>
  <h1 class="title">Alterar: <?= $material['descricao'] ?></h1>
    <main>
      <form method="post">
        <input name="id" value="<?= $idMaterial ?>" hidden/>
        <label>Descrição:</label>
        <input class="input" name="desc" value="<?= $material['descricao'] ?>" placeholder="Descreva o item..." required/>
        <input type="submit" value="Salvar" class="button submit">
      </form>
    </main>
  </section>
</body>
</html>