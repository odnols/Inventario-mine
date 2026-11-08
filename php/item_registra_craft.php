<?php include_once "conexao_obsoleta.php";
include_once "tipos_craft.php";

$id_item = filter_var($_POST["id_item"] ?? "", FILTER_VALIDATE_INT);
$tipo_craft = normaliza_tipo_craft($_POST["tipo_craft"] ?? "bancada");

if ($id_item === false || $id_item < 1) {
    http_response_code(400);
    exit("Item inválido.");
}

if ($tipo_craft === null) {
    http_response_code(400);
    exit("Tipo de bancada inválido.");
}

$configuracoes = [];
$configuracoes_enviadas = trim($_POST["craft_configs"] ?? "");

if ($configuracoes_enviadas !== "") {
    $dados_configuracoes = json_decode($configuracoes_enviadas, true);
    if (!is_array($dados_configuracoes)) {
        http_response_code(400);
        exit("Configurações de crafting inválidas.");
    }

    $ids_receitas = [];
    foreach ($dados_configuracoes as $configuracao) {
        if (!is_array($configuracao)) {
            http_response_code(400);
            exit("Configuração de crafting inválida.");
        }
        $tipo = normaliza_tipo_craft($configuracao["type"] ?? null);
        if ($tipo === null
            || !isset($configuracao["recipe"])
            || !is_array($configuracao["recipe"])
        ) {
            http_response_code(400);
            exit("Configuração de crafting inválida.");
        }

        $id_receita = $configuracao["id"] ?? null;
        if ($id_receita !== null) {
            $id_receita = filter_var($id_receita, FILTER_VALIDATE_INT);
            if ($id_receita === false || $id_receita < 1 || isset($ids_receitas[$id_receita])) {
                http_response_code(400);
                exit("Identificador de receita inválido.");
            }
            $ids_receitas[$id_receita] = true;
        }

        $receita = array_fill(0, TIPOS_CRAFT[$tipo], null);
        foreach (array_slice(array_values($configuracao["recipe"]), 0, TIPOS_CRAFT[$tipo]) as $indice => $id_ingrediente) {
            if ($id_ingrediente === null || $id_ingrediente === "") {
                continue;
            }

            $id_ingrediente = filter_var($id_ingrediente, FILTER_VALIDATE_INT);
            if ($id_ingrediente === false || $id_ingrediente < 1) {
                http_response_code(400);
                exit("Ingrediente inválido na configuração de crafting.");
            }
            $receita[$indice] = $id_ingrediente;
        }

        $qtd_produtos = filter_var($configuracao["products"] ?? 1, FILTER_VALIDATE_INT);
        if ($qtd_produtos === false || $qtd_produtos < 1) {
            http_response_code(400);
            exit("Quantidade de produtos inválida.");
        }

        $configuracoes[] = [
            "id" => $id_receita,
            "type" => $tipo,
            "recipe" => implode(",", array_map(
                static function ($id) {
                    return $id === null ? "" : (string) $id;
                },
                $receita
            )),
            "products" => $qtd_produtos
        ];
    }
} else {
    $array_craft = trim($_POST["array_craft"] ?? "");
    $quantidade_enviada = trim($_POST["qtd_produto"] ?? "");
    $qtd_produtos = $quantidade_enviada === "" ? 1 : filter_var($quantidade_enviada, FILTER_VALIDATE_INT);
    if ($qtd_produtos === false || $qtd_produtos < 1) {
        http_response_code(400);
        exit("Quantidade de produtos inválida.");
    }

    $resultado = $conexao->query(
        "SELECT id_craft FROM item_receita WHERE id_item = $id_item AND tipo_craft = '$tipo_craft' ORDER BY id_craft LIMIT 1"
    );
    if (!$resultado) {
        throw new RuntimeException("Falha ao verificar a receita: " . $conexao->error);
    }
    $existente = $resultado->fetch_assoc();
    $configuracoes[] = [
        "id" => $existente ? (int) $existente["id_craft"] : null,
        "type" => $tipo_craft,
        "recipe" => $array_craft,
        "products" => $qtd_produtos
    ];
}

$conexao->begin_transaction();
foreach ($configuracoes as $configuracao) {
    $receita_segura = $conexao->real_escape_string($configuracao["recipe"]);
    $qtd_produtos = (int) $configuracao["products"];
    $tipo = $configuracao["type"];
    $id_receita = $configuracao["id"];

    if ($id_receita !== null) {
        $resultado = $conexao->query(
            "SELECT id_craft FROM item_receita WHERE id_craft = $id_receita AND id_item = $id_item AND tipo_craft = '$tipo'"
        );
        if (!$resultado || $resultado->num_rows !== 1) {
            $conexao->rollback();
            throw new RuntimeException("Receita não encontrada para o item e tipo informados.");
        }
        $sucesso = $conexao->query(
            "UPDATE item_receita
             SET crafting = '$receita_segura', qtd_produtos = $qtd_produtos
             WHERE id_craft = $id_receita AND id_item = $id_item AND tipo_craft = '$tipo'"
        );
    } else {
        $sucesso = $conexao->query(
            "INSERT INTO item_receita (id_item, crafting, qtd_produtos, tipo_craft)
             VALUES ($id_item, '$receita_segura', $qtd_produtos, '$tipo')"
        );
    }

    if (!$sucesso) {
        $conexao->rollback();
        throw new RuntimeException("Falha ao salvar a receita: " . $conexao->error);
    }
}

if (!$conexao->commit()) {
    throw new RuntimeException("Falha ao confirmar as receitas: " . $conexao->error);
}

$params = '?' . http_build_query(array('id' => $id_item, 'rlod' => 1));
header('Location: ../pages/item_detalhes.php' . $params);
