<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "Padaria";
$port = 3306;

$conn = new mysqli($host, $user, $pass, $dbname, $port);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
