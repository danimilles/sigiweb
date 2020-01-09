<?php
session_start();
include_once("gestionBD.php");
include_once("gestionUsuarios.php");
if (!isset($_SESSION["login"])) {
    Header("Location: index.php");
} else {
    $carne = $_SESSION["login"]["usuario"];
    if ($_SESSION["login"]["usuario"] != '00000P') {
        Header("Location: exito_login.php");
    }
}


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Menú principal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
    <script src="js/funciones.js"></script>
</head>

<body>

    <?php include_once("header.php") ?>
    <br><?php include_once("sidenav.php"); ?>

    <main>
        <div id="main">
            <h2>Bienvenido, Administrador</h2>
            <hr class="principal">
            <br>
            <div class="col-12-m" align="center">
                <button type="button" class="initadmin" onclick="window.location.href='form_usuario.php'">Nuevo usuario</button>
                <button type="button" class="initadmin" onclick="window.location.href='accion_nuevo_material.php'">Nuevo material</button>
                <button type="button" class="initadmin" onclick="window.location.href='accion_nuevo_libro.php'">Nuevo libro</button>
                <button type="button" class="initadmin" onclick="window.location.href='accion_nuevo_departamento.php'">Nuevo departamento</button>
                <button type="button" class="initadmin" onclick="window.location.href='accion_nuevo_aula.php'">Nuevo aula</button>
            </div>
        </div>
    </main>

    <?php include_once("footer.php"); ?>
    <script>
        document.getElementById("sinicio").className = "active"
    </script>
</body>

</html>