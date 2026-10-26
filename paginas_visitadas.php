<?php
session_start();
include "conexao.php"; // OU o nome correto do seu arquivo de conexão

if (!isset($_SESSION['id_usuario'])) {
    // Se o usuário não estiver logado não registra
    exit;
}

$usuario_id = $_SESSION['id_usuario'];
$pagina = $_POST['page'] ?? '';

$sql = $conn->prepare("
    INSERT INTO paginas_visitadas (usuario_id, pagina)
    VALUES (?, ?)
");
$sql->bind_param("is", $usuario_id, $pagina);
$sql->execute();
$sql->close();
