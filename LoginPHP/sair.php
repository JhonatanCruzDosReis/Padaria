<?php
session_start(); 
ob_start(); 

$conn = new mysqli('localhost', 'root', '', 'padaria');
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

if (isset($_SESSION['login_time'])) {

    $tempo_min = round((time() - $_SESSION['login_time']) / 60); // Em minutos

    if (isset($_SESSION['user_id'])) {

        $stmt = $conn->prepare("UPDATE usuarios 
                                SET tempo_ficou = IFNULL(tempo_ficou, 0) + ? 
                                WHERE id = ?");
        $stmt->bind_param("ii", $tempo_min, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
    }
}


unset($_SESSION['id'], $_SESSION['nome'], $_SESSION['usuario'], $_SESSION['codigo_autenticacao']);

if((!isset($_SESSION['id'])) 
&& (!isset($_SESSION['usuario'])) 
&& (!isset($_SESSION['codigo_autenticacao']))) {

    $_SESSION['msg'] = "<p style='color: green;'>Deslogado com sucesso!</p>";

    header("Location: login.php");
    exit();
}

?>
