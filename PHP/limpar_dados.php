<?php include_once "conexao_obsoleta.php";

$verificar = "DELETE FROM item";
$executa = $conexao->query($verificar);

$verificar = "DELETE FROM item_titulo";
$executa = $conexao->query($verificar);

$verificar = "DELETE FROM item_durabilidade";
$executa = $conexao->query($verificar);

$verificar = "DELETE FROM item_receita";
$executa = $conexao->query($verificar);

$verificar = "DELETE FROM item_descricao";
$executa = $conexao->query($verificar);

$verificar = "DELETE FROM item_oculto";
$executa = $conexao->query($verificar);

$verificar = "DELETE FROM item_legado";
$executa = $conexao->query($verificar);

$verificar = "DELETE FROM item_guia";
$executa = $conexao->query($verificar);

header("Location: ../pages/index.php");
