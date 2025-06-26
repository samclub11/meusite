<?php
$server = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'simples_login';

$conn = new mysqli($server, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>