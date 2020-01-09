<?php
session_start();
if(isset($_SESSION["login"])) $carne = $_SESSION["login"]["usuario"];
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sobre nosotros</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
    <script src="js/funciones.js"></script>
</head>

<body>

    <?php include_once("header.php") ?><br>
    <?php include_once("sidenav.php"); ?>
    <main>
        <div id='main'>
            <div>
                <h2>Sobre el IES Azahar</h2>
                <hr class="principal"><br>
            </div>

            <div id="texto" class="divform">
                <p>Nuestro Centro educativo comenzó su andadura en el curso escolar 1988/89 para dar respuesta
                    educativa a la emergente Barriada de los Príncipes y zonas aledañas como extensión del "I.B. Pino
                    Montano". Durante el curso 1989/90 fue bautizado como "I.B. número 25" independizándose del citado
                    I.B. Pino Montano. Fue a lo largo del Curso académico 1991/92 cuando nuestro I.E.S. recibe su actual
                    nombre "Azahar".</p>
                <p>Convivimos en nuestro I.E.S. más de 500 alumnos y alumnas, 12 personas de
                    Administración y Servicios y más de 50 profesores y profesoras con la inestimable ayuda del A.M.P.A.
                    Séneca.</p>
            <br></div><br>
            <div id="fotos" align="center" class="divform">
                <img class="imgres" src="images/foto1.jpg" alt="Patio" >
                <img class="imgres" src="images/foto2.jpg" alt="Hall" >
            <br><br></div>
        </div>
    </main>

    <br><?php include_once("footer.php") ?>

</body>

</html>