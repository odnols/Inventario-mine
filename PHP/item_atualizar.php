<?php include_once "conexao_obsoleta.php"; 

$id_item = $_POST["id_item"];

$nome = $_POST["nome"];
$nome_interno = $_POST["nome_interno"];
$abamenu = $_POST["abamenu"];
$empilhavel = $_POST["empilhavel"];
$versao = $_POST["versao"];
$aliases = $_POST["aliases"];

$arq_name = $_FILES["img"]["name"]; //O nome do ficheiro
$arq_size = $_FILES["img"]["size"]; //O tamanho do ficheiro
$arq_tmp = $_FILES["img"]["tmp_name"]; //O nome temporário do arquivo

// Verifica se o item é coletável no sobrevivência
if(isset($_POST["coletavelsurvival"]))
    $coletavelsurvival = 1;
else
    $coletavelsurvival = 0;

if(isset($_POST["renovavel"]))
    $renovavel = 1;
else
    $renovavel = 0;

// Atualizando os campos principais
$insere = "UPDATE item set nome = '$nome', abamenu = '$abamenu', empilhavel = $empilhavel, coletavelSurvival = $coletavelsurvival, renovavel = $renovavel, aliases_nome = '$aliases' where id_item = $id_item";
$executa = $conexao->query($insere);

// Atualizando o nome interno
if(strlen($nome_interno) < 1)
    $insere = "UPDATE item set nome_interno = null where id_item = $id_item";
else
    $insere = "UPDATE item set nome_interno = '$nome_interno' where id_item = $id_item";
$executa = $conexao->query($insere);

// Atualizando a versão
if(strlen($versao) < 1 || $versao == "Outro")
    $insere = "UPDATE item set versao_adicionada = null where id_item = $id_item";
else
    $insere = "UPDATE item set versao_adicionada = '$versao' where id_item = $id_item";
$executa = $conexao->query($insere);

// Atualizando a imagem que está sendo utilizada
if(strlen($arq_name) > 0){
    $atualiza = "UPDATE item set img = '$arq_name' where id_item = $id_item";
    $executa = $conexao->query($atualiza);

    // Criando uma cópia da imagem
    move_uploaded_file($arq_tmp, "C:\wamp64\www\Minecraft\Img\Itens\\$abamenu/". $arq_name);
}

Header("Location: ../item_detalhes.php?id=$id_item");