<?php include_once "conexao_obsoleta.php";

$id_item = $_GET["id"];

$removedor = "DELETE from item where id_item = $id_item";
$executa = $conexao->query($removedor);

Header("Location: ../pages/index.php");