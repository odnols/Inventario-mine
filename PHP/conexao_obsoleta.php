<?php
$servidor = "localhost";
$user = "root";
$password = "admin";
$banco = "minecraft_itens";

$conexao = new mysqli($servidor, $user, $password, $banco);
// $conexao->query("SET NAMES 'utf8'");
// $conexao->query('SET character_set_connection=utf8');
// $conexao->query('SET character_set_client=utf8');
// $conexao->query('SET character_set_results=utf8');

if(!$conexao)
    die("Falha na conexão: ". $conexao->connect_error);

// Corretor ortográfico
mysqli_set_charset($conexao, "utf8");