<?php include_once "conexao_obsoleta.php";

$IDs_registrados = [];

// Este arquivo está disponível por padrão em sua pasta JSON
$arquivo = file_get_contents('../Files/JSON/dados_locais.json');
$data = json_decode($arquivo);

$verificar = "SELECT * FROM item";
$executa = $conexao->query($verificar);

// Salvando todos os IDs do banco num array
while ($dados = $executa->fetch_assoc()) {
    array_push($IDs_registrados, $dados["id_item"]);
}

// Registrando no banco os itens que só existem no JSON
foreach ($data as $key => $value) {

    $id_item = $value->id_item;
    $nome_icon = $value->nome_icon;
    $abamenu = $value->tipo_item;
    $nome = $value->nome_item;
    $coletavel = $value->coletavel;
    $nome_interno = $value->nome_interno;
    $empilhavel = $value->empilhavel;
    $versao = $value->versao_add;
    $renovavel = 1;
    $fabricavel = $value->fabricavel;

    if ($renovavel == null)
        $renovavel = 0;

    # Inserindo o item no banco de dados
    if (!in_array($value->id_item, $IDs_registrados)) {
        $insere = "INSERT into item (id_item, nome, empilhavel, coletavel, nome_icon, renovavel, versao_adicionada, nome_interno, fabricavel) VALUES ($id_item, '$nome', $empilhavel, $coletavel, '$nome_icon', $renovavel, $versao, '$nome_interno', $fabricavel);";
        $executa = $conexao->query($insere);
    }

    // Item com coloração de nome diferente
    if (array_key_exists("item_titulo", $value)) {
        $cor_item = $value->item_titulo;

        $id_titulo = $cor_item[0]->id_titulo;
        $tipo_item = $cor_item[0]->tipo_item;

        $insere = "INSERT INTO item_titulo (id_titulo, id_item, tipo_item) VALUES ($id_titulo, $id_item, $tipo_item)";
        $executa = $conexao->query($insere);
    }

    // Item com uma durabilidade declarada
    if (array_key_exists("item_durabilidade", $value)) {
        $durabili = $value->item_durabilidade;

        $id_durabilidade = $durabili[0]->id_durabilidade;
        $durabilidade = $durabili[0]->durabilidade;

        $insere = "INSERT INTO item_durabilidade (id_durabilidade, id_item, durabilidade) VALUES ($id_durabilidade, $id_item, $durabilidade)";
        $executa = $conexao->query($insere);
    }

    // Item com sprites antigos disponíveis
    if (array_key_exists("item_legado", $value)) {
        $item_legado = $value->item_legado;

        $id_legado = $item_legado[0]->id_legado;

        $insere = "INSERT INTO item_legado (id_legado, id_item, status_item) VALUES ($id_legado, $id_item, 1)";
        $executa = $conexao->query($insere);
    }

    // Item oculto do inventário
    if (array_key_exists("item_oculto", $value)) {
        $item_oculto = $value->item_oculto;

        $id_oculto = $item_oculto[0]->id_oculto;

        $insere = "INSERT INTO item_oculto (id_oculto, id_item, status_item) VALUES ($id_oculto, $id_item, 1)";
        $executa = $conexao->query($insere);
    }

    // Descrição dos itens
    if (array_key_exists("item_descricao", $value)) {
        $item_descricao = $value->item_descricao;

        $id_descricao = $item_descricao[0]->id_descricao;
        $descricao = $item_descricao[0]->descricao;

        $insere = "INSERT INTO item_descricao (id_descricao, id_item, descricao) VALUES ($id_descricao, $id_item, '$descricao')";
        $executa = $conexao->query($insere);
    }

    // Itens com receitas de fabricação
    if (array_key_exists("item_receita", $value)) {
        $crafting = $value->item_receita;

        $craft = $crafting[0]->receita;
        $produtos = $crafting[0]->produtos;
        $tipo_craft = $crafting[0]->tipo_craft;
        $id_receita = $crafting[0]->id_receita;

        $insere = "INSERT INTO item_receita (id_craft, id_item, crafting, qtd_produtos, tipo_craft) VALUES ($id_receita, $id_item, '$craft', $produtos, $tipo_craft)";
        $executa = $conexao->query($insere);
    }
}

Header("Location: ../pages/index.php");
