<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario']) || !isset($_GET['id'])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../index.php");
  exit();
}

$idMaterial = $_GET['id'];

require_once("../conexao.php");

$sql = "SELECT * FROM material WHERE id = $idMaterial";
$result = $conn->query($sql);
$material = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<?php require_once("./head.html") ?>
<body>
  <?php require_once("header.php") ?>
  <a class="button back-button" href="dados.php?id=<?= $material['id'] ?>">Dados de <?= $material['descricao'] ?></a>
  <section>
  <h1 class="title">Alterar: <?= $material['descricao'] ?></h1>
    <main>
      <form>
        <label>Descrição:</label>
        <input class="input" name="material['descricao']" value="<?= $material['descricao'] ?>" placeholder="Descreva o item..."/>
        <label>Patrimônio:</label>
        <div class="input-group">
          <input class="input" name="material['origem_patrimonio']" value="<?= $material['origem_patrimonio'] ?>" placeholder="Origem"/>
          <input class="input" name="material['patrimonio']" value="<?= $material['patrimonio'] ?>" placeholder="Número"/>
        </div>
        <label>Quantidade no Almoxarifado:</label>
        <input class="input" type="number" name="material['quantidade']" value="<?= $material['quantidade'] ?>"/>
        <input type="submit" value="Salvar" class="button">
      </form>
      <a class="button" href="desativar.php?id=<?= $material['id'] ?>" onclick="return confirm('Tem certeza de que deseja desativar?')">
        Desativar
      </a>
    </main>
  </section>
</body>
</html>