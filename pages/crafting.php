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

    <?php
    include_once "../php/conexao_obsoleta.php";

    $executa = $conexao->query(
        "SELECT item.id_item, item.nome, item.tipo, item.icon,
                (SELECT item_receita.crafting
                 FROM item_receita
                 WHERE item_receita.id_item = item.id_item
                 LIMIT 1) AS receita
         FROM item
         WHERE item.fabricavel = 1
         ORDER BY item.id_item"
    );

    if (!$executa) {
        throw new RuntimeException("Falha ao carregar os itens de criação: " . $conexao->error);
    }

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

    <div id="menu_criacao">

        <a id="click_abrir_crafting" href="#"></a>

        <a href="#" id="seta_esquerda_crafting" aria-label="Página anterior de itens"
            aria-controls="prancheta_itens_crafting" aria-expanded="false"></a>
        <a href="#" id="seta_direita_crafting" aria-label="Próxima página de itens"
            aria-controls="prancheta_itens_crafting" aria-expanded="false"></a>

        <input id="busca_crafting" type="search" placeholder="Buscar" aria-label="Buscar itens fabricáveis"
            autocomplete="off" aria-controls="prancheta_itens_crafting">
        <button id="limpar_busca_crafting" type="button" aria-label="Limpar pesquisa"></button>

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

            <?php
            while ($dados = $executa->fetch_assoc()) {

                $programmer_art = "new";

                $id_item = $dados["id_item"];
                $tipo_item = $dados["tipo"] ?? "";
                $nome_icon = $dados["icon"] ?? "";
                $geracao = "new";

                if ($programmer_art == 1 && $graphics)
                    $geracao = "classic";

                $recipe = $dados["receita"] ?? "";
                $json_flags = JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP;
                $recipe_argument = htmlspecialchars(json_encode($recipe, $json_flags), ENT_QUOTES, "UTF-8");
                $name_argument = htmlspecialchars(json_encode($dados["nome"], $json_flags), ENT_QUOTES, "UTF-8");
                $item_name = htmlspecialchars($dados["nome"], ENT_QUOTES, "UTF-8");
                $search_name = htmlspecialchars($dados["nome"], ENT_QUOTES, "UTF-8");
                $image_path = htmlspecialchars(
                    "../img/itens/$geracao/$tipo_item/$nome_icon",
                    ENT_QUOTES,
                    "UTF-8"
                );

                echo '<a href="#" data-item-name="' . $search_name . '"'
                    . ' onclick="mostra_crafting(' . $recipe_argument . ', ' . (int) $id_item . ', null, 1); return false;"'
                    . ' onmouseover="toolTip(' . $name_argument . ')" onmouseout="toolTip()">'
                    . '<div class="slot_item_crafting"><img class="sprite_slot_crafting" src="' . $image_path
                    . '" alt="' . $item_name . '"></div></a>';
            } ?>
        </div>

        <p id="num_pagina_craft" aria-live="polite"></p>
    </div>

    <script type="text/javascript" src="../files/JSON/dados_locais.json"></script>
    <script src="../js/engine.js"></script>
</body>

</html>