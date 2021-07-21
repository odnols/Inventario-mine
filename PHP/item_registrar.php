<?php include_once "conexao_obsoleta.php"; 

$nome = $_POST["nome"];
$nome_interno = $_POST["nome_interno"];

$abamenu = $_POST["abamenu"];
$empilhavel = $_POST["empilhavel"];
$versao = $_POST["versao"];

$arq_name = $_FILES["img"]["name"]; //O nome do ficheiro
$arq_size = $_FILES["img"]["size"]; //O tamanho do ficheiro
$arq_tmp = $_FILES["img"]["tmp_name"]; //O nome temporário do arquivo


if(strlen($nome_interno) == 0)
    $nome_interno = null;

if(isset($_POST["renovavel"]))
    $renovavel = 1;
else
    $renovavel = 0;

// Verifica se o item é coletável no sobrevivência
if(isset($_POST["coletavelsurvival"]))
    $coletavelsurvival = 1;
else
    $coletavelsurvival = 0;

$verificar = "SELECT nome from item where nome like '$nome'";
$executa = $conexao->query($verificar);

if(true){
    $insere = "INSERT into item (id_item, nome, abamenu, empilhavel, coletavelSurvival, img, renovavel, versao_adicionada, nome_interno) values (null, '$nome', '$abamenu', $empilhavel, $coletavelsurvival, '$arq_name', $renovavel, '$versao', '$nome_interno');";

    $executa = $conexao->query($insere);

    // Criando uma cópia da imagem
    move_uploaded_file($arq_tmp, "C:\wamp64\www\Minecraft\Img\Itens\\$abamenu/". $arq_name);
}

echo $insere;

Header("Location: ../index.php");