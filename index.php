<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <title>Inventário</title>
    <link rel="shortcut icon" href="IMG/Itens/Construcao/bloco_grama.png">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/anima.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>

    <?php include_once "PHP/conexao_obsoleta.php"; ?>
</head>
<body>

    <div id="lista_versoes" style="display: none">
        <?php

            $versoes = ["1.0", "1.1", "1.2", "1.3", "1.4", "1.5", "1.6", "1.7", "1.8", "1.9", "1.101", "1.11", "1.12", "1.13", "1.14", "1.15", "1.16", "1.17", "1.18", ""];

            for($i = 0; $i < sizeof($versoes) - 1; $i += 2){
                $x = $i + 1;

                if($versoes[$i] != "1.101")
                    echo "-> <a href='#' onclick='categoria($versoes[$i], 2)'>$versoes[$i]</a> |";
                else
                    echo "-> <a href='#' onclick='categoria(1.101, 2)'>1.10</a> |";

                echo " <a href='#' onclick='categoria($versoes[$x], 2)'>$versoes[$x]</a><br>";
            }
        ?>
    </div>
    
    <div id="estatisticas_inventario">
        <center><h2>Estatísticas</h2></center>
    
        <?php
        $verificar = "SELECT * from item";
        $executa = $conexao->query($verificar);

        echo "<p id='versao_referencia' class='estat'>Itens Adicionados: <span id='num_referencia'></span></p>";
        
        echo "<br><p class='estat'>Itens Registrados: ";
        echo $executa->num_rows. "</p>";

        $verificar = "SELECT * from item where coletavelsurvival = 1";
        $executa = $conexao->query($verificar);

        echo "<br><p class='estat'> Coletáveis: ";
        echo $executa->num_rows. "</p>";

        $verificar = "SELECT * from item where renovavel = 1";
        $executa = $conexao->query($verificar);

        echo "<br><p class='estat'>Renováveis: ";
        echo $executa->num_rows."</p>";
        
        $verificar = "SELECT * from item where empilhavel != 0";
        $executa = $conexao->query($verificar);

        echo "<br><p class='estat'>Empilháveis: ";
        echo $executa->num_rows."</p>";
        
        $verificar = "SELECT * from item where empilhavel != 0 and coletavelsurvival = 1";
        $executa = $conexao->query($verificar);

        echo "<br><p class='estat'>Coletáveis e empilhaveis: ";
        echo $executa->num_rows."</p>";

        $verificar = "SELECT * from item where coletavelsurvival = 1 and empilhavel != 0 and renovavel != 1";
        $executa = $conexao->query($verificar);

        echo "<br><p class='estat'>Coletaveis empilhaveis e não renováveis: ";
        echo $executa->num_rows."</p>";

        $verificar = "SELECT * from item where empilhavel like 0";
        $executa = $conexao->query($verificar);

        echo "<br><p class='estat'>Não empilhaveis: ";
        echo $executa->num_rows."</p>";

        // $verificar = "SELECT * from item where nome like '%cobre%'";
        // $verificar = "SELECT * from item order by posicao_item";
        $verificar = "SELECT * from item order by id_item desc";
        $executa = $conexao->query($verificar); ?>

    </div>
            
    <a href="PHP/cria_json.php">Criar json</a>
            
    <form id="prancheta_add" method="post" action="PHP/item_registrar.php" enctype="multipart/form-data">
        <input class="input_prancheta" id="barra_nome" type="text" placeholder="Nome" name="nome" required>

        <input class="input_prancheta" id="barra_nome_interno" type="text" placeholder="Nome interno" name="nome_interno">

        <div id="selects">
            <select name="abamenu" style="width: 505px;">

            <?php
                $categorias = ["Construcao", "Decorativos", "Redstone", "Transportes", "Diversos", "Alimentos", "Ferramentas", "Combate", "Pocoes"];
                $categorias_exib = ["Blocos de construção", "Blocos decorativos", "Redstone", "Transportes", "Diversos", "Alimentos", "Ferramentas", "Combate", "Poções"];
            
                for($i = 0; $i < sizeof($categorias); $i++){
                    echo "<option value='$categorias[$i]'>$categorias_exib[$i]</option>";
                } ?>
            </select><br><br>

            <select name="empilhavel" style="width: 505px;">
                <option value="64">64x</option>
                <option value="16">16x</option>
                <option value="0">Não</option>            
            </select><br><br>

            <select name="versao" style="width: 505px;">
                <option value="outro">Outro</option>
                <?php

                $versoes[10] = "1.10";

                for($i = sizeof($versoes); $i >= 0; $i--){
                    echo "<option value='$versoes[$i]'>$versoes[$i]</option>";
                } ?>
            </select>
        </div>
        
        <div id="checkboxes">
            <input class="input_check" type="checkbox" name="coletavelsurvival" checked> <img class="icon_check" src="IMG/Interface/coracao.png" title="Coletável no sobrevivência"><br>

            <input class="input_check" type="checkbox" name="renovavel" checked> <img class="icon_check" src="IMG/Itens/Decorativos/anvil.png" title="Recurso renovável">

            <input id="input_img" type="file" name="img" required accept="image/*" onchange="previewImage();">

            <img id="preview">
        </div>

        <input id="inserir_item" type="submit" value="Inserir">
    </form>

    <div id="menu_completo">
        <img id="menu" src="IMG/Interface/Menu.png">

        <div id="item_0" onclick="categoria(0, 0)"></div>
        <div id="item_1" onclick="categoria(1, 0)"></div>
        <div id="item_2" onclick="categoria(2, 0)"></div>
        <div id="item_3" onclick="categoria(3, 0)"></div>
        <div id="item_4" onclick="categoria(4, 0)"></div>
        <div id="item_5" onclick="categoria(5, 0)"></div>
        <div id="item_6" onclick="categoria(6, 0)"></div>
        <div id="item_7" onclick="categoria(7, 0)"></div>
        <div id="item_8" onclick="categoria(8, 0)"></div>
        <div id="item_9" onclick="categoria(9, 0)"></div>
        <div id="item_10" onclick="categoria(10, 0)"></div>

        <div id="item_11" onclick="clique('prancheta')"></div>

        <img id="img_construcao" class="aba_menu Construcao" src="IMG/Interface/aba_construcao.png">
        <img id="img_decorativos" class="aba_menu Decorativos" src="IMG/Interface/aba_decorativos.png">
        <img id="img_redstone" class="aba_menu Redstone" src="IMG/Interface/aba_redstone.png">
        <img id="img_transportes" class="aba_menu Transportes" src="IMG/Interface/aba_transportes.png">
        <img id="img_diversos" class="aba_menu Diversos" src="IMG/Interface/aba_diversos.png">
        <img id="img_alimentos" class="aba_menu Alimentos" src="IMG/Interface/aba_alimentos.png">
        <img id="img_ferramentas" class="aba_menu Ferramentas" src="IMG/Interface/aba_ferramentas.png">
        <img id="img_combate" class="aba_menu Combate" src="IMG/Interface/aba_combate.png">
        <img id="img_pocoes" class="aba_menu Pocoes" src="IMG/Interface/aba_pocoes.png">
        
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

        <img id="img_especiais" class="aba_menu Especiais" src="IMG/Interface/aba_especiais.png">
        <img id="img_pesquisa" class="aba_menu Pesquisa" src="IMG/Interface/aba_pesquisa.png">
        
        <img id="img_prancheta" class="aba_menu Prancheta" src="IMG/Interface/mascara_prancheta.png"> 
        
        <input class="Pesquisa" id="barra_pesquisa_input" type="text" onkeyup="filtra_pesquisa()" />
        
        <span id="titulo_aba"></span>

        <div id="barra_rolagem">
            <div id="barra_scroll" src="IMG/Interface/scroll.png" onmouseover="gerencia_scroll(0)" onmouseout="gerencia_scroll(1)"></div>
            <img id="barra_scroll_block" src="IMG/Interface/scroll_bloqueado.png">
        </div>
        
        <div id="lista_itens">
            <div id="listagem" onscroll="scrollSincronizado('listagem', 'barra_scroll')">
            <?php 
            while($dados = $executa->fetch_assoc()){
                
                $id_item = $dados["id_item"];
                $nome_img = $dados["img"];
                $tipo_item = $dados["abamenu"];
                $nome_item = $dados["nome"];
                $coletavel = $dados["coletavelSurvival"];
                $nome_interno = $dados["nome_interno"];
                $empilhavel = $dados["empilhavel"];
                $versao_add = $dados["versao_adicionada"];
                $renovavel = $dados["renovavel"];

                if(!$nome_interno)
                    $nome_interno = "off";
                else
                    $nome_interno = "";

                if(!$versao_add || $versao_add == "outro")
                    $versao_add = "off";
                
                if(!$renovavel)
                    $renovavel = "não renovável";
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

                $apelido = null;
                $converte = null;

                for($i = 0; $i < strlen($nome_item); $i++){
                    $converte = $converte." ";
                    
                    for($x = 0; $x <= $i; $x++){
                        $converte = $converte."".$nome_item[$x];
                    }
                }

                $auto_completa = strtolower($converte);

                echo "<div id='slot_item' class='$tipo_item $versao_add $nome_interno $renovavel $empilhavel $coletavel $auto_completa' onclick='exibe_detalhes_item($id_item)' onmouseover='toolTip($id_item)' onmouseout='toolTip()'>";
                    echo "<img class='icon_item' src='IMG/Itens/$tipo_item/$nome_img'>";
                echo "</div>";
            } ?>

                <div id="complementa_slots"></div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
    <script src="JS/engine.js"></script>
    <script src="JS/tooltip.js"></script>

    <script language="javascript"> main(); </script>

    <script type="text/javascript">

        categoria(10, 0);
        clique("prancheta", 0);

        document.addEventListener("onKeyDown", clique());
    </script>

    <!-- <script src="JSON/dados.json"></script>
    <script type="text/javascript">

    fetch("JSON/dados.json")
        .then(response => response.json())
        .then(json => { 

            // Listando os itens do JSON para o usuário
            for(var i = 0; i < json.length; i++){

                if(typeof json[i].id_item !== "undefined"){

                    if(json[i].nome_interno)
                        var nome_interno = "off";
                    else
                        var nome_interno = "";

                    if(!json[i].versao_add || json[i].versao_add === "outro")
                        var versao_add = "off";
                    else
                        var versao_add = json[i].versao_add;
                        
                    if(!json[i].renovavel)
                        var renovavel = "não renovável";
                    else
                        var renovavel = "renovável";
                    
                    if(json[i].empilhavel != 0)
                        var empilhavel = "empilhável";
                    else
                        var empilhavel = null;

                    if(json[i].coletavel != 0)
                        var coletavel = "coletável";
                    else
                        var coletavel = "não_coletável";

                    $("#listagem").append("<div id='slot_item' class='"+ json[i].tipo_item +" "+ versao_add +" "+ nome_interno +" "+ renovavel +" "+ empilhavel +" "+ coletavel +"' onclick='exibe_detalhes_item("+ json[i].id_item +")' onmouseover='toolTip("+ json[i].id_item +")' onmouseout='toolTip()'>");
                    $("#listagem").append("<img class='icon_item' src='IMG/Itens/"+ json[i].tipo_item +"/"+ json[i].nome_img + "'>");
                    $("#listagem").append("</div>");
                }
            }
        });
    </script> -->
</body>
</html>