<?php include_once "conexao_obsoleta.php"; 

$id_item = $_POST["id_item"];

$nome = $_POST["nome"];
$nome_interno = $_POST["nome_interno"];
$abamenu = $_POST["abamenu"];
$empilhavel = $_POST["empilhavel"];
$versao = $_POST["versao"];
$aliases = $_POST["aliases"];

$cor_tipo_item = $_POST["cor_tipo_item"];
$descricao = $_POST["descricao"];
$durabilidade = $_POST["durabilidade"];

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

if(isset($_POST["oculto_invt"]))
    $oculto_invt = 1;
else
    $oculto_invt = 0;

if(isset($_POST["programmer_art"]))
    $programmer_art = 1;
else
    $programmer_art = 0;
    
if(isset($_POST["fabricavel"]))
    $crafting = 1;
else
    $crafting = 0;

if(strlen($aliases) == 0)
    $aliases = null;

// Atualizando os campos principais
$insere = "UPDATE item SET nome = '$nome', abamenu = '$abamenu', empilhavel = $empilhavel, coletavelSurvival = $coletavelsurvival, renovavel = $renovavel, oculto_invt = $oculto_invt, programmer_art = $programmer_art, aliases_nome = '$aliases', fabricavel = $crafting WHERE id_item = $id_item";
$executa = $conexao->query($insere);

// Atualizando o nome interno
if(strlen($nome_interno) < 1)
    $insere = "UPDATE item SET nome_interno = null WHERE id_item = $id_item";
else
    $insere = "UPDATE item SET nome_interno = '$nome_interno' WHERE id_item = $id_item";
$executa = $conexao->query($insere);

// Atualizando a descrição do item
if(strlen($descricao) < 1)
    $insere = "UPDATE item SET descricao = null WHERE id_item = $id_item";
else
    $insere = "UPDATE item SET descricao = '$descricao' WHERE id_item = $id_item";
$executa = $conexao->query($insere);

// Atualizando a versão
if(strlen($versao) < 1 || $versao == "Outro")
    $insere = "UPDATE item SET versao_adicionada = 0 WHERE id_item = $id_item";
else
    $insere = "UPDATE item SET versao_adicionada = '$versao' WHERE id_item = $id_item";
$executa = $conexao->query($insere);

// Verifica se o item possui registros anteriores
$verifica_cor_item = "SELECT * FROM cor_item WHERE id_item = $id_item";
$executa_verificacao = $conexao->query($verifica_cor_item);

if($cor_tipo_item != 0 || $executa_verificacao->num_rows > 0){ // Só insere se for diferente de zero
    if($executa_verificacao->num_rows == 0) // Insere um novo
        $insere = "INSERT INTO cor_item VALUES (null, $id_item, $cor_tipo_item)";
    else if($cor_tipo_item != 0) // Atualiza
        $insere = "UPDATE cor_item SET tipo_item = $cor_tipo_item WHERE id_item = $id_item"; 
    else
        $insere = "DELETE FROM cor_item WHERE id_item = $id_item";

    $executa = $conexao-> query($insere);
}

// Verifica se o item possui registros anteriores
$verifica_durabilidade_item = "SELECT * FROM durabilidade_item WHERE id_item = $id_item";
$executa_verificacao = $conexao->query($verifica_durabilidade_item);

if($executa_verificacao->num_rows > 0)
    if(strlen($durabilidade) > 0)
        $insere = "UPDATE durabilidade_item SET durabilidade = $durabilidade WHERE id_item = $id_item";
    else
        $insere = "DELETE FROM durabilidade_item WHERE id_item = $id_item";
else
    $insere = "INSERT INTO durabilidade_item values (null, $id_item, $durabilidade)";

$executa = $conexao-> query($insere);

// Atualizando a imagem que está sendo utilizada
if(strlen($arq_name) > 0){
    $atualiza = "UPDATE item SET nome_icon = '$arq_name' WHERE id_item = $id_item";
    $executa = $conexao->query($atualiza);

    // Criando uma cópia da imagem
    move_uploaded_file($arq_tmp, "C:\wamp64\www\Minecraft\Img\Itens\\new\\$abamenu/". $arq_name);
}

Header("Location: ../pages/item_detalhes.php?id=$id_item");