<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Novo Cadastro</title>
  <style>
    body {
      background: linear-gradient(to right, #ffe6f0, #fff0f5);
      font-family: 'Comic Sans MS', cursive, sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
      overflow: hidden;
      position: relative;
    }

    h1 {
      color: #ff69b4;
      text-shadow: 2px 2px 4px #fff;
      font-size: 2.2rem;
      margin-bottom: 20px;
    }

    form {
      background-color: #fff0f5;
      border: 2px solid #ffb6c1;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 8px 16px rgba(255, 182, 193, 0.4);
      display: flex;
      flex-direction: column;
      gap: 15px;
      z-index: 10;
    }

    label {
      color: #c71585;
      font-weight: bold;
    }

    input[type="text"],
    input[type="password"] {
      padding: 10px;
      border: 2px solid #ffa7c4;
      border-radius: 10px;
      font-size: 1rem;
      background-color: #fff;
    }

    button {
      background-color: #ff69b4;
      color: white;
      padding: 10px;
      border: none;
      border-radius: 15px;
      font-size: 1.2rem;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #ff1493;
    }

    p {
      font-size: 1.1rem;
      color: green;
      margin-top: 10px;
    }

    /* Pétalas */
    .petal {
      position: absolute;
      width: 15px;
      height: 15px;
      background: white;
      border-radius: 50%;
      opacity: 0.8;
      pointer-events: none;
      animation: fall linear infinite;
    }

    @keyframes fall {
      0% {
        transform: translateY(-10vh) rotate(0deg);
        opacity: 1;
      }
      100% {
        transform: translateY(110vh) rotate(360deg);
        opacity: 0;
      }
    }
  </style>
</head>
<body>

  <h1>✨ Cadastre-se fofamente ✨</h1>

  <div>
    <form action="cadastro.php" method="post">
      <label for="nomeUsuario">Usuário:</label>
      <input type="text" name="nomeUsuario" id="nomeUsuario" required>

      <label for="login">Login:</label>
      <input type="text" name="login" id="login" required>

      <label for="senha">Senha:</label>
      <input type="password" name="senha" id="senha" required>

      <button type="submit">Cadastrar</button>
    </form>
  </div>

  <!-- Pétalas -->
  <script>
    const totalPetals = 25;

    for (let i = 0; i < totalPetals; i++) {
      const petal = document.createElement('div');
      petal.classList.add('petal');
      petal.style.left = Math.random() * 100 + "vw";
      petal.style.animationDuration = (5 + Math.random() * 5) + "s";
      petal.style.animationDelay = Math.random() * 5 + "s";
      document.body.appendChild(petal);
    }
  </script>

  <?php
    require './banco/validacoes.php';

    form_nao_enviado('Forms não foi enviado');
    ha_campos_em_branco_cadastro('Existe campos em branco');

    require './banco/conexao.php';

    $nomeUsuario = $_POST['nomeUsuario'];
    $login = $_POST['login'];
    $senha = $_POST['senha'];

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nome, login, senha) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    verificar_erro_stmt($stmt);

    mysqli_stmt_bind_param($stmt, "sss", $nomeUsuario, $login, $senhaHash);

    verificar_savamento(mysqli_stmt_execute($stmt));

    echo "<p>Dados inseridos com sucesso!</p>";

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    header("Location: index.php");
  ?>
</body>
</html>
