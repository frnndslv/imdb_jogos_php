<?php
    session_start();
    require './banco/conexao.php';

    if (!isset($_SESSION['id_usuario'])) {
        echo "<p style='color: red;'>Você precisa estar logado para avaliar.</p>";
        exit;
    }

    $id_jogo = isset($_POST['id_jogo']) ? intval($_POST['id_jogo']) : 0;
    $id_usuario = $_SESSION['id_usuario'];
    $comentario = isset($_POST['comentario']) ? trim($_POST['comentario']) : '';
    $nota = isset($_POST['estrelas']) ? intval($_POST['estrelas']) : 0;

    if ($id_jogo <= 0 || $nota < 1 || $nota > 5 || empty($comentario)) {
        echo "<p style='color: red;'>Preencha todos os campos corretamente.</p>";
        exit;
    }

    $sql_verifica = "SELECT * FROM avaliacao WHERE id_jogo = ? AND id_usuario = ?";
    $stmt_verifica = mysqli_prepare($conn, $sql_verifica);
    mysqli_stmt_bind_param($stmt_verifica, "ii", $id_jogo, $id_usuario);
    mysqli_stmt_execute($stmt_verifica);
    $result = mysqli_stmt_get_result($stmt_verifica);

    if (mysqli_num_rows($result) > 0) {
        echo "<p style='color: red;'>Você já avaliou este jogo.</p>";
        exit;
    }

    $sql = "INSERT INTO avaliacao (id_jogo, id_usuario, comentario, nota) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iisi", $id_jogo, $id_usuario, $comentario, $nota);

    if (mysqli_stmt_execute($stmt)) {
        echo "<p style='color: green;'>Avaliação salva com sucesso! 🌟</p>";
        echo "<a href='perfil.php'>Voltar à lista</a>";
    } else {
        echo "<p style='color: red;'>Erro ao salvar avaliação.</p>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
?>
