<?php
session_start();
include_once("gestionBD.php");
include_once("gestionLibros.php");
$carne = $_SESSION["login"]["usuario"];
if (!isset($_SESSION["login"])) {
    Header("Location: index.php");
} else if ($carne != '00000P' && $carne != '99999P') {
    Header("Location: exito_login.php");
}
if (isset($_SESSION["paginacion"])) {
    $paginacion = $_SESSION["paginacion"];
} else {
    $paginacion["pag_num"] = 1;
    $paginacion["pag_size"] = 10;
}

$page_num = isset($_POST["page_num"]) ? (int)$_POST["page_num"] : $paginacion["pag_num"];
$page_size = isset($_POST["page_size"]) ? (int)$_POST["page_size"] : $paginacion["pag_size"];

if ($page_num < 1) $page_num = 1;
if ($page_size < 1) $page_size = 10;

unset($_SESSION["paginacion"]);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Estadísticas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
    <script src="js/funciones.js"></script>
</head>

<body>

    <?php include_once("header.php") ?><br>
    <?php include_once("sidenav.php");
    $pagstr = isset($page_num) ? ('&page_num=' . $page_num . '&page_size=' . $page_size) : ('');
    ?>
    <main>
        <div id="main">
            <h2>Estadísticas</h2>
            <hr class="principal">

            <br>
            <div id="stats" align="center">
                <button type="button" class="btn" onclick="muestraEstadistica('estype=alumnos<?= $pagstr ?>')">
                    Alumnos que más préstamos hacen</button>
                <button type="button" class="btn" onclick="muestraEstadistica('estype=sexo&sexo=M<?= $pagstr ?>')">
                    Libros más prestados por sexo</button>
                <button type="button" class="btn" onclick="muestraEstadistica('estype=edad&edad=12<?= $pagstr ?>')">
                    Libros más prestados por edad</button>
                <button type="button" class="btn" onclick="muestraEstadistica('estype=curso&curso=1&programa=ESO<?= $pagstr ?>')">
                    Libros más prestados por curso</button>
                <button type="button" class="btn" onclick="muestraEstadistica('estype=genero<?= $pagstr ?>')">
                    Géneros mas prestados</button>
                <button type="button" class="btn" onclick="muestraEstadistica('estype=sanciones<?= $pagstr ?>')">
                    Alumnos sancionados</button>
            </div><br>

            <div id=tablaes class="divform"></div>


            <?php if (isset($_POST["tipo"])) {
                $tipo = $_POST["tipo"]; ?>
                <script>
                    muestraEstadistica('estype=<?= $tipo ?><?= $pagstr ?>');
                </script>

            <?php } ?>
        </div>
    </main>
    <script>
        document.getElementById("sestadisticas").className = "active"
    </script>

    <?php include_once("footer.php") ?>
</body>

</html>