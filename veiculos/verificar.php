<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario']) || !isset($_GET["id"])) {
    header("Location: ../index.php");
    exit();
}

if($_SESSION['usuario']['status'] != 'ativo') {
  header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$idVeiculo = $_GET["id"];

require_once("../conexao.php");

if(isset($_POST['materials'])) {
  $materiais = $_POST['materials'];
  


  foreach ($materiais as $mnvID => $mat) {
    $sql = "INSERT INTO check_mnv";
    if(isset($mat["check"])) {
      $sql .= " (idVerificador, idMateriais_no_veiculo) 
                values ({$usuario['id']}, $mnvID)";
    } else {
      $sql .= " (idVerificador, idMateriais_no_veiculo, estado, observacao, resolvido) 
                values ({$usuario['id']}, $mnvID, 0, '{$mat['observacao']}', 0)";
    }

    $conn->query($sql);
  }

  header("Location: ./index.php");
}


$sql = "SELECT * FROM veiculo WHERE id = $idVeiculo";
$veiculo = $conn->query($sql);

$sql = "SELECT mnv.id as 'id_mnv', mnv.quantidade, 
        ch.check, ch.data_check, ch.observacao, ch.resolvido,
        m.descricao, c.nome as 'nome_compartimento' FROM materiais_no_veiculo as mnv 
        LEFT JOIN material as m on m.id = mnv.idMaterial
        LEFT JOIN (
            SELECT idMateriais_no_veiculo, MAX(data_check) as max_data
            FROM check_mnv
            GROUP BY idMateriais_no_veiculo
        ) as max_ch ON mnv.id = max_ch.idMateriais_no_veiculo
        LEFT JOIN check_mnv as ch ON 
          ch.idMateriais_no_veiculo = mnv.id AND ch.data_check = max_ch.max_data
        LEFT JOIN compartimento as c on c.id = mnv.idCompartimento
        LEFT JOIN veiculo as v on v.id = c.idVeiculo
        WHERE v.id = $idVeiculo and mnv.status = 'ativo'
        ORDER BY c.ordem_verificacao, m.descricao";
$mnv = $conn->query($sql);

if ($veiculo->num_rows > 0) {
  $veiculo = $veiculo->fetch_assoc();
} else {
  header("Location: ../dashboard.php");
}
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <?php require_once("header.php") ?>
  <a class="button back-button" href="index.php">Veículos</a>
  <section>
    <h1 class="title">Checagem de <?= $veiculo['prefixo'] . '-' . $veiculo['posfixo']?></h1>
    <main>
      <form method="post">
        <input type="number" value="<?=$idVeiculo?>" hidden>
        <?php if ($mnv->num_rows == 0) { ?>
          <h1>Nada para verificar aqui!</h1>
        <?php } else {
          foreach ($mnv as $mat) { ?>
            <div class="card">
              <label class="switch">
                  <input type="checkbox" class="toggle-switch" 
                  name="materials[<?=$mat['id_mnv']?>][check]"
                  <?php if($mat['check'] != '0' || $mat['resolvido'] != '0') echo 'checked';?>>
                  <span class="slider round"></span>
              </label>
              <p><?= $mat['quantidade'] ?> | <?= $mat['descricao'] ?></p>
              <p class="form-item-description" <?php if($mat['check'] != '0' || $mat['resolvido'] != '0') echo 'style="display: none;"';?>>
                  <input class="input" type="text" name="materials[<?=$mat['id_mnv']?>][observacao]" value="<?=$mat['observacao']?>" placeholder="Descreva o problema">
              </p>
            </div>
          <?php } ?>
          <input type="submit" value="Salvar" class="button">
        <?php } ?>
      </form>
      <script>
        var toggleSwitches = document.querySelectorAll(".toggle-switch");

        toggleSwitches.forEach(function(switchElement) {
            switchElement.addEventListener("change", function() {
                var description = this.closest(".card").querySelector(".form-item-description");
                if (this.checked) {
                    description.style.display = "none";
                } else {
                    description.style.display = "block";
                }
            });
        });
      </script>
    </main>
  </section>
</body>
</html>