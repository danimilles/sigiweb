<?php
session_start();
if (isset($_SESSION["login"])) $carne = $_SESSION["login"]["usuario"];
if (isset($_SESSION["excepcion"])) {
	$excepcion = $_SESSION["excepcion"];
	unset($_SESSION["excepcion"]);
} else {
	$excepcion = "-No info-";
}

if (isset($_SESSION["destino"])) {
	$destino = $_SESSION["destino"];
	unset($_SESSION["destino"]);
} else $destino = "";

?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
	<script src="js/funciones.js"></script>
	<title>¡Se ha producido un problema!</title>
</head>

<body>

	<?php include_once("header.php"); ?><br>
	<?php include_once("sidenav.php"); ?>
	<main>
		<div id="main">
			<div class="divform pad margini row col-12-m col-10-es">
				<div align="center" class='col-5-es margini col-12-m img-error'>
					<img src="images/error.png" class='errorimg'>
				</div><br>
				<div class='col-5-es col-12-m text-error'>
					<h2>Ups!</h2>
					<?php if ($destino <> "") { ?>
						<p>Ocurrió un problema durante el procesado de los datos. Pulse <a href="<?php echo $destino ?>">aquí</a> para volver a la página principal.</p>
					<?php } else { ?>
						<p>Ocurrió un problema para acceder a la base de datos. </p>
					<?php } ?>
					<?php echo "<p>Información relativa al problema: .$excepcion .</p>" ?>
					
				</div>

			</div>
		</div>
	</main>
	<?php include_once("footer.php"); ?>

</body>

</html>