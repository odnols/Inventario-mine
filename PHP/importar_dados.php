<?php include_once "conexao_obsoleta.php";

$IDs_registrados = [];

// Este arquivo está disponível por padrão em sua pasta JSON
$arquivo = file_get_contents('../Files/JSON/dados_locais.json');
$data = json_decode($arquivo);

$verificar = "SELECT * FROM item";
$executa = $conexao->query($verificar);

// Salvando todos os IDs do banco num array
while($dados = $executa->fetch_assoc()){
    array_push($IDs_registrados, $dados["id_item"]);
}

// Registrando no banco os itens que só existem no JSON
foreach($data as $key => $value){
    
    $id_item = $value->id_item;
    $nome_icon = $value->nome_icon;
    $abamenu = $value->tipo_item;
    $nome = $value->nome_item;
    $coletavelsurvival = $value->coletavel;
    $nome_interno = $value->nome_interno;
    $empilhavel = $value->empilhavel;
    $versao = $value->versao_add;
    $renovavel = $value->renovavel;
    $aliases = $value->aliases;
    $descricao = $value->descricao;
    $oculto_invt = $value->oculto_invt;
    $programmer_art = $value->programmer_art;
    $fabricavel = $value->fabricavel;
    
    if($renovavel == null)
        $renovavel = 0;

    if($oculto_invt == null)
        $oculto_invt = 0;
    
    if($programmer_art == null)
        $programmer_art = 0;

    if(!in_array($value->id_item, $IDs_registrados)){
        # Inserindo o item no banco de dados
        $insere = "INSERT into item (id_item, nome, abamenu, empilhavel, coletavelSurvival, nome_icon, renovavel, oculto_invt, programmer_art, versao_adicionada, nome_interno, aliases_nome, descricao, fabricavel) values ($id_item, '$nome', '$abamenu', $empilhavel, $coletavelsurvival, '$nome_icon', $renovavel, $oculto_invt, $programmer_art, $versao, '$nome_interno', '$aliases', '$descricao', $fabricavel);";
        $executa = $conexao->query($insere);
    }

    if(array_key_exists("cor_item", $value)){ // Verifica se existe os dados de cor do item
        $cor_item = $value->cor_item;
        
        $id_cor = $cor_item[0]->id_cor;
        $tipo_item = $cor_item[0]->tipo_item;

        $insere = "INSERT into cor_item (id_cor, id_item, tipo_item) values ($id_cor, $id_item, $tipo_item)";
        $executa = $conexao->query($insere);
    }

    if(array_key_exists("durabilidade", $value)){
        $durabili = $value->durabilidade;

        $id_durabilidade = $durabili[0]->id_durabilidade;
        $durabilidade = $durabili[0]->durabilidade;

        $insere = "INSERT into durabilidade_item (id_durabilidade, id_item, durabilidade) values ($id_durabilidade, $id_item, $durabilidade)";
        $executa = $conexao->query($insere);
    }

    if(array_key_exists("fabricacao_item", $value)){
        $crafting = $value->fabricacao_item;

        $id_craft = $crafting[0]->id_crafting;
        $craft = $crafting[0]->craft;

        $insere = "INSERT into crafting_item (id_craft, crafting) values ($id_craft, $craft)";
        $executa = $conexao->query($insere);
    }
}

Header("Location: ../pages/index.php");