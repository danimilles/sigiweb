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
if (isset($_SESSION["departamento_controlado"])) {
    $departamento = $_SESSION["departamento_controlado"];
    $_SESSION["editando_departamento"] = true;
} elseif (isset($_SESSION["departamento"])) {
    $departamento = $_SESSION["departamento"];
} else {
    $departamento["nombre"] = "";
    $_SESSION["departamento"] = $departamento;
}

if (isset($_SESSION["errores"])) {
    $errores = $_SESSION["errores"];
    unset($_SESSION["errores"]);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php if (isset($_SESSION["editando_departamento"])) { ?>
        <title>Editar departamento</title>
    <?php } else { ?>
        <title>Nuevo departamento</title>
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
            <?php if (isset($_SESSION["editando_departamento"])) { ?>
                <h2 class="subtitulo">Editar departamento</h2>
            <?php } else { ?>
                <h2 class="subtitulo">Nuevo departamento</h2>
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
                </div> <?php
                } ?>

            <div class="divform col-12-m col-7-t">
                <form method="POST" id="departamento" action="validacion_departamento.php">
                    <fieldset>
                        <legend>Datos del departamento:</legend>
                        <input type="text" id="nombre" name="nombre" placeholder="Nombre" size="40" value="<?php echo $departamento["nombre"] ?>" required />
                        <?php if (isset($_SESSION["editando_departamento"])) { ?>
                            <input type="hidden" id="oid_d" name="oid_d" value="<?php echo $departamento["oid_d"] ?>" />
                        <?php } ?>
                        <div align="right">
                            <input type="submit" value="Enviar" class="btn" />
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </main>

    <br><?php include_once("footer.php") ?>
</body>

</html>