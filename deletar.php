<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <?php
        require './banco/conexao.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = intval($_POST['id']);

            $sql = "DELETE FROM jogos WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'i', $id);

            if (mysqli_stmt_execute($stmt)) {
                echo "Jogo deletado com sucesso.";
            } else {
                echo "Erro ao deletar o jogo.";
            }

            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            header("Location: perfil.php");
            exit;
        }
    ?>
    
</body>
</html>