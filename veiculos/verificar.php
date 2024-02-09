<?php
require_once("../checa_login.php");
require_once("../conexao.php");

if(isset($_POST['materials'])) {
  $materiais = $_POST['materials'];
  $verificador = $usuario['id'];

  foreach ($materiais as $mnvID => $mat) {
    // Limpar e validar os dados recebidos
    $idMateriais_no_veiculo = (int) $mnvID;
    $ok = isset($mat["ok"]) ? 1 : 0;
    $observacao = isset($mat["observacao"]) && !isset($mat["ok"]) ? $mat["observacao"] : null;
    $resolvido = isset($mat["ok"]) ? 1 : 0;

    // Preparar a consulta SQL
    $sql = "INSERT INTO check_mnv (idVerificador, idMateriais_no_veiculo, ok, observacao, resolvido) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Vincular os parâmetros
    $stmt->bind_param('ssisi', $verificador, $idMateriais_no_veiculo, $ok, $observacao, $resolvido);

    // Executar a consulta preparada
    $stmt->execute();
  }

  header("Location: ./index.php");
  exit;
}

if(!isset($_GET["id"])) {
  header("Location: ../dashboard.php");
  exit;
}

$idVeiculo = $_GET["id"];

if(!is_numeric($idVeiculo)) {
  echo "ID não é um número válido";
  exit;
}

$sql = "SELECT * FROM veiculo WHERE id = $idVeiculo";
$veiculo = $conn->query($sql);

$sql = "SELECT c.* FROM compartimento as c WHERE c.idVeiculo = $idVeiculo and c.status = 'ativo'
        ORDER BY c.id";
$compartimentos = $conn->query($sql);

if ($veiculo->num_rows > 0) {
  $veiculo = $veiculo->fetch_assoc();
} else {
  header("Location: ../dashboard.php");
  exit;
}

function getMateriais($idCompartimento) {
  require("../conexao.php");

  //busca todos os materiais do compartimento solicitado
  $sql = "SELECT mnv.id as 'id_mnv', mnv.quantidade, 
          ch.ok, ch.data_check, ch.observacao, ch.resolvido,
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
          WHERE c.id = $idCompartimento and mnv.status = 'ativo'
          ORDER BY c.id, m.descricao";

  $materiaisNoVeiculo = $conn->query($sql);
  return $materiaisNoVeiculo;
}
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <?php require_once("../header.php") ?>
  <a class="button back-button" href="index.php">Veículos</a>
  <section>
    <h1 class="title">Checagem de <?= $veiculo['prefixo'] . '-' . $veiculo['posfixo']?></h1>
    <main>
      <form method="post">
        <input type="number" value="<?=$idVeiculo?>" hidden>
        <?php foreach ($compartimentos as $comp) { ?>
          <h1><?= $comp['nome'] ?></h1>
          <?php
          $mnv = getMateriais($comp['id']);
          foreach ($mnv as $mat) { ?>
            <div class="card">
              <label class="switch">
                  <input type="checkbox" class="toggle-switch" 
                  name="materials[<?=$mat['id_mnv']?>][ok]"
                  <?php if($mat['ok'] != '0' || $mat['resolvido'] != '0') echo 'checked';?>>
                  <span class="slider round"></span>
              </label>
              <p><?php echo ($mat['quantidade'] != '') ? $mat['quantidade'] : '*' ?> | <?= $mat['descricao'] ?></p>
              <p class="form-item-description" <?php if($mat['ok'] != '0' || $mat['resolvido'] != '0') echo 'style="display: none;"';?>>
                  <input class="input" type="text" name="materials[<?=$mat['id_mnv']?>][observacao]" value="<?=$mat['observacao']?>" placeholder="Descreva o problema">
              </p>
            </div>
          <?php } 
        } ?>
        <input type="submit" value="Salvar" class="button">
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
<script>
//confirma que o usuário irá perder as informações já inseridas
window.onbeforeunload = function() {
  return 'Tem certeza que deseja sair? Seus dados serão perdidos.';
};
</script>