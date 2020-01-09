<?php

session_start();
include_once("gestionBD.php");
include_once("gestionMateriales.php");

if ($_SESSION["history"] == "inventario") {
    unset($_SESSION["material"]);
};
$page = "materiales";
$_SESSION["history"] = $page;
$carne = $_SESSION["login"]["usuario"];
if (!isset($_SESSION["login"])) {
    Header("Location: index.php");
} else if (substr($carne, -1) != "P" || $carne == "99999P") {
    Header("Location: exito_login.php");
} else if (!isset($_REQUEST["oid_m"])) {
    Header("Location: materiales.php");
} else {
    $material = $_GET["oid_m"];
    $descripcion = $_GET["descripcion"];
}

if (isset($_SESSION["fecha_ant"])) {
    $fecha_ant = strtotime($_SESSION["fecha_ant"]);
    unset($_SESSION["fecha_ant"]);
} else {
    $day_week = intval(date('w'));
    if ($day_week == 0) /*IT'S SUNDAY!*/ {
        $date = new DateTime(date('Y-m-d'));
        $date->add(new DateInterval('P1D'));
        $fecha_ant = $date->format('Y-m-d');
        $fecha_ant = strtotime($fecha_ant);
    } else if ($day_week == 6) /*IT'S CATURDAY!*/ {
        $date = new DateTime(date('Y-m-d'));
        $date->add(new DateInterval('P2D'));
        $fecha_ant = $date->format('Y-m-d');
        $fecha_ant = strtotime($fecha_ant);
    } else {
        $date = new DateTime(date('Y-m-d'));
        $fecha_ant = $date->format('Y-m-d');
        $fecha_ant = strtotime($fecha_ant);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
    <script src="js/funciones.js" charset="utf-8"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
    <title>Reserva de materiales</title>
</head>

<body>

    <?php include_once("header.php") ?><br />
    <?php include_once("sidenav.php"); ?>
    <main>
        <div id="main">

            <h2>Reserva de materiales</h2>
            <hr class="principal" /><br />

            <div class="divform">
            <div class="">
                <form>
                    <div align="center" id=ferm>
                        <input type="date" name="fecha" placeholder="Fecha" id="fecha" min="<?php echo date('Y-m-d', $fecha_ant) ?>" value="<?php echo date('Y-m-d', $fecha_ant) ?>" />
                    </div>
                </form>
            </div><br>
            <div id='tabla_materiales'>
                    <table class="tablahorarios">
                        <tr>
                            <th>Material</th>
                            <th>1ª hora</th>
                            <th>2ª hora</th>
                            <th>3ª hora</th>
                            <th>4ª hora</th>
                            <th>5ª hora</th>
                            <th>6ª hora</th>
                        </tr>
                        <tr>
                            <th class="td-start"><?php echo $descripcion; ?></th>
                            <?php
                            $fecha = date("Y-m-d", $fecha_ant);
                            $con = crearConexionBD();

                            for ($i = 1; $i <= 6; $i++) {
                                $tramo_reservado = reservasMaterialesPorTramo($con, $fecha, $material, $i); ?>
                                <td class="end-line-button">
                                    <form method="post" action="controlador_materiales.php">
                                        <?php if (!empty($tramo_reservado)) { ?>
                                            <input type="hidden" name="OID_RM" id="OID_RM" value="<?php echo $tramo_reservado['OID_RM'] ?>" />
                                            <input type="hidden" name="OID_M" id="OID_M" value="<?php echo $tramo_reservado['OID_M'] ?>" />
                                            <input type="hidden" name="FECHA_RESERVA" id="FECHA_RESERVA" value="<?php echo $fecha ?>" />
                                            <input type="hidden" name="TRAMO" id="TRAMO" value="<?php echo $tramo_reservado['TRAMO'] ?>" />
                                            <?php if ($carne == '00000P' || $carne == $tramo_reservado["NUM_CARNE"]) { ?>
                                                <input type="hidden" name="NUM_CARNE" id="NUM_CARNE" value="<?php echo $tramo_reservado['NUM_CARNE'] ?>" />
                                                <button id="cancelar" name="cancelar" type="submit" class="btn btnred">Cancelar</button></form>
                                        </td>

                                    <?php   } else { ?>
                                        <button class="aula-material-reservado" disabled>Reservado</button></form>
                                        </td>
                                    <?php   }
                            } else { ?>
                                    <input type="hidden" name="OID_M" id="OID_M" value="<?php echo $material ?>" />
                                    <input type="hidden" name="FECHA_RESERVA" id="FECHA_RESERVA" value="<?php echo $fecha ?>" />
                                    <input type="hidden" name="TRAMO" id="TRAMO" value="<?php echo $i ?>" />
                                    <input type="hidden" name="NUM_CARNE" id="NUM_CARNE" value="<?php echo $carne ?>" />
                                    <button id="reservar" name="reservar" type="submit" class="btn" <?php if ($carne == '00000P') echo 'disabled' ?>>Reservar</button></form>
                                    </td>
                                <?php }
                        }
                        cerrarConexionBD($con);
                        ?>
                        </tr>
                    </table><br>
            </div>
        </div>
                    </div>
    </main>

    <script>
        $("#fecha").change(function() {
            var date = new Date($('#fecha').val()).getUTCDay();

            if (date == 6 || date == 0) {
                $("#fecha")[0].setCustomValidity('No es posible seleccionar fines de semana. Por favor, elija un día laborable.');
            } else {
                $("#fecha")[0].setCustomValidity('');
                var carne = "<?php echo $carne; ?>";
                var material = "<?php echo $material; ?>";
                var descripcion = "<?php echo $descripcion; ?>";
                $.get("tabla_rmateriales.php", {
                        fecha: $('#fecha').val(),
                        carne: carne,
                        material: material,
                        descripcion: descripcion
                    },
                    function(response) {
                        $("#tabla_materiales").html(response);
                    });
            }
        });
    </script>

    <br><?php include_once("footer.php") ?>

</body>

</html>