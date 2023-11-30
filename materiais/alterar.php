<?php
require_once("../checa_login.php");

// Verificar se o usuário é adm
if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

// Verificar se há id
if(!isset($_GET["id"])) header("Location: ../dashboard.php");

$idMaterial = $_GET['id'];

//verifica se há informações do formulário
if(isset($_GET['desc'])) {
  $descricao = $_GET['desc'];

  $sql = "UPDATE material SET 
          descricao = '$descricao', 
          WHERE id = $idMaterial";
  $conn->query($sql);
  header("Location: dados.php?id=$idMaterial");
  exit;
}

require_once("../conexao.php");

$sql = "SELECT * FROM material WHERE id = $idMaterial";
$result = $conn->query($sql);
$material = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<?php require_once("./head.html") ?>
<body>
  <?php require_once("../header.php") ?>
  <a class="button back-button" href="dados.php?id=<?= $material['id'] ?>">Dados de <?= $material['descricao'] ?></a>
  <section>
  <h1 class="title">Alterar: <?= $material['descricao'] ?></h1>
    <main>
      <form>
        <input name="id" value="<?= $idMaterial ?>" hidden/>
        <label>Descrição:</label>
        <input class="input" name="desc" value="<?= $material['descricao'] ?>" placeholder="Descreva o item..." required/>
        <input type="submit" value="Salvar" class="button submit">
      </form>
    </main>
  </section>
</body>
</html>