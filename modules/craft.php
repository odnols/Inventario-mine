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
    include_once "../php/tipos_craft.php";
    $id_item_alvo = (int) ($_GET["id"] ?? 0);

    $executa = $conexao->query("SELECT * FROM item WHERE coletavel = 1 ORDER BY id_item DESC");
    $executa_produto = $conexao->query(
        "SELECT nome, tipo, icon, `internal` FROM item WHERE id_item = $id_item_alvo LIMIT 1"
    );
    if (!$executa_produto) {
        throw new RuntimeException("Falha ao carregar o produto da receita: " . $conexao->error);
    }
    $produto_alvo = $executa_produto->fetch_assoc();
    $executa_coleta = $conexao->query(
        "SELECT id_craft, crafting, tipo_craft, qtd_produtos FROM item_receita WHERE id_item = $id_item_alvo ORDER BY id_craft"
    );
    if (!$executa_coleta) {
        throw new RuntimeException("Falha ao carregar as receitas do produto: " . $conexao->error);
    }

    $craft_configs = [];
    $receita = "";
    $tipo_craft = "bancada";
    $qtd_produtos = 1;

    while ($dados4 = $executa_coleta->fetch_assoc()) {
        $tipo_receita = normaliza_tipo_craft($dados4["tipo_craft"]);
        if ($tipo_receita === null) {
            throw new RuntimeException("Tipo de bancada inválido na receita do produto.");
        }

        $craft_configs[$tipo_receita][] = [
            "id" => (int) $dados4["id_craft"],
            "recipe" => $dados4["crafting"],
            "products" => max(1, (int) $dados4["qtd_produtos"])
        ];
    }

    if ($craft_configs) {
        $tipo_craft = array_key_first($craft_configs);
        $receita = $craft_configs[$tipo_craft][0]["recipe"];
        $qtd_produtos = $craft_configs[$tipo_craft][0]["products"];
    }

    if ($qtd_produtos === 1 || trim($receita) === "") {
        $nome_produto = $produto_alvo["nome"] ?? "";

        if (preg_match('/\bal[çc]ap[aã]o\b/i', $nome_produto)) {
            $qtd_produtos = 2;
        } elseif (preg_match('/\bplacas?\b.*\b(?:press[aã]o|pesagem)\b/i', $nome_produto)) {
            $qtd_produtos = 1;
        } elseif (preg_match('/\bportas?\b/i', $nome_produto)) {
            $qtd_produtos = 3;
        } elseif (preg_match('/\btapetes?\b/i', $nome_produto)) {
            $qtd_produtos = 3;
        } elseif (preg_match('/^madeira\b/i', $nome_produto)) {
            $qtd_produtos = 3;
        } elseif (
            preg_match('/\bprateleiras?\b/i', $nome_produto)
            || preg_match('/\bplacas?\s+suspensas?\b/i', $nome_produto)
        ) {
            $qtd_produtos = 6;
        } elseif (
            preg_match('/\bplacas?\b/i', $nome_produto)
            && !preg_match('/\bpress[ã]o\b/i', $nome_produto)
        ) {
            $qtd_produtos = 3;
        } elseif (preg_match('/\b(?:muro|muros|laje|lajes)\b/i', $nome_produto)) {
            $qtd_produtos = 6;
        } elseif (
            preg_match('/\bescadas?\b/i', $nome_produto)
            || preg_match('/polid|tijolo/i', $nome_produto)
        ) {
            $qtd_produtos = 4;
        }
    } ?>

    <div id="menu_user">
        <a class="bttn_frrm" id="bttn_troca_tema" href="#" onclick="troca_tema(undefined, 1)" onmouseover="toolTip('Altere entre o modo escuro e claro')" onmouseout="toolTip()"><span id="icone_tema">☀️</span></a>
    </div>

    <!-- Menu interativo -->
    <?php $local_requisicao = 2;
    $mostrar_aba_remover = true;
    $graphics = false;
    $versao_jogo = 21;
    include_once "../modules/menu_completo.php"; ?>

    <a class="bttn_frrm" id="btn_fecha_tela_craft" href="../pages/item_detalhes.php?id=<?php echo $id_item_alvo ?>" onmouseover="toolTip('Fechar esta tela')" onmouseout="toolTip()"><span>Cancelar</span></a>

    <form id="craft_prancheta" data-tipo-craft="<?php echo $tipo_craft; ?>"
        data-craft-configs="<?php echo htmlspecialchars(json_encode($craft_configs, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP), ENT_QUOTES, "UTF-8"); ?>"
        action="../php/item_registra_craft.php" method="POST">

        <div id="craft_station_interface" aria-hidden="true"></div>
        <span class="cor_textos textos_craft">Fabricação</span>
        <span class="cor_textos textos_craft text_craft_2">Inventário</span>

        <div id="slots_atalho_itens"></div>

        <div id="grid_crafting">
            <?php for ($i = 0; $i < 9; $i++) {
                echo "<div class='grid_craft gric' onclick='seta_item_craft($i)'></div>";
            } ?>
        </div>

        <div id="produto_final">
            <div id="sprite_produto"
                <?php if ($produto_alvo) { ?>
                data-product-id="<?php echo $id_item_alvo ?>"
                data-product-type="<?php echo htmlspecialchars($produto_alvo["tipo"], ENT_QUOTES, "UTF-8") ?>"
                data-product-icon="<?php echo htmlspecialchars($produto_alvo["icon"], ENT_QUOTES, "UTF-8") ?>"
                data-product-name="<?php echo htmlspecialchars($produto_alvo["nome"], ENT_QUOTES, "UTF-8") ?>"
                data-product-internal-name="<?php echo htmlspecialchars($produto_alvo["internal"], ENT_QUOTES, "UTF-8") ?>"
                <?php } ?>></div>
            <div id="qtd_produto"></div>
            <input id="input_qtd_produto" type="number" name="qtd_produto" min="1" step="1" max="64"
                value="<?php echo $qtd_produtos ?? 1; ?>" aria-label="Quantidade de produtos">
        </div>

        <div id="seletor_tipo_craft" role="group" aria-label="Tipo de bancada">
            <button type="button" class="tipo_craft_opcao ativa" data-tipo-craft="bancada"
                aria-label="Bancada de trabalho" aria-pressed="true">
                <img src="../img/itens/new/decorativos/crafting_table.png" alt="Bancada de trabalho">
            </button>
            <button type="button" class="tipo_craft_opcao" data-tipo-craft="ferraria"
                aria-label="Bancada de ferraria" aria-pressed="false">
                <img src="../img/itens/new/decorativos/smithing_table.png" alt="Bancada de ferraria">
            </button>
            <button type="button" class="tipo_craft_opcao" data-tipo-craft="fornalha"
                aria-label="Fornalha" aria-pressed="false">
                <img src="../img/itens/new/decorativos/furnace.png" alt="Fornalha">
            </button>
            <button type="button" class="tipo_craft_opcao" data-tipo-craft="pocao"
                aria-label="Suporte de poções" aria-pressed="false">
                <img src="../img/itens/new/pocoes/brewing_stand.png" alt="Suporte de poções">
            </button>
        </div>

        <div id="seletor_receitas_craft" role="group" aria-label="Receitas da bancada selecionada">
            <button type="button" id="receita_craft_anterior" aria-label="Receita anterior">&#8249;</button>
            <span id="receita_craft_indicador" aria-live="polite"></span>
            <button type="button" id="receita_craft_proxima" aria-label="Próxima receita">&#8250;</button>
            <button type="button" id="receita_craft_nova">Nova receita</button>
        </div>

        <input type="text" class="input_hidden" name="id_item" value="<?php echo $id_item_alvo ?>">
        <input type="text" class="input_hidden" id="array_craft" name="array_craft" required>
        <input type="hidden" id="tipo_craft" name="tipo_craft" value="<?php echo $tipo_craft; ?>">
        <input type="hidden" id="craft_configs" name="craft_configs" value="">

        <button id="btn_confirma_craft">Confirma</button>
    </form>

</body>

<script type="text/javascript">
    aba_menu(10)
    document.addEventListener("onKeyDown", clique())

    inicializa_configuracoes_craft()
    seleciona_item('auto')
    seleciona_tipo_craft(<?php echo json_encode($tipo_craft); ?>)
</script>

</html>