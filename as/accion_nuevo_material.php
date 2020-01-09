<?php
session_start();
include_once("gestionBD.php");
include_once("gestionUsuarios.php");
include_once("gestionDepartamentos.php");
include_once("gestionLibros.php");

if (isset($_SESSION["history"]) && $_SESSION["history"] != "inventario") {
    unset($_SESSION["material"]);
}
$page = "inventario";
$_SESSION["history"] = $page;
if (isset($_SESSION["login"])) {
    $conexion = crearConexionBD();
    $carne = $_SESSION["login"]["usuario"];
    if (!esJefeDepartamento($conexion, $carne) && $carne != '00000P') {
        Header("Location: exito_login.php");
    }
} else Header("Location: index.php");
if (isset($_SESSION["material_controlado"])) {
    $material = $_SESSION["material_controlado"];
    $_SESSION["editando_material"] = true;
} elseif (isset($_SESSION["material"])) {
    $material = $_SESSION["material"];
} else {
    $material["descripcion"] = "";
    $material["estado"] = "";
    $material["unidades"] = "";
    $material["departamento"] = "";
    $new = false;
    $_SESSION["newm"] = $new;
    $_SESSION["material"] = $material;
}

if (isset($_SESSION["errores"])) {
    $errores = $_SESSION["errores"];
    unset($_SESSION["errores"]);
}
cerrarConexionBD($conexion);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php if (isset($_SESSION["editando_material"])) { ?>
        <title>Editar material</title>
    <?php } else { ?>
        <title>Nuevo material</title>
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
            <?php if (isset($_SESSION["editando_material"])) { ?>
                <h2 class="subtitulo">Editar material</h2>
            <?php } else { ?>
                <h2 class="subtitulo">Nuevo material</h2>
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
            <div class="divform col-12-m col-7-t">
                <form method="POST" id="material" action="validacion_material.php">
                    <fieldset>
                        <legend>Datos del material</legend>
                        <input type="text" id="descripcion" placeholder="Descripción" name="descripcion" size="80" value="<?php echo $material["descripcion"] ?>" required />
                        <div>
                            <label><select name="estado" id="estado">
                                    <option value="Perfecto" <?php if ($material["estado"] == "Perfecto") echo " selected "; ?>>Perfecto</option>
                                    <option value="Bueno" <?php if ($material["estado"] == "Bueno") echo " selected "; ?>>Bueno</option>
                                    <option value="Deteriorado" <?php if ($material["estado"] == "Deteriorado") echo " selected "; ?>>Deteriorado</option>
                                    <option value="Malo" <?php if ($material["estado"] == "Malo") echo " selected "; ?>>Malo</option>
                                </select></label>
                        </div>
                        <div>
                            <input type="number" id="unidades" name="unidades" min="1" value="<?php echo $material["unidades"] ?>" required />
                        </div>
                        <div>
                            <label><select name="departamento" id="departamento" <?php if ($carne != '00000P') echo "hidden" ?>>
                                    <?php
                                    $conexion = crearConexionBD();
                                    $departamentos = todosLosDepartamentos($conexion);
                                    cerrarConexionBD($conexion);
                                    foreach ($departamentos as $dept) {
                                        ?>
                                        <option value="<?php echo $dept["NOMBRE"]; ?>" <?php if ($material["departamento"] == $dept["NOMBRE"]) echo " selected "; ?>><?php echo $dept["NOMBRE"] ?></option>
                                    <?php } ?>
                                </select></label>
                        </div>
                        <?php if (isset($_SESSION["editando_material"])) { ?>
                            <input type="hidden" id="oid_m" name="oid_m" value="<?php echo $material["oid_m"] ?>" />
                        <?php } ?>
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