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

    # Remove o underline do nome interno do item
    // $nome_int = str_replace("_", " ", $nome_interno);
    $nome_int = $nome_interno;
    
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
        "aliases" => $aliases
    ));
}

$json = json_encode($data);
fwrite($file, $json);

fclose($file);

header("Location: ../index.php");