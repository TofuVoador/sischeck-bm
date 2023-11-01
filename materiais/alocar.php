<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario']) || !isset($_GET["id"])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../index.php");
  exit();
}

$idMaterial = $_GET["id"];

require_once("../conexao.php");

$sql = "SELECT mnv.* FROM materiais_no_veiculo as mnv where mnv.idMaterial = $idMaterial";
$result = $conn->query($sql);
$material = $result->fetch_assoc();

$sql = "SELECT v.*
        FROM veiculo as v
        ORDER BY v.prefixo, v.posfixo";
$veiculos = $conn->query($sql);

$sql = "SELECT v.*,
        CASE
          WHEN mnv.idMaterial = $idMaterial THEN 'Sim'
          ELSE 'Não'
        END as 'alocavel'
        FROM materiais_no_veiculo as mnv
        LEFT JOIN material as m on m.id = mnv.idMaterial
        LEFT JOIN compartimento as c on c.id = mnv.idCompartimento
        LEFT JOIN veiculo as v on v.id = c.idVeiculo
        LEFT JOIN check_mnv as ch on ch.idMateriais_no_veiculo
        WHERE m.id = $idMaterial AND mnv.status = 'ativo'
        ORDER BY v.prefixo, v.posfixo, m.descricao";
$compartimentos = $conn->query($sql);

function getCompartimentos() {

}

?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <?php require_once("header.php") ?>
  <a class="button back-button" href="index.php">Materiais</a>
  <section>
    <main>
      <form>
        <label for="veiculo">Selecione o Veiculo:</label>
        <select class="input" id="veiculo" onchange="updateVeiculo()">
          <?php foreach ($veiculos as $v) { ?>
            <option><?= $v['prefixo']."-".$v['posfixo'] ?></option>
          <?php } ?>
        </select>
        <label for="compartimento">Selecione o Compartimento:</label>
        <select class="input" id="compartimento">
        </select>
      </form>
    </main>
  </section>
</body>
</html>