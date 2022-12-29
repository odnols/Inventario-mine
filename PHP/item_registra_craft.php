<?php include_once "conexao_obsoleta.php";

$id_item = $_POST["id_item"];
$array_craft = $_POST["array_craft"];
$qtd_produtos = $_POST["qtd_produto"];

// Verificando dados registrados
$executa = "SELECT * FROM item_receita WHERE id_item = $id_item";
$resultado = $conexao->query($executa);

if ($resultado->num_rows > 0) // Atualizando registros existentes
    $processa = "UPDATE item_receita SET craft = '$array_craft', qtd_produtos = $qtd_produtos WHERE id_item = $id_item";
else // Inserindo um novo registro
    $processa = "INSERT INTO item_receita (id_craft, id_item, craft, qtd_produtos) VALUES (null, $id_item, '$array_craft', $qtd_produtos)";

$executa = $conexao->query($processa);

$params = '?' . http_build_query(array('id' => $id_item, 'rlod' => 1));
header('Location: ../pages/item_detalhes.php' . $params);
