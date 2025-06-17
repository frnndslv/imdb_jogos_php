<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conexão</title>
</head>
<body>

<?php
    $host = "localhost";         
    $usuario = "root";          
    $senha = "";                 
    $banco = "imdbjogos_bd";    

    $conn = mysqli_connect($host, $usuario, $senha, $banco);

    if (!$conn) {
        exit("Falha na conexão: " . mysqli_connect_error());
    }
?>
    
</body>
</html>