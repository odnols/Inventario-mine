<?php include_once "../php/conexao_obsoleta.php";

$executa = $conexao->query("SELECT * FROM item ORDER BY id_item DESC");

while ($dados = $executa->fetch_assoc()) {
    echo $dados["nome"] . " | " . $dados["abamenu"] . " | 1." . $dados["versao_adicionada"];

    $id_item = $dados["id_item"];

    echo "<br>";

    // itens com descrição
    if (strlen($dados["descricao"]) > 0) {
        $executa_var = $conexao->query("SELECT * FROM item_descricao WHERE id_item = $id_item");

        $descricao = $dados["descricao"];

        if ($executa_var->num_rows < 1) {
            $conexao->query("INSERT INTO item_descricao (id_item, descricao) VALUES ($id_item, '$descricao')");
            echo " | " . $executa_var->num_rows . " | " . $insere_item;
        }
    }

    // itens ocultos do inventário
    if ($dados["oculto_invt"]) {
        $executa_var = $conexao->query("SELECT * FROM item_oculto WHERE id_item = $id_item");

        if ($executa_var->num_rows < 1)
            $conexao->query("INSERT INTO item_oculto (id_item, status_item) VALUES ($id_item, 1)");
    }

    // itens com sprites legados
    if ($dados["programmer_art"]) {
        $conexao->query("SELECT * FROM item_legado WHERE id_item = $id_item");

        if ($executa_var->num_rows < 1)
            $conexao->query("INSERT INTO item_legado (id_item, status_item) VALUES ($id_item, 1)");
    }
}
