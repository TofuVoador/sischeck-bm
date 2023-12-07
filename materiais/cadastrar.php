<?php
require_once("../checa_login.php");

// Verificar se o usuário é adm
if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

//verifica se há informações do formulário
if(isset($_POST['descricao'])) {
  require_once("../conexao.php");

  $descricao = $_POST['descricao'];

  $sql = "INSERT INTO material (descricao)
          VALUES ('$descricao')";
  
  if($conn->query($sql)) {
    $idMaterial = $conn->insert_id;
    header("Location: dados.php?id=$idMaterial");
    exit;
  } else {
    echo "Algo deu errado...";
  }
}
?>
<!DOCTYPE html>
<html>
<?php require_once("./head.html") ?>
<body>
  <?php require_once("../header.php") ?>
  <a class="button back-button" href="index.php">Menu</a>
  <section>
    <h1 class="title">Cadastro de Material</h1>
    <main>
      <form method="post">
        <label>Descrição:</label>
        <input class="input" name="descricao" placeholder="Descreva o item..." required/>
        <input type="submit" value="Salvar" class="button submit">
      </form>
    </main>
  </section>
</body>
</html>