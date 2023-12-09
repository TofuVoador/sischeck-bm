<?php
require_once("../checa_login.php");

if($usuario['tipo'] !== 'administrador') {
  header("Location: ../dashboard.php");
  exit;
}

require_once("../conexao.php");

//busca todos os setores e a quantidade de veículos que possui
$sql = "SELECT s.*, 
        (SELECT COUNT(v.id) FROM veiculo as v where v.idSetor = s.id) as veiculos 
        FROM setor as s
        where s.status = 'ativo'
        order by s.nome";
$setores = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <?php require_once("../header.php") ?>
  <a class="button back-button" href="../dashboard.php">Menu</a>
  <section>
    <form action="cadastrar.php">
      <label>Novo Setor</label>
      <input class="input" name="nome" placeholder="Nome do Setor" required/>
      <input type="submit" class="button" value="Cadastrar"/>
    </form>
    <h1 class="title">Todos os Setores</h1>
    <main>
      <div class="list">
        <?php foreach ($setores as $setor) { ?>
          <div class="card">
            <h1 class="card-header"><?= $setor['nome'] ?></h1>
            <p>Veículos: <?php echo $setor['veiculos'] ?></p>
            <p class="card-action">
              <a class="button" href="confirmar.php?id=<?=$setor['id']?>">Desativar</a>
            </p>
          </div>
        <?php } ?>
      </div>
    </main>
  </section>
</body>
</html>