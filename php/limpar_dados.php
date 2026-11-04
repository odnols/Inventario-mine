<?php include_once "conexao_obsoleta.php";

// As tabelas dependentes precisam ser limpas antes de "item" por causa das chaves estrangeiras
$tabelas = ["item_titulo", "item_durabilidade", "item_receita", "item_descricao", "item_oculto", "item_legado", "item_guia", "item"];
$sucesso = true;

try {
    $conexao->begin_transaction();

    foreach ($tabelas as $tabela)
        $conexao->query("DELETE FROM $tabela");

    $conexao->commit();
} catch (Throwable $e) {
    $conexao->rollback();
    $sucesso = false;
}

if (isset($_GET["ajax"])) {
    header("Content-Type: application/json");
    echo json_encode(["ok" => $sucesso]);
    exit;
}

header("Location: ../pages/index.php");