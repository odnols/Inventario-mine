<?php include_once "conexao_obsoleta.php";

$executa = $conexao->query("DELETE FROM item");

$executa = $conexao->query("DELETE FROM item_titulo");

$executa = $conexao->query("DELETE FROM item_durabilidade");

$executa = $conexao->query("DELETE FROM item_receita");

$executa = $conexao->query("DELETE FROM item_descricao");

$executa = $conexao->query("DELETE FROM item_oculto");

$executa = $conexao->query("DELETE FROM item_legado");

$executa = $conexao->query("DELETE FROM item_guia");

header("Location: ../pages/index.php");
