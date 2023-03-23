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

    $id_item = $value->id;

    $nome = $value->name;
    $abamenu = $value->type;
    $nome_icon = $value->icon;
    $nome_interno = $value->internal_name;

    $oculto = $value->hide;
    $legacy = $value->legacy;

    $versao = $value->version;
    $empilhavel = $value->stackable;

    $renovavel = $value->renewable ? 1 : 0;
    $fabricavel = $value->craftable ? 1 : 0;
    $coletavel = $value->collectable ? 1 : 0;

    # Inserindo o item no banco de dados
    if (!in_array($value->id, $IDs_registrados)) {
        $insere = "INSERT into item (id_item, nome, empilhavel, coletavel, icon, renovavel, versao, internal, fabricavel, tipo) VALUES ($id_item, '$nome', $empilhavel, $coletavel, '$nome_icon', $renovavel, '$versao', '$nome_interno', $fabricavel, '$abamenu')";
        $executa = $conexao->query($insere);

        // Item com coloração de nome diferente
        if (property_exists($value, "title")) {
            $dados = $value->title;

            $tipo_item = $dados[0]->type;

            $insere = "INSERT INTO item_titulo (id_item, tipo_item) VALUES ($id_item, $tipo_item)";
            $executa = $conexao->query($insere);
        }

        // Item com uma durabilidade informada
        if (property_exists($value, "durability")) {
            $durabili = $value->durability;

            $durabilidade = $durabili[0]->value;

            $insere = "INSERT INTO item_durabilidade (id_item, durabilidade) VALUES ($id_item, $durabilidade)";
            $executa = $conexao->query($insere);
        }

        // Item com sprites antigos disponíveis
        if ($legacy) {
            $insere = "INSERT INTO item_legado (id_item, status_item) VALUES ($id_item, 1)";
            $executa = $conexao->query($insere);
        }

        // Item oculto do inventário
        if ($oculto) {
            $insere = "INSERT INTO item_oculto (id_item, status_item) VALUES ($id_item, 1)";
            $executa = $conexao->query($insere);
        }

        // Descrição dos itens
        if (property_exists($value, "description")) {
            $item_descricao = $value->description;

            $descricao = $item_descricao[0]->value;

            $insere = "INSERT INTO item_descricao (id_item, descricao) VALUES ($id_item, '$descricao')";
            $executa = $conexao->query($insere);
        }

        // Itens com receitas de fabricação
        if (property_exists($value, "recipe")) {
            $crafting = $value->recipe;

            $craft = $crafting[0]->recipe;
            $produtos = $crafting[0]->products;
            $tipo_craft = $crafting[0]->craft_type;

            $insere = "INSERT INTO item_receita (id_item, crafting, qtd_produtos, tipo_craft) VALUES ($id_item, '$craft', $produtos, $tipo_craft)";
            $executa = $conexao->query($insere);
        }

        // Itens com histórico de várias guias no menu
        if (property_exists($value, "item_tab")) {
            $guias = $value->item_tab;

            $historico_guias = $guias[0]->tab_history;

            $insere = "INSERT INTO item_guia (id_item, historico_guias) VALUES ($id_item, '$historico_guias')";
            $executa = $conexao->query($insere);
        }
    }
}

Header("Location: ../pages/index.php");
