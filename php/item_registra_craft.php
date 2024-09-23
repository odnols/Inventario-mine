<?php include_once "conexao_obsoleta.php";

$id_item = $_POST["id_item"];
$array_craft = $_POST["array_craft"];
$qtd_produtos = $_POST["qtd_produto"];
$tipo_craft = 0;

// Verificando dados registrados
$resultado = $conexao->query("SELECT * FROM item_receita WHERE id_item = $id_item");

if ($resultado->num_rows > 0) // Atualizando registros existentes
    $conexao->query("UPDATE item_receita SET crafting = '$array_craft', qtd_produtos = $qtd_produtos, tipo_craft = $tipo_craft WHERE id_item = $id_item");
else // Inserindo um novo registro
    $conexao->query("INSERT INTO item_receita (id_item, crafting, qtd_produtos, tipo_craft) VALUES ($id_item, '$array_craft', $qtd_produtos, 0)");

$params = '?' . http_build_query(array('id' => $id_item, 'rlod' => 1));
header('Location: ../pages/item_detalhes.php' . $params);
