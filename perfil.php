<?php
session_start();
include_once("gestionBD.php");
include_once("gestionUsuarios.php");
include_once("gestionDepartamentos.php");

if (!isset($_SESSION["login"])) {
    Header("Location: index.php");
}
$errores = array();
$carne = $_SESSION["login"]["usuario"];
if ($carne == '00000P' || $carne == '99999P') {
    Header("Location: index.php");
}
$conexion = crearConexionBD();

if (isset($_POST["1"])) {
    $actualPass = $_POST["actualPass"];
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
    } else if (consultarUsuario($conexion, $carne, $actualPass)) {
        cambiarPass($conexion, $carne, $newPass);
    } else array_push($errores, "<p>La contraseña actual introducida no es correcta</p>");
}

if (isset($_POST["2"])) {
    if ($_POST["telefono"] != "" && !filter_var($_POST["telefono"], FILTER_VALIDATE_INT)) {
        array_push($errores, "<p>El teléfono no es un número</p>");
    } else {
        cambiaTelefono($conexion, $carne, $_POST["telefono"]);
    }
    if ($_POST["email"] == "") {
        array_push($errores, "<p>El email no puede estar vacío</p>");
    } else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        array_push($errores, "<p>El correo electrónico introducido no es correcto</p>");
    } else {
        cambiaEmail($conexion, $carne, $_POST["email"]);
    }
}
if (isset($_POST["3"])) {
    if ($_FILES["foto"]["error"] == 0) {
        $valid_extensions = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');
        if (!in_array($_FILES["foto"]["type"], $valid_extensions)) {
            array_push($errores, "<p>El formato de la foto no es válido</p>");
        } else {
            cambiaFoto($conexion, $carne, "foto");
        }
    } else if ($_FILES["foto"]["error"] == 1) {
        array_push($errores, "<p>La imagen es demasiado grande</p>");
    } else {
        array_push($errores, "<p>Se ha producido un error al subir la imagen</p>");
    }
}
$datos = perfilUsuario($conexion, $carne);
cerrarConexionBD($conexion);
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Perfil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
    <script src="js/funciones.js"></script>
</head>

<body>

    <?php include_once("header.php") ?><br>
    <?php include_once("sidenav.php"); ?>
    <main>
        <div id="main">

            <h2>Perfil</h2>
            <hr class="principal"><br>

            <?php if (isset($_POST["1"]) || isset($_POST["2"]) || isset($_POST["3"])) {
                if (count($errores) > 0) { ?>
                    <div class="fallo">
                        <h4>Se han producido los siguientes errores:</h4>
                        <?php foreach ($errores as $error) echo $error; ?>
                    </div>
                <?php } else { ?>
                    <div class="exito">
                        <h4>Cambios realizados con éxito.</h4>
                    </div>
                <?php }
        }
        ?>

            <div class="divform marginp col-12-m col-9-t" id=foto>
                <legend>Foto carné</legend>
                <div>
                    <?php echo (($datos["FOTO"] != null) ? "<img id='fp' src=\"data:image/png;base64," . base64_encode(stream_get_contents($datos["FOTO"])) . "\" width=100px height=140px /><br>" : ""); ?>
                    <br>
                </div>
            </div>
            <div class="divform col-12-m col-9-t">
                <form id="cambioPerfil" method="POST" action="perfil.php">
                    <legend>Configurar perfil</legend>
                    <div id=perfil>

                        <div class="">
                            <p><?php echo "Nº de carné: " . $datos["NUM_CARNE"] . "<br>"; ?>
                            </p>
                            <p><?php echo "Nombre: " . $datos["NOMBRE"] . "<br>"; ?>
                            </p>
                            <p><?php echo "Apellidos: " . $datos["APELLIDOS"] . "<br>"; ?>
                            </p>
                            <p><?php echo "DNI: " . $datos["DNI"] . "<br>"; ?>
                            </p>
                            <p><?php echo "Fecha de nacimiento: " . $datos["FECHANACIMIENTO"] . "<br>"; ?>
                            </p>
                            <p><?php echo "Fecha de vencimiento del carné: " . $datos["FECHA_VALIDEZ_CARNE"] . "<br>"; ?>
                            </p>
                            <p><?php echo "Sexo: " . $datos["SEXO"] . "<br>"; ?>
                            </p>

                            <?php if (substr($carne, 5) == "A") { ?>

                                <p><?php echo "Programa académico: " . $datos["PROGRAMA_ACADEMICO"] . "<br>"; ?>
                                </p>
                                <p><?php echo "Curso: " . $datos["CURSO"] . "<br>"; ?>
                                </p>
                                <p><?php echo "Grupo: " . $datos["GRUPO"] . "<br>"; ?>
                                </p>
                            </div>
                        <?php } else { ?>
                            <p><?php $conexion = crearConexionBD();
                                echo "Departamento: " . obtenerDepartamento($conexion, $datos["OID_D"]) . "<br>"; ?>
                            </p>
                            <p><?php echo "Jefe de departamento: " . (esJefeDepartamento($conexion, $carne) ? "Sí" : "No") . "<br>";
                                cerrarConexionBD($conexion); ?>
                            </p>
                        </div>
                    <?php } ?>

                    <input type="text" id="telefono" name="telefono" title="Telefono" value="<?php echo $datos["TELEFONO"]; ?>" />
                    <input type="email" id="email" name="email" title="Email" value="<?php echo $datos["EMAIL"]; ?>" required /><br>
                    <br>
                    <div align="right">
                        <input type="submit" class="btn" name="2" id="2" value="Enviar" /></div>
            </div>



            </form>
        </div><br>
        <div class="divform col-12-m col-9-t">
            <form id="newPassForm" method="POST" onsubmit="return validaPass();" action="perfil.php">
                <div id=pass_new>
                    <legend>Nueva contraseña</legend>
                    <p>Por su seguridad, deberá introducir su contraseña actual.</p>
                    <input type="password" id="actualPass" placeholder="Contraseña actual" name="actualPass" title="Su contraseña actual" />
                    <input type="password" id="newPass" placeholder="Nueva contraseña" name="newPass" title="Una cadena de caracteres de al menos 10 caracteres con como mínimo una letra minúscula, una letra mayúscula y un dígito" />
                    <input type="password" id="repeatNewPass" placeholder="Repite nueva contraseña" name="repeatNewPass" /><br><br>
                    <div align="right">
                        <input type="submit" class="btn" name="1" id="1" value="Enviar" /></div>
                </div>
        </div>
        </form>
        </div><br>
        </div>
        </div>
    </main>
    <script>
        document.getElementById("sperfil").className = "active"
    </script>

    <br><?php include_once("footer.php");
        ?>


</body>

</html>