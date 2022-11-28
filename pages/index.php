<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <title>Inventário</title>
    <link rel="shortcut icon" href="../IMG/Itens/new/Construcao/grass_block.png">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../css/anima.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/tooltip.css">

    <script src="../JS/jquery-3.4.1.js"></script>

    <script src="../JS/custom.js"></script>

    <?php include_once "../PHP/conexao_obsoleta.php"; ?>
</head>

<body onload="sincroniza_tema(undefined, 1)">

    <div id="filtro_colorido"></div>
    <div id="lista_versoes" style="display: none">
        <?php
        for ($i = 0; $i < 20; $i += 3) {

            $x = $i + 1;
            $x2 = $i + 2;

            echo "-> <a href='#' onclick='filtragem(\"1.$i\", 2)'>1.$i</a>";
            echo " | <a href='#' onclick='filtragem(\"1.$x\", 2)'>1.$x</a>";
            echo " | <a href='#' onclick='filtragem(\"1.$x2\", 2)'>1.$x2</a>";

            echo "<br>";
        }
        ?>
    </div>

    <div id="estatisticas_inventario">
        <img id="prancheta" src="#">

        <div onclick="filtragem_automatica('oculto')" onmouseover="toolTip('Itens ocultos')" onmouseout="toolTip()">
            <img id="img_ocultos_2" class="aba_menu opcoes_baixo" src="../IMG/Interface/mascara_oculto.png">
            <img id="img_ocultos" class="aba_menu opcoes_baixo Pesquisa" src="#">
        </div>

        <div id="minetip-tooltip">
            <span id="nome_item_minetip"></span><br>
            <span id="descricao_item_minetip"></span><br>
            <span id="nome_interno_minetip"></span>
        </div>

        <div id="text_estatsc">
            <center>
                <h3 class="cor_textos">Estatísticas</h3>
            </center>

            <?php $graphics = true;

            if (!isset($_GET["dg"]))
                $graphics = false;

            $verificar = "SELECT * FROM item WHERE abamenu != 'Generico'";
            $executa = $conexao->query($verificar);

            echo "<p id='versao_referencia' class='estat cor_textos'>Itens Adicionados na versão <span id='num_referencia'></span></p>";

            echo "<br><p class='estat cor_textos'>Itens Registrados: ";
            echo $executa->num_rows . "</p>";

            $verificar = "SELECT * FROM item WHERE coletavelsurvival = 1 AND abamenu != 'Generico'";
            $executa = $conexao->query($verificar);

            echo "<br><p class='estat cor_textos'> Coletáveis: ";
            echo $executa->num_rows . "</p>";

            $verificar = "SELECT * FROM item WHERE fabricavel = 1";
            $executa = $conexao->query($verificar);

            echo "<br><p class='estat cor_textos'> Fabricáveis: ";
            echo $executa->num_rows . "</p>";

            $verificar = "SELECT * FROM item WHERE renovavel = 1 AND abamenu != 'Generico'";
            $executa = $conexao->query($verificar);

            echo "<br><p class='estat cor_textos'>Renováveis: ";
            echo $executa->num_rows . "</p>";

            $verificar = "SELECT * FROM item WHERE empilhavel != 0 AND abamenu != 'Generico'";
            $executa = $conexao->query($verificar);

            echo "<br><p class='estat cor_textos'>Empilháveis: ";
            echo $executa->num_rows . "</p>";

            $verificar = "SELECT * FROM item WHERE empilhavel != 0 AND coletavelsurvival = 1 AND abamenu != 'Generico'";
            $executa = $conexao->query($verificar);

            echo "<br><p class='estat cor_textos'>Coletáveis e empilháveis: ";
            echo $executa->num_rows . "</p>";

            $verificar = "SELECT * FROM item WHERE coletavelsurvival = 1 AND empilhavel != 0 AND renovavel != 1 AND abamenu != 'Generico'";
            $executa = $conexao->query($verificar);

            echo "<br><p class='estat cor_textos'>Coletáveis empilháveis e não renováveis: ";
            echo $executa->num_rows . "</p>";

            $verificar = "SELECT * FROM item WHERE empilhavel LIKE 0 AND abamenu != 'Generico'";
            $executa = $conexao->query($verificar);

            echo "<br><p class='estat cor_textos'>Não empilháveis: ";
            echo $executa->num_rows . "</p>";

            $verificar = "SELECT * FROM item ORDER BY id_item DESC";
            $executa = $conexao->query($verificar); ?>
        </div>
    </div>

    <div id="botoes_ferramentas">
        <a class="bttn_frrm" href="crafting.php" onmouseover="toolTip('O Crafting de todos os itens')" onmouseout="toolTip()"><img src="../IMG/interface/crafting_table.png"></a>

        <?php if ($executa->num_rows > 0) { ?>
            <!-- Só libera a utilização se houver dados -->
            <a class="bttn_frrm" href="../PHP/exportar_dados.php" onmouseover="toolTip('Exporte todos os dados para um JSON externo')" onmouseout="toolTip()">Exportar Dados</a> <?php } ?>
        <a class="bttn_frrm" id="button_importar_dados" href="../PHP/importar_dados.php" onclick="importar_dados()" onmouseover="toolTip('Importe todos os dados de um JSON externo')" onmouseout="toolTip()">Importar Dados</a>

        <?php if ($executa->num_rows > 0) { ?>
            <!-- Só libera a utilização se houver dados -->
            <a class="bttn_frrm" id="button_apagar_dados" href="../PHP/limpar_dados.php" onmouseover="toolTip('Apague todos os dados salvos no banco')" onmouseout="toolTip()">Limpar Dados</a>
        <?php } ?>
    </div>

    <div id="menu_user">
        <?php if ($executa->num_rows > 0) {
            if (!isset($_GET["dg"])) { ?>
                <a class="bttn_frrm" href="index.php?dg=true" onmouseover="toolTip('Os sprites originais do Minecraft')" onmouseout="toolTip()">Programmer Art</a> <?php } else { ?>
                <a class="bttn_frrm" id="bttn_programmers_atv" href="index.php" onmouseover="toolTip('Volte para os sprites atuais do Minecraft')" onmouseout="toolTip()">Gráficos padrões</a> <?php }
                                                                                                                                                                                        } ?>

        <?php if ($executa->num_rows > 0) { ?>
            <a class="bttn_frrm" href="visualizacao.php" onmouseover="toolTip('Uma volta ao passado...')" onmouseout="toolTip()">Máquina do tempo</a>

            <a class="bttn_frrm" href="../modules/criar_pagina.php" onmouseover="toolTip('Atualizar o site em HTML')" onmouseout="toolTip()">Atualizar HTML</a> <?php } ?>
        <a class="bttn_frrm" id="bttn_troca_tema" href="#" onclick="troca_tema(undefined, 1)" onmouseover="toolTip('Altere entre o modo escuro e claro')" onmouseout="toolTip()"><span id="icone_tema">☀️</span></a>
    </div>

    <!-- Adicionar item -->
    <form id="prancheta_add" method="post" action="../PHP/item_registrar.php" enctype="multipart/form-data">
        <div id="inputs_principais">
            <input class="input_prancheta" id="barra_nome" type="text" placeholder="Nome" name="nome" required>

            <input class="input_prancheta" id="barra_nome_interno_pr" type="text" placeholder="Nome interno" name="nome_interno">

            <div id="selects">
                <select name="abamenu" onmouseover="toolTip('A Categoria do item')" onmouseout="toolTip()">

                    <?php
                    $categorias = ["Construcao", "Decorativos", "Redstone", "Transportes", "Diversos", "Alimentos", "Ferramentas", "Combate", "Pocoes", "Especiais", "Generico"];
                    $categorias_exib = ["Blocos de construção", "Blocos decorativos", "Redstone", "Transportes", "Diversos", "Alimentos", "Ferramentas", "Combate", "Poções", "Especiais", "Genérico"];

                    for ($i = 0; $i < sizeof($categorias); $i++) {
                        echo "<option value='$categorias[$i]'>$categorias_exib[$i]</option>";
                    } ?>
                </select><br><br>

                <select name="empilhavel" onmouseover="toolTip('Quantos itens se juntam')" onmouseout="toolTip()">
                    <option value="64">64x</option>
                    <option value="16">16x</option>
                    <option value="0">Não</option>
                </select><br><br>

                <select name="versao" onmouseover="toolTip('A Versão que o item foi adicionado')" onmouseout="toolTip()">
                    <option value="outro">Outro</option>
                    <?php

                    for ($i = 20; $i >= 0; $i--) {
                        echo "<option value='$i'>1.$i</option>";
                    } ?>
                </select>
            </div>
        </div>

        <div id="checkboxes">
            <input class="input_check" type="checkbox" name="coletavelsurvival" checked onmouseover="toolTip('Coletável no sobrevivência')" onmouseout="toolTip()"> <img class="icon_check" src="../IMG/Interface/coracao.png" onmouseover="toolTip('Coletável no sobrevivência')" onmouseout="toolTip()"><br>

            <input class="input_check" type="checkbox" name="renovavel" checked onmouseover="toolTip('Recurso renovável')" onmouseout="toolTip()"> <img class="icon_check" src="../IMG/Itens/new/Decorativos/anvil.png" onmouseover="toolTip('Recurso renovável')" onmouseout="toolTip()">

            <input class="input_check" type="checkbox" name="fabricavel" checked onmouseover="toolTip('Pode fabricar')" onmouseout="toolTip()"> <img class="icon_check" src="../IMG/Interface/crafting_table.png" onmouseover="toolTip('Pode fabricar')" onmouseout="toolTip()">

            <div id="selecionar_sprite">
                <input id="input_img" type="file" name="img" required accept="image/*" onchange="previewImage(0);" onmouseover="toolTip('Sprite do item')" onmouseout="toolTip()">

                <img id="preview_sprite" onmouseover="toolTip('Sprite do item')" onmouseout="toolTip()">
            </div>
        </div>

        <input id="inserir_item" type="submit" value="Inserir">
    </form>

    <!-- Menu interativo -->
    <?php $local_requisicao = 1;
    $versao_jogo = 20;
    include_once "../modules/menu_completo.php"; ?>

    <script src="../JS/engine.js"></script>
    <script src="../JS/crafting.js"></script>

    <script type="text/javascript">
        aba_menu(0, 0)
        document.addEventListener("onKeyDown", clique())
    </script>
</body>

</html>