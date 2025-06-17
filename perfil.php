<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  />
  <title>Meu Perfil</title>
  <style>
    body {
      background: linear-gradient(to right, #ffe6f0, #fff0f5);
      font-family: 'Comic Sans MS', cursive, sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      padding: 40px 20px;
      height: 100vh;
      margin: 0;
      overflow: hidden;
      position: relative;
    }

    h1 {
      color: #ff69b4;
      font-size: 2.5rem;
      text-shadow: 2px 2px 5px #fff;
      margin-bottom: 30px;
    }

    table {
      background-color: #fff0f5;
      border-collapse: collapse;
      box-shadow: 0 0 10px rgba(255, 192, 203, 0.6);
      border-radius: 12px;
      overflow: hidden;
      z-index: 10;
    }

    th,
    td {
      padding: 12px 20px;
      border: 1px solid #ffc0cb;
      text-align: center;
    }

    th {
      background-color: #ffb6c1;
      color: white;
    }

    td {
      background-color: #fff;
      color: #c71585;
    }

    button {
      background: none;
      border: none;
      cursor: pointer;
      font-size: 1.2rem;
    }

    i.fas.fa-trash {
      color: #ff4d6d;
      transition: transform 0.2s;
    }

    i.fas.fa-trash:hover {
      transform: scale(1.2);
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

    <h1>🌸 Meus Jogos avaliados 🌸</h1>
    <a href="jogos.php">valiar novos jogos</a>

    <?php
        session_start();
        require './banco/conexao.php';

        $sql = "SELECT id, nome, plataforma, genero FROM jogos";
        $resultado = mysqli_query($conn, $sql);

        if (mysqli_num_rows($resultado) > 0) {
            echo "<table>";
            echo "<tr>";
            echo "<th>ID</th><th>Nome</th><th>Plataforma</th><th>Gênero</th>";

            if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
                echo "<th>Ações</th>";
            }

            echo "</tr>";

            while ($linha = mysqli_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td>" . $linha['id'] . "</td>";
                if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
                    echo "<td><a href='jogos.php?id=" . $linha['id'] . "' style='color: #ff1493; text-decoration: none; font-weight: bold;'>" . $linha['nome'] . "</a></td>";
                } else {
                    echo "<td><a href='avaliar_jogo.php?id=" . $linha['id'] . "' style='color: #c71585; text-decoration: none; font-weight: bold;'>" . $linha['nome'] . "</a></td>";
                }
                echo "<td>" . $linha['plataforma'] . "</td>";
                echo "<td>" . $linha['genero'] . "</td>";

                if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
                    echo "<td>
                            <form action='deletar.php' method='POST' onsubmit=\"return confirm('Tem certeza que deseja deletar este jogo?');\">
                                <input type='hidden' name='id' value='" . $linha['id'] . "'>
                                <button type='submit'>
                                    <i class='fas fa-trash'></i>
                                </button>
                            </form>
                        </td>";
                }

                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p style='color: #c71585; font-size: 1.2rem;'>Nenhum jogo foi encontrado.</p>";
        }

        mysqli_close($conn);

        
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
            echo "<a href='jogos.php'>➕ Criar novo jogo</a>";
        }
    ?>

  <!-- Pétalas fofinhas -->
  <script>
    const totalPetals = 30;

    for (let i = 0; i < totalPetals; i++) {
      const petal = document.createElement('div');
      petal.classList.add('petal');
      petal.style.left = Math.random() * 100 + "vw";
      petal.style.animationDuration = (4 + Math.random() * 6) + "s";
      petal.style.animationDelay = Math.random() * 5 + "s";

      // Tamanho aleatório entre 20px e 40px
      const size = 20 + Math.random() * 20;
      petal.style.width = size + "px";
      petal.style.height = size + "px";

      document.body.appendChild(petal);
    }
  </script>

</body>
</html>
