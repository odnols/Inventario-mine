<?php include_once "conexao_obsoleta.php";

$arquivo = "dados_locais.json";
$file = fopen("../files/JSON/" . $arquivo, 'w');

$data = array();

$executa = $conexao->query("SELECT * FROM item");

$i = 0;

while ($dados = $executa->fetch_assoc()) {

    $id_item = intval($dados["id_item"]);

    $nome_item = $dados["nome"];
    $tipo_item = $dados["tipo"];
    $nome_icon = $dados["icon"];
    $nome_interno = $dados["internal"];

    $renovavel = intval($dados["renovavel"]) ? true : false;
    $coletavel = intval($dados["coletavel"]) ? true : false;
    $empilhavel = intval($dados["empilhavel"]);
    $fabricavel = intval($dados["fabricavel"]) ? true : false;

    $item_oculto = false;
    $item_legado = false;

    $versao_add = $dados["versao"];

    // Tabelas secundárias
    $item_guia = array();
    $item_titulo = array();
    $item_receita = array();
    $item_descricao = array();
    $item_durabilidade = array();

    // Verifica se o item possui registros de cores no título
    $executa_item = $conexao->query("SELECT * FROM item_titulo WHERE id_item = $id_item");

    if ($executa_item->num_rows > 0) {

        $dados = $executa_item->fetch_assoc();
        $valor_cor = $dados["tipo_item"];

        array_push($item_titulo, array(
            "type" => intval($valor_cor)
        ));
    }

    // Verifica se o item possui registros de durabilidade
    $executa_item = $conexao->query("SELECT * FROM item_durabilidade WHERE id_item = $id_item");

    if ($executa_item->num_rows > 0) {

        $dados = $executa_item->fetch_assoc();
        $durabilidade = $dados["durabilidade"];

        array_push($item_durabilidade, array(
            "value" => intval($durabilidade)
        ));
    }

    // Verifica se o item possui registros de fabricação
    $executa_item = $conexao->query("SELECT * FROM item_receita WHERE id_item = $id_item");

    if ($executa_item->num_rows > 0) {

        $dados = $executa_item->fetch_assoc();

        $receita = $dados["crafting"];
        $qtd_produtos = $dados["qtd_produtos"];
        $tipo_craft = $dados["tipo_craft"];

        array_push($item_receita, array(
            "recipe" => $receita,
            "products" => intval($qtd_produtos),
            "craft_type" => intval($tipo_craft)
        ));
    }

    // Verifica se o item possui registros de descrição
    $executa_item = $conexao->query("SELECT * FROM item_descricao WHERE id_item = $id_item");

    if ($executa_item->num_rows > 0) {

        $dados = $executa_item->fetch_assoc();
        $descricao = $dados["descricao"];

        if (strlen($descricao) > 0)
            array_push($item_descricao, array(
                "value" => $descricao
            ));
    }

    // Verifica se o item é oculto no inventário
    $executa_item = $conexao->query("SELECT * FROM item_oculto WHERE id_item = $id_item");
    if ($executa_item->num_rows > 0) $item_oculto = true;

    // Verifica se o item possui registros de sprites legados
    $executa_item = $conexao->query("SELECT * FROM item_legado WHERE id_item = $id_item");
    if ($executa_item->num_rows > 0) $item_legado = true;

    // Verifica se o item possui registros de várias guias
    $executa_item = $conexao->query("SELECT * FROM item_guia WHERE id_item = $id_item");

    if ($executa_item->num_rows > 0) {

        $dados = $executa_item->fetch_assoc();

        $id_guia = $dados["id_guia"];
        $historico_guias = $dados["historico_guias"];

        array_push($item_guia, array(
            "id" => intval($id_guia),
            "tab_history" => $historico_guias
        ));
    }

    array_push($data, array(
        "id" => $id_item,
        "icon" => $nome_icon,
        "type" => $tipo_item,
        "name" => $nome_item,
        "internal_name" => $nome_interno,
        "version" => $versao_add,
        "collectable" => $coletavel,
        "renewable" => $renovavel,
        "stackable" => $empilhavel,
        "craftable" => $fabricavel,
        "hide" => $item_oculto,
        "legacy" => $item_legado
    ));

    // Dados provenientes de outras tabelas
    if (sizeof($item_titulo) > 0)
        $data[$i]["title"] = $item_titulo;

    if (sizeof($item_durabilidade) > 0)
        $data[$i]["durability"] = $item_durabilidade;

    if (sizeof($item_receita) > 0)
        $data[$i]["recipe"] = $item_receita;

    if (sizeof($item_descricao) > 0)
        $data[$i]["description"] = $item_descricao;

    if (sizeof($item_guia) > 0)
        $data[$i]["tab"] = $item_guia;

    $i++;
}

$json = json_encode($data, JSON_PRETTY_PRINT);
fwrite($file, $json);

fclose($file);

Header("Location: ../pages/index.php");
