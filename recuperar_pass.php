<?php
session_start();

include_once("gestionBD.php");
include_once("gestionUsuarios.php");
include_once("mails.php");

if (isset($_SESSION["login"])) Header("Location: exito_login.php");

if (isset($_POST) && !empty($_POST)) {
    $email = $_POST["email_pass"];
    $dni = $_POST["dni"];
    $conexion = crearConexionBD();
    $n = recuperarPass($conexion, $email, $dni);
    if ($n == 1) {
        $carne = carneDeEmail($conexion, $email);
        $pass = randomPassword();
        mailRecPass($email, $pass);
        cambiarPass($conexion, $carne, $pass);
    } else {
        $errores = "<div class='error'><p>No se ha podido recuperar la contraseña. Inténtelo de nuevo.</p></div>";
    }
    cerrarConexionBD($conexion);
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Recuperar contraseña</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
    <script src="js/funciones.js"></script>
</head>

<body>

    <?php include_once("header.php") ?><br>
    <?php include_once("sidenav.php"); ?>
    <main>
        <div id="main">
            <h2>Recuperar contraseña</h2>
            <hr class="inicio"><br>

            <?php
            if (isset($errores)) { 
                echo $errores;
            } else if (isset($_POST) && !empty($_POST)) {
                echo "<div class='exito'><p>Se ha enviado el correo electrónico con su nueva contraseña. Consulte su bandeja de entrada.</p></div>";
            }
            ?>

            <div class='divform col-12-m col-7-t'>
                <form method="POST" id="rec_pass" action="recuperar_pass.php">
                    <fieldset>
                        <legend>Inserta tu correo electrónico</legend>
                        <div>
                            <p>Por la seguridad de los usuarios, debe introducir su DNI.</p>
                        </div>
                        <div>
                            <input type="email"  placeholder="Correo electrónico"  id="email_pass" name="email_pass" /></div>
                        <div>
                            <input type="text" placeholder="DNI" id="dni" name="dni" /></div><br>
                        <div align="right">
                            <input type="submit" value="Enviar" /></div>
                    </fieldset>
                </form>
            </div>
        </div>
    </main>
    <br><?php include_once("footer.php") ?>

</body>

</html>