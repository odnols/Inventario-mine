<?php include_once "../php/conexao_obsoleta.php";

$verificar = "SELECT * FROM item ORDER BY id_item DESC";
$executa = $conexao->query($verificar);

while ($dados = $executa->fetch_assoc()) {
    echo $dados["nome"] . " | " . $dados["abamenu"] . " | 1." . $dados["versao_adicionada"];

    $id_item = $dados["id_item"];

    echo "<br>";

    // itens com descrição
    if (strlen($dados["descricao"]) > 0) {
        $verificar_registro = "SELECT * FROM item_descricao WHERE id_item = $id_item";
        $executa_var = $conexao->query($verificar_registro);

        $descricao = $dados["descricao"];

        if ($executa_var->num_rows < 1) {
            $insere_item = "INSERT INTO item_descricao (id_item, descricao) VALUES ($id_item, '$descricao')";
            $executa2 = $conexao->query($insere_item);
            echo " | " . $executa_var->num_rows . " | " . $insere_item;
        }
    }

    // itens ocultos do inventário
    if ($dados["oculto_invt"]) {
        $verificar_registro = "SELECT * FROM item_oculto WHERE id_item = $id_item";
        $executa_var = $conexao->query($verificar_registro);

        if ($executa_var->num_rows < 1) {
            $insere_item = "INSERT INTO item_oculto (id_item, status_item) VALUES ($id_item, 1)";
            $executa2 = $conexao->query($insere_item);
        }
    }

    // itens com sprites legados
    if ($dados["programmer_art"]) {
        $verificar_registro = "SELECT * FROM item_legado WHERE id_item = $id_item";
        $executa_var = $conexao->query($verificar_registro);

        if ($executa_var->num_rows < 1) {
            $insere_item = "INSERT INTO item_legado (id_item, status_item) VALUES ($id_item, 1)";
            $executa2 = $conexao->query($insere_item);
        }
    }
}
