<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <title>Inventário</title>
    <link rel="shortcut icon" href="IMG/Itens/Construcao/bloco_grama.png">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/anima.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/tooltip.css">
    
    <script src="JS/engine.js"></script>
    
    <?php include_once "PHP/conexao_obsoleta.php"; ?>
</head>
<body onload="sincroniza_tema()">

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
      
    $renovavel = "";
    $coletavel_s = "";
    $oculto_invt = "";

    $busca_dados = "SELECT * from item where id_item = $id_item";
    $executa = $conexao->query($busca_dados);
    
    $dados = $executa->fetch_assoc();

    $nome_icon = $dados["nome_icon"];
    
    $nome_item = $dados["nome"];
    $nome_interno = $dados["nome_interno"];
    $aliases = $dados["aliases_nome"];

    $tipo_item = $dados["abamenu"];
    $empilhavel = $dados["empilhavel"];
    $versao_add = $dados["versao_adicionada"];
    
    $descricao_item = $dados["descricao"];

    if($versao_add == null)
        $versao_add = "Outro";
 
    if($dados["coletavelSurvival"])
        $coletavel_s = "checked";
    
    if($dados["renovavel"])
        $renovavel = "checked";

    if($dados["oculto_invt"])
        $oculto_invt = "checked";
    
    $cor_item = 0;

    $verificar_item = "SELECT * from cor_item where id_item = $id_item";
    $executa_item = $conexao->query($verificar_item);

    if($executa_item->num_rows > 0){
        $dados2 = $executa_item->fetch_assoc();

        $cor_item = $dados2["tipo_item"];
    } ?>

    <button id="btn_voltar" onclick="voltar_pag()">Voltar</button>

    <div id="bttns_navegacao">
        <?php if($id_item - 1 > 0){ ?>
            <a href="item_detalhes.php?id=<?php echo $id_item - 1 ?>"><button class="navegacao"> < </button></a>
        <?php } ?>

        <a href="item_detalhes.php?id=<?php echo $id_item + 1 ?>"><button class="navegacao"> > </button></a>
    </div>

    <?php if(strlen($nome_item) > 0) { // Verifica se existem dados para o ID ?> 

    <button id="btn_apagar" onclick="apagarItem(<?php echo $id_item ?>)">Apagar</button>

    <?php echo "<img id='img_detalhes' src='IMG/Itens/$tipo_item/$nome_icon'>"; ?>    
    
    <form id="prancheta_att" method="post" action="PHP/item_atualizar.php" enctype="multipart/form-data">

        <input type="text" name="id_item" value="<?php echo $id_item ?>" style="display: none;">
    
        <input class="input_prancheta" id="barra_nome" type="text" placeholder="Nome" name="nome" required value="<?php echo $nome_item ?>" onmouseover="toolTip('Nome do item')" onmouseout="toolTip()">

        <input class="input_prancheta" id="barra_descricao" type="text" placeholder="Descrição" name="descricao" value="<?php echo $descricao_item ?>" onmouseover="toolTip('Descrição do item')" onmouseout="toolTip()">

        <input class="input_prancheta" id="barra_nome_interno" type="text" placeholder="Nome interno" name="nome_interno" value="<?php echo $nome_interno ?>" onmouseover="toolTip('Nome interno do item')" onmouseout="toolTip()">

        <input class="input_prancheta" id="barra_aliases" type="text" placeholder="Aliases" name="aliases" value="<?php echo $aliases ?>" onmouseover="toolTip('Aliases do item')" onmouseout="toolTip()">

        <div id="selects">
            <select name="abamenu" style="width: 505px;" onmouseover="toolTip('A Categoria do item')" onmouseout="toolTip()">

                <?php
                $categorias = ["Construcao", "Decorativos", "Redstone", "Transportes", "Diversos", "Alimentos", "Ferramentas", "Combate", "Pocoes", "Especiais", "Generico"];
                $categorias_exib = ["Blocos de construção", "Blocos decorativos", "Redstone", "Transportes", "Diversos", "Alimentos", "Ferramentas", "Combate", "Poções", "Especiais", "Genérico"];
                
                // Procura o indice da categoria do item e exibe formatado
                $indice = array_search($tipo_item, $categorias);
                echo "<option value='$tipo_item'>$categorias_exib[$indice]</option>";

                for($i = 0; $i < sizeof($categorias); $i++){
                    if($tipo_item != $categorias[$i])
                        echo "<option value='$categorias[$i]'>$categorias_exib[$i]</option>";
                } ?> 
            </select><br><br>

            <select name="cor_tipo_item" style="width: 505px;" onmouseover="toolTip('A Cor do item no inventário')" onmouseout="toolTip()">
                <?php
                    $cores_nome = ["Branco", "Azul", "Amarelo", "Rosa"];

                    echo "<option value='$cor_item'>$cores_nome[$cor_item]</option>";

                    for($i = sizeof($cores_nome) - 1; $i >= 0; $i--){
                        if($i != $cor_item)
                            echo "<option value='$i'>$cores_nome[$i]</option>";
                    } ?>
            </select><br><br>
            
            <select name="empilhavel" style="width: 505px;" onmouseover="toolTip('Quantos itens se juntam')" onmouseout="toolTip()">
                <?php
                    $empilhagens = [64, 16, 0];
                    $empilhagens_exib = ["64x", "16x", "Não"];

                    $indice = array_search($empilhavel, $empilhagens);
                    echo "<option value='$empilhavel'>$empilhagens_exib[$indice]</option>";

                    for($i = sizeof($empilhagens) - 1; $i >= 0; $i--){
                        if($empilhagens[$i] != $empilhavel)
                            echo "<option value='$empilhagens[$i]'>$empilhagens_exib[$i]</option>";
                    } ?>         
            </select><br><br>

            <select name="versao" style="width: 505px;" onmouseover="toolTip('A Versão que o item foi adicionado')" onmouseout="toolTip()">

                <?php echo "<option value='$versao_add'>1.$versao_add</option>";
        
                for($i = 18; $i >= 0; $i--){
                    if($versao_add != $i)
                        echo "<option value='$i'>1.$i</option>";
                } ?>
            </select>
        </div>

        <div id="checkboxes">
            <input class="input_check" type="checkbox" name="coletavelsurvival" <?php echo $coletavel_s ?> onmouseover="toolTip('Coletável no sobrevivência')" onmouseout="toolTip()"> <img class="icon_check" src="IMG/Interface/coracao.png" onmouseover="toolTip('Coletável no sobrevivência')" onmouseout="toolTip()"><br>

            <input class="input_check" type="checkbox" name="renovavel" <?php echo $renovavel ?> onmouseover="toolTip('Recurso renovável')" onmouseout="toolTip()"> <img class="icon_check" src="IMG/Itens/Decorativos/anvil.png" onmouseover="toolTip('Recurso renovável')" onmouseout="toolTip()">

            <input class="input_check" type="checkbox" name="oculto_invt" <?php echo $oculto_invt ?> onmouseover="toolTip('Oculto do inventário')" onmouseout="toolTip()"> <img class="icon_check" src="IMG/Interface/oculto.png" onmouseover="toolTip('Oculto do inventário')" onmouseout="toolTip()">

            <input id="input_img" type="file" name="img" accept="image/*" onchange="previewImage(1);" onmouseover="toolTip('Foto do item')" onmouseout="toolTip()">

            <img id="preview">
        </div>

        <input id="inserir_item" type="submit" value="Atualizar">
    </form>

    <div class="slot_item" style="display: none;"></div>
    
    <?php 
    
        echo "<script>toolTip(\"$nome_item\", \"$descricao_item\", \"$nome_interno\", $cor_item, 1)</script>";
    }else{
        echo "<script>toolTip(\"Oh, noo!\", \"[&2Este ID não existe ;C\", \"404\", 0, 1)</script>";

        echo "<style>
            #filtro_colorido{
                background: linear-gradient(0deg, rgba(0,0,0,0.9248074229691877) 0%, rgba(80,0,0,0.6699054621848739) 39%, rgba(255,0,0,0) 100%) !important;
            }
        </style>";
    } ?>
</body>
<script src="JS/engine.js"></script>
</html>