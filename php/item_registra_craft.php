<?php include_once "conexao_obsoleta.php";

$id_item = $_POST["id_item"];
$array_craft = $_POST["array_craft"];
$quantidade_enviada = trim($_POST["qtd_produto"] ?? "");
$qtd_produtos = $quantidade_enviada === "" ? 1 : filter_var($quantidade_enviada, FILTER_VALIDATE_INT);
$tipo_craft = filter_var($_POST["tipo_craft"] ?? 0, FILTER_VALIDATE_INT);

if ($qtd_produtos === false || $qtd_produtos < 1) {
    http_response_code(400);
    exit("Quantidade de produtos inválida.");
}

if ($tipo_craft === false || !in_array($tipo_craft, [0, 1, 2, 3], true)) {
    http_response_code(400);
    exit("Tipo de bancada inválido.");
}

// Verificando dados registrados
$resultado = $conexao->query("SELECT * FROM item_receita WHERE id_item = $id_item");

if ($resultado->num_rows > 0) // Atualizando registros existentes
    $conexao->query("UPDATE item_receita SET crafting = '$array_craft', qtd_produtos = $qtd_produtos, tipo_craft = $tipo_craft WHERE id_item = $id_item");
else // Inserindo um novo registro
    $conexao->query("INSERT INTO item_receita (id_item, crafting, qtd_produtos, tipo_craft) VALUES ($id_item, '$array_craft', $qtd_produtos, $tipo_craft)");

$params = '?' . http_build_query(array('id' => $id_item, 'rlod' => 1));
header('Location: ../pages/item_detalhes.php' . $params);
