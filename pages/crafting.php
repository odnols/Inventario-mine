<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <title>Criação</title>
    <link rel="shortcut icon" href="../img/itens/new/construcao/grass_block.png">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../css/anima.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/tooltip.css">

    <script src="../js/jquery-3.4.1.js"></script>
    <script src="../js/engine.js"></script>
    <script src="../js/crafting.js"></script>
    <script src="../js/custom.js"></script>

    <?php include_once "../php/conexao_obsoleta.php";

    $verificar = "SELECT * FROM item WHERE fabricavel = 1 ORDER BY id_item";
    $executa = $conexao->query($verificar);

    $graphics = true;

    if (!isset($_GET["dg"]))
        $graphics = false; ?>
</head>

<body onload="sincroniza_tema(undefined, 1)">

    <div id="prancheta_criar_crafting"></div>
    <div id="filtro_colorido"></div>

    <a class="bttn_frrm" id="btn_fecha_tela_craft" href="#" onmouseover="toolTip('Fechar esta tela')" onmouseout="toolTip()"><span>Cancelar</span></a>

    <div id="menu_user">
        <a class="bttn_frrm" id="bttn_troca_tema" href="#" onclick="troca_tema(undefined, 1)" onmouseover="toolTip('Altere entre o modo escuro e claro')" onmouseout="toolTip()"><span id="icone_tema">☀️</span></a>
    </div>

    <div id="minetip-tooltip">
        <span id="nome_item_minetip"></span><br>
        <span id="descricao_item_minetip"></span><br>
        <span id="nome_interno_minetip"></span>
    </div>

    <div id="botoes_ferramentas">
        <a class="bttn_frrm" href="index.php" onmouseover="toolTip('O Gerenciador de itens')" onmouseout="toolTip()"><img src="../img/interface/crafting_table.png"></a>
    </div>

    <?php
    for ($i = 1; $i <= 5; $i++) {
        echo "<a href='#'><div class='item_crafting itc_$i' onclick='crafting_categoria($i)'></div></a>";
    }
    ?>

    <div id='abas_craft_organiza'>
        <img id="img_craft_todos" class="aba_menu_craft" src="#">
        <img id="img_craft_ferramentas" class="aba_menu_craft" src="#">
        <img id="img_craft_blocos" class="aba_menu_craft" src="#">
        <img id="img_craft_diversos" class="aba_menu_craft" src="#">
        <img id="img_craft_redstone" class="aba_menu_craft" src="#">
    </div>

    <a href="#" id="seta_esquerda_crafting"></a>
    <a href="#" id="seta_direita_crafting"></a>

    <div id="menu_criacao">

        <a id="click_abrir_crafting" href="#"></a>

        <div id="grid_crafting">
            <?php for ($i = 0; $i < 9; $i++) {
                echo "<div class='grid_craft gric'></div>";
            } ?>
        </div>

        <div id="produto_final">
            <div id="sprite_produto"></div>
            <div id="qtd_produto"></div>
        </div>

        <div id="prancheta_itens_crafting">

            <?php $i = 0;

            while ($dados = $executa->fetch_assoc()) {

                $programmer_art = "new";

                $nome_item = $dados["nome"];
                $nome_interno = $dados["internal"];

                $tipo_item = $dados["tipo"];
                $id_item = $dados["id_item"];
                $nome_icon = $dados["icon"];

                $geracao = "new";

                if ($programmer_art == 1 && $graphics)
                    $geracao = "classic";

                $verificar_receita = "SELECT * FROM item_receita WHERE id_item = $id_item";
                $executa_receita = $conexao->query($verificar_receita);

                $recipe = '';
                $data_recipe = $executa_receita->fetch_assoc();

                if (isset($data_recipe["crafting"]))
                    $recipe = $data_recipe["crafting"];

                echo "<a href='#' onclick='mostra_crafting(\"$recipe\", $id_item, null, 1)' onmouseover='toolTip(\"$nome_item\")' onmouseout='toolTip()'><div class='slot_item_crafting'><img class='sprite_slot_crafting' src='../img/itens/$geracao/$tipo_item/$nome_icon'></div></a>";

                if ($i == 20)
                    break;
                $i++;
            } ?>
        </div>

        <p id="num_pagina_craft">13/21</p>
    </div>

    <script type="text/javascript" src="../files/JSON/dados_locais.json"></script>
    <script src="../js/engine.js"></script>
</body>

</html>