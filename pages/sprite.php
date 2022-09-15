<!DOCTYPE html>
<html lang="pt">

<head>
	<meta charset="utf-8">
	<title>Inventário</title>
	<link rel="shortcut icon" href="IMG/Itens/new/Construcao/grass_block.png">

	<style type="text/css">
		img {
			top: 50%;
			left: 50%;
			margin: 0;
			width: 480px;
			position: absolute;
			image-rendering: pixelated;
			transform: translate(-50%, -50%);
			-ms-transform: translate(-50%, -50%);
			filter: drop-shadow(0px 0px 500px white);
		}

		body {
			overflow: hidden;
			background: black;
		}
	</style>
</head>

<body>
	<?php $caminho = $_GET["caminho"]; ?>

	<img src="<?php echo $caminho ?>">
</body>

</html>