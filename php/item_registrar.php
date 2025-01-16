<?php include_once "conexao_obsoleta.php";

$nome = $_POST["nome"];
$nome_interno = $_POST["nome_interno"];

$abamenu = $_POST["abamenu"];
$empilhavel = $_POST["empilhavel"];
$versao = $_POST["versao"];

$arq_name = $_FILES["img"]["name"]; //O nome do ficheiro
$arq_size = $_FILES["img"]["size"]; //O tamanho do ficheiro
$arq_tmp = $_FILES["img"]["tmp_name"]; //O nome temporário do arquivo

$renovavel = 0;
$coletavel = 0;
$fabricavel = 0;

if (strlen($nome_interno) == 0)
    $nome_interno = null;

if (isset($_POST["renovavel"]))
    $renovavel = 1;

// Verifica se o item é coletável no sobrevivência
if (isset($_POST["coletavel"]))
    $coletavel = 1;

// Verifica se o item pode ser fabricado manualmente
if (isset($_POST["fabricavel"]))
    $fabricavel = 1;

$executa = $conexao->query("SELECT nome FROM item WHERE nome LIKE '$nome'");

// Nomes de itens que se repetem no inventário
$nomes_reservados = array("Disco Musical", "Livro Encantado", "Chifre de Cabra", "Luz", "Molde de Ferraria", "Fragmento de Cerâmica", "Quadro", "Flecha com Efeito", "Desenho para Estandarte", "Frasco Sombrio", "Bloco de Teste");

if ($executa->num_rows == 0 || in_array($nome, $nomes_reservados)) {
    $conexao->query("INSERT INTO item (nome, tipo, empilhavel, coletavel, icon, renovavel, versao, internal, fabricavel) VALUES ('$nome', '$abamenu', $empilhavel, $coletavel, '$arq_name', $renovavel, '$versao', '$nome_interno', $fabricavel);");

    // Criando uma cópia da imagem
    move_uploaded_file($arq_tmp, "../img/itens/new/$abamenu/" . $arq_name);
}

Header("Location: ../pages/index.php");
