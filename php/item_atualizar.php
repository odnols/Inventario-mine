<?php include_once "conexao_obsoleta.php";

$id_item = filter_var($_POST["id_item"] ?? null, FILTER_VALIDATE_INT);
if ($id_item === false || $id_item < 1) {
    http_response_code(400);
    exit("ID do item inválido.");
}

$nome = $_POST["nome"];
$nome_interno = $_POST["nome_interno"];
$empilhavel = $_POST["empilhavel"];
$versao = $_POST["versao"];
$tipo_item = $_POST["tipo_item"] ?? "";
$guias_validas = [
    "construcao", "decorativos", "redstone", "transportes", "diversos",
    "alimentos", "ferramentas", "combate", "pocoes", "especiais"
];
if (!in_array($tipo_item, $guias_validas, true)) {
    http_response_code(400);
    exit("Guia do menu inválida.");
}

$resultado_item_atual = $conexao->query(
    "SELECT tipo, icon FROM item WHERE id_item = $id_item LIMIT 1"
);
if (!$resultado_item_atual || $resultado_item_atual->num_rows !== 1) {
    http_response_code(404);
    exit("Item não encontrado.");
}
$item_atual = $resultado_item_atual->fetch_assoc();
$tipo_anterior = $item_atual["tipo"];
$icon_anterior = basename((string) $item_atual["icon"]);
if (!in_array($tipo_anterior, $guias_validas, true)) {
    throw new RuntimeException("A guia atual do item é inválida.");
}

$cor_tipo_item = $_POST["cor_tipo_item"];
$descricao = $_POST["descricao"];
$durabilidade = $_POST["durabilidade"] ?? "";

$upload_erro = $_FILES["img"]["error"] ?? UPLOAD_ERR_NO_FILE;
if ($upload_erro !== UPLOAD_ERR_OK && $upload_erro !== UPLOAD_ERR_NO_FILE) {
    http_response_code(400);
    exit("Falha ao receber o sprite enviado.");
}
$tem_upload_sprite = $upload_erro === UPLOAD_ERR_OK;
$arq_name = $tem_upload_sprite ? basename($_FILES["img"]["name"]) : "";
$arq_tmp = $tem_upload_sprite ? $_FILES["img"]["tmp_name"] : "";
if ($tem_upload_sprite && $arq_name === "") {
    http_response_code(400);
    exit("O nome do sprite enviado é inválido.");
}

// Verifica se o item é coletável no sobrevivência
if (isset($_POST["coletavel"])) $coletavel = 1;
else $coletavel = 0;

if (isset($_POST["renovavel"])) $renovavel = 1;
else $renovavel = 0;

if (isset($_POST["lancado"])) $lancado = 1;
else $lancado = 0;

if (isset($_POST["oculto_invt"])) $oculto_invt = 1;
else $oculto_invt = 0;

if (isset($_POST["programmer_art"])) $programmer_art = 1;
else $programmer_art = 0;

if (isset($_POST["fabricavel"])) $crafting = 1;
else $crafting = 0;

// Atualizando os campos principais
$conexao->query("UPDATE item SET nome = '$nome', empilhavel = $empilhavel, coletavel = $coletavel, renovavel = $renovavel, lancado = $lancado, fabricavel = $crafting WHERE id_item = $id_item");

// Atualizando o nome interno
if (strlen($nome_interno) < 1)
    $conexao->query("UPDATE item SET internal = null WHERE id_item = $id_item");
else
    $conexao->query("UPDATE item SET internal = '$nome_interno' WHERE id_item = $id_item");

// Verifica se o item possui descricao registrada
$executa_verificacao = $conexao->query("SELECT * FROM item_descricao WHERE id_item = $id_item");

// Atualizando a descrição do item
if ($executa_verificacao->num_rows > 0) {
    if (strlen($descricao) < 1)
        $conexao->query("DELETE FROM item_descricao WHERE id_item = $id_item");
    else
        $conexao->query("UPDATE item_descricao SET descricao = '$descricao' WHERE id_item = $id_item");
} else if (strlen($descricao > 0)) // Só adiciona uma decrição caso haja texto
    $conexao->query("INSERT INTO item_descricao VALUES (null, $id_item, '$descricao')");

// Atualizando a versão
if (strlen($versao) < 1 || $versao == "Outro")
    $conexao->query("UPDATE item SET versao = '1.0' WHERE id_item = $id_item");
else
    $conexao->query("UPDATE item SET versao = '$versao' WHERE id_item = $id_item");

// Verifica se o item possui registros anteriores
$executa_verificacao = $conexao->query("SELECT * FROM item_titulo WHERE id_item = $id_item");

// Só insere se for diferente de zero
if ($cor_tipo_item != 0 || $executa_verificacao->num_rows > 0) {
    if ($executa_verificacao->num_rows == 0) // Insere um novo
        $conexao->query("INSERT INTO item_titulo VALUES (null, $id_item, $cor_tipo_item)");
    else if ($cor_tipo_item != 0) // Atualiza
        $conexao->query("UPDATE item_titulo SET tipo_item = $cor_tipo_item WHERE id_item = $id_item");
    else
        $conexao->query("DELETE FROM item_titulo WHERE id_item = $id_item");
}

// Verifica se o item possui registros de durabilidade
$executa_verificacao = $conexao->query("SELECT * FROM item_durabilidade WHERE id_item = $id_item");

if ($executa_verificacao->num_rows > 0)
    if (strlen($durabilidade) > 0)
        $conexao->query("UPDATE item_durabilidade SET durabilidade = $durabilidade WHERE id_item = $id_item");
    else
        $conexao->query("DELETE FROM item_durabilidade WHERE id_item = $id_item");
else if (strlen($durabilidade) > 0)
    $conexao->query("INSERT INTO item_durabilidade values (null, $id_item, $durabilidade)");

// Verifica se o item possui sprites antigos
$executa_verificacao = $conexao->query("SELECT * FROM item_legado WHERE id_item = $id_item");

if ($executa_verificacao->num_rows > 0) {
    if (!$programmer_art)
        $conexao->query("DELETE FROM item_legado WHERE id_item = $id_item");
} else if ($programmer_art)
    $conexao->query("INSERT INTO item_legado values (null, $id_item, 1)");

// Verifica se o item está oculto no inventário
$executa_verificacao = $conexao->query("SELECT * FROM item_oculto WHERE id_item = $id_item");

if ($executa_verificacao->num_rows > 0) {
    if (!$oculto_invt)
        $conexao->query("DELETE FROM item_oculto WHERE id_item = $id_item");
} else if ($oculto_invt)
    $conexao->query("INSERT INTO item_oculto values (null, $id_item, 1)");

// Copia os sprites para a nova guia antes de atualizar os caminhos do item.
$icon_novo = $tem_upload_sprite ? $arq_name : $icon_anterior;
$sprites = [];
$sprites_antigos = [];
$diretorio_itens = realpath(__DIR__ . "/../img/itens");

if ($diretorio_itens === false) {
    throw new RuntimeException("Diretório de sprites não encontrado.");
}

if ($icon_anterior !== "") {
    $sprite_novo_atual = "$diretorio_itens/new/$tipo_anterior/$icon_anterior";
    if (is_file($sprite_novo_atual)) {
        $sprites_antigos["new"] = $sprite_novo_atual;
        if (!$tem_upload_sprite)
            $sprites["new"] = $sprite_novo_atual;
    }

    $sprite_classic_atual = "$diretorio_itens/classic/$tipo_anterior/$icon_anterior";
    if (is_file($sprite_classic_atual)) {
        $sprites["classic"] = $sprite_classic_atual;
        $sprites_antigos["classic"] = $sprite_classic_atual;
    }
}

if ($tem_upload_sprite)
    $sprites["new"] = $arq_tmp;

$mudou_categoria = $tipo_item !== $tipo_anterior;
$precisa_mover_sprites = $mudou_categoria || $tem_upload_sprite;

if ($precisa_mover_sprites && $sprites) {
    $icon_base = $icon_novo;
    $nome_base = pathinfo($icon_base, PATHINFO_FILENAME);
    $extensao = pathinfo($icon_base, PATHINFO_EXTENSION);
    $sufixo_extensao = $extensao === "" ? "" : ".$extensao";
    $tentativa = 0;
    $conflito_encontrado = true;

    while ($conflito_encontrado) {
        $candidato = $tentativa === 0
            ? $icon_base
            : $id_item . "_" . $nome_base . ($tentativa > 1 ? "_$tentativa" : "") . $sufixo_extensao;
        $conflito_encontrado = false;
        $icon_sql_teste = $conexao->real_escape_string($candidato);
        $consulta_uso = $conexao->query(
            "SELECT id_item FROM item WHERE tipo = '$tipo_item' AND icon = '$icon_sql_teste' AND id_item <> $id_item LIMIT 1"
        );

        if (!$consulta_uso) {
            throw new RuntimeException("Falha ao verificar o nome do sprite: " . $conexao->error);
        }

        $ha_referencias = $consulta_uso->num_rows > 0;
        foreach ($sprites as $geracao => $origem) {
            $destino = "$diretorio_itens/$geracao/$tipo_item/$candidato";
            if (is_file($destino)) {
                if (hash_file("sha256", $destino) !== hash_file("sha256", $origem))
                    $conflito_encontrado = true;
            } elseif ($ha_referencias) {
                $conflito_encontrado = true;
            }
        }

        $tentativa++;
    }

    $icon_novo = $candidato;

    foreach ($sprites as $geracao => $origem) {
        $diretorio_destino = "$diretorio_itens/$geracao/$tipo_item";
        if (!is_dir($diretorio_destino) && !mkdir($diretorio_destino, 0775, true) && !is_dir($diretorio_destino)) {
            throw new RuntimeException("Não foi possível criar a pasta de sprites da guia.");
        }

        $destino = "$diretorio_destino/$icon_novo";
        if (realpath($origem) === realpath($destino))
            continue;

        if (is_file($destino) && hash_file("sha256", $destino) === hash_file("sha256", $origem))
            continue;

        if (!copy($origem, $destino)) {
            throw new RuntimeException("Não foi possível copiar o sprite para a nova guia.");
        }
    }
}

$icon_sql = $conexao->real_escape_string($icon_novo);
if (!$conexao->query(
    "UPDATE item SET tipo = '$tipo_item', icon = '$icon_sql' WHERE id_item = $id_item"
)) {
    throw new RuntimeException("Falha ao salvar a guia e o sprite do item: " . $conexao->error);
}

foreach ($sprites_antigos as $geracao => $origem) {
    if (!$mudou_categoria && $icon_novo === $icon_anterior)
        continue;

    $tipo_sql_antigo = $conexao->real_escape_string($tipo_anterior);
    $icon_sql_antigo = $conexao->real_escape_string($icon_anterior);
    if ($geracao === "classic") {
        $consulta_referencias = $conexao->query(
            "SELECT item.id_item FROM item
             INNER JOIN item_legado ON item_legado.id_item = item.id_item
             WHERE item.tipo = '$tipo_sql_antigo'
               AND item.icon = '$icon_sql_antigo'
               AND item.id_item <> $id_item
             LIMIT 1"
        );
    } else {
        $consulta_referencias = $conexao->query(
            "SELECT id_item FROM item
             WHERE tipo = '$tipo_sql_antigo'
               AND icon = '$icon_sql_antigo'
               AND id_item <> $id_item
             LIMIT 1"
        );
    }

    if (!$consulta_referencias) {
        throw new RuntimeException("Falha ao verificar referências ao sprite antigo: " . $conexao->error);
    }

    if ($consulta_referencias->num_rows === 0 && is_file($origem))
        unlink($origem);
}

Header("Location: ../pages/item_detalhes.php?id=$id_item");
