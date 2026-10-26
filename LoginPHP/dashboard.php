<?php
session_start();
ob_start();

date_default_timezone_set('America/Sao_Paulo');

// --- VERIFICAÇÃO DE LOGIN ---
if(
    (!isset($_SESSION['id'])) || 
    (!isset($_SESSION['usuario'])) ||
    (!isset($_SESSION['codigo_autenticacao']))
){
    $_SESSION['msg'] = "<p class='erro-login'>Erro: Necessário realizar o login para acessar a página!</p>";
    header("Location: login.php");
    exit();
}

// --- SE O LOGIN ESTÁ OK, REDIRECIONA DIRETO PARA home.php ---
header("Location: ../home.php");
exit();

?>
