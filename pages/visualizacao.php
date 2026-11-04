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

    // Índice da versão: 0 a 21 = 1.0 a 1.21, 22 a 25 = 26.1 a 26.4
    $ultima_versao = 25;
    $versao_jogo = isset($_GET["versao_jogo"]) ? (int) $_GET["versao_jogo"] : 5;

    if ($versao_jogo < 0 || $versao_jogo > $ultima_versao)
        $versao_jogo = 5;

    $rotulos_versoes = [];
    for ($i = 0; $i <= 21; $i++) $rotulos_versoes[] = "1.$i";
    for ($i = 1; $i <= 4; $i++) $rotulos_versoes[] = "26.$i";

    $rotulo_versao_jogo = $rotulos_versoes[$versao_jogo];

    // Mesma ordem de versões usada no filtro em JavaScript
    $ordem_versao_sql = "(IF(SUBSTRING_INDEX(versao, '.', 1) >= 26, 21, 0) + CAST(SUBSTRING_INDEX(versao, '.', -1) AS UNSIGNED))";
    $filtro_versao_sql = "versao REGEXP '^[0-9]+\\\\.[0-9]+$' AND $ordem_versao_sql <= $versao_jogo"; ?>
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

        foreach (array_chunk(array_slice($rotulos_versoes, 0, $versao_jogo + 1), 3) as $linha) {
            $links = array_map(fn($v) => "<a href='#' onclick='filtragem(\"$v\", 2)'>$v</a>", $linha);

            echo "-> " . implode(" | ", $links) . "<br>";
        } ?>
    </div>

    <div id="menu_user">

        <?php if (($versao_jogo - 1) > -1) { ?>
            <a class="nav_versao" onclick="navega_versao(event)" href="visualizacao.php?versao_jogo=<?php echo $versao_jogo - 1; ?><?php echo $graphics ? "&dg=true" : "" ?>"><button class="navegacao" onmouseover="toolTip('Versão anterior')" onmouseout="toolTip()">
                    < </button></a>
        <?php }

        if ($versao_jogo < $ultima_versao) { ?>
            <a class="nav_versao" onclick="navega_versao(event)" href="visualizacao.php?versao_jogo=<?php echo $versao_jogo + 1; ?><?php echo $graphics ? "&dg=true" : "" ?>"><button class="navegacao" onmouseover="toolTip('Próxima versão')" onmouseout="toolTip()"> > </button></a>
        <?php } ?>

        <a class="bttn_frrm<?php echo $graphics ? " programmers_ativo" : "" ?>" id="button_graficos" href="visualizacao.php?versao_jogo=<?php echo $versao_jogo . ($graphics ? "" : "&dg=true"); ?>" onclick="alterna_graficos(event)" onmouseover="toolTip('<?php echo $graphics ? "Volte para os sprites atuais do Minecraft" : "Os gráficos originais do Minecraft" ?>')" onmouseout="toolTip()"><?php echo $graphics ? "Gráficos padrões" : "Programmer Art" ?></a>
        <a class="bttn_frrm" href="index.php" onmouseover="toolTip('O gerenciador de itens')" onmouseout="toolTip()">Gerenciador</a>
        <a class="bttn_frrm" id="bttn_troca_tema" href="#" onclick="troca_tema(<?php echo $versao_jogo ?>, 1)" onmouseover="toolTip('Altere entre o modo escuro e claro')" onmouseout="toolTip()"><span id="icone_tema">☀️</span></a>
    </div>

    <div id="infos_versao">
        Edição Java <?php echo $rotulo_versao_jogo; ?>
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
            $totais = $conexao->query(
                "SELECT COUNT(*) AS total,
                    COALESCE(SUM(versao = '$rotulo_versao_jogo'), 0) AS adicionados,
                    COALESCE(SUM(coletavel = 1), 0) AS coletaveis,
                    COALESCE(SUM(renovavel = 1), 0) AS renovaveis,
                    COALESCE(SUM(empilhavel != 0), 0) AS empilhaveis,
                    COALESCE(SUM(empilhavel != 0 AND coletavel = 1), 0) AS coletaveis_empilhaveis,
                    COALESCE(SUM(coletavel = 1 AND empilhavel != 0 AND renovavel != 1), 0) AS coletaveis_empilhaveis_nao_renovaveis,
                    COALESCE(SUM(empilhavel = 0), 0) AS nao_empilhaveis
                 FROM item WHERE $filtro_versao_sql"
            )->fetch_assoc();

            echo "<p class='estat cor_textos'>itens adicionados na versão $rotulo_versao_jogo ( " . (int) $totais["adicionados"] . " )</p>";

            $estatisticas = [
                "itens no Inventário" => "total",
                " Coletáveis" => "coletaveis",
                "Renováveis" => "renovaveis",
                "Empilháveis" => "empilhaveis",
                "Coletáveis e empilháveis" => "coletaveis_empilhaveis",
                "Coletáveis empilháveis e não renováveis" => "coletaveis_empilhaveis_nao_renovaveis",
                "Não empilháveis" => "nao_empilhaveis"
            ];

            foreach ($estatisticas as $rotulo => $campo)
                echo "<br><p class='estat cor_textos'>$rotulo: " . (int) $totais[$campo] . "</p>";
            $executa = $conexao->query("SELECT * FROM item WHERE $filtro_versao_sql ORDER BY id_item DESC"); ?>
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