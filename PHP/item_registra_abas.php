<?php include_once "conexao_obsoleta.php";

$id_item = $_POST["id_item"];

$params = '?' . http_build_query(array('id' => $id_item, 'rlod' => 1));
Header('Location: ../pages/item_detalhes.php' . $params);
