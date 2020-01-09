<?php
session_start();
include_once("gestionBD.php");
include_once("gestionMateriales.php");
include_once("gestionAulas.php");
include_once("gestionDepartamentos.php");
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
    <title>Historial de reservas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/funciones.js" charset="utf-8"></script>
</head>

<body>

    <?php include_once("header.php") ?><br>
    <?php include_once("sidenav.php");
    $pagstr = isset($page_num) ? ('&page_num=' . $page_num . '&page_size=' . $page_size) : ('');
    $bus = isset($_POST["bus"]) ? $_POST["bus"] : '';
    $val = (isset($_POST["val"]) && $_POST["val"] == 'yes') ? $_POST["val"] : '';
    $retype = (isset($_POST["retype"]) && ($_POST["retype"] == 'mat' || $_POST["retype"] == 'aul')) ? $_POST["retype"] : 'aul'; ?>

    <main>
        <div id="main">
            <h2>Historial de reservas</h2>
            <hr class="principal"><br>

            <?php
            if (isset($_SESSION["cancelar_reserva_exito"])) {
                $cancelarExito = $_SESSION["cancelar_reserva_exito"];
                unset($_SESSION["cancelar_reserva_exito"]);
                if ($cancelarExito) { ?>
                    <div class="exito">
                        <p>La reserva ha sido borrada con exito.</p>
                    </div>
                <?php } else { ?>
                    <div class="fallo">
                        <p>La reserva no se ha podido borrar de la base de datos. Por favor, inténtelo más tarde.</p>
                    </div>
                <?php }
        }
        ?>


            <div align="center">
            <button id="aul" type="button" class="btn" onclick="document.getElementById('retype').setAttribute('value','aul');tablaHistorialReservas('retype=aul&busqueda='+$('#bus').val()+'&val='+((document.getElementById('val').checked==true)?'yes':'')+'<?= $pagstr ?>');">Reservas de aulas</button>
            <button id="mat" type="button" class="btn" onclick="document.getElementById('retype').setAttribute('value','mat');tablaHistorialReservas('retype=mat&busqueda='+$('#bus').val()+'&val='+((document.getElementById('val').checked==true)?'yes':'')+'<?= $pagstr ?>');">Reservas de materiales</button>
            </div>
            <br>
            <div class="divform">
                <form id="f" oninput="tablaHistorialReservas('retype='+$('#retype').val()+'&busqueda='+$('#bus').val()+'&val='+((document.getElementById('val').checked==true)?'yes':'')+'<?= $pagstr ?>'); return false; ">
                    <input type="text" id='retype' value='<?= $retype ?>' hidden>
                    <img id="icon_busq" src="images/search_icon.png" alt="Buscar" width="18px" height="18px">
                    <input type='text' placeholder="Buscar..." id='bus' onpaste="return false;" onkeypress="return validaBusqueda(event)" value='<?= $bus ?>'>
                    <input type="checkbox" id="val" name="val" value="val" <?php echo $val == 'yes' ? 'checked' : ''; ?>>Mostrar reservas activas
                </form>
                <div id="tabla"></div>
            </div>
            <script>
                tablaHistorialReservas('retype=<?= $retype ?>&busqueda=' + $('#bus').val() + '&val=' + ((document.getElementById('val').checked == true) ? 'yes' : '') + '<?= $pagstr ?>');
            </script>
            <br>
        </div>
    </main>
    <script>
        document.getElementById("sreservas").className = "active"
    </script>

    <?php include_once("footer.php") ?>

</body>

</html>