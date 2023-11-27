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
  $origem = $_POST['origem'];
  $patrimonio = $_POST['patrimonio'];

  $sql = "INSERT INTO material (descricao, origem_patrimonio, patrimonio)
          VALUES ('$descricao', '$origem', '$patrimonio')";
  
  $conn->query($sql);
  $idMaterial = $conn->insert_id;
  header("Location: dados.php?id=$idMaterial");
  exit;
}
?>
<!DOCTYPE html>
<html>
<?php require_once("./head.html") ?>
<body>
  <?php require_once("../header.php") ?>
  <a class="button back-button" href="../dashboard.php">Menu</a>
  <section>
    <h1 class="title">Cadastro de Material</h1>
    <main>
      <form method="post">
        <label>Descrição:</label>
        <input class="input" name="descricao" placeholder="Descreva o item..." required/>
        <label>Patrimônio:</label>
        <div class="input-group">
          <input class="input" name="origem" placeholder="Origem"/>
          <input class="input" name="patrimonio" placeholder="Número"/>
        </div>
        <input type="submit" value="Salvar" class="button submit">
      </form>
    </main>
  </section>
</body>
</html>