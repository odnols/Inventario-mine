<?php include_once "conexao_obsoleta.php";

$executa = $conexao->query("SELECT * FROM item");
$ignorar = array("de", "com", "da", "do");

// Capitaliza a primeira letra de cada palavra do nome dos itens
while ($dados = $executa->fetch_assoc()) {

    $id_item = $dados["id_item"];

    $nome = $dados["nome"];

    $novo_nome = explode(" ", $nome);
    $computado = array();

    foreach ($novo_nome as $palavra) {

        if (!in_array($palavra, $ignorar))
            $palavra = ucfirst($palavra);

        array_push($computado, $palavra);
    }

    $nome_computado = implode(" ", $computado);

    $conexao->query("UPDATE item set nome = '$nome_computado' WHERE id_item = $id_item");
}
