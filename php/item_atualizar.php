<?php include_once "conexao_obsoleta.php";

$id_item = $_POST["id_item"];

$nome = $_POST["nome"];
$nome_interno = $_POST["nome_interno"];
$empilhavel = $_POST["empilhavel"];
$versao = $_POST["versao"];
$tipo_item = $_POST["tipo_item"];

$cor_tipo_item = $_POST["cor_tipo_item"];
$descricao = $_POST["descricao"];
$durabilidade = $_POST["durabilidade"];

$arq_name = $_FILES["img"]["name"]; //O nome do ficheiro
$arq_size = $_FILES["img"]["size"]; //O tamanho do ficheiro
$arq_tmp = $_FILES["img"]["tmp_name"]; //O nome temporário do arquivo

// Verifica se o item é coletável no sobrevivência
if (isset($_POST["coletavel"]))
    $coletavel = 1;
else
    $coletavel = 0;

if (isset($_POST["renovavel"]))
    $renovavel = 1;
else
    $renovavel = 0;

if (isset($_POST["oculto_invt"]))
    $oculto_invt = 1;
else
    $oculto_invt = 0;

if (isset($_POST["programmer_art"]))
    $programmer_art = 1;
else
    $programmer_art = 0;

if (isset($_POST["fabricavel"]))
    $crafting = 1;
else
    $crafting = 0;

// Atualizando os campos principais
$insere = "UPDATE item SET nome = '$nome', empilhavel = $empilhavel, coletavel = $coletavel, renovavel = $renovavel, fabricavel = $crafting WHERE id_item = $id_item";
$executa = $conexao->query($insere);

// Atualizando o nome interno
if (strlen($nome_interno) < 1)
    $insere = "UPDATE item SET internal = null WHERE id_item = $id_item";
else
    $insere = "UPDATE item SET internal = '$nome_interno' WHERE id_item = $id_item";
$executa = $conexao->query($insere);

// Verifica se o item possui descricao registrada
$verifica_descricao_item = "SELECT * FROM item_descricao WHERE id_item = $id_item";
$executa_verificacao = $conexao->query($verifica_descricao_item);

$insere = "";

// Atualizando a descrição do item
if ($executa_verificacao->num_rows > 0) {
    if (strlen($descricao) < 1)
        $insere = "DELETE FROM item_descricao WHERE id_item = $id_item";
    else
        $insere = "UPDATE item_descricao SET descricao = '$descricao' WHERE id_item = $id_item";
} else if (strlen($descricao > 0)) // Só adiciona uma decrição caso haja texto
    $insere = "INSERT INTO item_descricao VALUES (null, $id_item, '$descricao')";

if ($insere !== "")
    $executa = $conexao->query($insere);

$insere = "";
// Atualizando a versão
if (strlen($versao) < 1 || $versao == "Outro")
    $insere = "UPDATE item SET versao = '1.0' WHERE id_item = $id_item";
else
    $insere = "UPDATE item SET versao = '$versao' WHERE id_item = $id_item";

if ($insere !== "")
    $executa = $conexao->query($insere);

$insere = "";
// Verifica se o item possui registros anteriores
$verifica_cor_item = "SELECT * FROM item_titulo WHERE id_item = $id_item";
$executa_verificacao = $conexao->query($verifica_cor_item);

// Só insere se for diferente de zero
if ($cor_tipo_item != 0 || $executa_verificacao->num_rows > 0) {
    if ($executa_verificacao->num_rows == 0) // Insere um novo
        $insere = "INSERT INTO item_titulo VALUES (null, $id_item, $cor_tipo_item)";
    else if ($cor_tipo_item != 0) // Atualiza
        $insere = "UPDATE item_titulo SET tipo_item = $cor_tipo_item WHERE id_item = $id_item";
    else
        $insere = "DELETE FROM item_titulo WHERE id_item = $id_item";

    if ($insere !== "")
        $executa = $conexao->query($insere);
}

$insere = "";
// Verifica se o item possui registros de durabilidade
$verifica_durabilidade_item = "SELECT * FROM item_durabilidade WHERE id_item = $id_item";
$executa_verificacao = $conexao->query($verifica_durabilidade_item);

if ($executa_verificacao->num_rows > 0)
    if (strlen($durabilidade) > 0)
        $insere = "UPDATE item_durabilidade SET durabilidade = $durabilidade WHERE id_item = $id_item";
    else
        $insere = "DELETE FROM item_durabilidade WHERE id_item = $id_item";
else
    $insere = "INSERT INTO item_durabilidade values (null, $id_item, $durabilidade)";

if ($insere !== "")
    $executa = $conexao->query($insere);

$insere = "";
// Verifica se o item possui sprites antigos
$verifica_legado_item = "SELECT * FROM item_legado WHERE id_item = $id_item";
$executa_verificacao = $conexao->query($verifica_legado_item);

if ($executa_verificacao->num_rows > 0) {
    if (!$programmer_art)
        $insere = "DELETE FROM item_legado WHERE id_item = $id_item";
} else if ($programmer_art)
    $insere = "INSERT INTO item_legado values (null, $id_item, 1)";

if ($insere !== "")
    $executa = $conexao->query($insere);

$insere = "";
// Verifica se o item está oculto no inventário
$verifica_oculto_item = "SELECT * FROM item_oculto WHERE id_item = $id_item";
$executa_verificacao = $conexao->query($verifica_oculto_item);

if ($executa_verificacao->num_rows > 0) {
    if (!$oculto_invt)
        $insere = "DELETE FROM item_oculto WHERE id_item = $id_item";
} else if ($oculto_invt)
    $insere = "INSERT INTO item_oculto values (null, $id_item, 1)";

if ($insere !== "")
    $executa = $conexao->query($insere);

// Atualizando a imagem que está sendo utilizada
if (strlen($arq_name) > 0) {
    $atualiza = "UPDATE item SET icon = '$arq_name' WHERE id_item = $id_item";
    $executa = $conexao->query($atualiza);

    // Criando uma cópia da imagem
    move_uploaded_file($arq_tmp, "C:\wamp64\www\Minecraft\Img\itens\\new\\$tipo_item/" . $arq_name);
}

Header("Location: ../pages/item_detalhes.php?id=$id_item");
