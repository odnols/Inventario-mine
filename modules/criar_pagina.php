<?php include_once "../PHP/conexao_obsoleta.php"; 

ob_start(); ?>

<html lang="pt">
<head>
    <meta charset="utf-8">
    <title>Inventário</title>
    <link rel="shortcut icon" href="IMG/Itens/new/Construcao/grass_block.png">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/anima.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/tooltip.css">

    <script src="JS/jquery.min.js"></script>
    <script src="JS/jquery-1.11.3.min.js"></script>
</head>
<body onload="sincroniza_tema(undefined, 0)">

    <div id="filtro_colorido"></div>
    <div id="lista_versoes" style="display: none">
        <script>
            for(let i = 0; i < 19; i += 2){
                let x = i + 1;

                document.write(`-> <a href='#' onclick='categoria(\"1.${i}\", 2)'>1.${i}</a> |`);
                document.write(` <a href='#' onclick='categoria(\"1.${x}\", 2)'>1.${x}</a><br>`);
            }
        </script>
    </div>

    <div id="estatisticas_inventario">
        <img id="prancheta" src="#">

        <div onclick="filtragem_automatica('oculto')" onmouseover="toolTip('Itens ocultos')" onmouseout="toolTip()">
            <img id="img_ocultos_2" class="aba_menu opcoes_baixo" src="IMG/Interface/mascara_oculto.png">
            <img id="img_ocultos" class="aba_menu opcoes_baixo Pesquisa" src="#">
        </div>
        
        <div id="text_estatsc">
            <center><h2 class="cor_textos">Estatísticas</h2></center>
            
            <p id='versao_referencia' class='estat cor_textos'>Itens Adicionados na versão <span id='num_referencia'></span></p>
            <br><p class='estat cor_textos'>Itens Registrados: <span id='qtd_itens_inventario'>Carregando...</span></p>
            <br><p class='estat cor_textos'>Coletáveis: <span id='qtd_itens_inventario_colet'>Carregando...</span></p>
            <br><p class='estat cor_textos'>Fabricáveis: <span id='qtd_itens_fabricaveis'>Carregando...</span></p>
            <br><p class='estat cor_textos'>Renováveis: <span id='qtd_itens_inventario_renov'>Carregando...</span></p>
            <br><p class='estat cor_textos'>Empilháveis: <span id='qtd_itens_inventario_empil'>Carregando...</span></p>
            <br><p class='estat cor_textos'>Coletáveis e empilháveis: <span id='qtd_itens_inventario_colet_empil'>Carregando...</span></p>
            <br><p class='estat cor_textos'>Coletáveis empilháveis e não renováveis: <span id='qtd_itens_inventario_colet_empil_n_renov'>Carregando...</span></p>
            <br><p class='estat cor_textos'>Não empilháveis: <span id='qtd_itens_inventario_n_empil'>Carregando...</span></p>
        </div>
    </div>

    <div id="menu_user">
        <a class="bttn_frrm" id="bttn_troca_tema" href="#" onclick="troca_tema(undefined, 0)" onmouseover="toolTip('Altere entre o modo escuro e claro')" onmouseout="toolTip()"><span id="icone_tema">☀️</span></a>
    </div>

    <!-- Menu interativo -->
    <div id="menu_completo">
        <img id="menu" src="#">

        <script>
            for(let i = 0; i < 11; i++){
                document.write(`<div id='item_${i}' onclick='categoria(${i}, 0)'></div>`);
            }
        </script>
        
        <div id="item_11" onclick="clique('prancheta')"></div>

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
            <img id="img_configs_2" class="aba_menu opcoes_laterais" src="IMG/Interface/mascara_configs.png">
            <img id="img_configs" class="aba_menu opcoes_laterais Pesquisa" src="IMG/Interface/aba_configs.png">
        </div>
        
        <div onclick="filtragem_automatica('não_coletável')" onmouseover="toolTip('Mostrar itens que não são coletáveis no sobrevivência')" onmouseout="toolTip()">
            <img id="img_coletaveis_2" class="aba_menu opcoes_laterais" src="IMG/Interface/mascara_nao_coletaveis.png">
            <img id="img_coletaveis" class="aba_menu opcoes_laterais Pesquisa" src="IMG/Interface/aba_nao_coletaveis.png">
        </div>

        <div onclick="lista_versoes()" onmouseover="toolTip('Filtrar por versões')" onmouseout="toolTip()">
            <img id="img_versoes_2" class="aba_menu" src="IMG/Interface/mascara_atts.png">
            <img id="img_versoes" class="aba_menu opcoes_laterais Pesquisa" src="IMG/Interface/aba_atts.png">
        </div>
        
        <div onclick="filtragem_automatica('genéricos')" onmouseover="toolTip('Itens genéricos')" onmouseout="toolTip()">
            <img id="img_genericos_2" class="aba_menu opcoes_laterais" src="IMG/Interface/mascara_generic.png">
            <img id="img_genericos" class="aba_menu opcoes_laterais Pesquisa" src="IMG/Interface/aba_generic.png">
        </div>

        <img id="img_especiais" class="aba_menu Especiais" src="#">
        <img id="img_pesquisa" class="aba_menu Pesquisa" src="#">
        
        <img id="img_prancheta" class="aba_menu Prancheta" src="IMG/Interface/mascara_prancheta.png"> 
        
        <input class="Pesquisa" id="barra_pesquisa_input" type="text" onkeyup="filtra_pesquisa()" />
        
        <span id="titulo_aba"></span>

        <div id="barra_rolagem">
            <div id="barra_scroll" src="IMG/Interface/scroll.png" onmouseover="gerencia_scroll(0)" onmouseout="gerencia_scroll(1)"></div>
            <img id="barra_scroll_block" src="#">
        </div>
        
        <div id="minetip-tooltip">
            <span id="nome_item_minetip"></span><br>
            <span id="descricao_item_minetip"></span><br>
            <span id="nome_interno_minetip"></span>
        </div>

        <div id="lista_itens">
            <div id="listagem" onscroll="scrollSincronizado('listagem', 'barra_scroll')">

            <?php 
            $verificar = "SELECT * FROM item ORDER BY id_item DESC";
            $executa = $conexao->query($verificar);
            
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

                // if($programmer_art == 1)
                    // $geracao = "classic";

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

                for($i = 0; $i < 20; $i++){ // Elimina todos os números de versão da descrição
                    $descricao_pesq = str_replace("1.".$i, "", $descricao_pesq);
                }
                
                if($tipo_item != "Generico" && $oculto_invt != "Oculto"){
                    echo "<div class='slot_item $tipo_item $versao_add $nome_interno $renovavel $empilhavel $coletavel $auto_completa $descricao_pesq' onclick='exibe_item($id_item)' onmouseover='toolTip(\"$nome_item\", \"$descricao\", \"$nome_interno\", $cor_item)' onmouseout='toolTip()'>";
                        echo "<img class='icon_item' src='IMG/Itens/$geracao/$tipo_item/$nome_icon'>";
                    echo "</div>";
                }else{
                    if($oculto_invt != "Oculto"){
                        echo "<div class='slot_item $tipo_item' onclick='exibe_item($id_item)' onmouseover='toolTip(\"$nome_item\", \"$descricao\", \"$nome_interno\", $cor_item)' onmouseout='toolTip()'>";
                    }else{
                        echo "<div class='slot_item oculto' onclick='exibe_item($id_item)' onmouseover='toolTip(\"$nome_item\", \"$descricao\", \"$nome_interno\", $cor_item)' onmouseout='toolTip()'>";
                    }
                        echo "<img class='icon_item' src='IMG/Itens/$geracao/$tipo_item/$nome_icon'>";
                    echo "</div>";
                }
            } ?>
                <div id="complementa_slots"></div>
            </div>
        </div>
    </div>

    <script src="JS/jquery-3.4.1.js"></script>
    <script src="JS/engine.js"></script>
    <script type="text/javascript">

        (function (){
            setTimeout(() => {
                document.getElementById("qtd_itens_inventario").innerHTML = 1417;
                document.getElementById("qtd_itens_inventario_colet").innerHTML = 1319;
                document.getElementById("qtd_itens_fabricaveis").innerHTML = 778;
                document.getElementById("qtd_itens_inventario_renov").innerHTML = 1251;
                document.getElementById("qtd_itens_inventario_empil").innerHTML = 1007;
                document.getElementById("qtd_itens_inventario_colet_empil").innerHTML = 914;
                document.getElementById("qtd_itens_inventario_colet_empil_n_renov").innerHTML = 57;
                document.getElementById("qtd_itens_inventario_n_empil").innerHTML = 410;
            }, 1000);
        })();

        categoria(0, 0);
        document.addEventListener("onKeyDown", clique());
    </script>
</body>
</html>

<?php // Recolhendo os dados que seriam mostrados e salvando num outro arquivo
$gerado = ob_get_contents();
ob_end_clean();

file_put_contents('../index.html', $gerado);
header("Location: ../pages/index.php") ?>