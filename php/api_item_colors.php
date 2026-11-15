<?php
header('Content-Type: application/json; charset=utf-8');

include_once "conexao_obsoleta.php";

// Busca todas as cores de items do banco de dados
$executa = $conexao->query("SELECT id_item, tipo_item FROM item_titulo");

$cores = [];

if ($executa) {
    while ($linha = $executa->fetch_assoc()) {
        $cores[(int)$linha["id_item"]] = (int)$linha["tipo_item"];
    }
}

echo json_encode($cores);
?>
