<?php include_once "../PHP/conexao_obsoleta.php";

    $busca_itens = "SELECT * FROM item WHERE coletavelSurvival = 1";
    $executa = $conexao->query($busca_itens);
    
    echo $executa->num_rows;
?>