<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario']) || !isset($_GET['id'])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
}

$idMaterial = $_GET['id'];

if(isset($_GET['desc'])) {
  $descricao = $_GET['desc'];
  $origem = $_GET['orig'];
  $patrimonio = $_GET['patr'];
  $quantidade = $_GET['qtd'];

  $sql = "UPDATE material SET 
          descricao = '$descricao', 
          origem_patrimonio = '$origem', 
          patrimonio = '$patrimonio', 
          quantidade = $quantidade
          WHERE id = $idMaterial";
  $conn->query($sql);
  header("Location: dados.php?id=$idMaterial");
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
        <label>Patrimônio:</label>
        <div class="input-group">
          <input class="input" name="orig" value="<?= $material['origem_patrimonio'] ?>" placeholder="Origem"/>
          <input class="input" name="patr" value="<?= $material['patrimonio'] ?>" placeholder="Número"/>
        </div>
        <label>Quantidade no Almoxarifado:</label>
        <input class="input" type="number" name="qtd" value="<?= $material['quantidade'] ?>" <?php if($material['patrimonio'] != "") echo "readonly"; ?> required/>
        *apenas editável para itens sem patrimônio
        <input type="submit" value="Salvar" class="button">
      </form>
    </main>
  </section>
</body>
</html>