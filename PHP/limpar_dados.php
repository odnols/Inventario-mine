<?php include_once "conexao_obsoleta.php";

$verificar = "DELETE FROM item";
$executa = $conexao->query($verificar);

header("Location: ../pages/index.php");
