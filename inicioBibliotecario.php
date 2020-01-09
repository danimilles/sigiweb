<?php
session_start();
include_once("gestionBD.php");
include_once("gestionUsuarios.php");
if (!isset($_SESSION["login"])) {
    Header("Location: index.php");
} else {
    $carne = $_SESSION["login"]["usuario"];
    if ($_SESSION["login"]["usuario"] != '99999P') {
        Header("Location: exito_login.php");
    }
}


if (!isset($_SESSION["prestamo"])) {
    $prestamo["ncarne"] = "";
    $prestamo["codigo"] = "";
    $_SESSION["prestamo"] = $prestamo;
}
else $prestamo = $_SESSION["prestamo"];

if (!isset($_SESSION["prestamo_devolver"])) {
    $prestamo_devolver["codigo_devolver"] = "";
    $_SESSION["prestamo_devolver"] = $prestamo_devolver;
} else $prestamo_devolver = $_SESSION["prestamo_devolver"];
if (isset($_SESSION["errores"])) {
    $errores = $_SESSION["errores"];
    $_SESSION["errores"] = null;
} else if (isset($_SESSION["exito"])) {
    $exito = $_SESSION["exito"];
    unset($_SESSION["exito"]);
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
    <?php include_once("header.php") ?><br>
    <?php include_once("sidenav.php"); ?>

    <main>
        <div id="main">
            <h2>Bienvenido, Bibliotecario</h2>
            <hr class="principal">
            <br>
            <?php
            if (isset($errores) && count($errores) > 0) {
                echo "<div class=\"error\">";
                echo "<h4>Se han producido los siguientes errores:</h4>";
                foreach ($errores as $error) echo '<p>'.$error.'</p>';
                echo "</div>";
            } else if (isset($exito)) {
                echo "<div class=\"exito\">";
                echo $exito;
                echo "</div>";
            }
            ?>

            <div>
                <div class="divform col-12-m col-7-t inline">
                    <fieldset>
                        <legend>Hacer un préstamo</legend>
                        <form id="prestamo" method="POST" action="validacion_prestamos.php">
                            <input type='text' name="codigo" pattern="^[0-9]+" placeholder="Código del ejemplar" value="<?php echo $prestamo["codigo"] ?>" required />
                            <input type='text' name="ncarne" pattern="^[0-9]{5}[AP]" placeholder="Número de carne" value="<?php echo $prestamo["ncarne"] ?>" required />
                            <div align="right"> <input class="btn" type='submit' name="sub" id="sub" value="Prestar">
                            </div>
                        </form>
                </div>
                <br><br>
                <div class="divform col-12-m col-7-t inline">
                    <fieldset>
                        <legend>Devolución</legend>
                        <form id="prestamo_devolver" method="POST" action="validacion_devolver_prestamos.php">
                            <input type='text' name="codigo_devolver" pattern="^[0-9]+" placeholder="Código del ejemplar" value="<?php echo $prestamo_devolver["codigo_devolver"] ?>" required />
                            <div align="right"> <input class="btn" type='submit' name="sub" id="sub" value="Devolver">
                            </div>
                        </form>
                    </fieldset>
                </div>

            </div><br>
            <div class="col-12-m" align="center">
                <button type="button" class="initadmin" onclick="window.location.href='form_usuario.php'">Nuevo usuario</button>
                <button type="button" class="initadmin" onclick="window.location.href='accion_nuevo_libro.php'">Nuevo libro</button>
            </div>
        </div>
    </main>
    <script>
        document.getElementById("sinicio").className = "active"
    </script>

    <?php include_once("footer.php"); ?>
</body>

</html>