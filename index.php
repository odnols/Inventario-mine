<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <title>Inventário</title>
    <link rel="shortcut icon" href="IMG/Itens/new/Construcao/bloco_grama.png">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/anima.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/tooltip.css">

    <script src="JS/jquery.min.js"></script>
    <script src="JS/jquery-1.11.3.min.js"></script>
    
    <?php include_once "PHP/conexao_obsoleta.php"; ?>
</head>
<body onload="sincroniza_tema()">

    <div id="filtro_colorido"></div>
    <div id="lista_versoes" style="display: none">
        <?php
            for($i = 0; $i < 18; $i += 2){
                $x = $i + 1;

                echo "-> <a href='#' onclick='categoria(\"1.$i\", 2)'>1.$i</a> |";
                echo " <a href='#' onclick='categoria(\"1.$x\", 2)'>1.$x</a><br>";
            }
        ?>
    </div>
    
    <div id="estatisticas_inventario">
        <img id="prancheta" src="#">

        <div onclick="filtragem_automatica('oculto')" onmouseover="toolTip('Itens ocultos')" onmouseout="toolTip()">
            <img id="img_ocultos_2" class="aba_menu opcoes_baixo" src="IMG/Interface/mascara_oculto.png">
            <img id="img_ocultos" class="aba_menu opcoes_baixo Pesquisa" src="#">
        </div>
        
        <div id="text_estatsc">
            <center><h2 class="cor_textos">Estatísticas</h2></center>
        
            <?php

            $graphics = true;

            if(!isset($_GET["dg"]))
                $graphics = false;    
            
            $verificar = "SELECT * from item where abamenu != 'Generico'";
            $executa = $conexao->query($verificar);

            echo "<p id='versao_referencia' class='estat cor_textos'>Itens Adicionados na versão <span id='num_referencia'></span></p>";
            
            echo "<br><p class='estat cor_textos'>Itens Registrados: ";
            echo $executa->num_rows ."</p>";

            $verificar = "SELECT * from item where coletavelsurvival = 1 and abamenu != 'Generico'";
            $executa = $conexao->query($verificar);

            echo "<br><p class='estat cor_textos'> Coletáveis: ";
            echo $executa->num_rows ."</p>";

            $verificar = "SELECT * from item where renovavel = 1 and abamenu != 'Generico'";
            $executa = $conexao->query($verificar);

            echo "<br><p class='estat cor_textos'>Renováveis: ";
            echo $executa->num_rows ."</p>";
            
            $verificar = "SELECT * from item where empilhavel != 0 and abamenu != 'Generico'";
            $executa = $conexao->query($verificar);

            echo "<br><p class='estat cor_textos'>Empilháveis: ";
            echo $executa->num_rows ."</p>";
            
            $verificar = "SELECT * from item where empilhavel != 0 and coletavelsurvival = 1 and abamenu != 'Generico'";
            $executa = $conexao->query($verificar);

            echo "<br><p class='estat cor_textos'>Coletáveis e empilháveis: ";
            echo $executa->num_rows ."</p>";

            $verificar = "SELECT * from item where coletavelsurvival = 1 and empilhavel != 0 and renovavel != 1 and abamenu != 'Generico'";
            $executa = $conexao->query($verificar);

            echo "<br><p class='estat cor_textos'>Coletáveis empilháveis e não renováveis: ";
            echo $executa->num_rows ."</p>";

            $verificar = "SELECT * from item where empilhavel like 0 and abamenu != 'Generico'";
            $executa = $conexao->query($verificar);

            echo "<br><p class='estat cor_textos'>Não empilháveis: ";
            echo $executa->num_rows ."</p>";
            
            $verificar = "SELECT * from item order by id_item desc";
            $executa = $conexao->query($verificar); ?>
        </div>
    </div>
    
    <div id="botoes_ferramentas">
        <?php if($executa->num_rows > 0) { ?> <!-- Só libera a utilização se houver dados -->
        <a class="bttn_frrm" href="PHP/exportar_dados.php">Exportar Dados</a> <?php } ?>
        <a class="bttn_frrm" id="button_importar_dados" href="PHP/importar_dados.php" onclick="importar_dados()">Importar Dados</a>
        
        <?php if($executa->num_rows > 0) { ?> <!-- Só libera a utilização se houver dados -->
        <a class="bttn_frrm" id="button_apagar_dados" href="PHP/limpar_dados.php">Limpar Dados</a>
        <a class="bttn_frrm" href="JSON/substituir.php">Traduzir itens</a>   
        <?php } ?>
    </div>

    <div id="menu_user">
        <?php if(!isset($_GET["dg"])) { ?>
        <a class="bttn_frrm" href="index.php?dg=true" onclick="#" onmouseover="toolTip('Os gráficos originais do Minecraft')" onmouseout="toolTip()">Programmer Art</a> <?php } else { ?>
        <a class="bttn_frrm" href="index.php" onclick="#" >Gráficos padrões</a> <?php } ?>

        <a class="bttn_frrm" href="visualizacao.php" onmouseover="toolTip('Uma volta ao passado...')" onmouseout="toolTip()">Máquina do tempo</a>
        <a class="bttn_frrm" href="#" onclick="troca_tema()" onmouseover="toolTip('Altere entre o modo escuro e claro')" onmouseout="toolTip()">Tema</a>
    </div>

    <!-- Importar célula de dados para o banco -->
    <form id="selecionar_celula_dados" method="post" action="PHP/importar_dados.php" enctype="multipart/form-data">

        <h2>Selecione um arquivo JSON apropriado para fazer a importação de dados</h2>
        <input type="file" name="arquivo" required accept=".json"><br><br>

        <input type="submit" value="Importar">
    </form>

    <!-- Adicionar item -->
    <form id="prancheta_add" method="post" action="PHP/item_registrar.php" enctype="multipart/form-data">
        <input class="input_prancheta" id="barra_nome" type="text" placeholder="Nome" name="nome" required>
        
        <input class="input_prancheta" id="barra_nome_interno_pr" type="text" placeholder="Nome interno" name="nome_interno">

        <div id="selects">
            <select name="abamenu" style="width: 505px;" onmouseover="toolTip('A Categoria do item')" onmouseout="toolTip()">

            <?php
                $categorias = ["Construcao", "Decorativos", "Redstone", "Transportes", "Diversos", "Alimentos", "Ferramentas", "Combate", "Pocoes", "Especiais", "Generico"];
                $categorias_exib = ["Blocos de construção", "Blocos decorativos", "Redstone", "Transportes", "Diversos", "Alimentos", "Ferramentas", "Combate", "Poções", "Especiais", "Genérico"];
            
                for($i = 0; $i < sizeof($categorias); $i++){
                    echo "<option value='$categorias[$i]'>$categorias_exib[$i]</option>";
                } ?>
            </select><br><br>

            <select name="empilhavel" style="width: 505px;" onmouseover="toolTip('Quantos itens se juntam')" onmouseout="toolTip()">
                <option value="64">64x</option>
                <option value="16">16x</option>
                <option value="0">Não</option>            
            </select><br><br>

            <select name="versao" style="width: 505px;" onmouseover="toolTip('A Versão que o item foi adicionado')" onmouseout="toolTip()">
                <option value="outro">Outro</option>
                <?php

                for($i = 18; $i >= 0; $i--){
                    echo "<option value='$i'>1.$i</option>";
                } ?>
            </select>
        </div>
        
        <div id="checkboxes">
            <input class="input_check" type="checkbox" name="coletavelsurvival" checked  onmouseover="toolTip('Coletável no sobrevivência')" onmouseout="toolTip()"> <img class="icon_check" src="IMG/Interface/coracao.png"  onmouseover="toolTip('Coletável no sobrevivência')" onmouseout="toolTip()"><br>

            <input class="input_check" type="checkbox" name="renovavel" checked onmouseover="toolTip('Recurso renovável')" onmouseout="toolTip()"> <img class="icon_check" src="IMG/Itens/new/Decorativos/anvil.png" onmouseover="toolTip('Recurso renovável')" onmouseout="toolTip()">

            <input id="input_img" type="file" name="img" required accept="image/*" onchange="previewImage(0);" onmouseover="toolTip('Foto do item')" onmouseout="toolTip()">

            <img id="preview">
        </div>

        <input id="inserir_item" type="submit" value="Inserir">
    </form>

    <!-- Menu interativo -->
    <div id="menu_completo">
        <img id="menu" src="#">

        <?php for($i = 0; $i < 11; $i++){
            echo "<div id='item_$i' onclick='categoria($i, 0)'></div>";
        } ?>

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

                $verificar_item = "SELECT * from cor_item where id_item = $id_item";
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
                    echo "<div class='slot_item $tipo_item $versao_add $nome_interno $renovavel $empilhavel $coletavel $auto_completa $descricao_pesq' onclick='exibe_detalhes_item($id_item)' onmouseover='toolTip(\"$nome_item\", \"$descricao\", \"$nome_interno\", $cor_item)' onmouseout='toolTip()'>";
                        echo "<img class='icon_item' src='IMG/Itens/$geracao/$tipo_item/$nome_icon'>";
                    echo "</div>";
                }else{
                    if($oculto_invt != "Oculto"){
                        echo "<div class='slot_item $tipo_item' onclick='exibe_detalhes_item($id_item)' onmouseover='toolTip(\"$nome_item\", \"$descricao\", \"$nome_interno\", $cor_item)' onmouseout='toolTip()'>";
                    }else{
                        echo "<div class='slot_item oculto' onclick='exibe_detalhes_item($id_item)' onmouseover='toolTip(\"$nome_item\", \"$descricao\", \"$nome_interno\", $cor_item)' onmouseout='toolTip()'>";
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

        categoria(1, 0);

        document.addEventListener("onKeyDown", clique());
    </script>
</body>
</html>