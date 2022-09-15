<?php include_once "../PHP/conexao_obsoleta.php";

// Base de dados em JSON
$arquivo_json = file_get_contents("dados_locais.json");
$data = json_decode($arquivo_json);

// Arquivo que será traduzido
$arquivo_txt = "tab_wk.txt";
$fh = fopen($arquivo_txt, 'r+');
$dados = fread($fh, filesize($arquivo_txt));

$dados = explode("|-", $dados);

foreach ($data as $key => $value) {
    for ($i = 0; $i < sizeof($dados); $i++) {
        if (strpos(strtolower($dados[$i]), strtolower($value->nome_interno)) !== false)
            $dados[$i] = str_replace("[[" . ucwords($value->nome_interno) . "]]", "[[" . ucfirst($value->nome_item) . "]]", $dados[$i]);
    }
}

echo "<br><br>// Substituídos <br><br>";

for ($i = 0; $i < sizeof($dados); $i++) {

    $quebra_linha = explode("\n| ", $dados[$i]);

    for ($x = 0; $x < sizeof($quebra_linha); $x++) {
        if (strlen($quebra_linha[$x]) > 2)
            echo "| " . $quebra_linha[$x] . "<br>";
    }

    echo "|-<br>";
}
