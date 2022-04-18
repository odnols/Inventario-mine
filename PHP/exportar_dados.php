<?php include_once "conexao_obsoleta.php";

$arquivo = "dados_locais.json";
$file = fopen("../Files/JSON/". $arquivo, 'w');

$data = array();

$verificar = "SELECT * FROM item";
$executa = $conexao->query($verificar);

while($dados = $executa->fetch_assoc()){
    
    $id_item = intval($dados["id_item"]);
    $nome_icon = $dados["nome_icon"];
    $tipo_item = $dados["abamenu"];
    $nome_item = $dados["nome"];
    $coletavel = intval($dados["coletavelSurvival"]);
    $nome_interno = $dados["nome_interno"];
    $empilhavel = intval($dados["empilhavel"]);
    $versao_add = floatval($dados["versao_adicionada"]);
    $renovavel = intval($dados["renovavel"]);
    $aliases = $dados["aliases_nome"];
    $descricao = $dados["descricao"];
    $oculto_invt = intval($dados["oculto_invt"]);
    $programmer_art = intval($dados["programmer_art"]);
    $fabricavel = intval($dados["fabricavel"]);
    
    if(strlen($aliases) == 0)
        $aliases = null;

    if(strlen($descricao) == 0)
        $descricao = null;
    
    // Verifica se o item possui registros de cores no título
    $verificar_item = "SELECT * FROM cor_item WHERE id_item = $id_item";
    $executa_item_1 = $conexao->query($verificar_item);

    if($executa_item_1->num_rows > 0){

        $cor_item = array();

        $dados2 = $executa_item_1->fetch_assoc();

        $id_cor = $dados2["id_cor"];
        $valor_cor = $dados2["tipo_item"];

        array_push($cor_item, array(
            "id_cor" => $id_cor,
            "tipo_item" => $valor_cor
        ));
    }

    // Verifica se o item possui registros de durabilidade
    $verificar_durabilidade = "SELECT * FROM durabilidade_item WHERE id_item = $id_item";
    $executa_item_2 = $conexao->query($verificar_durabilidade);
    $durabilidade = 0;

    if($executa_item_2->num_rows > 0){
    
        $durabilidade_item = array();

        $dados2 = $executa_item_2->fetch_assoc();

        $id_durabilidade = intval($dados2["id_durabilidade"]);
        $durabilidade = intval($dados2["durabilidade"]);

        array_push($durabilidade_item, array(
            "id_durabilidade" => $id_durabilidade,
            "durabilidade" => $durabilidade
        ));
    }

    // Verifica se o item possui registros de fabricação
    $verificar_item = "SELECT * FROM crafting_item WHERE id_item = $id_item";
    $executa_item_3 = $conexao->query($verificar_item);

    if($executa_item_3->num_rows > 0){

        $fabricacao = array();

        $dados3 = $executa_item_3->fetch_assoc();

        $id_craft = $dados3["id_craft"];
        $crafting = $dados3["craft"];
        $qtd_produtos = $dados3["qtd_produtos"];

        array_push($fabricacao, array(
            "id_craft" => $id_craft,
            "receita" => $crafting,
            "produtos" => $qtd_produtos
        ));
    }

    if($executa_item_1 -> num_rows == 0 && $executa_item_2 -> num_rows == 0 && $executa_item_3 -> num_rows == 0){
        array_push($data, array(
            "id_item" => $id_item,
            "nome_icon" => $nome_icon,
            "tipo_item" => $tipo_item,
            "nome_item" => $nome_item,
            "coletavel" => $coletavel,
            "nome_interno" => $nome_interno,
            "empilhavel" => $empilhavel,
            "versao_add" => $versao_add,
            "renovavel" => $renovavel,
            "aliases" => $aliases,
            "descricao" => $descricao,
            "oculto_invt" => $oculto_invt,
            "fabricavel" => $fabricavel,
            "programmer_art" => $programmer_art
        ));
    }else if($executa_item_2 -> num_rows == 0 && $executa_item_3 -> num_rows == 0){
        array_push($data, array(
            "id_item" => $id_item,
            "nome_icon" => $nome_icon,
            "tipo_item" => $tipo_item,
            "nome_item" => $nome_item,
            "coletavel" => $coletavel,
            "nome_interno" => $nome_interno,
            "empilhavel" => $empilhavel,
            "versao_add" => $versao_add,
            "renovavel" => $renovavel,
            "aliases" => $aliases,
            "descricao" => $descricao,
            "oculto_invt" => $oculto_invt,
            "fabricavel" => $fabricavel,
            "programmer_art" => $programmer_art,
            "cor_item" => $cor_item
        ));
    }else if($executa_item_1 -> num_rows == 0 && $executa_item_3 -> num_rows == 0){
        array_push($data, array(
            "id_item" => $id_item,
            "nome_icon" => $nome_icon,
            "tipo_item" => $tipo_item,
            "nome_item" => $nome_item,
            "coletavel" => $coletavel,
            "nome_interno" => $nome_interno,
            "empilhavel" => $empilhavel,
            "durabilidade" => $durabilidade_item,
            "versao_add" => $versao_add,
            "renovavel" => $renovavel,
            "aliases" => $aliases,
            "descricao" => $descricao,
            "oculto_invt" => $oculto_invt,
            "fabricavel" => $fabricavel,
            "programmer_art" => $programmer_art
        ));
    }else if($executa_item_1 -> num_rows == 0 && $executa_item_2 -> num_rows == 0){
        array_push($data, array(
            "id_item" => $id_item,
            "nome_icon" => $nome_icon,
            "tipo_item" => $tipo_item,
            "nome_item" => $nome_item,
            "coletavel" => $coletavel,
            "nome_interno" => $nome_interno,
            "empilhavel" => $empilhavel,
            "versao_add" => $versao_add,
            "renovavel" => $renovavel,
            "aliases" => $aliases,
            "descricao" => $descricao,
            "oculto_invt" => $oculto_invt,
            "fabricavel" => $fabricavel,
            "programmer_art" => $programmer_art,
            "fabricacao" => $fabricacao
        ));
    }else{
        array_push($data, array(
            "id_item" => $id_item,
            "nome_icon" => $nome_icon,
            "tipo_item" => $tipo_item,
            "nome_item" => $nome_item,
            "coletavel" => $coletavel,
            "nome_interno" => $nome_interno,
            "empilhavel" => $empilhavel,
            "durabilidade" => $durabilidade_item,
            "versao_add" => $versao_add,
            "renovavel" => $renovavel,
            "aliases" => $aliases,
            "descricao" => $descricao,
            "oculto_invt" => $oculto_invt,
            "fabricavel" => $fabricavel,
            "programmer_art" => $programmer_art,
            "cor_item" => $cor_item,
            "fabricacao" => $fabricacao
        ));
    }
}

$json = json_encode($data, JSON_PRETTY_PRINT);
fwrite($file, $json);

fclose($file);

Header("Location: ../pages/index.php");