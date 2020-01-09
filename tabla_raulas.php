<?php
     
     session_start();
     
     require_once("gestionBD.php");
     require_once("gestionAulas.php");

     if(!isset($_SESSION["login"])) Header("Location: index.php");

    $carne_usuario = $_SESSION["login"]["usuario"];

    if(substr($carne_usuario, 5) == 'A') Header("Location: exito_login.php");

    if(isset($_GET["fecha"]) && isset($_GET["carne"])) {
        $carne = $_GET["carne"];
        $fecha = $_GET["fecha"];
        $fecha_bonita = new DateTime($fecha);
        echo "<tr>";
        echo "<th>Aulas</th>";
        echo "<th>1ª hora</th>";
        echo "<th>2ª hora</th>";
        echo "<th>3ª hora</th>";
        echo "<th>4ª hora</th>";
        echo "<th>5ª hora</th>";
        echo "<th>6ª hora</th>";
        echo "</tr>";
        
        $con = crearConexionBD();
        $aulas = listaAulas($con);

        foreach($aulas as $aula) {
            echo "<tr>";
            echo "<th class='td-start'>" . $aula["NOMBRE"] . "</td>";
            for($i = 1; $i <=6; $i++) {
                $tramo_reservado = reservasAulasPorAulaTramo($con, $fecha, $aula["NUMERO"], $i);
                echo '<td class="end-line-button"><form method="post" action="controlador_aulas.php">';

                if(!empty($tramo_reservado)) {
                    echo '<input type="hidden" name="OID_RA" id="OID_RA" value="' . $tramo_reservado['OID_RA'] . '">';
                    echo '<input type="hidden" name="NUMERO" id="NUMERO" value="' . $tramo_reservado['NUMERO'] . '">';
                    echo '<input type="hidden" name="FECHA_RESERVA" id="FECHA_RESERVA" value="'. $fecha . '">';
                    echo '<input type="hidden" name="TRAMO" id="TRAMO" value="' . $tramo_reservado['TRAMO'] . '">';
                    if($carne == '00000P' || $carne == $tramo_reservado["NUM_CARNE"]) {
                        echo '<input type="hidden" name="NUM_CARNE" id="NUM_CARNE" value="' . $tramo_reservado['NUM_CARNE'] . '">';
                        echo '<button id="cancelar" name="cancelar" type="submit" class="btn btnred">Cancelar</button></form></td>';
                    } else {
                        echo '<button class="aula-material-reservado" disabled>Reservado</button></form></td>';
                    }
                } else {
                    echo '<input type="hidden" name="NUMERO" id="NUMERO" value="' . $aula['NUMERO'] . '">';
                    echo '<input type="hidden" name="FECHA_RESERVA" id="FECHA_RESERVA" value="' . $fecha . '">';
                    echo '<input type="hidden" name="TRAMO" id="TRAMO" value="' . $i . '"/>';
                    echo '<input type="hidden" name="NUM_CARNE" id="NUM_CARNE" value="' . $carne . '">';
                    $administradorDisabled = '';
                    if($carne == '00000P') $administradorDisabled = 'disabled';
                    echo '<button id="reservar" name="reservar" type="submit" class="btn" ' . $administradorDisabled . ' >Reservar</button></form></td>';
                }
            }
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';
        cerrarConexionBD($con);
        unset($_GET["fecha"]);
        unset($_GET["carne"]);
    }

?>