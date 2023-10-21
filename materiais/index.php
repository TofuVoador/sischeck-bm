<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../index.php");
  exit();
}

require_once("../conexao.php");

$sql = "SELECT m.id, m.descricao, m.patrimonio, m.origem_patrimonio,
        mnv.quantidade, v.prefixo, v.posfixo, v.id as 'idVeiculo' FROM material as m 
        LEFT JOIN materiais_no_veiculo as mnv ON mnv.idMaterial = m.id 
        LEFT JOIN compartimento as c ON c.id = mnv.idCompartimento
        LEFT JOIN veiculo as v ON v.id = c.idVeiculo
        WHERE mnv.status = 'ativo'
        ORDER BY m.id";
$materiais = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<?php require_once("./head.html") ?>
<body>
  <?php require_once("header.php") ?>
  <a class="button back-button" href="../dashboard.php">Menu</a>
  <section>
    <a class="button novo-button" href="cadastrar.php">Novo</a>
    <h1 class="title">Todos os Materiais</h1>
    <main>
      <?php foreach ($materiais as $mat) { ?>
        <div class="card">
          <h1 class=""><?php echo $mat['descricao'] ?></h1>
          <p><?php echo $mat['origem_patrimonio'] . '-' . $mat['patrimonio'] ?></p>
          <p>
            <a class="button" href="dados.php?id=<?=$mat['id']?>">Abrir</a>
            <a class="button" href="../veiculos/dados.php?id=<?=$mat['idVeiculo']?>"><?php echo ($mat['prefixo'] . '-' . $mat['posfixo']) ?></a>
          </p>
        </div>
      <?php } ?>
    </main>
  </section>
</body>
</html>