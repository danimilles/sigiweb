<?php
session_start();
include_once("gestionBD.php");
include_once("gestionUsuarios.php");
include_once("gestionDepartamentos.php");

if (!isset($_SESSION["login"])) {
    Header("Location: index.php");
} else {
    $errores = array();
    $carne = $_SESSION["login"]["usuario"];
    if ($carne != '00000P' && $carne != '99999P') {
        Header("Location: index.php");
    } else if (isset($_SESSION["num_carne"])) {
        $num_carne = $_SESSION["num_carne"];
        $con = crearConexionBD();
        $datos = perfilUsuario($con, $num_carne);
        cerrarConexionBD($con);


        if (isset($_POST["1"])) {
            $newPass = $_POST["newPass"];
            $repNewPass = $_POST["repeatNewPass"];
            $len = strlen($newPass);
            if ($newPass == "" || $len < 10) {
                array_push($errores, "<p>La contraseña debe tener más de 10 caracteres</p>");
            } else if (
                !preg_match("/[0-9]+/", $newPass) ||
                !preg_match("/[a-z]+/", $newPass) ||
                !preg_match("/[A-Z]+/", $newPass)
            ) {
                array_push($errores, "<p>La contraseña debe estar formada por al menos una letra mayúscula, una letra minúscula y un dígito</p>");
            } else if ($newPass != $repNewPass) {
                array_push($errores, "<p>Las contraseñas no coinciden</p>");
            } else {
                $con = crearConexionBD();
                cambiarPass($con, $num_carne, $newPass);
                cerrarConexionBD($con);
                unset($_SESSION["num_carne"]);
                $_SESSION["exito"] = "<p>El usuario se ha editado correctamente.</p>";
                Header("Location: lista_usuarios.php");
            }
        } else if (isset($_POST["2"])) {
            $datos["NOMBRE"] = $_REQUEST["nombre"];
            $datos["APELLIDOS"] = $_REQUEST["apellidos"];
            $datos["DNI"] = $_REQUEST["dni"];
            $datos["SEXO"] = $_REQUEST["sexo"];
            $datos["FECHANACIMIENTO"] = $_REQUEST["fechaNacimiento"];
            $datos["EMAIL"] = $_REQUEST["email"];
            $datos["TELEFONO"] = $_REQUEST["telefono"];
            if (substr($num_carne, 5) == "A") {
                $datos["PROGRAMA_ACADEMICO"] = $_REQUEST["progAcademico"];
                $datos["CURSO"] = $_REQUEST["curso"];
                $datos["GRUPO"] = $_REQUEST["grupo"];
                $datos["departamento"] = "";
                $datos["jefe"] = "";
            } else {
                $datos["PROGRAMA_ACADEMICO"] = "";
                $datos["CURSO"] = "";
                $datos["GRUPO"] = "";
                $datos["departamento"] = $_REQUEST["departamento"];
                $datos["jefe"] = $_REQUEST["jefe"];
            }

            if ($datos["DNI"] == "") {
                array_push($errores, "<p>El campo 'DNI' no puede estar vacío</p>");
            } else if (!preg_match("/^[0-9]{8}[A-Z]$/", $datos["DNI"])) {
                array_push($errores, "<p>El DNI debe estar formado por 8 dígitos y una letra mayúscula</p>");
            }

            if ($datos["NOMBRE"] == "") {
                array_push($errores, "<p>El campo 'Nombre' no puede estar vacío</p>");
            }

            if ($datos["APELLIDOS"] == "") {
                array_push($errores, "<p>El campo 'Apellidos' no puede estar vacío</p>");
            }

            if ($datos["SEXO"] != "M" && $datos["SEXO"] != "F") {
                array_push($errores, "<p>El sexo debe ser masculino o femenino</p>");
            }

            if ($datos["EMAIL"] == "") {
                array_push($errores, "<p>El campo 'Correo electrónico' no puede estar vacío</p>");
            } else if (!filter_var($datos["EMAIL"], FILTER_VALIDATE_EMAIL)) {
                array_push($errores, "<p>El correo electrónico introducido no es correcto</p>");
            }

            if ($datos["TELEFONO"] != "" && !filter_var($datos["TELEFONO"], FILTER_VALIDATE_INT)) {
                array_push($errores, "<p>El teléfono no es un número</p>");
            }

            if (substr($num_carne, 5) == "A") {

                if (
                    $datos["PROGRAMA_ACADEMICO"] != "ESO" &&
                    $datos["PROGRAMA_ACADEMICO"] != "BACHILLERATO" &&
                    $datos["PROGRAMA_ACADEMICO"] != "FP Básica" &&
                    $datos["PROGRAMA_ACADEMICO"] != "CFGM Gestión Administrativa" &&
                    $datos["PROGRAMA_ACADEMICO"] != "CFGS Administración y Finanzas"
                ) {
                    array_push($errores, "<p>El programa académico debe ser 'ESO', 'Bachillerato', 'FP Básica', 'CFGM Gestión Administrativa' o 'CFGS Administración y Finanzas'</p>");
                }

                if ($datos["CURSO"] == "") {
                    array_push($errores, "<p>El campo 'Curso' no puede estar vacío</p>");
                } else if ($datos["PROGRAMA_ACADEMICO"] == "ESO" && ($datos["CURSO"] < 1 || $datos["CURSO"] > 4)) {
                    array_push($errores, "<p>El curso de ESO solo puede ser 1, 2, 3 o 4</p>");
                } else if ($datos["PROGRAMA_ACADEMICO"] != "ESO" && ($datos["CURSO"] < 1 || $datos["CURSO"] > 2)) {
                    array_push($errores, "<p>El curso de Bachillerato, FP y los grados solo puede ser 1 o 2</p>");
                }

                if ($datos["GRUPO"] == "") {
                    array_push($errores, "<p>El campo 'Grupo' no puede estar vacío</p>");
                } else if (!preg_match("/^[ABCD]{1}$/", $datos["GRUPO"])) {
                    array_push($errores, "<p>El grupo puede ser A,B,C o D");
                }
            } else {
                $con = crearConexionBD();
                if ($datos["departamento"] == "") {
                    array_push($errores, "<p>El departamento no puede estar vacío");
                } else if (empty(obtenerOidDepartamento($con, $datos["departamento"]))) {
                    array_push($errores, "<p>El departamento dado no existe");
                }
                if ($datos["jefe"] != "SI" && $datos["jefe"] != "") {
                    array_push($errores, "<p>El campo ¿Es jefe del departamento? debe valer 'Sí' o 'No'</p>");
                }
            }

            if (count($errores) == 0) {
                $con = crearConexionBD();
                actualizarDatosUsuario($con, $num_carne, $datos);
                cerrarConexionBD($con);
                unset($_SESSION["num_carne"]);
                $_SESSION["exito"] = "<p>El usuario se ha editado correctamente.</p>";
                Header("Location: lista_usuarios.php");
            }
        } else if (isset($_POST["3"])) {
            if ($_FILES["foto"]["error"] == 0) {
                $valid_extensions = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');
                if (!in_array($_FILES["foto"]["type"], $valid_extensions)) {
                    array_push($errores, "<p>El formato de la foto no es valido</p>");
                } else {
                    $con = crearConexionBD();
                    cambiaFoto($con, $num_carne, "foto");
                    cerrarConexionBD($con);
                    unset($_SESSION["num_carne"]);
                    $_SESSION["exito"] = "<p>El usuario se ha editado correctamente.</p>";
                    Header("Location: lista_usuarios.php");
                }
            } else if ($_FILES["foto"]["error"] == 1) {
                array_push($errores, "<p>La imagen es demasiado grande</p>");
            } else {
                array_push($errores, "<p>Se ha producido un error al subir la imagen</p>");
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Editar perfil - SIGI Azahar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
    <script src="js/funciones.js"></script>
</head>

<body>

    <?php include_once("header.php") ?><br>

    <?php include_once("sidenav.php"); ?>


    <main>
        <div id='main'>
            <h2>Editar perfil</h2>
            <hr class="principal"><br>

            <?php if (isset($_POST["1"]) || isset($_POST["2"]) || isset($_POST["3"])) {
                if (count($errores) > 0) { ?>
                    <div id="errores_pwd" class="fallo">
                        <h5>Se han producido los siguientes errores:</h5><br>
                        <?php foreach ($errores as $error) echo $error; ?>
                    </div>
                <?php }
        }
        ?>
            <div>
                <div class="divform col-12-m col-9-t">
                    <form id="fotoForm" method="POST" enctype="multipart/form-data" action="accion_editar_perfil.php">
                        <fieldset id=fotoform>
                            <legend>Foto</legend>
                            <div>
                                <?php echo (($datos["FOTO"] != null) ? "<img src=\"data:image/png;base64," . base64_encode(stream_get_contents($datos["FOTO"])) . "\" width=100px height=140px />" : ""); ?>
                                <br></div>
                            <div>
                                <label for="foto">Subir</label>
                                <input type="file" id="foto" name="foto" title="Foto" />
                            </div>
                            <p>Tamaño máximo: 2MB.<br>
                                Formatos válidos: Gif, Jpeg, Jpg y Png.</p>
                            <div align="right">
                                <input type="submit" class="btn" name="3" id="3" value="Enviar" />
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
            <br>
            <div class="divform col-12-m col-9-t">
                <form id="cambioPerfil" method="POST" action="accion_editar_perfil.php">
                    <fieldset id=perfil>
                        <legend>Configurar perfil</legend>
                        <p><?php echo "Fecha de vencimiento de carné: " . $datos["FECHA_VALIDEZ_CARNE"] . "<br>"; ?>
                        </p>
                            <input type="text" id="carn" name="carn" size="50" value="<?php echo $datos["NUM_CARNE"] ?>" disabled />
                        <div>
                            <input type="text" id="nombre" placeholder="Nombre" name="nombre" size="50" value="<?php echo $datos["NOMBRE"] ?>" required />
                        </div>
                        <div>
                            <input type="text" placeholder="Apellidos" id="apellidos" name="apellidos" size="50" value="<?php echo $datos["APELLIDOS"] ?>" required />
                        </div>
                        <div>
                            <input type="text" id="dni" name="dni" placeholder="12345678X" pattern="^[0-9]{8}[A-Z]" title="8 dígitos seguidos de una letra mayúscula" value="<?php echo $datos["DNI"] ?>" required />
                        </div>
                        <div>
                            <input type="date" id="fechanacimiento" name="fechaNacimiento" title="Fecha de nacimiento" value="<?php echo date('Y-m-d', strtotime($datos["FECHANACIMIENTO"])) ?>" required />
                        </div><br>

                        <div><label>Sexo:</label>
                            <label><input type="radio" name="sexo" value="M" <?php if ($datos["SEXO"] == "M") echo " checked "; ?>>
                                Masculino</label>
                            <label><input type="radio" name="sexo" value="F" <?php if ($datos["SEXO"] == "F") echo " checked "; ?>>
                                Femenino</label>
                        </div><br>

                        <?php if (substr($num_carne, 5) == "A") { ?>

                            <div>Programa academico:
                                <label><input type="radio" name="progAcademico" value="ESO" <?php if ($datos["PROGRAMA_ACADEMICO"] == "ESO") echo " checked "; ?> />
                                    ESO</label>
                                <label><input type="radio" name="progAcademico" value="BACHILLERATO" <?php if ($datos["PROGRAMA_ACADEMICO"] == "BACHILLERATO") echo " checked "; ?> />
                                    Bachillerato</label>
                                <label><input type="radio" name="progAcademico" value="FP Básica" <?php if ($datos["PROGRAMA_ACADEMICO"] == "FP Básica") echo " checked "; ?> />
                                    FP Básica</label>
                                <label><input type="radio" name="progAcademico" value="CFGM Gestión Administrativa" <?php if ($datos["PROGRAMA_ACADEMICO"] == "CFGM Gestión Administrativa") echo " checked "; ?> />
                                    CFGM Gestión Administrativa</label>
                                <label><input type="radio" name="progAcademico" value="CFGS Administración y Finanzas" <?php if ($datos["PROGRAMA_ACADEMICO"] == "CFGS Administración y Finanzas") echo " checked "; ?> />
                                    CFGS Administración y Finanzas</label>
                            </div><br>
                            <div><label for="curso">
                                <input type="number" id="curso" placeholder="Curso" name="curso" min="1" max="" value="<?php echo $datos["CURSO"] ?>" required />
                            </div>
                            <div><label for="grupo">
                                <input type="text" size=1 id="grupo" placeholder="Grupo" name="grupo" pattern="^[ABCD]{1}" value="<?php echo $datos["GRUPO"] ?>" required />
                            </div>
                        <?php } else { ?>
                            <div>
                                <label><select name="departamento"  title="Departamento" id="departamento"></label>
                                <?php $conexion = crearConexionBD();
                                $deptProf = isset($datos["departamento"]) ? $datos["departamento"] : obtenerDepartamento($conexion, $datos["OID_D"]);
                                $departamentos = todosLosDepartamentos($conexion);
                                cerrarConexionBD($conexion);
                                foreach ($departamentos as $dept) { ?>
                                    <option value="<?php echo $dept["NOMBRE"]; ?>" <?php if ($deptProf == $dept["NOMBRE"]) echo " selected "; ?>><?php echo $dept["NOMBRE"] ?></option>
                                <?php } ?>
                                </select>
                            </div>
                            <div><label for="jefe">¿Es jefe del departamento?</label>
                                <input type="checkbox" name="jefe" value="SI" <?php if (isset($datos["jefe"]) && $datos["jefe"] == 'SI') echo " checked ";
                                                                                else if (esJefeDepartamento($conexion, $num_carne)) echo " checked "; ?>>
                            </div><br>
                        <?php } ?>
                        <div><label for="telefono">
                            <input type="text" id="telefono" name="telefono" placeholder="Telefono" title="Telefono" value="<?php echo $datos["TELEFONO"]; ?>" />
                        </div>
                        <div><label for="email">
                            <input type="email" id="email" name="email" title="Email" placeholder="Email" value="<?php echo $datos["EMAIL"]; ?>" required />
                        </div><br>
                        <div align="right">
                            <input type="submit" class="btn" name="2" id="2" value="Enviar" /></div>
                    </fieldset>
                </form>
            </div>
            <br>
            <div class="divform col-12-m col-9-t">
                <form id="newPassForm" method="POST" onsubmit="return validaPass();" action="accion_editar_perfil.php">
                    <fieldset id=pass_new>
                        <legend>Nueva contraseña</legend><br>
                        <div>
                            <input placeholder="Nueva contraseña" type="password" id="newPass" name="newPass" title="Una cadena de caracteres de al menos 10 caracteres con como mínimo una letra minúscula, una letra mayúscula y un dígito" />
                        </div>
                        <div>
                            <input placeholder="Repite nueva contraseña" type="password" id="repeatNewPass" name="repeatNewPass" /><br><br>
                            <div align="right">
                                <input type="submit" class="btn" name="1" id="1" value="Enviar" /></div>
                        </div>
                    </fieldset>
                </form>
            </div><br>
        </div>
    </main>

    <br><?php include_once("footer.php");
        ?>

</body>

</html>