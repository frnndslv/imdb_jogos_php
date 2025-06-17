


<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Avaliar Jogo</title>
  <style>
    body {
      background: linear-gradient(to right, #ffe6f0, #fff0f5);
      font-family: 'Comic Sans MS', cursive, sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 40px 20px;
      min-height: 100vh;
      margin: 0;
      position: relative;
      overflow: hidden;
      overflow-y: auto;
      
    }

    h1 {
      color: #ff69b4;
      text-shadow: 2px 2px 5px #fff;
      margin-bottom: 20px;
    }

    .container {
      background: #fff0f5;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 0 15px rgba(255, 192, 203, 0.7);
      width: 100%;
      max-width: 500px;
      z-index: 1;
    }

    label {
      font-weight: bold;
      color: #c71585;
      display: block;
      margin-top: 15px;
    }

    input[type="text"], textarea, select {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 2px solid #ffc0cb;
      border-radius: 8px;
      background: #fff;
      font-family: inherit;
      font-size: 1rem;
    }

    textarea {
      resize: vertical;
    }

    .stars {
      display: flex;
      gap: 10px;
      margin-top: 10px;
    }

    .stars input {
      display: none;
    }

    .stars label {
      font-size: 2rem;
      color: #ddd;
      cursor: pointer;
      transition: transform 0.2s;
    }

    .stars input:checked ~ label,
    .stars label:hover,
    .stars label:hover ~ label {
      color: #ffc107;
      transform: scale(1.2);
    }

    button {
      margin-top: 20px;
      background-color: #ff69b4;
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 10px;
      cursor: pointer;
      font-size: 1rem;
    }

    .petal {
      position: absolute;
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

<?php
        session_start();
        require './banco/conexao.php';

        if (!isset($_GET['id'])) {
            echo "<p style='color: red;'>ID do jogo não especificado.</p>";
            exit;
        }

        $id = intval($_GET['id']);
        $sql = "SELECT * FROM jogos WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($resultado) !== 1) {
            echo "<p style='color: red;'>Jogo não encontrado.</p>";
            exit;
        }

        $jogo = mysqli_fetch_assoc($resultado);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
  ?>

  <?php
    require './banco/conexao.php';

    $exibirFormulario = false;
    $jaAvaliou = false;

    if (isset($_SESSION['id_usuario'])) {
        $id_usuario = $_SESSION['id_usuario'];

        $sqlCheck = "SELECT * FROM avaliacao WHERE id_jogo = ? AND id_usuario = ?";
        $stmtCheck = mysqli_prepare($conn, $sqlCheck);
        mysqli_stmt_bind_param($stmtCheck, "ii", $id, $id_usuario);
        mysqli_stmt_execute($stmtCheck);
        $resCheck = mysqli_stmt_get_result($stmtCheck);
        

        if (mysqli_num_rows($resCheck) === 0) {
            $exibirFormulario = true;
        } else {
            $jaAvaliou = true;
        }

        mysqli_stmt_close($stmtCheck);
    }
    ?>


  <h1>🌸 Avaliar Jogo 🌸</h1>

  

    <div class="container">
        <p><strong>Nome:</strong> <?= htmlspecialchars($jogo['nome']) ?></p>
        <p><strong>Plataforma:</strong> <?= htmlspecialchars($jogo['plataforma']) ?></p>
        <p><strong>Gênero:</strong> <?= htmlspecialchars($jogo['genero']) ?></p>

        <?php if ($exibirFormulario): ?>
        <form action="salvar_avaliacao.php" method="post">
            <input type="hidden" name="id_jogo" value="<?= $jogo['id'] ?>">

            <label for="estrelas">Nota:</label>
            <div class="stars">
            <?php for ($i = 5; $i >= 1; $i--): ?>
                <input type="radio" name="estrelas" id="estrela<?= $i ?>" value="<?= $i ?>" required>
                <label for="estrela<?= $i ?>">★</label>
            <?php endfor; ?>
            </div>

            <label for="comentario">Comentário:</label>
            <textarea name="comentario" id="comentario" rows="4" required></textarea>

            <button type="submit">Enviar Avaliação</button>
        </form>
        <?php elseif ($jaAvaliou): ?>
        <p style="color:#c71585; font-weight:bold; margin-top:20px;">🌸 Você já avaliou este jogo. Obrigado pela sua opinião! 💬</p>
        <?php endif; ?>

       

        
    </div>

    <?php
            require './banco/conexao.php';

            $sqlAvaliacoes = "SELECT a.nota, a.comentario, u.nome AS usuario_nome
                                FROM avaliacao a
                                JOIN usuarios u ON a.id_usuario = u.id
                                WHERE a.id_jogo = ?
                                ORDER BY a.id DESC";

            $stmtAvaliacao = mysqli_prepare($conn, $sqlAvaliacoes);
            mysqli_stmt_bind_param($stmtAvaliacao, "i", $id);
            mysqli_stmt_execute($stmtAvaliacao);
            $resultAvaliacoes = mysqli_stmt_get_result($stmtAvaliacao);

            echo "<div class='container' style='margin-top: 30px;'>";
            echo "<h2 style='text-align:center; color:#c71585;'>Avaliações dos usuários 💬</h2>";

            if (mysqli_num_rows($resultAvaliacoes) > 0) {
                while ($av = mysqli_fetch_assoc($resultAvaliacoes)) {
                    echo "<div style='border-top:1px dashed #ffc0cb; padding-top:15px; margin-top:15px;'>";
                    echo "<p><strong>" . htmlspecialchars($av['usuario_nome']) . "</strong> avaliou com ";
                    
                    // Estrelas renderizadas
                    for ($i = 1; $i <= 5; $i++) {
                        echo $i <= $av['nota'] ? "⭐" : "☆";
                    }

                    echo "</p>";
                    echo "<p style='color:#c71585;'>\"" . nl2br(htmlspecialchars($av['comentario'])) . "\"</p>";
                    echo "</div>";
                }
            } else {
                echo "<p style='color:#999;'>Nenhuma avaliação ainda. Seja o primeiro a avaliar! 😊</p>";
            }

            echo "</div>";
            mysqli_stmt_close($stmtAvaliacao);
            mysqli_close($conn);
            ?>

   

  <script>
    const totalPetals = 40;
    for (let i = 0; i < totalPetals; i++) {
      const petal = document.createElement('div');
      petal.classList.add('petal');
      petal.style.left = Math.random() * 100 + "vw";
      petal.style.animationDuration = (4 + Math.random() * 5) + "s";
      petal.style.animationDelay = Math.random() * 5 + "s";
      petal.style.width = petal.style.height = (20 + Math.random() * 20) + "px";
      document.body.appendChild(petal);
    }
  </script>

</body>
</html>
