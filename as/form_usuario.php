<?php
session_start();

include_once("gestionDepartamentos.php");
include_once("gestionBD.php");

if (!isset($_SESSION["login"])) {
    Header("Location: index.php");
} else {
    $carne = $_SESSION["login"]["usuario"];
    if ($carne != '00000P' && $carne != '99999P') {
        Header("Location: index.php");
    }
}
if (!isset($_SESSION["formulario"])) {
    $formulario["tipoUsuario"] = "";
    $formulario["nombre"] = "";
    $formulario["apellidos"] = "";
    $formulario["dni"] = "";
    $formulario["sexo"] = "";
    $formulario["fechaNacimiento"] = "";
    $formulario["email"] = "";
    $formulario["telefono"] = "";
    $formulario["progAcademico"] = "";
    $formulario["grupo"] = "";
    $formulario["curso"] = "";
    $formulario["departamento"] = "";
    $formulario["jefe"] = "";
    $_SESSION["formulario"] = $formulario;
} else $formulario = $_SESSION["formulario"];
if (isset($_SESSION["errores"])) {
    $errores = $_SESSION["errores"];
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Registro de usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
    <script src="js/funciones.js"></script>
</head>

<body>

    <?php include_once("header.php") ?> <br>
    <?php include_once("sidenav.php"); ?>
    <main>
        <div id="main">
        <h2>Nuevo usuario</h2>
        <hr class="principal"><br>
            <?php
            if (isset($errores) && count($errores) > 0) {
                echo "<div class=\"error\">";
                echo "<h4>Se han producido los siguientes errores:</h4>";
                foreach ($errores as $error) echo $error;
                echo "</div>";
                $_SESSION["errores"] = null;
            }
            ?>
            <div class="divform col-12-m col-9-t">
            <p id="inf"><i>Los campos obligatorios están marcados con </i><em>*</em></p>
                <form id="formulario" method="POST" enctype="multipart/form-data" action="validacion_usuario.php">
                    <fieldset>
                        <legend>Foto</legend>
                        <div class="">
                        <div><label for="foto">Subir</label>
                            <input type="file" id="foto" name="foto" title="Foto" />
                        </div><br>
                        <div>
                            <p>Tamaño máximo: 2MB.<br>
                            Formatos válidos: GIF, JPEG, JPG y PNG.</p>
                        </div>
                     </div>
                    </fieldset><br>
                    <fieldset>
                        <legend>Datos del usuario</legend>
                        <div class="">
                        <div><label>Tipo de usuario:<em>*</em></label>
                            <label><input type="radio" name="tipoUsuario" value="alumno" onclick="enableDisable('datosAlumno', 'datosProfesor')" <?php if ($formulario["tipoUsuario"] == "alumno") echo " checked "; ?> />
                                Alumno</label>
                            <label><input type="radio" name="tipoUsuario" value="profesor" onclick="enableDisable('datosProfesor', 'datosAlumno')" <?php if ($formulario["tipoUsuario"] == "profesor") echo " checked "; ?> />
                                Profesor</label>
                        </div>
                        <input type="text" id="nombre" name="nombre" placeholder="Nombre*" size="50" value="<?php echo $formulario["nombre"] ?>" required />
                        <input type="text" id="apellidos" name="apellidos" placeholder="Apellidos*" size="50" value="<?php echo $formulario["apellidos"] ?>" required />
                        <input type="text" id="dni" name="dni" placeholder="DNI*" pattern="^[0-9]{8}[A-Z]" title="8 dígitos seguidos de una letra mayúscula" value="<?php echo $formulario["dni"] ?>" required />
                        <div><label>Sexo:<em>*</em></label>
                            <label><input type="radio" name="sexo" value="M" <?php if ($formulario["sexo"] == "M") echo " checked "; ?>>
                                Masculino</label>
                            <label><input type="radio" name="sexo" value="F" <?php if ($formulario["sexo"] == "F") echo " checked "; ?>>
                                Femenino</label>
                        </div>
                        <div>
                            <input type="date" id="fechanacimiento" name="fechaNacimiento" title="Fecha de nacimiento" value="<?php echo $formulario["fechaNacimiento"] ?>" required />
                        </div>
                        <input type="email" id="email" name="email" placeholder="Correo electrónico*" value="<?php echo $formulario["email"] ?>" required />
                        <input type="text" id="telefono" name="telefono" placeholder="Teléfono*" pattern="[0-9]{9}" title="Un teléfono de 9 dígitos" value="<?php echo $formulario["telefono"] ?>" />
        </div></fieldset><br>

                    <fieldset id="datosAlumno">
                        <legend>Datos de alumno</legend>
                        <div class="">
                        <div><label>Programa académico:<em>*</em></label>
                            <label><input type="radio" name="progAcademico" value="ESO" <?php if ($formulario["progAcademico"] == "ESO") echo " checked "; ?> />
                                ESO</label>
                            <label><input type="radio" name="progAcademico" value="BACHILLERATO" <?php if ($formulario["progAcademico"] == "BACHILLERATO") echo " checked "; ?> />
                                Bachillerato</label>
                            <label><input type="radio" name="progAcademico" value="FP Básica" <?php if ($formulario["progAcademico"] == "FP Básica") echo " checked "; ?> />
                                FP Básica</label>
                            <label><input type="radio" name="progAcademico" value="CFGM Gestión Administrativa" <?php if ($formulario["progAcademico"] == "CFGM Gestión Administrativa") echo " checked "; ?> />
                                CFGM Gestión Administrativa</label>
                            <label><input type="radio" name="progAcademico" value="CFGS Administración y Finanzas" <?php if ($formulario["progAcademico"] == "CFGS Administración y Finanzas") echo " checked "; ?> />
                                CFGS Administración y Finanzas</label>
                        </div>
                        <div><label for="curso">
                            <input type="number" id="curso" name="curso" min="1" max="4" title="Curso" value="<?php echo $formulario["curso"] ?>" required />
                        </div>
                        <input type="text" size=1 id="grupo" name="grupo" placeholder="Grupo*" pattern="^[ABCD]{1}" value="<?php echo $formulario["grupo"] ?>" required />
        </div></fieldset><br>

                    <fieldset id="datosProfesor">
                        <legend>Datos de profesor</legend>
                        <div class="">
                        <div>
                            <label><select name="departamento" id="departamento"></label>
                            <?php
                            $conexion = crearConexionBD();
                            $departamentos = todosLosDepartamentos($conexion);
                            cerrarConexionBD($conexion);
                            foreach ($departamentos as $dept) {
                                ?>
                                <option value="<?php echo $dept["NOMBRE"]; ?>" <?php if ($formulario["departamento"] == $dept["NOMBRE"]) echo " selected "; ?>><?php echo $dept["NOMBRE"] ?></option>
                            <?php } ?>
                            </select>
                        </div>
                        <div><label for="jefe">¿Es jefe del departamento?</label>
                            <input type="checkbox" name="jefe" value="SI" <?php if ($formulario["jefe"] == "SI") echo " checked "; ?>>
                        </div></div>
                    </fieldset>

                    <?php
                    if ($formulario["tipoUsuario"] == "alumno") { ?>
                        <script type='text/javascript'>
                            enableDisable('datosAlumno', 'datosProfesor');
                        </script>
                    <?php } else if ($formulario["tipoUsuario"] == "profesor") { ?>
                        <script type='text/javascript'>
                            enableDisable('datosProfesor', 'datosAlumno');
                        </script>
                    <?php } ?>
                    <div align="right"><input type="submit" name="sub" id="sub" class="btn" value="Enviar" /></div>
                </form>
            </div><br>
        </div>
    </main>
    <?php include_once("footer.php") ?>

</body>

</html>