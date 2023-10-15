<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario']) || !isset($_GET["id"])) {
    header("Location: ../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$idVeiculo = $_GET["id"];

require_once("../conexao.php");

if(isset($_POST['materials'])) {
  $materiais = $_POST['materials'];
  
  $sql = "INSERT INTO check_mnv (idMateriais_no_veiculo)";

  foreach ($materiais as $matID => $mat) {
    
    echo $matID;
    echo isset($mat["check"]);
    echo $mat["description"];
  }
}


$sql = "SELECT * FROM veiculo WHERE id = $idVeiculo";
$veiculo = $conn->query($sql);

$sql = "SELECT mnv.id as 'id_mnv', mnv.quantidade, 
        ch.estado, ch.data_check, ch.observacao, ch.resolvido,
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
        WHERE v.id = $idVeiculo
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
  <div class="button back-button">
    <a href="index.php">Veículos</a>
  </div>
  <section>
    <h1 class="title">Checagem de <?= $veiculo['prefixo'] . '-' . $veiculo['posfixo']?></h1>
    <main>
      <form method="post">
        <input type="number" value="<?=$idVeiculo?>" hidden>
        <?php foreach ($mnv as $mat) { ?>
            <div class="form-item">
                <div class="form-item-title">
                    <label class="switch">
                        <input type="checkbox" class="toggle-switch" 
                        name="materials[<?=$mat['id_mnv']?>][check]"
                        <?php if($mat['estado'] != '0' || $mat['resolvido'] != '0') echo 'checked';?>>
                        <span class="slider round"></span>
                    </label>
                    <p><?= $mat['quantidade'] ?></p>
                    <h2><?= $mat['descricao'] ?></h2>
                </div>
                <div class="form-item-description" <?php if($mat['estado'] != '0' || $mat['resolvido'] != '0') echo 'style="display: none;"';?>>
                    <input class="input" type="text" name="materials[<?=$mat['id_mnv']?>][description]" value="<?=$mat['observacao']?>">
                </div>
            </div>
        <?php } ?>
        <input type="submit" value="Salvar" class="button">
      </form>
      <script>
        // Get all the toggle switches
        var toggleSwitches = document.querySelectorAll(".toggle-switch");

        // Add event listeners to each toggle switch
        toggleSwitches.forEach(function(switchElement) {
            switchElement.addEventListener("change", function() {
                var description = this.closest(".form-item").querySelector(".form-item-description");
                if (this.checked) {
                    description.style.display = "none"; // Hide description when the switch is on
                } else {
                    description.style.display = "block"; // Show description when the switch is off
                }
            });
        });
      </script>
    </main>
  </section>
</body>
</html>