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
    <script src="../JS/engine.js"></script>
    <script src="../JS/custom.js"></script>
    <script src="../JS/crafting.js"></script>
</head>

<body onload="sincroniza_tema(undefined, 1)">

    <div id="minetip-tooltip">
        <span id="nome_item_minetip"></span><br>
        <span id="descricao_item_minetip"></span><br>
        <span id="nome_interno_minetip"></span>
    </div>

    <div id="filtro_colorido"></div>

    <?php include_once "../PHP/conexao_obsoleta.php";
    $id_item_alvo = $_GET["id"];

    $dados_item = "SELECT historico_guias FROM item WHERE id_item = $id_item_alvo";
    $executa = $conexao->query($dados_item); 
    $dados = $executa->fetch_assoc(); ?>

    <div id="menu_user">
        <a class="bttn_frrm" id="bttn_troca_tema" href="#" onclick="troca_tema(undefined, 1)" onmouseover="toolTip('Altere entre o modo escuro e claro')" onmouseout="toolTip()"><span id="icone_tema">☀️</span></a>
    </div>

    <!-- Menu interativo -->
    <?php $local_requisicao = 2;
    $graphics = false;
    $versao_jogo = 20; 
    
    echo $dados["nome"]; ?>

    <a class="bttn_frrm" id="btn_fecha_tela_craft" href="../pages/item_detalhes.php?id=<?php echo $id_item_alvo ?>" onmouseover="toolTip('Fechar esta tela')" onmouseout="toolTip()"><span>Cancelar</span></a>

    <form id="craft_prancheta" action="../PHP/item_registra_abas.php" method="POST">

        <span class="cor_textos textos_craft">Linha do tempo</span>

        <div id="slots_atalho_itens"></div>

        <div id="grid_crafting">
            <?php for ($i = 0; $i < 9; $i++) {
                echo "<div class='grid_craft gric' onclick='seta_item_craft($i)'></div>";
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
</body>

<script type="text/javascript">
    document.addEventListener("onKeyDown", clique())
</script>

</html>