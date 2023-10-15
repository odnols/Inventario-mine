<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <title>Inventário</title>
    <link rel="shortcut icon" href="../img/itens/new/construcao/grass_block.png">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../css/anima.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/tooltip.css">

    <script src="../js/jquery-3.4.1.js"></script>
    <script src="../js/engine.js"></script>
    <script src="../js/custom.js"></script>
    <script src="../js/crafting.js"></script>
</head>

<body onload="sincroniza_tema(undefined, 1)">

    <div id="minetip-tooltip">
        <span id="nome_item_minetip"></span><br>
        <span id="descricao_item_minetip"></span><br>
        <span id="nome_interno_minetip"></span>
    </div>

    <div id="filtro_colorido"></div>

    <div id="menu_user">
        <a class="bttn_frrm" id="bttn_troca_tema" href="#" onclick="troca_tema(undefined, 1)" onmouseover="toolTip('Altere entre o modo escuro e claro')" onmouseout="toolTip()"><span id="icone_tema">☀️</span></a>
    </div>

    <?php include_once "../php/conexao_obsoleta.php";
    $id_item_alvo = $_GET["id"];

    $dados_item = "SELECT historico_guias FROM item WHERE id_item = $id_item_alvo";
    $executa = $conexao->query($dados_item);

    if ($executa->num_rows > 0)
        $dados = $executa->fetch_assoc();

    // Menu interativo
    $local_requisicao = 2;
    $graphics = false;
    $versao_jogo = 21;

    echo $dados["nome"]; ?>

    <a class="bttn_frrm" id="btn_fecha_tela_craft" href="../pages/item_detalhes.php?id=<?php echo $id_item_alvo ?>" onmouseover="toolTip('Fechar esta tela')" onmouseout="toolTip()"><span>Cancelar</span></a>

    <form id="prancheta_historico" action="../php/item_registra_abas.php" method="POST">


    </form>
</body>

<script type="text/javascript">
    document.addEventListener("onKeyDown", clique())
</script>

</html>