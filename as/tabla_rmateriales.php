<?php

session_start();
     
require_once("gestionBD.php");
require_once("gestionMateriales.php");

if(!isset($_SESSION["login"])) Header("Location: index.php");

$num_carne = $_SESSION["login"]["usuario"];

if(substr($num_carne, 5) == 'A') Header("Location: exito_login.php");

if(isset($_GET["fecha"]) && isset($_GET["carne"]) && isset($_GET["material"]) && isset($_GET["descripcion"])) {
    $carne = $_GET["carne"];
    $fecha = $_GET["fecha"];
    $material = $_GET["material"];
    $descripcion = $_GET["descripcion"];
    $fecha_bonita = new DateTime($fecha);
    $s = $fecha_bonita->format("d/m/Y");

    echo "<table class='tablahorarios'>";
    echo "<tr>";
    echo "<th>Material</th>";
    echo "<th>1ª hora</th>";
    echo "<th>2ª hora</th>";
    echo "<th>3ª hora</th>";
    echo "<th>4ª hora</th>";
    echo "<th>5ª hora</th>";
    echo "<th>6ª hora</th>";
    echo "</tr>";
    echo "<tr>";
    echo "<th class='td-start'>" . $descripcion . "</td>";

    $con = crearConexionBD();

    for($i = 1; $i <=6; $i++) {
        $tramo_reservado = reservasMaterialesPorTramo($con, $fecha, $material, $i);
        echo '<td class="end-line-button"><form method="post" action="controlador_materiales.php">';
        if(!empty($tramo_reservado)) {
            echo '<input type="hidden" name="OID_RM" id="OID_RM" value="' . $tramo_reservado['OID_RM'] . '">';
            echo '<input type="hidden" name="OID_RA" id="OID_RA" value="' . $tramo_reservado['OID_M'] . '">';
            echo '<input type="hidden" name="FECHA_RESERVA" id="FECHA_RESERVA" value='. $tramo_reservado['FECHA_RESERVA'] . '">';
            echo '<input type="hidden" name="TRAMO" id="TRAMO" value="' . $tramo_reservado['TRAMO'] . '">';
            if($carne == '00000P' || $carne == $tramo_reservado["NUM_CARNE"]) {
                echo '<input type="hidden" name="NUM_CARNE" id="NUM_CARNE" value="' . $tramo_reservado['NUM_CARNE'] . '">';
                echo '<button id="cancelar" name="cancelar" type="submit" class="btn btnred">Cancelar</button></form></td>';
            } else {
                echo '<button class="aula-material-reservado" disabled>Reservado</button></form></td>';
            }
        } else {
            echo '<input type="hidden" name="OID_M" id="OID_M" value="' . $material . '">';
            echo '<input type="hidden" name="FECHA_RESERVA" id="FECHA_RESERVA" value="' . $fecha . '">';
            echo '<input type="hidden" name="TRAMO" id="TRAMO" value="' . $i . '"/>';
            echo '<input type="hidden" name="NUM_CARNE" id="NUM_CARNE" value="' . $carne . '">';
            $administradorDisabled = '';
            if($carne == '00000P') $administradorDisabled = 'disabled';
            echo '<button id="reservar" name="reservar" type="submit" class="btn" ' . $administradorDisabled . ' >Reservar</button></form></td>';
        }
    }
    echo '</tr>';
    echo '</table>';
    echo '</div>';
    cerrarConexionBD($con);
    unset($_GET["fecha"]);
    unset($_GET["carne"]);
    unset($_GET["material"]);
    unset($_GET["descripcion"]);
}

?>