<?php include_once "../php/conexao_obsoleta.php"; ?>

<div id="menu_completo">
    <img id="menu" src="#">

    <?php if (!empty($mostrar_aba_remover)) { ?>
        <button id="aba_remover_item_selecionado" type="button" onclick="remove_item_selecionado()"
            aria-label="Remover item selecionado" title="Remover item selecionado" onmouseover="toolTip('Remover item selecionado')" onmouseout="toolTip()">
            <img src="../img/interface/aba_generic.png" alt="">
        </button>
    <?php } ?>

    <?php // Nas versões 1.0 a 1.2 o menu não tem abas visuais, então elas nem são renderizadas
    $menu_sem_abas = isset($versao_jogo) && $versao_jogo <= 2;

    if (!$menu_sem_abas)
        for ($i = 0; $i < 11; $i++)
            echo "<div id='item_$i' onclick='aba_menu($i)'></div>";

    if ($local_requisicao === 1) { // Apenas visivel na tela inicial, prancheta de inserção de novos itens 
    ?>
        <div id="item_11" onclick="clique('prancheta')"></div>
    <?php }

    if ($versao_jogo > 2) { ?>
        <img id="img_construcao" class="aba_menu aba_menu_construcao construcao" src="#">
        <img id="img_decorativos" class="aba_menu aba_menu_decorativos decorativos" src="#">
        <img id="img_redstone" class="aba_menu aba_menu_redstone redstone" src="#">
        <img id="img_transportes" class="aba_menu aba_menu_transportes transportes" src="#">
        <img id="img_diversos" class="aba_menu aba_menu_diversos diversos" src="#">
        <img id="img_alimentos" class="aba_menu aba_menu_alimentos alimentos" src="#">
        <img id="img_ferramentas" class="aba_menu aba_menu_ferramentas ferramentas" src="#">
        <img id="img_combate" class="aba_menu aba_menu_combate combate" src="#">
        <img id="img_pocoes" class="aba_menu aba_menu_pocoes pocoes" src="#">

        <div onclick="filtragem_automatica('off')" onmouseover="toolTip('Mostrar itens sem versão informada ou sem nome interno')" onmouseout="toolTip()">
            <img id="img_configs_2" class="aba_menu opcoes_laterais" src="../img/interface/mascara_configs.png">
            <img id="img_configs" class="aba_menu opcoes_laterais pesquisa" src="../img/interface/aba_configs.png">
        </div>

        <div onclick="filtragem_automatica('não_coletável')" onmouseover="toolTip('Mostrar itens que não são coletáveis no sobrevivência')" onmouseout="toolTip()">
            <img id="img_coletaveis_2" class="aba_menu opcoes_laterais" src="../img/interface/mascara_nao_coletaveis.png">
            <img id="img_coletaveis" class="aba_menu opcoes_laterais pesquisa" src="../img/interface/aba_nao_coletaveis.png">
        </div>

        <div onclick="lista_versoes()" onmouseover="toolTip('Filtrar por versões')" onmouseout="toolTip()">
            <img id="img_versoes_2" class="aba_menu" src="../img/interface/mascara_atts.png">
            <img id="img_versoes" class="aba_menu opcoes_laterais pesquisa" src="../img/interface/aba_atts.png">
        </div>

        <img id="img_especiais" class="aba_menu aba_menu_especiais especiais" src="#">
        <img id="img_pesquisa" class="aba_menu aba_menu_pesquisa pesquisa" src="#">

        <img id="img_prancheta" class="aba_menu prancheta" src="../img/interface/mascara_prancheta.png">

        <input class="pesquisa" id="barra_pesquisa_input" type="text" onkeyup="filtra_pesquisa()" />
    <?php } ?>

    <span id="titulo_aba"></span>

    <div id="barra_rolagem">
        <div id="barra_scroll" src="../img/interface/scroll.png"></div>
        <img id="barra_scroll_block" src="#">
    </div>

    <div id="lista_itens">
        <?php if ($local_requisicao !== 3) { ?>
            <div id="listagem" onscroll="scrollSincronizado('listagem', 'barra_scroll')">
            <?php } else { ?>
                <div id="listagem" onscroll="scrollSincronizado('listagem', 'barra_scroll', <?php echo $versao_jogo; ?>)">
                <?php }

            // Carrega as tabelas auxiliares de uma vez para evitar uma consulta por item
            $mapa_ocultos = [];
            $mapa_legados = [];
            $mapa_descricoes = [];
            $mapa_titulos = [];

            $consulta_aux = $conexao->query("SELECT id_item FROM item_oculto");
            while ($linha = $consulta_aux->fetch_assoc()) $mapa_ocultos[$linha["id_item"]] = true;

            $consulta_aux = $conexao->query("SELECT id_item FROM item_legado");
            while ($linha = $consulta_aux->fetch_assoc()) $mapa_legados[$linha["id_item"]] = true;

            $consulta_aux = $conexao->query("SELECT id_item, descricao FROM item_descricao");
            while ($linha = $consulta_aux->fetch_assoc())
                if (!isset($mapa_descricoes[$linha["id_item"]])) $mapa_descricoes[$linha["id_item"]] = $linha["descricao"];

            $consulta_aux = $conexao->query("SELECT id_item, tipo_item FROM item_titulo");
            while ($linha = $consulta_aux->fetch_assoc())
                if (!isset($mapa_titulos[$linha["id_item"]])) $mapa_titulos[$linha["id_item"]] = $linha["tipo_item"];

            while ($dados = $executa->fetch_assoc()) {

                $apelido = "";
                $descricao_pesq = "";
                $oculto_invt = "no";
                $geracao = "new";

                $id_item = $dados["id_item"];
                $nome_icon = $dados["icon"];
                $tipo_item = $dados["tipo"]; // Temporário
                $nome_item = $dados["nome"];
                $coletavel = $dados["coletavel"];
                $nome_interno = $dados["internal"];
                $empilhavel = $dados["empilhavel"];
                $versao_add = $dados["versao"];
                $renovavel = $dados["renovavel"];

                $programmer_art = 0;

                if (isset($mapa_ocultos[$id_item])) $oculto_invt = "oculto";

                if (isset($mapa_legados[$id_item])) $programmer_art = 1;

                $descricao = "[&1" . $tipo_item;

                // Descricao do item
                if (isset($mapa_descricoes[$id_item]))
                    $descricao = $descricao . " " . $mapa_descricoes[$id_item];

                $descricao_pes = str_replace("[&r", "", $descricao);

                if (!$nome_interno)
                    $nome_interno = "off";

                $atributo_legado = $programmer_art == 1 ? " data-legado='1'" : "";

                if ($programmer_art == 1 && $graphics)
                    $geracao = "classic";

                if ($versao_add === null) $versao_add = "off";
                else $versao_add = $versao_add;

                if ($renovavel) $renovavel = "renovável";
                else $renovavel = "não_renovável";

                if ($empilhavel !== 0) $empilhavel = "empilhável";
                else $empilhavel = "não_empilhavel";

                if ($coletavel) $coletavel = "coletável";
                else $coletavel = "não_coletável";

                $cor_item = 0;
                $descricao_pesq = "";

                if (isset($mapa_titulos[$id_item]))
                    $cor_item = $mapa_titulos[$id_item];

                $descricao_pesq = strtolower($descricao_pesq);

                $slot_item = "slot_item";
                $nome_pesq = strtolower($nome_item);

                if ($local_requisicao === 3 && $versao_add === ($rotulo_versao_jogo ?? null))
                    $slot_item = "slot_item slot_item_add"; // Mantém "slot_item" para os filtros e usa o fundo de slot_add

                for ($i = 0; $i < 21; $i++) // Elimina todos os números de versão da descrição
                    $descricao_pesq = str_replace($i, "", $descricao_pesq);

                $atributos_craft = "";
                if ($local_requisicao == 2) {
                    $atributos_craft = " data-craft-item-id='" . (int) $id_item . "'"
                        . " data-craft-item-type='" . htmlspecialchars((string) $tipo_item, ENT_QUOTES, "UTF-8") . "'"
                        . " data-craft-item-icon='" . htmlspecialchars((string) $nome_icon, ENT_QUOTES, "UTF-8") . "'"
                        . " data-craft-item-name='" . htmlspecialchars((string) $nome_item, ENT_QUOTES, "UTF-8") . "'"
                        . " data-craft-item-internal-name='" . htmlspecialchars((string) $nome_interno, ENT_QUOTES, "UTF-8") . "'";
                }

                if ($local_requisicao == 1) { // Requisição proveniente da página inicial
                    if ($oculto_invt !== "oculto") {

                        echo "<div class='slot_item $tipo_item $versao_add $nome_interno $coletavel $nome_pesq $descricao_pesq' onclick='exibe_detalhes_item($id_item)' onmouseover='toolTip(\"$nome_item\", \"$descricao\", \"$nome_interno\", $cor_item)' onmouseout='toolTip()'>";
                        echo "<img class='icon_item'$atributo_legado loading='lazy' decoding='async' src='../img/itens/$geracao/$tipo_item/$nome_icon'>";
                        echo "</div>";
                    } else {

                        echo "<div class='slot_item oculto' onclick='exibe_detalhes_item($id_item)' onmouseover='toolTip(\"$nome_item\", \"$descricao\", \"$nome_interno\", $cor_item)' onmouseout='toolTip()'>";

                        echo "<img class='icon_item'$atributo_legado loading='lazy' decoding='async' src='../img/itens/$geracao/$tipo_item/$nome_icon'>";
                        echo "</div>";
                    }
                } else if ($local_requisicao == 2) { // Requisição proveniente da página de receitas
                    if ($oculto_invt !== "oculto") {
                        echo "<div class='slot_item $tipo_item $nome_interno $versao_add $nome_pesq $descricao_pesq'$atributos_craft onclick='seleciona_item($id_item)' onmouseover='toolTip(\"$nome_item\", \"$descricao\", \"$nome_interno\", $cor_item)' onmouseout='toolTip()'>";
                        echo "<img class='icon_item'$atributo_legado loading='lazy' decoding='async' src='../img/itens/$geracao/$tipo_item/$nome_icon'>";
                        echo "</div>";
                    } else {

                        echo "<div class='slot_item oculto'$atributos_craft onclick='seleciona_item($id_item)' onmouseover='toolTip(\"$nome_item\", \"$descricao\", \"$nome_interno\", $cor_item)' onmouseout='toolTip()'>";

                        echo "<img class='icon_item'$atributo_legado loading='lazy' decoding='async' src='../img/itens/$geracao/$tipo_item/$nome_icon'>";
                        echo "</div>";
                    }
                } else { // Requisição proveniente da máquina do tempo
                    if ($oculto_invt !== "oculto") {
                        echo "<div onclick='expande_sprite(this.querySelector(\"img\").getAttribute(\"src\"))' class='$slot_item $tipo_item $versao_add $nome_interno $coletavel $nome_pesq $descricao_pesq' onmouseover='toolTip(\"$nome_item\", \"$descricao\", \"$nome_interno\", $cor_item)' onmouseout='toolTip()'>";
                        echo "<img class='icon_item'$atributo_legado loading='lazy' decoding='async' src='../img/itens/$geracao/$tipo_item/$nome_icon'>";
                        echo "</div>";
                    } else {

                        echo "<div onclick='expande_sprite(this.querySelector(\"img\").getAttribute(\"src\"))' class='$slot_item oculto' onmouseover='toolTip(\"$nome_item\", \"$descricao\", \"$nome_interno\", $cor_item)' onmouseout='toolTip()'>";

                        echo "<img class='icon_item'$atributo_legado loading='lazy' decoding='async' src='../img/itens/$geracao/$tipo_item/$nome_icon'>";
                        echo "</div>";
                    }
                }
            } ?>
                <div id="complementa_slots"></div>
                </div>
            </div>
    </div>
</div>