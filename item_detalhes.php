<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <title>Inventário</title>
    <link rel="shortcut icon" href="IMG/Itens/Construcao/bloco_grama.png">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/anima.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <?php include_once "PHP/conexao_obsoleta.php"; ?>
</head>
<body>

    <?php 
    
    $id_item = $_GET["id"];
    
    $busca_dados = "SELECT * from item where id_item = $id_item";
    $executa = $conexao->query($busca_dados);
    
    $dados = $executa->fetch_assoc();

    $nome_img = $dados["img"];
    
    $nome_item = $dados["nome"];
    $nome_interno = $dados["nome_interno"];

    $tipo_item = $dados["abamenu"];
    $empilhavel = $dados["empilhavel"];
    $versao_add = $dados["versao_adicionada"];

    if($versao_add == null)
        $versao_add = "Outro";

    if($dados["coletavelSurvival"])
        $coletavel_s = "checked";
    else
        $coletavel_s = "";
    
    if($dados["renovavel"])
        $renovavel = "checked";
    else
        $renovavel = "";
    
    ?>

    <button id="btn_voltar" onclick="voltar_pag()">Voltar</button>
    <button id="btn_apagar" onclick="apagarItem(<?php echo $id_item ?>)">Apagar</button>

    <?php echo "<img id='img_detalhes' src='IMG/Itens/$tipo_item/$nome_img'>"; ?>    
    
    <form id="prancheta_att" method="post" action="PHP/item_atualizar.php" enctype="multipart/form-data">

        <input type="text" name="id_item" value="<?php echo $id_item ?>" style="display: none;">
    
        <input class="input_prancheta" id="barra_nome" type="text" placeholder="Nome" name="nome" required value="<?php echo $nome_item ?>">

        <input class="input_prancheta" id="barra_nome_interno" type="text" placeholder="Nome interno" name="nome_interno" value="<?php echo $nome_interno ?>">

        <div id="selects">
            <select name="abamenu" style="width: 505px;">

                <?php
                $categorias = ["Construcao", "Decorativos", "Redstone", "Transportes", "Diversos", "Alimentos", "Ferramentas", "Combate", "Pocoes", "Especiais"];
                $categorias_exib = ["Blocos de construção", "Blocos decorativos", "Redstone", "Transportes", "Diversos", "Alimentos", "Ferramentas", "Combate", "Poções", "Especiais"];
                
                // Procura o indice da categoria do item e exibe formatado
                $indice = array_search($tipo_item, $categorias);
                echo "<option value='$tipo_item'>$categorias_exib[$indice]</option>";

                for($i = 0; $i < sizeof($categorias); $i++){
                    if($tipo_item != $categorias[$i])
                        echo "<option value='$categorias[$i]'>$categorias_exib[$i]</option>";
                } ?> 
            </select><br><br>

            <select name="empilhavel" style="width: 505px;">
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

            <select name="versao" style="width: 505px;">
                <option value="<?php echo $versao_add ?>"><?php echo $versao_add ?></option>

                <?php

                $versoes = ["1.0", "1.1", "1.2", "1.3", "1.4", "1.5", "1.6", "1.7", "1.8", "1.9", "1.10", "1.11", "1.12", "1.13", "1.14", "1.15", "1.16", "1.17", "1.18"];

                for($i = sizeof($versoes) - 1; $i > 0; $i--){
                    if($versao_add != $versoes[$i])
                        echo "<option value='$versoes[$i]'>$versoes[$i]</option>";
                } ?>
            </select>
        </div>

        <div id="checkboxes">
            <input class="input_check" type="checkbox" name="coletavelsurvival" <?php echo $coletavel_s ?>> <img class="icon_check" src="IMG/Interface/coracao.png" title="Coletável no sobrevivência"><br>

            <input class="input_check" type="checkbox" name="renovavel" <?php echo $renovavel ?>> <img class="icon_check" src="IMG/Itens/Decorativos/anvil.png" title="Recurso renovável">

            <input id="input_img" type="file" name="img" accept="image/*" onchange="previewImage();">

            <img id="preview">
        </div>

        <input id="inserir_item" type="submit" value="Atualizar">
    </form>

    <script src="JS/engine.js"></script>
</body>
</html>