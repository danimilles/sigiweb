<?php
session_start();
include_once("gestionBD.php");
include_once("gestionUsuarios.php");
unset($_SESSION["num_carne"]);
$carne = $_SESSION["login"]["usuario"];
if (!isset($_SESSION["login"])) {
    Header("Location: index.php");
} else if ($carne != '00000P' && $carne != '99999P') {
    Header("Location: exito_login.php");
} else {
    if (isset($_SESSION["paginacion"])) {
        $paginacion = $_SESSION["paginacion"];
    } else {
        $paginacion["pag_num"] = 1;
        $paginacion["pag_size"] = 10;
    }

    $page_num = isset($_POST["page_num"]) ? (int)$_POST["page_num"] : $paginacion["pag_num"];
    $page_size = isset($_POST["page_size"]) ? (int)$_POST["page_size"] : $paginacion["pag_size"];

    if ($page_num < 1) $page_num = 1;
    if ($page_size < 1) $page_size = 10;

    unset($_SESSION["paginacion"]);
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Usuarios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/funciones.js" charset="utf-8"></script>
</head>

<body>

    <?php include_once("header.php") ?><br>
    <?php include_once("sidenav.php");
    $pagstr = isset($page_num) ? ('&page_num=' . $page_num . '&page_size=' . $page_size) : (''); ?>
    <main>
        <div id='main'>
            <div>
                <h2>Lista de usuarios</h2>
                <hr class="principal"><br>
            </div>

            <?php if (isset($_SESSION["exito"])) {
                echo "<div class='exito'>" . $_SESSION["exito"] . "</div>";
                unset($_SESSION["exito"]);
            } else if (isset($_SESSION["errores"])) {
                $errores = $_SESSION["errores"];
                unset($_SESSION["errores"]);
                echo "<div class='fallo'>";
                echo "<h4>Se han producido los siguientes errores:</h4>";
                foreach ($errores as $error) {
                    echo $error;
                }
                echo "</div>";
            }
            ?>

            <?php
            if ($carne == '00000P' || $carne == '99999P') { ?>
                <div align="center">
                <button type="button" class="btn" onclick="window.location.href='form_usuario.php'">Nuevo usuario</button></div>
                <br>
            <?php } ?>
            <div class="divform">
                <form oninput="tablaUsuarios('busqueda='+$('#bus').val()+'&val='+((document.getElementById('val').checked==true)?'yes':'')+'&ustype='+((document.getElementById('prof').checked==true)?'prof':'')+((document.getElementById('alum').checked==true)?'alum':'')+'<?= $pagstr ?>'); return false; ">
                    <img id="icon_busq" src="images/search_icon.png" alt="Buscar" width="18px" height="18px">
                    <input type='text' placeholder="Buscar..." id='bus' onpaste="return false;" onkeypress="return validaBusqueda(event)" value='<?php echo (isset($_POST["bus"]) ? $_POST["bus"] : ""); ?>'>
                    <input type="checkbox" id="prof" name='prof' value='prof' <?php echo (isset($_POST["ustype"]) && $_POST["ustype"] == 'prof' ? 'checked' : ''); ?>>Mostrar profesores
                    <input type="checkbox" id="alum" name="alum" value="alum" <?php echo (isset($_POST["ustype"]) && $_POST["ustype"] == 'alum' ? 'checked' : ''); ?>>Mostrar alumnos
                    <input type="checkbox" id="val" name="val" value="val" <?php echo (isset($_POST["val"]) && $_POST["val"] == 'yes' ? 'checked' : ''); ?>>Mostrar usuarios activos
                </form>
                <br>
                <div id='tabla'></div>
            </div>
            <script>
                tablaUsuarios('busqueda=' + $('#bus').val() + '&val=' + ((document.getElementById('val').checked == true) ? 'yes' : '') + '&ustype=' + ((document.getElementById('prof').checked == true) ? 'prof' : '') + ((document.getElementById('alum').checked == true) ? 'alum' : '') + '<?= $pagstr ?>');
                document.getElementById("susuarios").className = "active"
            </script>
        </div>
    </main>
    <br><?php include_once("footer.php") ?>

</body>

</html>