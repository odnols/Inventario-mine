<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <title>Criação</title>
    <link rel="shortcut icon" href="IMG/Itens/new/Construcao/grass_block.png">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/anima.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/tooltip.css">

    <script src="JS/jquery.min.js"></script>
    <script src="JS/jquery-1.11.3.min.js"></script>
    
    <?php include_once "PHP/conexao_obsoleta.php";
    
    $verificar = "SELECT * from item where fabricavel = 1 order by id_item";
    $executa = $conexao->query($verificar); 
    
    $graphics = true;

    if(!isset($_GET["dg"]))
        $graphics = false; ?>

    <style>
        .aba_menu{
            background-color: red !important;
        }
    </style>
</head>
<body onload="sincroniza_tema()">

    <div id="menu_user">    
        <a class="bttn_frrm" id="bttn_troca_tema" href="#" onclick="troca_tema()" onmouseover="toolTip('Altere entre o modo escuro e claro')" onmouseout="toolTip()"><span id="icone_tema">☀️</span></a>
    </div>

    <?php
        for($i = 1; $i <= 5; $i++){
            echo "<a href='#'><div class='item_crafting itc_$i' onclick='crafting_categoria($i)'></div></a>";
        }
    ?>

    <a href="#" id="seta_esquerda_crafting"></a>
    <a href="#" id="seta_direita_crafting"></a>

    <div id="menu_criacao">

        <div id="grid_crafting">
            <?php for($i = 0; $i < 9; $i++){
                echo "<div class='grid_craft gric gric_$i'></div>";
            } ?>
        </div>


        <div id="prancheta_itens_crafting">

        <?php 

        $i = 0;

        while($dados = $executa->fetch_assoc()){
            
            $programmer_art = $dados["programmer_art"];
            $tipo_item = $dados["abamenu"];
            $nome_icon = $dados["nome_icon"];
            $geracao = "new";

            if($programmer_art == 1 && $graphics)
                    $geracao = "classic";

            echo "<a href='#'><div class='slot_item_crafting'> <img class='sprite_slot_crafting' src='IMG/Itens/$geracao/$tipo_item/$nome_icon'></div></a>";

            if($i == 19)
                break;

            $i++;
        } ?>
        </div>
    </div>
    
    <script src="JS/jquery-3.4.1.js"></script>
    <script src="JS/engine.js"></script>
</body>
</html>