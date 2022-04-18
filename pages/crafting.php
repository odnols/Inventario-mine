<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <title>Criação</title>
    <link rel="shortcut icon" href="../IMG/Itens/new/Construcao/grass_block.png">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../CSS/anima.css">
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">
    <link rel="stylesheet" type="text/css" href="../CSS/tooltip.css">

    <script src="../JS/jquery.min.js"></script>
    <script src="../JS/jquery-1.11.3.min.js"></script>
    
    <?php include_once "../PHP/conexao_obsoleta.php";
    
    $verificar = "SELECT * FROM item WHERE fabricavel = 1 ORDER BY id_item";
    $executa = $conexao->query($verificar); 
    
    $graphics = true;

    if(!isset($_GET["dg"]))
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
        <a class="bttn_frrm" href="index.php" onmouseover="toolTip('O Gerenciador de itens')" onmouseout="toolTip()"><img src="../IMG/interface/crafting_table.png"></a>
    </div>

    <?php
        for($i = 1; $i <= 5; $i++){
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
            <?php for($i = 0; $i < 9; $i++){
                echo "<div class='grid_craft gric'></div>";
            } ?>
        </div>

        <div id="produto_final">
            <div id="sprite_produto"></div>
            <div id="qtd_produto"></div>
        </div>

        <div id="prancheta_itens_crafting">

        <?php $i = 0;

        while($dados = $executa->fetch_assoc()){

            $nome_item = $dados["nome"];
            $nome_interno = $dados["nome_interno"];

            $programmer_art = $dados["programmer_art"];
            $tipo_item = $dados["abamenu"];
            $id_item = $dados["id_item"];
            $nome_icon = $dados["nome_icon"];
            $geracao = "new";

            if($programmer_art == 1 && $graphics)
                    $geracao = "classic";

            echo "<a href='#' onclick='mostra_crafting([], $id_item, null, 1)' onmouseover='toolTip(\"$nome_item\")' onmouseout='toolTip()'><div class='slot_item_crafting'><img class='sprite_slot_crafting' src='../IMG/Itens/$geracao/$tipo_item/$nome_icon'></div></a>";

            if($i == 19)
                break;
            $i++;
        } ?>
        </div>

        <p id="num_pagina_craft">13/21</p>
    </div>
    
    <script type="text/javascript" src="../Files/JSON/dados_locais.json"></script>
    <script src="../JS/jquery-3.4.1.js"></script>
    <script src="../JS/engine.js"></script>
</body>
</html>