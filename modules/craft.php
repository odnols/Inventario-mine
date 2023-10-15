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
    <div id="lista_versoes" style="display: none">
        <script>
            for (let i = 0; i < 21; i += 2) {
                let x = i + 1

                document.write(`-> <a href='#' onclick='filtragem(\"1.${i}\", 2)'>1.${i}</a> |`)
                document.write(` <a href='#' onclick='filtragem(\"1.${x}\", 2)'>1.${x}</a><br>`)
            }
        </script>
    </div>

    <?php include_once "../php/conexao_obsoleta.php";
    $id_item_alvo = $_GET["id"];

    $busca_itens = "SELECT * FROM item WHERE coletavel = 1";
    $executa = $conexao->query($busca_itens); ?>

    <div id="menu_user">
        <a class="bttn_frrm" id="bttn_troca_tema" href="#" onclick="troca_tema(undefined, 1)" onmouseover="toolTip('Altere entre o modo escuro e claro')" onmouseout="toolTip()"><span id="icone_tema">☀️</span></a>
    </div>

    <!-- Menu interativo -->
    <?php $local_requisicao = 2;
    $graphics = false;
    $versao_jogo = 21;
    include_once "../modules/menu_completo.php"; ?>

    <a class="bttn_frrm" id="btn_fecha_tela_craft" href="../pages/item_detalhes.php?id=<?php echo $id_item_alvo ?>" onmouseover="toolTip('Fechar esta tela')" onmouseout="toolTip()"><span>Cancelar</span></a>

    <form id="craft_prancheta" action="../php/item_registra_craft.php" method="POST">

        <span class="cor_textos textos_craft">Fabricação</span>
        <span class="cor_textos textos_craft text_craft_2">Inventário</span>

        <div id="slots_atalho_itens"></div>

        <div id="grid_crafting">
            <?php for ($i = 0; $i < 9; $i++) {
                echo "<div class='grid_craft gric'onclick='seta_item_craft($i)'></div>";
            } ?>
        </div>

        <div id="produto_final">
            <div id="sprite_produto"></div>
            <div id="qtd_produto"></div>
        </div>

        <input type="text" class="input_hidden" name="id_item" value="<?php echo $id_item_alvo ?>">
        <input type="text" class="input_hidden" id="array_craft" name="array_craft" required>
        <input type="text" class="input_hidden" id="array_craft" name="qtd_produto" value="1">

        <button id="btn_confirma_craft">Confirma</button>
    </form>

    <?php
    $coleta_receita = "SELECT * FROM item_receita WHERE id_item = $id_item_alvo";
    $executa_coleta = $conexao->query($coleta_receita);

    $receita = "";

    if ($executa_coleta->num_rows > 0) {
        $dados4 = $executa_coleta->fetch_assoc();
        $receita = $dados4["crafting"];
    } ?>
</body>

<script type="text/javascript">
    aba_menu(0, 0)
    document.addEventListener("onKeyDown", clique())

    seleciona_item('auto')

    setTimeout(() => {
        mostra_crafting('<?php echo $receita; ?>', <?php echo $id_item_alvo; ?>, null, 2)
    }, 500)
</script>

</html>