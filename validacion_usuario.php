<?php
session_start();
include_once("gestionBD.php");
include_once("gestionUsuarios.php");
if (!isset($_SESSION["login"])) {
    Header("Location: index.php");
}
$carne = $_SESSION["login"]["usuario"];
if ($carne != '00000P' && $carne != '99999P') {
    Header("Location: exito_login.php");
}

if (isset($_SESSION["formulario"])) {
    $newUser["tipoUsuario"] = $_REQUEST["tipoUsuario"];
    $newUser["nombre"] = $_REQUEST["nombre"];
    $newUser["apellidos"] = $_REQUEST["apellidos"];
    $newUser["dni"] = $_REQUEST["dni"];
    $newUser["sexo"] = $_REQUEST["sexo"];
    $newUser["fechaNacimiento"] = $_REQUEST["fechaNacimiento"];
    $newUser["email"] = $_REQUEST["email"];
    $newUser["telefono"] = $_REQUEST["telefono"];
    if ($newUser["tipoUsuario"] == "alumno") {
        $newUser["progAcademico"] = $_REQUEST["progAcademico"];
        $newUser["curso"] = $_REQUEST["curso"];
        $newUser["grupo"] = $_REQUEST["grupo"];
        $newUser["departamento"] = "";
        $newUser["jefe"] = "";
    } else {
        $newUser["progAcademico"] = "";
        $newUser["curso"] = "";
        $newUser["grupo"] = "";
        $newUser["departamento"] = $_REQUEST["departamento"];
        $newUser["jefe"] = isset($_REQUEST["jefe"])? $_REQUEST["jefe"] : "";
    }
    $errores = validacionErrores($newUser);
    if ($_FILES["foto"]["error"] == 0) {
        $valid_extensions = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');
        if (!in_array($_FILES["foto"]["type"], $valid_extensions)) {
            array_push($errores, "<p>El formato de la foto no es valido</p>");
        }
    } else if ($_FILES["foto"]["error"] == 1) {
        array_push($errores, "<p>La imagen es demasiado grande</p>");
    } else {
        array_push($errores, "<p>Se ha producido un error al subir la imagen</p>");
    }

    if (count($errores) > 0) {
        $_SESSION["errores"] = $errores;
        $_SESSION["formulario"] = $newUser;
        Header("Location: form_usuario.php");
    }
} else Header("Location: form_usuario.php");

function validacionErrores($usuario)
{
    $errores = array();
    if ($usuario["tipoUsuario"] != "alumno" && $usuario["tipoUsuario"] != "profesor") {
        array_push($errores, "<p>El tipo de usuario debe ser 'Alumno' o 'Profesor'</p>");
    }
    if ($usuario["dni"] == "") {
        array_push($errores, "<p>El campo 'DNI' no puede estar vacío</p>");
    } else if (!preg_match("/^[0-9]{8}[A-Z]$/", $usuario["dni"])) {
        array_push($errores, "<p>El DNI debe estar formado por 8 dígitos y una letra mayúscula</p>");
    }
    if ($usuario["nombre"] == "") {
        array_push($errores, "<p>El campo 'Nombre' no puede estar vacío</p>");
    }
    if ($usuario["apellidos"] == "") {
        array_push($errores, "<p>El campo 'Apellidos' no puede estar vacío</p>");
    }
    if ($usuario["sexo"] != "M" && $usuario["sexo"] != "F") {
        array_push($errores, "<p>El sexo debe ser masculino o femenino</p>");
    }
    if ($usuario["email"] == "") {
        array_push($errores, "<p>El campo 'Correo electrónico' no puede estar vacío</p>");
    } else if (!filter_var($usuario["email"], FILTER_VALIDATE_EMAIL)) {
        array_push($errores, "<p>El correo electrónico introducido no es correcto</p>");
    }
    if ($usuario["telefono"] != "" && !filter_var($usuario["telefono"], FILTER_VALIDATE_INT)) {
        array_push($errores, "<p>El teléfono no es un número</p>");
    }
    if ($usuario["tipoUsuario"] == "alumno") {
        if (
            $usuario["progAcademico"] != "ESO" &&
            $usuario["progAcademico"] != "BACHILLERATO" &&
            $usuario["progAcademico"] != "FP Básica" &&
            $usuario["progAcademico"] != "CFGM Gestión Administrativa" &&
            $usuario["progAcademico"] != "CFGS Administración y Finanzas"
        ) {
            array_push($errores, "<p>El programa académico debe ser 'ESO', 'Bachillerato', 'FP Básica', 'CFGM Gestión Administrativa' o 'CFGS Administración y Finanzas'</p>");
        }
        if ($usuario["curso"] == "") {
            array_push($errores, "<p>El campo 'Curso' no puede estar vacío</p>");
        } else if ($usuario["progAcademico"] == "ESO" && ($usuario["curso"] < 1 || $usuario["curso"] > 4)) {
            array_push($errores, "<p>El curso de ESO solo puede ser 1, 2, 3 o 4</p>");
        } else if ($usuario["progAcademico"] != "ESO" && ($usuario["curso"] < 1 || $usuario["curso"] > 2)) {
            array_push($errores, "<p>El curso de Bachillerato, FP y los grados solo puede ser 1 o 2</p>");
        }

        if ($usuario["grupo"] == "") {
            array_push($errores, "<p>El campo 'Grupo' no puede estar vacío</p>");
        } else if (!preg_match("/^[ABCD]{1}$/", $usuario["grupo"])) {
            array_push($errores, "<p>El grupo puede ser A,B,C o D");
        }
    } else {
        if ($usuario["jefe"] != "SI" && $usuario["jefe"] != "") {
            array_push($errores, "<p>El campo ¿Es jefe del departamento? debe valer 'Sí' o 'No'</p>");
        }
    }

    return $errores;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Formulario realizado</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
    <script src="js/funciones.js"></script>
</head>

<body>
    <?php include_once("header.php") ?><br>
    <?php include_once("sidenav.php"); ?>
    <main>
        <div id="main">
            <h2>Formulario realizado</h2>
            <hr class="inicio"><br>
            <?php
            $conexion = crearConexionBD();
            if ($newUser != null && (count($errores) == 0)) {
                if (altaUsuario($conexion, $newUser)) {
                    cambiaFoto($conexion, carneDeEmail($conexion, $newUser["email"]), "foto");
                    if ($newUser["jefe"] == "SI") {
                        setJefeDepartamento($conexion, carneDeEmail($conexion, $newUser["email"]));
                    }

                    ?>
                    <div id="exito" class="exito">
                        <h4>El usuario ha sido dado de alta correctamente.</h4>
                        <p>Pulse <a href='form_usuario.php' class='a_white'>aquí</a> para dar de alta a otro usuario o <a href="exito_login.php" class="a_white">aquí</a> para volver al menú principal.</p>
                    </div>
                    <?php $_SESSION["formulario"] = null;
                }
                else { ?>
                    <div id="fallo" class="fallo">
                        <h4>Lo sentimos, se ha producido un error.</h4>
                        <p>Pulse <a href="form_usuario.php">aquí</a> para volver al formulario.</p>
                    </div>
                <?php }
        } ?>
        </div>
    </main>
    <?php include_once("footer.php"); ?>

</body>

</html>

<?php cerrarConexionBD($conexion); ?>