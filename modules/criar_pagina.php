<?php include_once "../php/conexao_obsoleta.php";

ob_start(); ?>

<html lang="pt">

<head>
    <meta charset="utf-8">
    <title>Inventário</title>
    <link rel="shortcut icon" href="img/itens/new/construcao/grass_block.png">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/anima.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/tooltip.css">

    <script src="js/jquery-3.4.1.js"></script>
</head>

<body onload="sincroniza_tema(undefined, 0)">

    <div id="filtro_colorido"></div>
    <div id="lista_versoes" style="display: none">
        <?php $lista_versoes = [];
        for ($i = 0; $i <= 21; $i++) $lista_versoes[] = "1.$i";
        for ($i = 1; $i <= 4; $i++) $lista_versoes[] = "26.$i";

        foreach (array_chunk($lista_versoes, 3) as $linha) {
            $links = array_map(fn($v) => "<a href='#' onclick='filtragem(\"$v\", 2)'>$v</a>", $linha);
            echo "-> " . implode(" | ", $links) . "<br>";
        } ?>
    </div>

    <div id="estatisticas_inventario">
        <img id="prancheta" src="#">

        <div onclick="filtragem_automatica('oculto')" onmouseover="toolTip('itens ocultos')" onmouseout="toolTip()">
            <img id="img_ocultos_2" class="aba_menu opcoes_baixo" src="img/interface/mascara_oculto.png">
            <img id="img_ocultos" class="aba_menu opcoes_baixo pesquisa" src="#">
        </div>

        <div id="text_estatsc">
            <center>
                <h2 class="cor_textos">Estatísticas</h2>
            </center>

            <p id='versao_referencia' class='estat cor_textos'>itens Adicionados na versão <span id='num_referencia'></span></p>
            <br>
            <p class='estat cor_textos'>Itens registrados: <span id='qtd_itens_inventario'>Carregando...</span></p>
            <br>
            <p class='estat cor_textos'>Coletáveis: <span id='qtd_itens_inventario_colet'>Carregando...</span></p>
            <br>
            <p class='estat cor_textos'>Fabricáveis: <span id='qtd_itens_fabricaveis'>Carregando...</span></p>
            <br>
            <p class='estat cor_textos'>Renováveis: <span id='qtd_itens_inventario_renov'>Carregando...</span></p>
            <br>
            <p class='estat cor_textos'>Empilháveis: <span id='qtd_itens_inventario_empil'>Carregando...</span></p>
            <br>
            <p class='estat cor_textos'>Coletáveis e empilháveis: <span id='qtd_itens_inventario_colet_empil'>Carregando...</span></p>
            <br>
            <p class='estat cor_textos'>Coletáveis empilháveis e não renováveis: <span id='qtd_itens_inventario_colet_empil_n_renov'>Carregando...</span></p>
            <br>
            <p class='estat cor_textos'>Não empilháveis: <span id='qtd_itens_inventario_n_empil'>Carregando...</span></p>
        </div>
    </div>

    <div id="menu_user">
        <a class="bttn_frrm" id="bttn_troca_tema" href="#" onclick="troca_tema(undefined, 0)" onmouseover="toolTip('Altere entre o modo escuro e claro')" onmouseout="toolTip()"><span id="icone_tema">☀️</span></a>
    </div>

    <!-- Menu interativo -->
    <div id="menu_completo">
        <img id="menu" src="#">

        <script>
            for (let i = 0; i < 11; i++) {
                document.write(`<div id='item_${i}' onclick='aba_menu(${i})'></div>`)
            }
        </script>

        <div id="item_11" onclick="clique('prancheta')"></div>

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
            <img id="img_configs_2" class="aba_menu opcoes_laterais" src="img/interface/mascara_configs.png">
            <img id="img_configs" class="aba_menu opcoes_laterais pesquisa" src="img/interface/aba_configs.png">
        </div>

        <div onclick="filtragem_automatica('não_coletável')" onmouseover="toolTip('Mostrar itens que não são coletáveis no sobrevivência')" onmouseout="toolTip()">
            <img id="img_coletaveis_2" class="aba_menu opcoes_laterais" src="img/interface/mascara_nao_coletaveis.png">
            <img id="img_coletaveis" class="aba_menu opcoes_laterais pesquisa" src="img/interface/aba_nao_coletaveis.png">
        </div>

        <div onclick="lista_versoes()" onmouseover="toolTip('Filtrar por versões')" onmouseout="toolTip()">
            <img id="img_versoes_2" class="aba_menu" src="img/interface/mascara_atts.png">
            <img id="img_versoes" class="aba_menu opcoes_laterais pesquisa" src="img/interface/aba_atts.png">
        </div>

        <img id="img_especiais" class="aba_menu aba_menu_especiais especiais" src="#">
        <img id="img_pesquisa" class="aba_menu aba_menu_pesquisa pesquisa" src="#">

        <img id="img_prancheta" class="aba_menu prancheta" src="img/interface/mascara_prancheta.png">

        <input class="pesquisa" id="barra_pesquisa_input" type="text" onkeyup="filtra_pesquisa()" />

        <span id="titulo_aba"></span>

        <div id="barra_rolagem">
            <div id="barra_scroll" src="img/interface/scroll.png"></div>
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
                // Tabelas auxiliares carregadas de uma vez, evitando consultas por item
                $mapa_ocultos = [];
                $mapa_descricoes = [];
                $mapa_titulos = [];

                $consulta_aux = $conexao->query("SELECT id_item FROM item_oculto");
                while ($linha = $consulta_aux->fetch_assoc()) $mapa_ocultos[$linha["id_item"]] = true;

                $consulta_aux = $conexao->query("SELECT id_item, descricao FROM item_descricao");
                while ($linha = $consulta_aux->fetch_assoc())
                    if (!isset($mapa_descricoes[$linha["id_item"]])) $mapa_descricoes[$linha["id_item"]] = $linha["descricao"];

                $consulta_aux = $conexao->query("SELECT id_item, tipo_item FROM item_titulo");
                while ($linha = $consulta_aux->fetch_assoc())
                    if (!isset($mapa_titulos[$linha["id_item"]])) $mapa_titulos[$linha["id_item"]] = $linha["tipo_item"];

                $verificar = "SELECT * FROM item ORDER BY id_item DESC";
                $executa = $conexao->query($verificar);

                while ($dados = $executa->fetch_assoc()) {

                    $apelido = null;
                    $descricao_pesq = null;
                    $oculto_invt = null;
                    $geracao = "new";

                    $id_item = $dados["id_item"];
                    $nome_icon = $dados["icon"];
                    $tipo_item = $dados["tipo"];
                    $nome_item = $dados["nome"];
                    $coletavel = $dados["coletavel"];
                    $nome_interno = $dados["internal"];
                    $empilhavel = $dados["empilhavel"];
                    $versao_add = $dados["versao"];
                    $renovavel = $dados["renovavel"];

                    $oculto_invt = isset($mapa_ocultos[$id_item]) ? "oculto" : null;
                    $programmer_art = null;

                    $descricao = "[&1" . $tipo_item;

                    if (isset($mapa_descricoes[$id_item]))
                        $descricao = $descricao . " " . $mapa_descricoes[$id_item];

                    $descricao_pes = str_replace("[&r", "", $descricao);

                    if (!$nome_interno)
                        $nome_interno = "off";

                    // if($programmer_art == 1)
                    // $geracao = "classic";

                    if ($versao_add == null)
                        $versao_add = "off";
                    else
                        $versao_add = $versao_add;

                    if ($oculto_invt == 1)
                        $oculto_invt = "Oculto";

                    if (!$renovavel)
                        $renovavel = "não_renovável";
                    else
                        $renovavel = "renovável";

                    if ($empilhavel != 0)
                        $empilhavel = "empilhável";
                    else
                        $empilhavel = null;

                    if ($coletavel != 0)
                        $coletavel = "coletável";
                    else
                        $coletavel = "não_coletável";

                    $cor_item = $mapa_titulos[$id_item] ?? 0;

                    $nome_pesq = strtolower($nome_item);
                    $descricao_pesq = strtolower($descricao_pes);

                    for ($i = 0; $i < 21; $i++) { // Elimina todos os números de versão da descrição
                        $descricao_pesq = str_replace($i, "", $descricao_pesq);
                    }

                    $caminho_sprite = 'img/itens/' . $geracao . '/' . $tipo_item . '/' . $nome_icon;

                    if ($oculto_invt != "oculto") {
                        echo "<div class='slot_item $tipo_item $versao_add $nome_interno $coletavel $nome_pesq $descricao_pesq' onclick='exibe_item(`$caminho_sprite`)' onmouseover='toolTip(\"$nome_item\", \"$descricao\", \"$nome_interno\", $cor_item)' onmouseout='toolTip()'>";
                    } else {
                        echo "<div class='slot_item oculto' onclick='exibe_item(`$caminho_sprite`)' onmouseover='toolTip(\"$nome_item\", \"$descricao\", \"$nome_interno\", $cor_item)' onmouseout='toolTip()'>";
                    }

                    echo "<img class='icon_item' loading='lazy' decoding='async' src='$caminho_sprite'>";
                    echo "</div>";
                } ?>
                <div id="complementa_slots"></div>
            </div>
        </div>
    </div>

    <script src="js/engine.js"></script>
    <script src="js/custom.js"></script>
    <script type="text/javascript">
        (function() {
            const estatisticas = <?php
                $contar = function ($filtro) use ($conexao) {
                    return (int) $conexao->query("SELECT COUNT(*) c FROM item" . ($filtro ? " WHERE $filtro" : ""))->fetch_assoc()["c"];
                };
                echo json_encode([
                    "qtd_itens_inventario" => $contar(""),
                    "qtd_itens_inventario_colet" => $contar("coletavel = 1"),
                    "qtd_itens_fabricaveis" => $contar("fabricavel = 1"),
                    "qtd_itens_inventario_renov" => $contar("renovavel = 1"),
                    "qtd_itens_inventario_empil" => $contar("empilhavel != 0"),
                    "qtd_itens_inventario_colet_empil" => $contar("empilhavel != 0 AND coletavel = 1"),
                    "qtd_itens_inventario_colet_empil_n_renov" => $contar("coletavel = 1 AND empilhavel != 0 AND renovavel != 1"),
                    "qtd_itens_inventario_n_empil" => $contar("empilhavel = 0")
                ]); ?>;

            Object.keys(estatisticas).forEach(id => {
                const alvo = document.getElementById(id)
                if (alvo) alvo.innerHTML = estatisticas[id]
            })
        })()

        aba_menu(0)
        document.addEventListener("onKeyDown", clique())
    </script>
</body>

</html>

<?php // Recolhendo os dados que seriam mostrados e salvando num outro arquivo
$gerado = ob_get_contents();
ob_end_clean();

file_put_contents('../index.html', $gerado);

if (isset($_GET["ajax"])) {
    header("Content-Type: application/json");
    echo json_encode(["ok" => true, "bytes" => strlen($gerado)]);
    exit;
}

header("Location: ../pages/index.php") ?>