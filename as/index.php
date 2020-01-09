<?php

session_start();

include_once("gestionBD.php");
include_once("gestionUsuarios.php");

if (isset($_POST["usuario"], $_POST["pass"])) {
    $login["usuario"] = $_POST["usuario"];
    $login["pass"] = $_POST["pass"];
    $conexion = crearConexionBD();
    $n = consultarUsuario($conexion, $login["usuario"], $login["pass"]);
    $carne = carneDeEmail($conexion, $login["usuario"]);
    cerrarConexionBD($conexion);
    if ($n) {
        $login["usuario"] = $carne;
        $conexion = crearConexionBD();
        $login["jefeDept"] = esJefeDepartamento($conexion, $carne);
        cerrarConexionBD($conexion);
        $_SESSION["login"] = $login;

        Header("Location: exito_login.php");
    }
}
else if (isset($_SESSION["login"])) Header("Location: exito_login.php");


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Inicio: SIGI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
    <script type="text/javascript" src="js/funciones.js" charset="utf-8"></script>
</head>

<body class="index">

    <?php include_once("header.php") ?><br>
    <?php include_once("sidenav.php"); ?>

    <div id="main">


        <?php
        if (isset($login)) {
            echo "<div class=\"error\">";
            if (!isset($_SESSION["errores"])) echo "<p>El usuario o la contraseña son incorrectos, o el usuario no existe.</p>";
            else {
                $errores = $_SESSION["errores"];
                foreach ($errores as $error) {
                    echo $error;
                }
                unset($_SESSION["errores"]);
            }
            echo "</div>";
        }
        ?>

        <div id="log" class=" containerlogin loginback col-10-m col-4-es col-6-t index">
            <form class="form-signin" method="POST" id="login" accion="index.php" novalidate>
                <fieldset class="f">
                    <legend>Introduce tus datos</legend>
                    <div>
                        <div>
                            <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Correo electrónico o número de carné" value="<?php if (isset($login)) echo $login["usuario"] ?>" />
                        </div>
                        <div>
                            <input type="password" id="pass" name="pass" class="form-control" placeholder="Contraseña" />
                        </div>
                        <br>
                        <div align="right">
                            <a href="recuperar_pass.php" class="btn">¿Has olvidado tu contraseña?</a>
                            <input type="submit" class="btn" value="Entrar" />
                        </div>
                        <p>¿No tienes cuenta? ¡Pídesela al bibliotecario!</p>
                    </div>
                </fieldset>
            </form>


        </div>
    </div>

    <?php include_once("footer.php") ?>

</body>

</html>