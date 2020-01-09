<?php
session_start();
include_once("gestionBD.php");
include_once("gestionUsuarios.php");

if (!isset($_SESSION["login"])) {
    Header("Location: index.php");
} else {
    $carne = $_SESSION["login"]["usuario"];
    $datos["carne"] = "";
    $datos["progAcademico"] = "";
    $datos["curso"] = "";
    $datos["grupo"] = "";
    if (isset($_SESSION["num_carne"])) {
        $numCarne = $_SESSION["num_carne"];
        unset($_SESSION["num_carne"]);
    } else if (isset($_REQUEST["progAcademico"])) {
        $datos["carne"] = $_REQUEST["carne"];
        $datos["progAcademico"] = $_REQUEST["progAcademico"];
        $datos["curso"] = $_REQUEST["curso"];
        $datos["grupo"] = $_REQUEST["grupo"];
        $errores = validaErrores($datos);
        if (count($errores) > 0) {
            $_SESSION["errores"] = $errores;
        } else {
            $con = crearConexionBD();
            promocionarAlumno($con, $datos["carne"], $datos["progAcademico"], $datos["curso"], $datos["grupo"]);
            cerrarConexionBD($con);
            $_SESSION["exito"] = "<p>El alumno ha sido promocionado correctamente.</p>";
            Header("Location: lista_usuarios.php");
        }
    }
}

function validaErrores($form)
{
    $errores = array();
    if (
        $form["progAcademico"] != "ESO" &&
        $form["progAcademico"] != "BACHILLERATO" &&
        $form["progAcademico"] != "FP Básica" &&
        $form["progAcademico"] != "CFGM Gestión Administrativa" &&
        $form["progAcademico"] != "CFGS Administración y Finanzas"
    ) {
        array_push($errores, "<p>El programa académico debe ser 'ESO', 'Bachillerato', 'FP Básica', 'CFGM Gestión Administrativa' o 'CFGS Administración y Finanzas'</p>");
    }
    if ($form["curso"] == "") {
        array_push($errores, "<p>El campo 'Curso' no puede estar vacío</p>");
    } else if ($form["progAcademico"] == "ESO" && ($form["curso"] < 1 || $form["curso"] > 4)) {
        array_push($errores, "<p>El curso de ESO solo puede ser 1, 2, 3 o 4</p>");
    } else if ($form["progAcademico"] != "ESO" && ($form["curso"] < 1 || $form["curso"] > 2)) {
        array_push($errores, "<p>El curso de Bachillerato, FP y los grados solo puede ser 1 o 2</p>");
    }
    if ($form["grupo"] == "") {
        array_push($errores, "<p>El campo 'Grupo' no puede estar vacío</p>");
    } else if (!preg_match("/^[ABCD]{1}$/", $form["grupo"])) {
        array_push($errores, "<p>El grupo puede ser A,B,C o D");
    }
    return $errores;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Promocionar curso - SIGI Azahar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
    <script src="js/funciones.js"></script>
</head>

<body>

    <?php include_once("header.php") ?><br>
    <?php include_once("sidenav.php"); ?>
    <div id='main'>
        <main>
            <div>
                <h2>Lista de usuarios</h2>
                <hr class="principal"><br>
            </div>

            <?php if (isset($errores)) {
                echo "<div id='error'>";
                echo "<h4>Se han producido los siguientes errores:</h4>";
                foreach ($errores as $error) {
                    echo $error;
                }
                echo "</div>";
                unset($_SESSION["errores"]);
            } ?>

            <div class="divform col-12-m col-9-t">
                <form method='post' action='accion_promocionar_curso.php'>
                    <fieldset>
                        <legend>Promocionar curso</legend><br>
                        <input type='hidden' id='carne' name='carne' value='<?php if (isset($numCarne)) echo $numCarne;
                                                                            else echo $datos["carne"]; ?>' />
                        <div>
                            <input type="number" id="curso" name="curso" min="1" max="4" title="Curso"  value='<?php if (isset($datos["curso"])) echo $datos["curso"]; ?>' />
                        </div><br>
                        <div><label>Programa académico:</label>
                            <label><input type="radio" name="progAcademico" value="ESO" <?php if ($datos["progAcademico"] == "ESO") echo " checked "; ?> />
                                ESO</label>
                            <label><input type="radio" name="progAcademico" value="BACHILLERATO" <?php if ($datos["progAcademico"] == "BACHILLERATO") echo " checked "; ?> />
                                Bachillerato</label>
                            <label><input type="radio" name="progAcademico" value="FP Básica" <?php if ($datos["progAcademico"] == "FP Básica") echo " checked "; ?> />
                                FP Básica</label>
                            <label><input type="radio" name="progAcademico" value="CFGM Gestión Administrativa" <?php if ($datos["progAcademico"] == "CFGM Gestión Administrativa") echo " checked "; ?> />
                                CFGM Gestión Administrativa</label>
                            <label><input type="radio" name="progAcademico" value="CFGS Administración y Finanzas" <?php if ($datos["progAcademico"] == "CFGS Administración y Finanzas") echo " checked "; ?> />
                                CFGS Administración y Finanzas</label>
                        </div><br>
                        <div>
                            <input type='text' id='grupo' name='grupo' placeholder="Grupo" value='<?php if (isset($datos["grupo"])) echo $datos["grupo"]; ?>' />
                        </div><br>
                        <div align="right">
                        <input type="submit" value="Enviar" /></div>
                    </fieldset>
                </form>
            </div>
        </main>
    </div>

    <br><?php include_once("footer.php") ?>
</body>

</html>