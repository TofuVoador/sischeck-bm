<?php
require_once("../checa_login.php");

require_once("../conexao.php");

//busca todos os veículos e a data da última checagem
$sql = "SELECT v.*, s.nome as setor_nome, c.nome as compartimento_nome, mnv.id as id_mnv, MAX(ch.data_check) as 'verificado' FROM veiculo as v 
        LEFT JOIN setor as s ON s.id = v.idSetor
        LEFT JOIN compartimento as c ON c.idVeiculo = v.id
        LEFT JOIN materiais_no_veiculo as mnv ON mnv.idCompartimento = c.id
        LEFT JOIN (
            SELECT idMateriais_no_veiculo, MAX(data_check) as max_data
            FROM check_mnv
            GROUP BY idMateriais_no_veiculo
        ) as max_ch ON mnv.id = max_ch.idMateriais_no_veiculo
        LEFT JOIN check_mnv as ch ON mnv.id = ch.idMateriais_no_Veiculo AND ch.data_check = max_ch.max_data
        WHERE v.status = 'ativo'
        GROUP BY v.id, c.idVeiculo";
$veiculos = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <?php require_once("../header.php") ?>
  <a class="button back-button" href="../dashboard.php">Menu</a>
  <section>
    <?php if($usuario['tipo'] == 'administrador') {?> 
      <a class="button novo-button" href="cadastrar.php">Novo</a>
    <?php } ?>
    <h1 class="title">Todos os Veículos</h1>
    <main>
      <input class="input" id="search" onkeyup="filterVeiculo()" placeholder="Pesquisar pelo veículo...">
      <script>
        function filterVeiculo() {
          var input = document.getElementById('search');
          var filter = input.value.toUpperCase();
          var cards = document.querySelectorAll('.card');

          cards.forEach(function(card) {
            var title = card.querySelector('.card-header');
            var txt = title.textContent || title.innerHTML;
            if(txt.toUpperCase().indexOf(filter) > -1) {
              card.style.display = '';
            } else {
              card.style.display = 'none';
            }
          });
        }
      </script>
      <?php foreach ($veiculos as $veiculo) { ?>
        <div class="card">
          <h1 class="card-header"><?php echo ($veiculo['prefixo'] . '-' . $veiculo['posfixo']) ?></h1>
          <p>Placa: <?php echo $veiculo['placa'] ?></p>
          <p>Marca/Modelo: <?php echo ($veiculo['marca'] . " " . $veiculo['modelo']) ?></p>
          <p>Setor: <?php echo $veiculo['setor_nome'] ?></p>
          <p>Verificado: <?= $veiculo['verificado'] != null ? date('H:i - d/m/Y', strtotime($veiculo['verificado'])) : 'Nunca' ?></p>
          <p class="card-action">
            <a class="button" href="verificar.php?id=<?=$veiculo['id']?>">Verificar</a>
            <?php if($usuario['tipo'] == 'administrador') {?> 
              <a class="button" href="dados.php?id=<?=$veiculo['id']?>">Abrir</a> 
            <?php } ?>
          </p>
        </div>
      <?php } ?>
    </main>
  </section>
</body>
</html>