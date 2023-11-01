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

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../index.php");
  exit();
}

require_once("../conexao.php");

$sql = "SELECT * FROM setor";
$setores = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<?php require_once("./head.html") ?>
<body>
  <?php require_once("header.php") ?>
  <a class="button back-button" href="../dashboard.php">Menu</a>
  <section>
    <h1 class="title">Cadastro de Veículo</h1>
    <main>
      <form>
        <label>Código</label>
        <div class="input-group">
          <input class="input" name="veiculo['prefixo']" placeholder="Prefixo"/>
          <input class="input" name="veiculo['posfixo']" placeholder="Posfixo"/>
        </div>
        <label>Placa</label>
        <input class="input" name="veiculo['placa']"/>
        <label>Marca/Modelo</label>
        <div class="input-group">
          <input class="input" name="veiculo['marca']" placeholder="Marca"/>
          <input class="input" name="veiculo['modelo']" placeholder="Modelo"/>
        </div>
        <label>Renavan</label>
        <input class="input" name="veiculo['renavan']"/>
        <label>Setor</label>
        <select class="input" id="setor">
          <?php foreach ($setores as $s) { ?>
            <option value="<?php $s['id'] ?>"><?= $s['nome'] ?></option>
          <?php } ?>
        </select>
        <label></label>
        <input type="submit" value="Salvar" class="button">
        <label></label>
      </form>
    </main>
  </section>
</body>
</html>