<?php include_once "conexao_obsoleta.php";

$arquivo = "dados_locais.json";
$file = fopen("../JSON/". $arquivo, 'w');

$data = array();

$verificar = "SELECT * from item";
$executa = $conexao->query($verificar);

while($dados = $executa->fetch_assoc()){
    
    $id_item = $dados["id_item"];
    $nome_icon = $dados["nome_icon"];
    $tipo_item = $dados["abamenu"];
    $nome_item = $dados["nome"];
    $coletavel = $dados["coletavelSurvival"];
    $nome_interno = $dados["nome_interno"];
    $empilhavel = $dados["empilhavel"];
    $versao_add = $dados["versao_adicionada"];
    $renovavel = $dados["renovavel"];
    $aliases = $dados["aliases_nome"];
    $descricao = $dados["descricao"];
    $oculto_invt = $dados["oculto_invt"];
    $programmer_art = $dados["programmer_art"];

    if(strlen($aliases) == 0)
        $aliases = null;

    if(strlen($descricao) == 0)
        $descricao = null;

    $versao_add = floatval($versao_add); 

    $id_item = intval($id_item);
    $empilhavel = intval($empilhavel);
    $coletavel = intval($coletavel);
    $renovavel = intval($renovavel);
    $oculto_invt = intval($oculto_invt);
    $programmer_art = intval($programmer_art);

    # Remove o underline do nome interno do item
    // $nome_int = str_replace("_", " ", $nome_interno);
    $nome_int = $nome_interno;
    
    // Verifica se o item possui registros de cores no título
    $verificar_item = "SELECT * from cor_item where id_item = $id_item";
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

    $verificar_durabilidade = "SELECT * from durabilidade_item where id_item = $id_item";
    $executa_item_2 = $conexao->query($verificar_durabilidade);
    $durabilidade = 0;

    if($executa_item_2->num_rows > 0){
    
        $durabilidade_item = array();

        $dados2 = $executa_item_2->fetch_assoc();

        $id_durabilidade = $dados2["id_durabilidade"];
        $durabilidade = $dados2["durabilidade"];

        array_push($durabilidade_item, array(
            "id_durabilidade" => $id_durabilidade,
            "durabilidade" => $durabilidade
        ));
    }

    if($executa_item_1 -> num_rows == 0 && $executa_item_2 -> num_rows == 0){
        array_push($data, array(
            "id_item" => $id_item,
            "nome_icon" => $nome_icon,
            "tipo_item" => $tipo_item,
            "nome_item" => $nome_item,
            "coletavel" => $coletavel,
            "nome_interno" => $nome_int,
            "empilhavel" => $empilhavel,
            "versao_add" => $versao_add,
            "renovavel" => $renovavel,
            "aliases" => $aliases,
            "descricao" => $descricao,
            "oculto_invt" => $oculto_invt,
            "programmer_art" => $programmer_art
        ));
    }else if($executa_item_2 -> num_rows == 0){
        array_push($data, array(
            "id_item" => $id_item,
            "nome_icon" => $nome_icon,
            "tipo_item" => $tipo_item,
            "nome_item" => $nome_item,
            "coletavel" => $coletavel,
            "nome_interno" => $nome_int,
            "empilhavel" => $empilhavel,
            "versao_add" => $versao_add,
            "renovavel" => $renovavel,
            "aliases" => $aliases,
            "descricao" => $descricao,
            "oculto_invt" => $oculto_invt,
            "programmer_art" => $programmer_art,
            "cor_item" => $cor_item
        ));
    }else{
        array_push($data, array(
            "id_item" => $id_item,
            "nome_icon" => $nome_icon,
            "tipo_item" => $tipo_item,
            "nome_item" => $nome_item,
            "coletavel" => $coletavel,
            "nome_interno" => $nome_int,
            "empilhavel" => $empilhavel,
            "durabilidade" => $durabilidade_item,
            "versao_add" => $versao_add,
            "renovavel" => $renovavel,
            "aliases" => $aliases,
            "descricao" => $descricao,
            "oculto_invt" => $oculto_invt,
            "programmer_art" => $programmer_art,
            "cor_item" => $cor_item
        ));
    }
}

$json = json_encode($data, JSON_PRETTY_PRINT);
fwrite($file, $json);

fclose($file);

header("Location: ../index.php");