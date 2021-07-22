<?php include_once "conexao_obsoleta.php";

$IDs_registrados = [];

// Este arquivo está disponível por padrão em sua pasta JSON
$arquivo = file_get_contents('../JSON/dados_locais.json');
$data = json_decode($arquivo);

$verificar = "SELECT * from item";
$executa = $conexao->query($verificar);

// Salvando todos os IDs do banco num array
while($dados = $executa->fetch_assoc()){
    array_push($IDs_registrados, $dados["id_item"]);
}

// Registrando no banco os itens que só existem no JSON
foreach($data as $key => $value){  
    if(!in_array($value->id_item, $IDs_registrados)){

        $id_item = $value->id_item;
        $arq_name = $value->nome_img;
        $abamenu = $value->tipo_item;
        $nome = $value->nome_item;
        $coletavelsurvival = $value->coletavel;
        $nome_interno = $value->nome_interno;
        $empilhavel = $value->empilhavel;
        $versao = $value->versao_add;
        $renovavel = $value->renovavel;

        # Inserindo o item no banco de dados
        $insere = "INSERT into item (id_item, nome, abamenu, empilhavel, coletavelSurvival, img, renovavel, versao_adicionada, nome_interno) values ($id_item, '$nome', '$abamenu', $empilhavel, $coletavelsurvival, '$arq_name', $renovavel, '$versao', '$nome_interno');";
        $executa = $conexao->query($insere);
    }
}

header("Location: ../index.php");
?>

<html>
<head>
    <meta charset="utf-8">
    <title>Importação</title>
    <link rel="shortcut icon" href="IMG/Itens/Construcao/bloco_grama.png">

    <style>
        body{
            background-color: black;
            text-align: center !important;
            overflow-y: hidden;
            overflow-x: hidden;
        }
        img{
            position: absolute;
            left: 50%;
            top: 10%;
            margin-left: -400px;
        }
        h1{
            color: white;
            font-family: "Minecraftia";
        }
    </style>
</head>
<body>
    <img src="https://www.criatives.com.br/wp-content/uploads/2014/10/gifs_matematicas_David_Whyte_01.gif">
    <h1>Estamos importando uns dados...</h1>
</body>
</html>