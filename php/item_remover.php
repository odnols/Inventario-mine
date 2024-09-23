<?php include_once "conexao_obsoleta.php";

$id_item = $_GET["id"];

$executa = $conexao->query("DELETE from item where id_item = $id_item");

Header("Location: ../pages/index.php");
