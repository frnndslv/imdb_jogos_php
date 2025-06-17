<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= $modoEdicao ? 'Editar Jogo' : 'Cadastrar Jogo' ?></title>
  <style>
    body {
      background: linear-gradient(to right, #ffe6f0, #fff0f5);
      font-family: 'Comic Sans MS', cursive, sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 50px 20px;
      height: 100vh;
      margin: 0;
      overflow: hidden;
      position: relative;
    }

    h1 {
      color: #ff69b4;
      font-size: 2.5rem;
      margin-bottom: 20px;
      text-shadow: 2px 2px 5px #fff;
      z-index: 10;
    }

    form {
      background-color: #fff0f5;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 0 15px rgba(255, 192, 203, 0.6);
      display: flex;
      flex-direction: column;
      gap: 15px;
      z-index: 10;
      width: 100%;
      max-width: 400px;
    }

    label {
      font-weight: bold;
      color: #c71585;
    }

    input {
      padding: 10px;
      border: 1px solid #ffc0cb;
      border-radius: 8px;
      font-size: 1rem;
    }

    button {
      background-color: #ff69b4;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 1.1rem;
      transition: background 0.3s;
    }

    button:hover {
      background-color: #ff1493;
    }

    .petal {
      position: absolute;
      width: 20px;
      height: 20px;
      background: white;
      border-radius: 50%;
      opacity: 0.7;
      pointer-events: none;
      animation: fall linear infinite;
      z-index: 1;
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

    .scattered-petal {
      position: absolute;
      width: 20px;
      height: 20px;
      background: white;
      border-radius: 50%;
      opacity: 0.4;
      transform: rotate(var(--rotate));
      filter: blur(1px);
      z-index: 0;
    }
  </style>
</head>
<body>

<?php
    require './banco/conexao.php';
    session_start();


    if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
        die("Acesso negado.");
    }

    $modoEdicao = false;
    $jogo = ['id' => '', 'nome' => '', 'plataforma' => '', 'genero' => ''];


    if (isset($_GET['id'])) {
        $modoEdicao = true;
        $id = (int)$_GET['id'];
        $sql = "SELECT * FROM jogos WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if ($res && mysqli_num_rows($res) === 1) {
            $jogo = mysqli_fetch_assoc($res);
        } else {
            echo "Jogo não encontrado.";
            exit();
        }
        mysqli_stmt_close($stmt);
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $nome = $_POST['nome'];
        $plataforma = $_POST['plataforma'];
        $genero = $_POST['genero'];

        if (!empty($_POST['id'])) {
            $id = (int)$_POST['id'];
            $sql = "UPDATE jogos SET nome=?, plataforma=?, genero=? WHERE id=?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssi", $nome, $plataforma, $genero, $id);
            mysqli_stmt_execute($stmt);
        } else {
            $sql = "INSERT INTO jogos (nome, plataforma, genero) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $nome, $plataforma, $genero);
            mysqli_stmt_execute($stmt);
        }

        mysqli_stmt_close($stmt);
        header("Location: perfil.php");
        exit();
    }

    mysqli_close($conn);
    ?>


  <h1><?= $modoEdicao ? 'Editar Jogo 🎮' : 'Cadastrar Novo Jogo 🌸' ?></h1>

  <form action="jogos.php<?= $modoEdicao ? '?id=' . $jogo['id'] : '' ?>" method="post">
    <input type="hidden" name="id" value="<?= htmlspecialchars($jogo['id']) ?>">

    <label for="nome">Nome:</label>
    <input type="text" name="nome" id="nome" required value="<?= htmlspecialchars($jogo['nome']) ?>">

    <label for="plataforma">Plataforma:</label>
    <input type="text" name="plataforma" id="plataforma" required value="<?= htmlspecialchars($jogo['plataforma']) ?>">

    <label for="genero">Gênero:</label>
    <input type="text" name="genero" id="genero" required value="<?= htmlspecialchars($jogo['genero']) ?>">

    <button type="submit"><?= $modoEdicao ? 'Salvar Alterações 💾' : 'Criar Jogo ➕' ?></button>
  </form>

  
  
  <script>
    const totalPetals = 40;

    for (let i = 0; i < totalPetals; i++) {
      const petal = document.createElement('div');
      petal.classList.add('petal');
      petal.style.left = Math.random() * 100 + "vw";
      petal.style.animationDuration = (5 + Math.random() * 5) + "s";
      petal.style.animationDelay = Math.random() * 5 + "s";
      document.body.appendChild(petal);
    }

    const scattered = 30;
    for (let i = 0; i < scattered; i++) {
      const sp = document.createElement('div');
      sp.classList.add('scattered-petal');
      sp.style.left = Math.random() * 100 + "vw";
      sp.style.top = Math.random() * 100 + "vh";
      sp.style.setProperty('--rotate', Math.random() * 360 + "deg");
      document.body.appendChild(sp);
    }
  </script>

</body>
</html>
