<?php
require_once("../checa_login.php");
?>
<!DOCTYPE html>
<html>
<?php require_once("head.html") ?>
<body>
  <?php require_once("../header.php") ?>  
  <a class="button back-button" href="../dashboard.php">Menu</a>
  <section>
    <h1 class="title">Meu Perfil</h1>
    <main>
      <form method="post" action="alterar.php">
        <input name="user" value="<?= $usuario['id'] ?>" hidden/>
        <label>Login (não editável)</label>
        <input class="input" name="login" placeholder="Nome" value="<?= $usuario['login'] ?>" readonly/>
        <label>Nome</label>
        <input class="input" name="nome" placeholder="Nome" value="<?= $usuario['nome'] ?>" required/>
        <label>Nova Senha</label>
        <input class="input" type="password" name="novasenha" placeholder="Nova Senha" required/>
        <label>Senha</label>
        <input class="input" type="password" name="senha" placeholder="Senha" required/>
        <input type="submit" class="button" value="Salvar"/>
      </form>
    </main>
  </section>
</body>
</html>