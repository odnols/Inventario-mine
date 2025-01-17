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

    <?php include_once "../php/conexao_obsoleta.php";

    if (isset($_GET["versao_jogo"]))
        $versao_jogo = $_GET["versao_jogo"];

    if (!isset($versao_jogo) || $versao_jogo < 0 || $versao_jogo > 21)
        $versao_jogo = 5; ?>
</head>

<body>

    <input id="input_versao_jogo" type="text" value="<?php echo $versao_jogo ?>" style="display: none;">

    <div id="minetip-tooltip">
        <span id="nome_item_minetip"></span><br>
        <span id="descricao_item_minetip"></span><br>
        <span id="nome_interno_minetip"></span>
    </div>

    <div id="filtro_colorido"></div>
    <div id="lista_versoes" style="display: none">
        <?php
        $graphics = true;

        if (!isset($_GET["dg"]))
            $graphics = false;

        for ($i = 0; $i < 22; $i += 3) {
            echo "-> <a href='#' onclick='filtragem(\"1.$i\", 2)'>1.$i</a>";

            $x = $i + 1;
            $x2 = $i + 2;

            if ($i + 1 <= $versao_jogo)
                echo " | <a href='#' onclick='filtragem(\"1.$x\", 2)'>1.$x</a>";

            if ($i + 2 <= $versao_jogo)
                echo " | <a href='#' onclick='filtragem(\"1.$x2\", 2)'>1.$x2</a>";

            echo "<br>";

            if ($versao_jogo == $i || $versao_jogo == $i + 1 || $versao_jogo == $i + 2) // Para o menu
                break;
        } ?>
    </div>

    <div id="menu_user">

        <?php if (($versao_jogo - 1) > -1) { ?>
            <a href="visualizacao.php?versao_jogo=<?php echo $versao_jogo - 1; ?>&dg=<?php echo $graphics ?>"><button class="navegacao" onmouseover="toolTip('Versão anterior')" onmouseout="toolTip()">
                    < </button></a>
        <?php }

        if ($versao_jogo < 21) { ?>
            <a href="visualizacao.php?versao_jogo=<?php echo $versao_jogo + 1; ?>&dg=<?php echo $graphics ?>"><button class="navegacao" onmouseover="toolTip('Próxima versão')" onmouseout="toolTip()"> > </button></a>
        <?php } ?>

        <?php if (!isset($_GET["dg"])) { ?>
            <a class="bttn_frrm" href="visualizacao.php?versao_jogo=<?php echo $versao_jogo; ?>&dg=true" onclick="#" onmouseover="toolTip('Os gráficos originais do Minecraft')" onmouseout="toolTip()">Programmer Art</a> <?php } else { ?>
            <a class="bttn_frrm" id="bttn_programmers_atv" href="visualizacao.php?versao_jogo=<?php echo $versao_jogo; ?>" onmouseover="toolTip('Volte para os sprites atuais do Minecraft')" onmouseout="toolTip()">Gráficos padrões</a> <?php } ?>

        <a class="bttn_frrm" href="index.php" onmouseover="toolTip('O gerenciador de itens')" onmouseout="toolTip()">Gerenciador</a>
        <a class="bttn_frrm" id="bttn_troca_tema" href="#" onclick="troca_tema(<?php echo $versao_jogo ?>, 1)" onmouseover="toolTip('Altere entre o modo escuro e claro')" onmouseout="toolTip()"><span id="icone_tema">☀️</span></a>
    </div>

    <div id="infos_versao">
        Edição Java 1.<?php echo $versao_jogo; ?>
    </div>

    <div id="estatisticas_inventario">
        <img id="prancheta" src="#">

        <div class="botoes_menu">
            <div onclick="filtragem_automatica('oculto')" onmouseover="toolTip('itens ocultos')" onmouseout="toolTip()">
                <img id="img_ocultos_2" class="aba_menu opcoes_baixo" src="../img/interface/mascara_oculto.png">
                <img id="img_ocultos" class="aba_menu opcoes_baixo pesquisa" src="#">
            </div>
        </div>

        <div id="text_estatsc">
            <center>
                <h2 class="cor_textos">Estatísticas</h2>
            </center>

            <?php
            $executa = $conexao->query("SELECT * FROM item WHERE versao = 1.$versao_jogo");
            echo "<p class='estat cor_textos'>itens adicionados na versão 1.$versao_jogo ( ";
            echo $executa->num_rows . " )</p>";

            $executa = $conexao->query("SELECT * FROM item WHERE versao <= $versao_jogo");
            echo "<br><p class='estat cor_textos'>itens no Inventário: ";
            echo $executa->num_rows . "</p>";

            $executa = $conexao->query("SELECT * FROM item WHERE versao <= $versao_jogo AND coletavel = 1");
            echo "<br><p class='estat cor_textos'> Coletáveis: ";
            echo $executa->num_rows . "</p>";

            $executa = $conexao->query("SELECT * FROM item WHERE versao <= $versao_jogo AND renovavel = 1");
            echo "<br><p class='estat cor_textos'>Renováveis: ";
            echo $executa->num_rows . "</p>";

            $executa = $conexao->query("SELECT * FROM item WHERE versao <= $versao_jogo AND empilhavel != 0");
            echo "<br><p class='estat cor_textos'>Empilháveis: ";
            echo $executa->num_rows . "</p>";

            $executa = $conexao->query("SELECT * FROM item WHERE versao <= $versao_jogo AND empilhavel != 0 AND coletavel = 1");
            echo "<br><p class='estat cor_textos'>Coletáveis e empilháveis: ";
            echo $executa->num_rows . "</p>";

            $executa = $conexao->query("SELECT * FROM item WHERE versao <= $versao_jogo AND coletavel = 1 AND empilhavel != 0 AND renovavel != 1");
            echo "<br><p class='estat cor_textos'>Coletáveis empilháveis e não renováveis: ";
            echo $executa->num_rows . "</p>";

            $executa = $conexao->query("SELECT * FROM item WHERE versao <= $versao_jogo AND empilhavel LIKE 0");
            echo "<br><p class='estat cor_textos'>Não empilháveis: ";
            echo $executa->num_rows . "</p>";

            $executa = $conexao->query("SELECT * FROM item WHERE versao <= $versao_jogo ORDER BY id_item DESC"); ?>
        </div>
    </div>

    <!-- Menu interativo -->
    <?php $local_requisicao = 3;
    include_once "../modules/menu_completo.php"; ?>

    <script src="../js/engine.js"></script>
    <script src="../js/custom.js"></script>

    <script type="text/javascript">
        filtragem(10, 0)
        sincroniza_tema(<?php echo $versao_jogo ?>, 1)
    </script>
</body>

</html>