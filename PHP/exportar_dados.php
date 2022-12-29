<?php include_once "conexao_obsoleta.php";

$arquivo = "dados_locais.json";
$file = fopen("../Files/JSON/" . $arquivo, 'w');

$data = array();

$verificar = "SELECT * FROM item";
$executa = $conexao->query($verificar);

$i = 0;

while ($dados = $executa->fetch_assoc()) {

    $id_item = intval($dados["id_item"]);
    $nome_icon = $dados["nome_icon"];
    $tipo_item = $dados["abamenu"];
    $nome_item = $dados["nome"];
    $coletavel = intval($dados["coletavelSurvival"]);
    $nome_interno = $dados["nome_interno"];
    $empilhavel = intval($dados["empilhavel"]);
    $versao_add = floatval($dados["versao_adicionada"]);
    $renovavel = intval($dados["renovavel"]);
    $fabricavel = intval($dados["fabricavel"]);

    // Tabelas secundárias
    $item_oculto = array();
    $item_legado = array();
    $item_titulo = array();
    $item_receita = array();
    $item_descricao = array();
    $item_durabilidade = array();
    
    // Verifica se o item possui registros de cores no título
    $verificar_item = "SELECT * FROM item_titulo WHERE id_item = $id_item";
    $executa_item = $conexao->query($verificar_item);

    if ($executa_item->num_rows > 0) {

        $dados = $executa_item->fetch_assoc();

        $id_titulo = $dados["id_cor"];
        $valor_cor = $dados["tipo_item"];

        array_push($item_titulo, array(
            "id_titulo" => intval($id_titulo),
            "tipo_item" => intval($valor_cor)
        ));
    }

    // Verifica se o item possui registros de durabilidade
    $verificar_item = "SELECT * FROM item_durabilidade WHERE id_item = $id_item";
    $executa_item = $conexao->query($verificar_item);

    if ($executa_item->num_rows > 0) {

        $dados = $executa_item->fetch_assoc();

        $id_durabilidade = $dados["id_durabilidade"];
        $durabilidade = $dados["durabilidade"];

        array_push($item_durabilidade, array(
            "id_durabilidade" => intval($id_durabilidade),
            "durabilidade" => intval($durabilidade)
        ));
    }

    // Verifica se o item possui registros de fabricação
    $verificar_item = "SELECT * FROM item_receita WHERE id_item = $id_item";
    $executa_item = $conexao->query($verificar_item);

    if ($executa_item->num_rows > 0) {

        $dados = $executa_item->fetch_assoc();

        $id_receita = $dados["id_craft"];
        $receita = $dados["craft"];
        $qtd_produtos = $dados["qtd_produtos"];
        $tipo_craft = $dados["tipo_craft"];

        array_push($item_receita, array(
            "id_receita" => intval($id_receita),
            "receita" => $receita,
            "produtos" => intval($qtd_produtos),
            "tipo_craft" => intval($tipo_craft)
        ));
    }

    // Verifica se o item possui registros de descrição
    $verificar_item = "SELECT * FROM item_descricao WHERE id_item = $id_item";
    $executa_item = $conexao->query($verificar_item);

    if ($executa_item->num_rows > 0) {

        $dados = $executa_item->fetch_assoc();

        $id_descricao = $dados["id_descricao"];
        $descricao = $dados["descricao"];

        array_push($item_descricao, array(
            "id_descricao" => intval($id_descricao),
            "descricao" => $descricao
        ));
    }

    // Verifica se o item é oculto no inventário
    $verificar_item = "SELECT * FROM item_oculto WHERE id_item = $id_item";
    $executa_item = $conexao->query($verificar_item);

    if ($executa_item->num_rows > 0) {

        $dados = $executa_item->fetch_assoc();

        $id_oculto = $dados["id_oculto"];

        array_push($item_oculto, array(
            "id_oculto" => intval($id_oculto),
            "status_item" => 1
        ));
    }

    // Verifica se o item possui registros de sprites legados
    $verificar_item = "SELECT * FROM item_legado WHERE id_item = $id_item";
    $executa_item = $conexao->query($verificar_item);

    if ($executa_item->num_rows > 0) {

        $dados = $executa_item->fetch_assoc();

        $id_legado = $dados["id_legado"];

        array_push($item_legado, array(
            "id_legado" => intval($id_legado),
            "status_item" => 1
        ));
    }

    array_push($data, array(
        "id_item" => $id_item,
        "nome_icon" => $nome_icon,
        "tipo_item" => $tipo_item,
        "nome_item" => $nome_item,
        "coletavel" => $coletavel,
        "renovavel" => $renovavel,
        "nome_interno" => $nome_interno,
        "empilhavel" => $empilhavel,
        "versao_add" => $versao_add,
        "fabricavel" => $fabricavel,
    ));

    // Dados provenientes de outras tabelas
    if (sizeof($item_titulo) > 0)
        $data[$i]["item_titulo"] = $item_titulo;

    if (sizeof($item_durabilidade) > 0)
        $data[$i]["item_durabilidade"] = $item_durabilidade;

    if (sizeof($item_receita) > 0)
        $data[$i]["item_receita"] = $item_receita;

    if (sizeof($item_descricao) > 0)
        $data[$i]["item_descricao"] = $item_descricao;

    if (sizeof($item_oculto) > 0)
        $data[$i]["item_oculto"] = $item_oculto;

    if (sizeof($item_legado) > 0)
        $data[$i]["item_legado"] = $item_legado;

    $i++;
}

$json = json_encode($data, JSON_PRETTY_PRINT);
fwrite($file, $json);

fclose($file);

Header("Location: ../pages/index.php");
