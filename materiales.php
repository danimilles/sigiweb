<?php
session_start();
include_once("gestionBD.php");
include_once("gestionMateriales.php");
include_once("gestionDepartamentos.php");
$carne = $_SESSION["login"]["usuario"];
if (isset($_SESSION["history"]) && $_SESSION["history"] == "inventario") {
    unset($_SESSION["material"]);
};
$page = "materiales";
$_SESSION["history"] = $page;
if (!isset($_SESSION["login"])) {
    Header("Location: index.php");
} else if (substr($carne, -1) != "P" || $carne == "99999P" || $carne == "00000P") {
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
    <title>Reservar materiales</title>
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
            <h2>Lista de materiales para reservar</h2>
            <hr class="principal"><br />

            <?php if (isset($_SESSION["errores"])) { ?>
                <div class="fallo">
                    <?php
                    $errores = $_SESSION["errores"];
                    foreach ($errores as $error) { ?>
                        <p><?php echo $error ?></p>
                    </div>
                <?php  }
        }
        unset($_SESSION["errores"]); ?>

            <?php if (isset($_SESSION["mensaje"])) { ?>
                <div class="exito">
                    <h4><?php echo $_SESSION["mensaje"] ?></h4>
                </div>
            <?php  }
        unset($_SESSION["mensaje"]); ?>
        <div class="row">
        <?php if ($carne != '00000P') { ?>
                    <div id="cuadrorm" class="divform prestamosalum hg inlineblock margini col-12-m col-3-es">
                            <legend>Materiales reservados</legend><br>
                            <?php
                            $con = crearConexionBD();
                            $materiales_reservados = reservasMaterialesPorUsuario($con, $carne);
                            foreach ($materiales_reservados as $reservada) { ?>
                            <div class="prestamo">
                                <form method="post" action="controlador_materiales.php">
                                    <input type="hidden" name="OID_RM" id="OID_RM" value="<?php echo $reservada['OID_RM'] ?>" />
                                    <input type="hidden" name="OID_M" id="OID_M" value="<?php echo $reservada['OID_M'] ?>" />
                                    <input type="hidden" name="FECHA_RESERVA" id="FECHA_RESERVA" value="<?php echo $reservada['FECHA_RESERVA'] ?>" />
                                    <input type="hidden" name="TRAMO" id="TRAMO" value="<?php echo $reservada['TRAMO'] ?>" />
                                    <input type="hidden" name="NUM_CARNE" id="NUM_CARNE" value="<?php echo $reservada['NUM_CARNE'] ?>" />
                                    <div>
                                        <h5><?php echo $reservada["DESCRIPCION"] ?></h5>
                                        <p><?php echo $reservada["FECHA_RESERVA"] . ", " . $reservada["TRAMO"] . "ª hora" ?></p>
                                        <button class="btn btnred" id="cancelar" name="cancelar" type="submit" class="cancelar">Cancelar</button>
                                    </div>
                                </form>
                            </div>
                            <?php } ?>
                    </div>
                <?php }
            cerrarConexionBD($con); ?>

            <div class="divform  prestamosalum inlineblock hg col-12-m col-8-es">
                <form oninput="tablaMateriales('busqueda='+$('#bus').val()+'<?= $pagstr ?>'); return false; ">
                    <img id="icon_busq" src="images/search_icon.png" alt="Buscar" width="18px" height="18px">
                    <input type='text' placeholder="Buscar..." id='bus' onpaste="return false;" onkeypress="return validaBusqueda(event)" value='<?php echo (isset($_POST["bus"]) ? $_POST["bus"] : ""); ?>'>
                </form>
                <br>
                <div id="tabla"></div>
            </div>

            <script>
                tablaMateriales('busqueda=' + $('#bus').val() + '<?= $pagstr ?>');
            </script>
        </div>
    </main>
    <script>
        document.getElementById("smateriales").className = "active";
    </script>

    <br><?php include_once("footer.php") ?>

</body>

</html>