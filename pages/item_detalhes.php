<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <title>Detalhes do Item</title>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../css/anima.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/tooltip.css">
    <link rel="stylesheet" type="text/css" href="../css/att_item.css">

    <script src="../js/jquery-3.4.1.js"></script>
    <script src="../js/engine.js"></script>
    <script src="../js/custom.js"></script>
    <script src="../js/crafting.js"></script>

    <?php include_once "../php/conexao_obsoleta.php"; ?>
</head>

<body onload="sincroniza_tema(undefined, 1)">

    <div id="fundo_personali"></div>
    <div id="filtro_colorido"></div>

    <div id="minetip-tooltip">
        <span id="nome_item_minetip"></span><br>
        <span id="descricao_item_minetip"></span><br>
        <span id="nome_interno_minetip"></span>
    </div>

    <div id="minetip-tooltip" class="caixa_item_detalhes">
        <span id="nome_item_minetp"></span><br>
        <span id="descricao_item_minetp"></span><br>
        <span id="nome_interno_minetp"></span>
    </div>

    <?php
    $id_item = $_GET["id"];

    if (isset($_GET["rlod"])) // Reload na página
        header("Location: ./item_detalhes.php?id=$id_item");

    $nome_item = "";
    $descricao_item = "";

    $renovavel = "";
    $coletavel_s = "";
    $oculto_invt = "";
    $programmer_art = "";
    $crafting = "";

    // Último ID registrado de item
    $executa_ultimo_id = $conexao->query("SELECT id_item FROM item ORDER BY id_item DESC LIMIT 1");
    $dados = $executa_ultimo_id->fetch_assoc();
    $ultimo_id = $dados["id_item"];

    $executa = $conexao->query("SELECT * FROM item WHERE id_item = $id_item");

    $dados = $executa->fetch_assoc();

    if ($executa->num_rows > 0) {

        $nome_item = $dados["nome"];
        $nome_interno = $dados["internal"];

        $nome_icon = $dados["icon"];

        $tipo_item = $dados["tipo"];
        $empilhavel = $dados["empilhavel"];
        $versao_add = $dados["versao"];

        if ($versao_add == null)
            $versao_add = "Outro";

        if ($dados["coletavel"])
            $coletavel_s = "checked";

        if ($dados["renovavel"])
            $renovavel = "checked";

        if ($dados["fabricavel"])
            $crafting = "checked";

        $verifica_descricao_item = "SELECT * FROM item_descricao WHERE id_item = $id_item";
        $executa_verificacao = $conexao->query($verifica_descricao_item);
        $dados_descricao = $executa_verificacao->fetch_assoc();

        if ($executa_verificacao->num_rows > 0)
            $descricao_item = $dados_descricao["descricao"];

        $verifica_oculto_item = "SELECT * FROM item_oculto WHERE id_item = $id_item";
        $executa_verificacao = $conexao->query($verifica_oculto_item);

        if ($executa_verificacao->num_rows > 0)
            $oculto_invt = "checked";

        $verifica_legado_item = "SELECT * FROM item_legado WHERE id_item = $id_item";
        $executa_verificacao = $conexao->query($verifica_legado_item);

        if ($executa_verificacao->num_rows > 0)
            $programmer_art = "checked";

        $cor_item = 0;
        $durabilidade = "";

        $executa_item = $conexao->query("SELECT * FROM item_titulo WHERE id_item = $id_item");
        if ($executa_item->num_rows > 0) {
            $dados2 = $executa_item->fetch_assoc();

            $cor_item = $dados2["tipo_item"];
        }

        $executa_item = $conexao->query("SELECT * FROM item_durabilidade WHERE id_item = $id_item");
        if ($executa_item->num_rows > 0) {
            $dados3 = $executa_item->fetch_assoc();

            $durabilidade = $dados3["durabilidade"];
        }
    } ?>

    <button id="btn_voltar" onclick="voltar_pag()">Voltar</button>

    <div id="menu_user">
        <a class="bttn_frrm" id="bttn_troca_tema" href="#" onclick="troca_tema(undefined, 1)" onmouseover="toolTip('Altere entre o modo escuro e claro')" onmouseout="toolTip()"><span id="icone_tema">☀️</span></a>
    </div>

    <div id="bttns_navegacao">
        <?php if ($id_item - 1 > 0) { ?>
            <a href="./item_detalhes.php?id=<?php echo $id_item - 1 ?>"><button class="navegacao" onmouseover="toolTip('Item anterior')" onmouseout="toolTip()">
                    < </button></a>
        <?php } ?>

        <?php if ($id_item < $ultimo_id) { ?>
            <a href="./item_detalhes.php?id=<?php echo $id_item + 1 ?>"><button class="navegacao" onmouseover="toolTip('Próximo item')" onmouseout="toolTip()"> > </button></a>
        <?php } ?>
    </div>

    <?php if (strlen($nome_item) > 0) { // Verifica se existem dados para o ID 
    ?>

        <button id="btn_apagar" onclick="apagarItem(<?php echo $id_item; ?>)">Apagar</button>

        <?php echo "<img id='img_detalhes' src='../img/itens/new/$tipo_item/$nome_icon' onmouseover='toolTip(\"O sprite atual deste item\")' onmouseout='toolTip()'>";

        if ($programmer_art != "")
            echo "<img id='img_detalhes_classic' src='../img/itens/classic/$tipo_item/$nome_icon' onmouseover='toolTip(\"O sprite original deste item\")' onmouseout='toolTip()'>"; ?>

        <form id="prancheta_att" method="post" action="../php/item_atualizar.php" enctype="multipart/form-data">
            <div id="selecionador">
                <div class="pag_1_opcoes">
                    <input type="text" name="id_item" value="<?php echo $id_item; ?>" style="display: none;">

                    <input class="input_prancheta" id="barra_nome" type="text" placeholder="Nome" name="nome" required value="<?php echo $nome_item; ?>" onmouseover="toolTip('Nome do item')" onmouseout="toolTip()">

                    <input class="input_prancheta" id="barra_descricao" type="text" placeholder="Descrição" name="descricao" value="<?php echo $descricao_item; ?>" onmouseover="toolTip('Descrição do item')" onmouseout="toolTip()">

                    <?php if ($empilhavel == 0) { ?>
                        <input class="input_prancheta" id="barra_durabilidade" type="text" placeholder="Durabilidade" name="durabilidade" value="<?php echo $durabilidade; ?>" onmouseover="toolTip('Durabilidade do item')" onmouseout="toolTip()">
                    <?php } ?>

                    <input class="input_prancheta" id="barra_nome_interno" type="text" placeholder="Nome interno" name="nome_interno" value="<?php echo $nome_interno; ?>" onmouseover="toolTip('Nome interno do item')" onmouseout="toolTip()">

                    <?php if ($crafting == "checked")
                        echo "<input type='button' id='btn_fabricacao' value='Fabricação' onclick='inicia_craft($id_item)'>"; ?>

                    <br><br>
                    <?php echo "<input type='button' id='btn_abamenu' value='Guia no menu' onclick='escolhe_aba_menu($id_item)'>" ?>
                </div>

                <div id="selects" class="pag_2_opcoes">
                    <select name="cor_tipo_item" style="width: 505px;" onmouseover="toolTip('A Cor do item no inventário')" onmouseout="toolTip()">
                        <?php
                        $cores_nome = ["Branco", "Azul", "Amarelo", "Rosa", "Laranja"];

                        echo "<option value='$cor_item'>$cores_nome[$cor_item]</option>";

                        for ($i = sizeof($cores_nome) - 1; $i >= 0; $i--) {
                            if ($i != $cor_item)
                                echo "<option value='$i'>$cores_nome[$i]</option>";
                        } ?>
                    </select><br><br>

                    <select name="empilhavel" style="width: 505px;" onmouseover="toolTip('Quantos itens se juntam')" onmouseout="toolTip()">
                        <?php
                        $empilhagens = [64, 16, 0];
                        $empilhagens_exib = ["64x", "16x", "Não"];

                        $indice = array_search($empilhavel, $empilhagens);
                        echo "<option value='$empilhavel'>$empilhagens_exib[$indice]</option>";

                        for ($i = sizeof($empilhagens) - 1; $i >= 0; $i--) {
                            if ($empilhagens[$i] != $empilhavel)
                                echo "<option value='$empilhagens[$i]'>$empilhagens_exib[$i]</option>";
                        } ?>
                    </select><br><br>

                    <select name="versao" style="width: 505px;" onmouseover="toolTip('A Versão que o item foi adicionado')" onmouseout="toolTip()">

                        <?php echo "<option value='$versao_add'>$versao_add</option>";

                        for ($i = 21; $i >= 0; $i--) {
                            if ($versao_add != "1." . $i)
                                echo "<option value='1.$i'>1.$i</option>";
                        } ?>
                    </select>
                </div><br><br>

                <!-- Botões para navegar entre as páginas -->
                <input type="button" value=">" id="pag_2" class="troca_pag_button" onclick="troca_itens(2)" onmouseover="toolTip('Outros dados')" onmouseout="toolTip()">
                <input type="button" value="<" id="pag_1" class="troca_pag_button" onclick="troca_itens(1)" onmouseover="toolTip('Outros dados')" onmouseout="toolTip()">
            </div>

            <div id="checkboxes">
                <div id="separador_checks">
                    <div id="opcoes_esquerda">
                        <input class="input_check" type="checkbox" name="coletavel" <?php echo $coletavel_s ?> onmouseover="toolTip('Coletável no sobrevivência')" onmouseout="toolTip()"> <img class="icon_check" src="../img/interface/coracao.png" onmouseover="toolTip('Coletável no sobrevivência')" onmouseout="toolTip()"><br>

                        <input class="input_check" type="checkbox" name="renovavel" <?php echo $renovavel ?> onmouseover="toolTip('Recurso renovável')" onmouseout="toolTip()"> <img class="icon_check" src="../img/itens/new/decorativos/anvil.png" onmouseover="toolTip('Recurso renovável')" onmouseout="toolTip()">
                    </div>
                    <div id="opcoes_direita">
                        <input class="input_check" type="checkbox" name="oculto_invt" <?php echo $oculto_invt ?> onmouseover="toolTip('Oculto do inventário')" onmouseout="toolTip()"> <img class="icon_check" src="../img/interface/oculto.png" onmouseover="toolTip('Oculto do inventário')" onmouseout="toolTip()">

                        <input class="input_check" type="checkbox" name="programmer_art" <?php echo $programmer_art ?> onmouseover="toolTip('Programmers Art')" onmouseout="toolTip()"> <img class="icon_check" src="../img/interface/grass_block.png" onmouseover="toolTip('Programmers Art')" onmouseout="toolTip()">

                        <input class="input_check" type="checkbox" name="fabricavel" <?php echo $crafting ?> onmouseover="toolTip('Pode fabricar')" onmouseout="toolTip()"> <img class="icon_check" src="../img/interface/crafting_table.png" onmouseover="toolTip('Pode fabricar')" onmouseout="toolTip()">
                    </div>
                </div>

                <div id="selecionar_sprite">
                    <input id="input_img" type="file" name="img" accept="image/*" onchange="previewImage(1);" onmouseover="toolTip('Sprite do item')" onmouseout="toolTip()">

                    <img id="preview_sprite" onmouseover="toolTip('Sprite do item')" onmouseout="toolTip()">
                </div>
            </div>

            <input name="tipo_item" value="<?php echo $tipo_item ?>" style="display: none">

            <input id="inserir_item" type="submit" value="Atualizar">
        </form>

        <?php if ($crafting == "checked") {

            $executa_coleta = $conexao->query("SELECT * FROM item_receita WHERE id_item = $id_item");
            $dados4 = $executa_coleta->fetch_assoc();

            if ($executa_coleta->num_rows > 0)
                $receita = $dados4["crafting"]; ?>

            <div id="preview_item_craft">
                <?php for ($i = 0; $i < 9; $i++) {
                    echo "<div class='grid_craft gric'></div>";
                } ?>
            </div>

    <?php }
        echo "<script>toolTip(\"$nome_item\", \"$descricao_item\", \"$nome_interno\", $cor_item, 1)</script>";

        echo "<style>
            #fundo_personali{
                background: url('../img/itens/new/$tipo_item/$nome_icon'),
                url('../img/itens/new/$tipo_item/$nome_icon');
            }
        </style>";
    } else {
        echo "<script>toolTip(\"Oh, noo!\", \"[&2Este ID não existe ;C\", \"404\", 0, 1)</script>";

        echo "<style>
            #filtro_colorido{
                background: linear-gradient(0deg, rgba(0,0,0,0.9248074229691877) 0%, rgba(80,0,0,0.6699054621848739) 39%, rgba(255,0,0,0) 100%) !important;
            }
        </style>";
    } ?>
</body>

<?php if ($crafting == "checked") { ?>
    <script type="text/javascript">
        setTimeout(() => {
            mostra_crafting('<?php echo $receita ?>', null, 1)
        }, 0)
    </script>
<?php } ?>

</html>