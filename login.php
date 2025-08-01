<?php
session_start();
error_reporting(0);

$usuario_valido = "teste";
$senha_valida = "teste";

if (isset($_POST['usuario'], $_POST['senha'])) {
    if ($_POST['usuario'] === $usuario_valido && $_POST['senha'] === $senha_valida) {
        $_SESSION['logado'] = true;
        header("Location: index.php");
        exit;
    } else {
        $erro = "Usuário ou senha inválidos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Login - CENTRAL LUNAR</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: "Orbitron", monospace;
      background: radial-gradient(circle, #1b1b2f 0%, #090a1a 100%);
      color: #f0f0f0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .login-container {
      background-color: rgba(0, 0, 0, 0.65);
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 0 20px #bb00ff88;
      width: 90%;
      max-width: 400px;
      text-align: center;
    }
    h2 {
      color: #bb00ff;
      margin-bottom: 20px;
      font-size: 24px;
    }
    input[type="text"], input[type="password"] {
      width: 90%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 12px;
      border: 2px solid #bb00ff;
      background-color: #1a0a2a;
      color: #bb00ff;
      font-family: "Orbitron", monospace;
    }
    input[type="submit"] {
      padding: 12px 25px;
      border: none;
      border-radius: 12px;
      background-color: #7e00ff;
      color: #fff;
      font-weight: bold;
      cursor: pointer;
      margin-top: 15px;
      box-shadow: 0 0 15px #bb00ff;
      font-family: "Orbitron", monospace;
    }
    input[type="submit"]:hover {
      background-color: #bb00ff;
      color: #000;
    }
    .erro {
      color: #ff5555;
      margin-top: 10px;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>LOGIN - CENTRAL LUNAR</h2>
    <form method="post">
      <input type="text" name="usuario" placeholder="Usuário" required><br>
      <input type="password" name="senha" placeholder="Senha" required><br>
      <input type="submit" value="Entrar">
    </form>
    <?php if (isset($erro)) echo "<div class='erro'>$erro</div>"; ?>
  </div>
</body>
</html>
