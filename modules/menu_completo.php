<?php include_once "../php/conexao_obsoleta.php"; ?>

<div id="menu_completo">
    <img id="menu" src="#">

    <?php for($i = 0; $i < 11; $i++){
        echo "<div id='item_$i' onclick='categoria($i, 0)'></div>";
    } 

    if($local_requisicao == 1){ // Apenas visivel na tela inicial, prancheta de inserção de novos itens ?>
        <div id="item_11" onclick="clique('prancheta')"></div>
    <?php } 
    
    if($versao_jogo > 2) { ?>
        <img id="img_construcao" class="aba_menu Construcao" src="#">
        <img id="img_decorativos" class="aba_menu Decorativos" src="#">
        <img id="img_redstone" class="aba_menu Redstone" src="#">
        <img id="img_transportes" class="aba_menu Transportes" src="#">
        <img id="img_diversos" class="aba_menu Diversos" src="#">
        <img id="img_alimentos" class="aba_menu Alimentos" src="#">
        <img id="img_ferramentas" class="aba_menu Ferramentas" src="#">
        <img id="img_combate" class="aba_menu Combate" src="#">
        <img id="img_pocoes" class="aba_menu Pocoes" src="#">
        
        <div onclick="filtragem_automatica('off')" onmouseover="toolTip('Mostrar itens sem versão informada ou sem nome interno')" onmouseout="toolTip()">
            <img id="img_configs_2" class="aba_menu opcoes_laterais" src="../IMG/Interface/mascara_configs.png">
            <img id="img_configs" class="aba_menu opcoes_laterais Pesquisa" src="../IMG/Interface/aba_configs.png">
        </div>
        
        <div onclick="filtragem_automatica('não_coletável')" onmouseover="toolTip('Mostrar itens que não são coletáveis no sobrevivência')" onmouseout="toolTip()">
            <img id="img_coletaveis_2" class="aba_menu opcoes_laterais" src="../IMG/Interface/mascara_nao_coletaveis.png">
            <img id="img_coletaveis" class="aba_menu opcoes_laterais Pesquisa" src="../IMG/Interface/aba_nao_coletaveis.png">
        </div>

        <div onclick="lista_versoes()" onmouseover="toolTip('Filtrar por versões')" onmouseout="toolTip()">
            <img id="img_versoes_2" class="aba_menu" src="../IMG/Interface/mascara_atts.png">
            <img id="img_versoes" class="aba_menu opcoes_laterais Pesquisa" src="../IMG/Interface/aba_atts.png">
        </div>
        
        <div onclick="filtragem_automatica('genéricos')" onmouseover="toolTip('Itens genéricos')" onmouseout="toolTip()">
            <img id="img_genericos_2" class="aba_menu opcoes_laterais" src="../IMG/Interface/mascara_generic.png">
            <img id="img_genericos" class="aba_menu opcoes_laterais Pesquisa" src="../IMG/Interface/aba_generic.png">
        </div>

        <img id="img_especiais" class="aba_menu Especiais" src="#">
        <img id="img_pesquisa" class="aba_menu Pesquisa" src="#">
        
        <img id="img_prancheta" class="aba_menu Prancheta" src="../IMG/Interface/mascara_prancheta.png"> 
        
        <input class="Pesquisa" id="barra_pesquisa_input" type="text" onkeyup="filtra_pesquisa()" />
    <?php } ?>

    <span id="titulo_aba"></span>

    <div id="barra_rolagem">
        <div id="barra_scroll" src="../IMG/Interface/scroll.png" onmouseover="gerencia_scroll(0)" onmouseout="gerencia_scroll(1)"></div>
        <img id="barra_scroll_block" src="#">
    </div>
    
    <div id="lista_itens">

        <?php if($local_requisicao != 3) { ?>
            <div id="listagem" onscroll="scrollSincronizado('listagem', 'barra_scroll')">
        <?php } else { ?>
            <div id="listagem" onscroll="scrollSincronizado('listagem', 'barra_scroll', <?php echo $versao_jogo; ?>)">
        <?php }

        while($dados = $executa->fetch_assoc()){

            $apelido = null;
            $converte = null;
            $descricao_pesq = null;
            $oculto_invt = null;
            $geracao = "new";

            $id_item = $dados["id_item"];
            $nome_icon = $dados["nome_icon"];
            $tipo_item = $dados["abamenu"];
            $nome_item = $dados["nome"];
            $coletavel = $dados["coletavelSurvival"];
            $nome_interno = $dados["nome_interno"];
            $empilhavel = $dados["empilhavel"];
            $versao_add = $dados["versao_adicionada"];
            $renovavel = $dados["renovavel"];
            $oculto_invt = $dados["oculto_invt"];
            $programmer_art = $dados["programmer_art"];

            $descricao = "[&1". $tipo_item;

            $descricao = $descricao ." ". $dados["descricao"];

            $descricao_pes = str_replace("[&r", "", $descricao);

            if(!$nome_interno)
                $nome_interno = "off";

            if($programmer_art == 1 && $graphics)
                $geracao = "classic";

            if($versao_add == null)
                $versao_add = "off";
            else
                $versao_add = "1.". $versao_add;

            if($oculto_invt == 1)
                $oculto_invt = "Oculto";

            if(!$renovavel)
                $renovavel = "não_renovável";
            else
                $renovavel = "renovável";
            
            if($empilhavel != 0)
                $empilhavel = "empilhável";
            else
                $empilhavel = null;

            if($coletavel != 0)
                $coletavel = "coletável";
            else
                $coletavel = "não_coletável";

            for($i = 0; $i < strlen($nome_item); $i++){
                $converte = $converte." ";
                
                for($x = 0; $x <= $i; $x++){
                    $converte = $converte."".$nome_item[$x];
                }
            }

            for($i = 0; $i < strlen($descricao_pes); $i++){
                $descricao_pesq = $descricao_pesq." ";
                
                for($x = 0; $x <= $i; $x++){
                    $descricao_pesq = $descricao_pesq."".$descricao_pes[$x];
                }
            }

            $cor_item = 0;

            $verificar_item = "SELECT * FROM cor_item WHERE id_item = $id_item";
            $executa_item = $conexao->query($verificar_item);

            if($executa_item->num_rows > 0){
                $dados2 = $executa_item->fetch_assoc();

                $cor_item = $dados2["tipo_item"];
            }
            
            $auto_completa = strtolower($converte);
            $descricao_pesq = strtolower($descricao_pesq);

            $slot_item = "slot_item";

            if($local_requisicao == 3 && $versao_add == "1.".$versao_jogo)
                $slot_item = "slot_item_add";

            for($i = 0; $i < 20; $i++){ // Elimina todos os números de versão da descrição
                $descricao_pesq = str_replace("1.".$i, "", $descricao_pesq);
            }

            if($local_requisicao == 1){ // Requisição proveniente da página inicial
                if($tipo_item != "Generico" && $oculto_invt != "Oculto"){
                    echo "<div class='slot_item $tipo_item $versao_add $nome_interno $renovavel $empilhavel $coletavel $auto_completa $descricao_pesq' onclick='exibe_detalhes_item($id_item)' onmouseover='toolTip(\"$nome_item\", \"$descricao\", \"$nome_interno\", $cor_item)' onmouseout='toolTip()'>";
                        echo "<img class='icon_item' src='../IMG/Itens/$geracao/$tipo_item/$nome_icon'>";
                    echo "</div>";
                }else{
                    if($oculto_invt != "Oculto"){
                        echo "<div class='slot_item $tipo_item' onclick='exibe_detalhes_item($id_item)' onmouseover='toolTip(\"$nome_item\", \"$descricao\", \"$nome_interno\", $cor_item)' onmouseout='toolTip()'>";
                    }else{
                        echo "<div class='slot_item oculto' onclick='exibe_detalhes_item($id_item)' onmouseover='toolTip(\"$nome_item\", \"$descricao\", \"$nome_interno\", $cor_item)' onmouseout='toolTip()'>";
                    }
                        echo "<img class='icon_item' src='../IMG/Itens/$geracao/$tipo_item/$nome_icon'>";
                    echo "</div>";
                }
            }else if($local_requisicao == 2){ // Requisição proveniente da página de receitas
                if($tipo_item != "Generico" && $oculto_invt != "Oculto"){
                    echo "<div class='slot_item $tipo_item $nome_interno $renovavel $versao_add $auto_completa $descricao_pesq' onclick='seleciona_item($id_item)' onmouseover='toolTip(\"$nome_item\", \"$descricao\", \"$nome_interno\", $cor_item)' onmouseout='toolTip()'>";
                        echo "<img class='icon_item' src='../IMG/Itens/$geracao/$tipo_item/$nome_icon'>";
                    echo "</div>";
                }else{
                    if($oculto_invt != "Oculto"){
                        
                        echo "<div class='slot_item $tipo_item' onclick='seleciona_item($id_item)' onmouseover='toolTip(\"$nome_item\", \"$descricao\", \"$nome_interno\", $cor_item)' onmouseout='toolTip()'>";
                    }else{
                        echo "<div class='slot_item oculto' onclick='seleciona_item($id_item)' onmouseover='toolTip(\"$nome_item\", \"$descricao\", \"$nome_interno\", $cor_item)' onmouseout='toolTip()'>";
                    }
                        echo "<img class='icon_item' src='../IMG/Itens/$geracao/$tipo_item/$nome_icon'>";
                    echo "</div>";
                }
            }else{ // Requisição proveniente da máquina do tempo
                if($tipo_item != "Generico" && $oculto_invt != "Oculto"){
                    echo "<div onclick='expande_sprite(`../IMG/Itens/$geracao/$tipo_item/$nome_icon`)' class='$slot_item $tipo_item $versao_add $nome_interno $renovavel $empilhavel $coletavel $auto_completa $descricao_pesq' onmouseover='toolTip(\"$nome_item\", \"$descricao\", \"$nome_interno\", $cor_item)' onmouseout='toolTip()'>";
                        echo "<img class='icon_item' src='../IMG/Itens/$geracao/$tipo_item/$nome_icon'>";
                    echo "</div>";
                }else{
                    if($oculto_invt != "Oculto"){
                        echo "<div onclick='expande_sprite(`../IMG/Itens/$geracao/$tipo_item/$nome_icon`)' class='$slot_item $tipo_item' onmouseover='toolTip(\"$nome_item\", \"$descricao\", \"$nome_interno\", $cor_item)' onmouseout='toolTip()'>";
                    }else{
                        echo "<div onclick='expande_sprite(`../IMG/Itens/$geracao/$tipo_item/$nome_icon`)' class='$slot_item oculto' onmouseover='toolTip(\"$nome_item\", \"$descricao\", \"$nome_interno\", $cor_item)' onmouseout='toolTip()'>";
                    }
                        echo "<img class='icon_item' src='../IMG/Itens/$geracao/$tipo_item/$nome_icon'>";
                    echo "</div>";
                }
            }
        } ?>
            <div id="complementa_slots"></div>
        </div>
    </div>
</div>