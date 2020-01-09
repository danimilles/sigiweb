<?php
session_start();
include_once("gestionBD.php");
include_once("gestionUsuarios.php");
include_once("gestionDepartamentos.php");

$carne = $_SESSION["login"]["usuario"];
if (!isset($_SESSION["login"])) {
    Header("Location: index.php");
} else if ($carne != '00000P') {
    Header("Location: exito_login.php");
}

$con = crearConexionBD();
if (isset($_SESSION["aula_controlada"])) {
    $naula = $_SESSION["aula_controlada"];
    $_SESSION["editando_aula"] = true;
} elseif (isset($_SESSION["naula"])) {
    $naula = $_SESSION["naula"];
} else {
    $naula["nombre"] = "";
    $naula["numero"] = "";
    $_SESSION["naula"] = $naula;
}

if (isset($_SESSION["errores"])) {
    $errores = $_SESSION["errores"];
    unset($_SESSION["errores"]);
}

cerrarConexionBD($con);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php if (isset($_SESSION["editando_aula"])) { ?>
        <title>Editar aula</title>
    <?php } else { ?>
        <title>Nuevo aula</title>
    <?php } ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
    <script src="js/funciones.js"></script>
</head>

<body>

    <?php include_once("header.php") ?><br>
    <?php include_once("sidenav.php"); ?>

    <main>
        <div id="main">
            <?php if (isset($_SESSION["editando_aula"])) { ?>
                <h2 class="subtitulo">Editar aula</h2>
            <?php } else { ?>
                <h2 class="subtitulo">Nuevo aula</h2>
            <?php } ?>
            <hr class="inicio"><br>
            <?php
            if (isset($errores)) { ?>
                <div class="error">
                    <h4>Se han producido los siguientes errores:</h4>
                    <?php foreach ($errores as $error) {
                        echo $error;
                    }
                    ?>
                </div>
                <?php unset($errores);
            }
            ?>

            <div class="divform col-12-m col-7-t">
                <form method="POST" id="naula" action="validacion_aula.php">
                    <fieldset>
                        <legend>Datos del aula</legend>
                        <div>
                            <input type="text" id="nombre" name="nombre" placeholder="Nombre" value="<?php echo $naula["nombre"] ?>" required />
                        </div>
                        <?php if (isset($_SESSION["editando_aula"])) { ?>
                            <input type="hidden" id="numero" name="numero" value="<?php echo $naula["numero"] ?>" />
                        <?php } else { ?>
                            <div>
                                <input type="text" id="numero" name="numero" placeholder="Número" pattern="^[0-9]+" value="<?php echo $naula["numero"] ?>" required />
                            </div>
                        <?php } ?>
                        <div align="right">
                            <input type="submit" value="Enviar" class="btn" /></div>
                    </fieldset>
                </form>
            </div>
        </div>
    </main>

    <br><?php include_once("footer.php") ?>
</body>

</html>