<?php
session_start();
include_once("gestionBD.php");
include_once("gestionLibros.php");

if (!isset($_SESSION["login"])) {
    Header("Location: index.php");
} else {
    $carne = $_SESSION["login"]["usuario"];
    $controles = $carne == '00000P' || $carne == '99999P';
    $conexion = crearConexionBD();
    $isbnej = isset($_POST["isbnej"]) ? $_POST["isbnej"] : $_GET["isbnej"];
    if (isset($_SESSION["ejemplar"]) && !isset($_SESSION["ejemplar_controlado"])) {
        $ejemplarNuevo = $_SESSION["ejemplar"];
        unset($_SESSION["ejemplar"]);
        unset($_SESSION["errores"]);
        if ($_SESSION["newe"]) {
            $bE = nuevoEjemplar($conexion, $ejemplarNuevo);
            unset($_SESSION["newe"]);
        }
    }

    if (isset($_SESSION["ejemplar_controlado"])) {
        $ejemplarEditado = $_SESSION["ejemplar_controlado"];
        unset($_SESSION["ejemplar_controlado"]);
        unset($_SESSION["errores"]);
        unset($_SESSION["editando"]);
        if ($_SESSION["edite"]) {
            $editadoCorrectamenteE = modificarEjemplar($conexion, $ejemplarEditado);
            unset($_SESSION["edite"]);
        }
    }

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
    <title>Ejemplares</title>
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
        <div id="main">
            <h2>Ejemplares</h2>
            <hr class="principal"><br>

            <?php
            if (isset($bE)) {
                if ($bE) { ?>
                    <div class="exito">
                        <p>El ejemplar se ha añadido correctamente.</p>
                    </div><br />
                <?php } else { ?>
                    <div class="fallo">
                        <p>El ejemplar no se ha podido insertar en la base de datos. Por favor, inténtelo de nuevo.</p>
                    </div><br />
                <?php }
        } ?>

            <?php
            if (isset($editadoCorrectamenteE)) {
                if ($editadoCorrectamenteE) { ?>
                    <div class="exito">
                        <p>El ejemplar se ha editado correctamente.</p>
                    </div><br />
                <?php } else { ?>
                    <div class="fallo">
                        <p>El ejemplar no se ha podido editar en la base de datos. Por favor, inténtelo de nuevo.</p>
                    </div><br />
                <?php }
        }
        $libro = obtieneLibro($conexion, $isbnej);
        ?>

            <?php
            if (isset($_SESSION["baja_ejemplar_exito"])) {
                $bajaEjemplarExito = $_SESSION["baja_ejemplar_exito"];
                unset($_SESSION["baja_ejemplar_exito"]);
                if ($bajaEjemplarExito) { ?>
                    <div class="exito">
                        <p>El ejemplar se ha dado de baja correctamente.</p>
                    </div><br />
                <?php } else { ?>
                    <div class="fallo">
                        <p>El ejemplar no se ha podido dar de baja en la base de datos. Por favor, asegúrese de que no está actualmente prestado.</p>
                    </div><br />
                <?php }
        } ?>
            <div class='divform col-12-m col-10-t'>
            <legend>Datos del libro</legend>
                <div class="prestamo">
                   
                    Título: <?php echo $libro["TITULO"] ?><br>
                    Autor: <?php echo $libro["AUTOR"] ?><br>
                    Género: <?php echo $libro["GENERO"] ?><br>
                    CDU: <?php echo $libro["CDU"] ?><br>
                    ISBN: <?php echo $libro["ISBN"] ?><br>
                    Copias: <?php echo $libro["COPIAS"] ?><br>
                </div><br>
            </div>
            <br>

            <div class="divform">
                <form oninput="tablaEjemplares('isbnej=<?= $isbnej ?>&busqueda='+$('#bus').val()+'&val='+((document.getElementById('val').checked==true)?'yes':'')+'<?= $pagstr ?>'); return false; ">
                    <img id="icon_busq" src="images/search_icon.png" alt="Buscar" width="18px" height="18px">
                    <input placeholder="Buscar..." type='text' id='bus' onpaste="return false;" onkeypress="return validaBusqueda(event)" value='<?php echo (isset($_POST["bus"]) ? $_POST["bus"] : ""); ?>'>
                    <input type="checkbox" id="val" name="val" value="val" <?php echo (isset($_POST["val"]) && $_POST["val"] == 'yes' ? 'checked' : ''); ?> <?php if ($carne != '00000P' && $carne != '99999P') {
                                                                                                                                                                echo 'hidden';
                                                                                                                                                            } ?>>
                    <?php if ($carne == '00000P' || $carne == '99999P') {
                        echo 'Mostrar libros activos';
                    } ?>
                </form>
                <br>
                <div id="tabla"></div>
            </div>
            <script>
                tablaEjemplares('isbnej=<?= $isbnej ?>&busqueda=' + $('#bus').val() + '&val=' + ((document.getElementById('val').checked == true) ? 'yes' : '') + '<?= $pagstr ?>');
            </script>

            <br>
        </div>
    </main>
    <?php include_once("footer.php") ?>
</body>

</html>