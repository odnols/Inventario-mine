<?php include_once "conexao_obsoleta.php";
    
$arquivo = "dados_locais.json";
$file = fopen("../JSON/". $arquivo, 'w');

$data = array();

$verificar = "SELECT * from item";
$executa = $conexao->query($verificar);

while($dados = $executa->fetch_assoc()){
    
    $id_item = $dados["id_item"];
    $nome_img = $dados["img"];
    $tipo_item = $dados["abamenu"];
    $nome_item = $dados["nome"];
    $coletavel = $dados["coletavelSurvival"];
    $nome_interno = $dados["nome_interno"];
    $empilhavel = $dados["empilhavel"];
    $versao_add = $dados["versao_adicionada"];
    $renovavel = $dados["renovavel"];
    $aliases = $dados["aliases_nome"];
    $descricao = $dados["descricao"];
    
    if(strlen($aliases) == 0)
        $aliases = null;

    if(strlen($descricao) == 0)
        $descricao = null;
        
    # Remove o underline do nome interno do item
    // $nome_int = str_replace("_", " ", $nome_interno);
    $nome_int = $nome_interno;
    
    // Verifica se o item possui registros de cores no título
    $verificar_item = "SELECT * from cor_item where id_item = $id_item";
    $executa_item = $conexao->query($verificar_item);

    if($executa_item->num_rows > 0){

        $cor_item = array();

        $dados2 = $executa_item->fetch_assoc();

        $id_cor = $dados2["id_cor"];
        $valor_cor = $dados2["tipo_item"];

        array_push($cor_item, array(
            "id_cor" => $id_cor,
            "tipo_item" => $valor_cor
        ));
    }

    if($executa_item->num_rows == 0){
        array_push($data, array(
            "id_item" => $id_item,
            "nome_img" => $nome_img,
            "tipo_item" => $tipo_item,
            "nome_item" => $nome_item,
            "coletavel" => $coletavel,
            "nome_interno" => $nome_int,
            "empilhavel" => $empilhavel,
            "versao_add" => $versao_add,
            "renovavel" => $renovavel,
            "aliases" => $aliases,
            "descricao" => $descricao
        ));
    }else{
        array_push($data, array(
            "id_item" => $id_item,
            "nome_img" => $nome_img,
            "tipo_item" => $tipo_item,
            "nome_item" => $nome_item,
            "coletavel" => $coletavel,
            "nome_interno" => $nome_int,
            "empilhavel" => $empilhavel,
            "versao_add" => $versao_add,
            "renovavel" => $renovavel,
            "aliases" => $aliases,
            "descricao" => $descricao,
            "cor_item" => $cor_item
        ));
    }
}

$json = json_encode($data, JSON_PRETTY_PRINT);
fwrite($file, $json);

fclose($file);

header("Location: ../index.php");