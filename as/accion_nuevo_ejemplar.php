<?php
session_start();

if (isset($_SESSION["login"])) {
    $carne = $_SESSION["login"]["usuario"];
    if ($carne != '00000P' && $carne != '99999P') {
        Header("Location: index.php");
    }
} else header("Location: index.php");

if (!isset($_SESSION["ejemplar"]) && !isset($_SESSION["ejemplar_controlado"])) {
    $ejemplar["isbn"] = "";
    $ejemplar["codigo"] = "";
    $ejemplar["estado"] = "";
    $new = false;
    $_SESSION["newe"] = $new;
    $_SESSION["ejemplar"] = $ejemplar;
} elseif (isset($_SESSION["ejemplar"])) {
    $ejemplar = $_SESSION["ejemplar"];
} elseif (isset($_SESSION["ejemplar_controlado"])) {
    $ejemplar = $_SESSION["ejemplar_controlado"];
    $_SESSION["editando"] = true;
    $new = false;
    $_SESSION["edite"] = $new;
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
    <?php if (isset($_SESSION["editando"])) { ?>
        <title>Editar ejemplar</title>
    <?php } else { ?>
        <title>Nuevo ejemplar</title>
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
            <?php if (isset($_SESSION["editando"])) { ?>
                <h2 class="subtitulo">Editar ejemplar</h2>
            <?php } else { ?>
                <h2 class="subtitulo">Nuevo ejemplar</h2>
            <?php } ?>
            <hr class="inicio"><br>

            <?php
            if (isset($errores)) { ?>
                <div class="error">
                    <h4>Se han producido los siguientes errores:</h4>
                    <?php foreach ($errores as $error) {
                        echo $error;
                    } ?>
                </div>
            <?php } ?>

            <div class='divform col-12-m col-7-t'>
                <form method="POST" <?php if (isset($_SESSION["editando"])) { ?> id="ejemplar_controlado" <?php } else { ?> id="ejemplar" <?php } ?> onsubmit="return validaISBN();" action="validacion_ejemplar.php">
                    <fieldset>
                        <legend>Datos del ejemplar</legend>
                        <div>
                            <input type="text" id="isbn" name="isbn" size="14" <?php if (!isset($_SESSION["editando"])) echo 'placeholder="(978-)1234567890"' ?> title="Un ISBN 10 o 13" value="<?php echo $ejemplar["isbn"] ?>" <?php if (isset($_SESSION["editando"])) { ?> disabled <?php } else { ?> required <?php } ?> />
                        </div>
                        <?php if (isset($_SESSION["editando"])) { ?>
                            <input type="hidden" id="codigo" name="codigo" value="<?php echo $ejemplar["codigo"] ?>" />
                        <?php } ?>
                        <?php if (isset($_SESSION["editando"])) { ?>
                            <input type="hidden" id="isbn" name="isbn" value="<?php echo $ejemplar["isbn"] ?>" />
                        <?php } ?>
                        <div>
                            <label><select name="estado" id="estado">
                                    <option value="Perfecto" <?php if ($ejemplar["estado"] == "Perfecto") echo " selected "; ?>>Perfecto</option>
                                    <option value="Bueno" <?php if ($ejemplar["estado"] == "Bueno") echo " selected "; ?>>Bueno</option>
                                    <option value="Deteriorado" <?php if ($ejemplar["estado"] == "Deteriorado") echo " selected "; ?>>Deteriorado</option>
                                    <option value="Malo" <?php if ($ejemplar["estado"] == "Malo") echo " selected "; ?>>Malo</option>
                                </select></label>
                        </div>
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