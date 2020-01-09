<?php
session_start();

if (isset($_SESSION["login"])) {
    $carne = $_SESSION["login"]["usuario"];
    if ($carne != '00000P' && $carne != '99999P') {
        Header("Location: index.php");
    }
} else header("Location: index.php");
if (!isset($_SESSION["book"]) && !isset($_SESSION["libro_controlado"])) {
    $libro["titulo"] = "";
    $libro["autor"] = "";
    $libro["isbn"] = "";
    $libro["cdu"] = "";
    $libro["genero"] = "";
    $libro["estado"] = "";
    $libro["num_copias"] = "";
    $new = false;
    $_SESSION["newb"] = $new;
    $_SESSION["book"] = $libro;
} elseif (isset($_SESSION["book"])) {
    $libro = $_SESSION["book"];
} elseif (isset($_SESSION["libro_controlado"])) {
    $libro = $_SESSION["libro_controlado"];
    $_SESSION["editando"] = true;
    $new = false;
    $_SESSION["editb"] = $new;
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
        <title>Editar libro</title>
    <?php } else { ?>
        <title>Nuevo libro</title>
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
                <h2 class="subtitulo">Editar libro</h2>
            <?php } else { ?>
                <h2 class="subtitulo">Nuevo libro</h2>
            <?php } ?>
            <hr class="inicio"><br>

            <?php
            if (isset($errores)) { ?>
                <div class="error">
                    <h4>Se han producido los siguientes errores:</h4>
                    <?php foreach ($errores as $error) {
                        echo "<p>" . $error . "</p>";
                    } ?>
                </div>
            <?php } ?>
            <div class="divform col-12-m col-7-t">
                <form method="POST" <?php if (isset($_SESSION["editando"])) { ?> id="libro_controlado" <?php } else { ?> id="book" <?php } ?> onsubmit="return validaISBN();" action="validacion_libro.php">
                    <fieldset>
                        <legend>Datos del libro</legend>
                        <input type="text" id="titulo" name="titulo" placeholder="Título" size="50" value="<?php echo $libro["titulo"] ?>" required />
                        <input type="text" id="autor" name="autor" size="50" placeholder="Autor/es" value="<?php echo $libro["autor"] ?>" required />
                        <input type="text" id="isbn" name="isbn" size="14" placeholder="ISBN" <?php if (!isset($_SESSION["editando"])) echo 'placeholder="(978-)1234567890"' ?> title="Un ISBN 10 o 13" value="<?php echo $libro["isbn"] ?>" <?php if (isset($_SESSION["editando"])) { ?> disabled <?php } else { ?> required <?php } ?> />
                        <?php if (isset($_SESSION["editando"])) { ?>
                            <input type="hidden" id="isbn" name="isbn" value="<?php echo $libro["isbn"] ?>" />
                        <?php } ?>
                        <input type="text" id="cdu" name="cdu"  size="4" placeholder="CDU" title="El CDU del libro" value="<?php echo $libro["cdu"] ?>" required />
                        <div>
                            <label><select id="genero" name="genero">
                                    <option value="Aventura" <?php if ($libro["genero"] == "Aventura") echo " selected "; ?>>Aventura</option>
                                    <option value="Acción" <?php if ($libro["genero"] == "Acción") echo " selected "; ?>>Acción</option>
                                    <option value="Comedia" <?php if ($libro["genero"] == "Comedia") echo " selected "; ?>>Comedia</option>
                                    <option value="Suspense" <?php if ($libro["genero"] == "Suspense") echo " selected "; ?>>Suspense</option>
                                    <option value="Terror" <?php if ($libro["genero"] == "Terror") echo " selected "; ?>>Terror</option>
                                    <option value="Romance" <?php if ($libro["genero"] == "Romance") echo " selected "; ?>>Romance</option>
                                    <option value="Thriller" <?php if ($libro["genero"] == "Thriller") echo " selected "; ?>>Thriller</option>
                                    <option value="Drama" <?php if ($libro["genero"] == "Drama") echo " selected "; ?>>Drama</option>
                                    <option value="Fantasía" <?php if ($libro["genero"] == "Fantasía") echo " selected "; ?>>Fantasía</option>
                                    <option value="Docencia" <?php if ($libro["genero"] == "Docencia") echo " selected "; ?>>Docencia</option>
                                    <option value="Novela" <?php if ($libro["genero"] == "Novela") echo " selected "; ?>>Novela</option>
                                </select></label>
                        </div>
                        <?php if (!isset($_SESSION["editando"])) { ?>
                            <div>
                                <label><select name="estado" id="estado">
                                        <option value="Perfecto" <?php if ($libro["estado"] == "Perfecto") echo " selected "; ?>>Perfecto</option>
                                        <option value="Bueno" <?php if ($libro["estado"] == "Bueno") echo " selected "; ?>>Bueno</option>
                                        <option value="Deteriorado" <?php if ($libro["estado"] == "Deteriorado") echo " selected "; ?>>Deteriorado</option>
                                        <option value="Malo" <?php if ($libro["estado"] == "Malo") echo " selected "; ?>>Malo</option>
                                    </select></label>
                            </div>
                        <?php } ?>
                        <div>
                            <input type="number" id="num_copias" name="num_copias" min="1" value="<?php echo $libro["num_copias"] ?>" <?php if (isset($_SESSION["editando"])) { ?> disabled <?php } else { ?> required <?php } ?> />
                        </div>
                        <div align="right">
                            <input type="submit" class="btn" value="Enviar" />
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </main>

    <br><?php include_once("footer.php") ?>
</body>

</html>