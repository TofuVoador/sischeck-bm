<?php
require_once("../checa_login.php");

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

require_once("../conexao.php");

//verifica se há informações do formulário
if(isset($_POST['prefixo'], $_POST['posfixo'], $_POST['placa'], $_POST['marca'], $_POST['modelo'], $_POST['setor'])) {
  $prefixo = $_POST['prefixo'];
  $posfixo = $_POST['posfixo'];
  $placa = $_POST['placa'];
  $marca = $_POST['marca'];
  $modelo = $_POST['modelo'];
  $setor = $_POST['setor'];

  // Preparar a consulta SQL
  $sql = "INSERT INTO veiculo (prefixo, posfixo, placa, marca, modelo, idSetor)
          VALUES (?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('sssssi', $prefixo, $posfixo, $placa, $marca, $modelo, $setor);

  if ($stmt->execute()) {
    $idVeiculo = $conn->insert_id;
    header("Location: dados.php?id=$idVeiculo");
    exit;
  } else {
    echo "Algo deu errado...";
  }
}

// Consulta SQL para obter os setores
$sql = "SELECT * FROM setor";
$setores = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<?php require_once("./head.html") ?>
<body>
<?php require_once("../header.php") ?>
  <a class="button back-button" href="index.php">Veiculos</a>
  <section>
    <h1 class="title">Cadastro de Veículo</h1>
    <main>
      <form method="post">
        <label>Código</label>
        <div class="input-group">
          <input class="input" name="prefixo" placeholder="Prefixo" required/>
          <input class="input" name="posfixo" placeholder="Posfixo" required/>
        </div>
        <label>Placa</label>
        <input class="input" name="placa" required/>
        <label>Marca/Modelo</label>
        <div class="input-group">
          <input class="input" name="marca" placeholder="Marca" required/>
          <input class="input" name="modelo" placeholder="Modelo" required/>
        </div>
        <label>Setor</label>
        <select class="input select" name="setor">
          <?php foreach ($setores as $s) { ?>
            <option value="<?= $s['id'] ?>"><?= $s['nome'] ?></option>
          <?php } ?>
        </select>
        <label></label>
        <input type="submit" value="Cadastrar" class="button">
      </form>
    </main>
  </section>
</body>
</html>