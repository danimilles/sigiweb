<?php
session_start();

include_once("gestionBD.php");
include_once("gestionAulas.php");

if (!isset($_SESSION["login"])) Header("Location: index.php");

$carne = $_SESSION["login"]["usuario"];

if ($carne == '99999P' || substr($carne, 5) == 'A' || $carne == '00000P') {
    Header("Location: index.php");
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
    } else if ($day_week == 6) /*IT'S SATURDAY!*/ {
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
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
    <script type="text/javascript" src="js/funciones.js" charset="utf-8"></script>
    <title>Reserva de aulas</title>
</head>

<body>

    <?php include_once("header.php") ?><br />
    <?php include_once("sidenav.php"); ?>

    <main>
        <div id="main">
            <h2>Reserva de aulas</h2>
            <hr class="principal" /><br />

            <?php if (isset($_SESSION["mensaje"])) { ?>
                <div class="exito">
                    <h4><?php echo $_SESSION["mensaje"] ?></h4>
                </div>
            <?php  } else if (isset($_SESSION["errores"])) { ?>
                <div class="fallo">
                    <?php
                    $errores = $_SESSION["errores"];
                    foreach ($errores as $error) {
                        echo "<h4>" . $error . "</h4>";
                    } ?>
                </div>
            <?php  }
        unset($_SESSION["errores"]);
        unset($_SESSION["mensaje"]); ?>

                    <div class="row">
            <div class="divform margini prestamosalum hg col-12-m col-3-es">
            <?php if ($carne != '00000P') { ?>
                    
                            <legend>Aulas reservadas</legend><br>
                            <?php
                            $con = crearConexionBD();
                            $aulas_reservadas = reservasAulasPorUsuario($con, $carne);
                            foreach ($aulas_reservadas as $reservada) { ?>
                            <div class="prestamo">
                                <form method="post" action="controlador_aulas.php">
                                    <input type="hidden" name="OID_RA" id="OID_RA" value="<?php echo $reservada['OID_RA'] ?>" />
                                    <input type="hidden" name="NUMERO" id="NUMERO" value="<?php echo $reservada['NUMERO'] ?>" />
                                    <input type="hidden" name="FECHA_RESERVA" id="FECHA_RESERVA" value="<?php echo $reservada['FECHA_RESERVA'] ?>" />
                                    <input type="hidden" name="TRAMO" id="TRAMO" value="<?php echo $reservada['TRAMO'] ?>" />
                                    <input type="hidden" name="NUM_CARNE" id="NUM_CARNE" value="<?php echo $reservada['NUM_CARNE'] ?>" />
                                    <div>
                                        <h5><?php echo $reservada["NOMBRE"] ?></h5>
                                        <p><?php echo $reservada["FECHA_RESERVA"] . ", " . $reservada["TRAMO"] . "ª hora" ?></p>
                                        <button class="btn btnred" id="cancelar" name="cancelar" type="submit" class="cancelar">Cancelar</button>
                                    </div>
                                </form></div>
                            <?php } ?>
                    </div>
                <?php }
            cerrarConexionBD($con); ?>
            <div class="divform prestamosalum hg col-12-m col-8-es">
                <div id="">
                <div align="center">
                                <form>
                                    <div id=ferm>
                                        <input type="date" name="fecha" id="fecha" min="<?php echo date('Y-m-d') ?>" value="<?php echo date('Y-m-d', $fecha_ant) ?>" />
                                    </div>
                                </form>
                            </div><br>


                            <img id="icon_busq" src="images/search_icon.png" alt="Buscar" width="18px" height="18px">
                            <input type="text" id="bus" placeholder="Buscar..." onpaste="return false;" onkeypress="return validaBusqueda(event)" oninput="buscaEnTabla('tabla','bus')">

                            <br><br>
                            <table id="tabla_aulas" class=" hg tablahorarios">
                                <tr>
                                    <th>Aulas</th>
                                    <th>1ª hora</th>
                                    <th>2ª hora</th>
                                    <th>3ª hora</th>
                                    <th>4ª hora</th>
                                    <th>5ª hora</th>
                                    <th>6ª hora</th>
                                </tr>
                                <?php
                                $fecha = date('Y-m-d', $fecha_ant);
                                $con = crearConexionBD();
                                $aulas = listaAulas($con);
                                foreach ($aulas as $aula) { ?>
                                    <tr>
                                        <th class='td-start'><strong><?php echo $aula["NOMBRE"] ?></strong></th>
                                        <?php

                                        for ($i = 1; $i <= 6; $i++) {
                                            $tramo_reservado = reservasAulasPorAulaTramo($con, $fecha, $aula["NUMERO"], $i) ?>
                                            <td class="end-line-button">
                                                <form method="post" action="controlador_aulas.php">
                                                    <?php if (!empty($tramo_reservado)) { ?>
                                                        <input type="hidden" name="OID_RA" id="OID_RA" value="<?php echo $tramo_reservado['OID_RA'] ?>" />
                                                        <input type="hidden" name="NUMERO" id="NUMERO" value="<?php echo $tramo_reservado['NUMERO'] ?>" />
                                                        <input type="hidden" name="FECHA_RESERVA" id="FECHA_RESERVA" value="<?php echo $fecha ?>" />
                                                        <input type="hidden" name="TRAMO" id="TRAMO" value="<?php echo $tramo_reservado['TRAMO'] ?>" />
                                                        <?php if ($carne == '00000P' || $carne == $tramo_reservado["NUM_CARNE"]) { ?>
                                                            <input type="hidden" name="NUM_CARNE" id="NUM_CARNE" value="<?php echo $tramo_reservado['NUM_CARNE'] ?>" />
                                                            <button id="cancelar" name="cancelar" type="submit" class="btn btnred">Cancelar</button></form>
                                                    </td>

                                                <?php           } else { ?>
                                                    <button class="aula-material-reservado" disabled>Reservado</button></form>
                                                    </td>
                                                <?php           }
                                        } else { ?>
                                                <input type="hidden" name="NUMERO" id="NUMERO" value="<?php echo $aula['NUMERO'] ?>" />
                                                <input type="hidden" name="FECHA_RESERVA" id="FECHA_RESERVA" value="<?php echo $fecha ?>" />
                                                <input type="hidden" name="TRAMO" id="TRAMO" value="<?php echo $i ?>" />
                                                <input type="hidden" name="NUM_CARNE" id="NUM_CARNE" value="<?php echo $carne ?>" />
                                                <button id="reservar" name="reservar" type="submit" class="btn" <?php if ($carne == '00000P') echo 'disabled' ?>>Reservar</button></form>
                                                </td>
                                            <?php       } ?>
                                        <?php   } ?>
                                    </tr>
                                <?php }
                            cerrarConexionBD($con); ?>
                            </table>
                       
                </div>
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
                $.get("tabla_raulas.php", {
                        fecha: $('#fecha').val(),
                        carne: carne
                    },
                    function(response) {
                        $("#tabla_aulas").html(response);
                    });
            }
        });
        document.getElementById("saulas").className = "active";
    </script>


    <br /><?php include_once("footer.php") ?>
</body>

</html>