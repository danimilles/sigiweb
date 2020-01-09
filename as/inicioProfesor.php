<?php
session_start();
include_once("gestionBD.php");
include_once("gestionUsuarios.php");
include_once("gestionLibros.php");
include_once("gestionAulas.php");
include_once("gestionMateriales.php");

if (!isset($_SESSION["login"])) {
    Header("Location: index.php");
} else {
    $conexion = crearConexionBD();
    $carne = $_SESSION["login"]["usuario"];
    $esJefe = esJefeDepartamento($conexion, $carne);
    cerrarConexionBD($conexion);
}
$con = crearConexionBD();
$name = perfilUsuario($con, $carne);
cerrarConexionBD($con);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Menú principal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
    <script src="js/funciones.js"></script>
</head>

<body>

    <?php include_once("header.php") ?>
    <br>
    <?php include_once("sidenav.php"); ?>
    <main>
        <div id='main'>
            <h2>Bienvenid<?php echo $name["SEXO"] == 'M' ? 'o' : 'a' ?>, <?php echo $name["NOMBRE"] ?></h2>
            <hr class="principal"><br>

            <?php if (isset($_SESSION["errores"])) { ?>
                <div class="fallo">
                    <?php
                    $errores = $_SESSION["errores"];
                    foreach ($errores as $error) { ?>
                        <p><?php echo $error ?><p>
                    </div>
                <?php  }
        }
        unset($_SESSION["errores"]); ?>
        
        <div class="row col-12-m">
            <div class="divform margini prestamosalum inlineblock col-12-m col-12-t col-3-es">
                    <legend>Préstamos activos</legend><br>
                    <?php
                    $con = crearConexionBD();
                    $prestamos = prestamosActivos($con, $carne);
                    foreach ($prestamos as $prestamo) {
                        echo "<div class='prestamo'>Título: ";
                        echo $prestamo["TITULO"];
                        echo "<br>Autor: ";
                        echo $prestamo["AUTOR"];
                        echo "<br>Código: ";
                        echo $prestamo["CODIGO"];
                        echo "<br></div>";
                    }
                    ?>
            </div>
            <div class="divform margini prestamosalum inlineblock col-12-m col-12-t col-3-es">
                    <legend>Reservas de aulas activas</legend><br>
                    <?php
                    $con = crearConexionBD();
                    $raulas = r_aulasActivas($con, $carne);
                    foreach ($raulas as $raula) {
                        echo "<div  class='prestamo'>Aula: ";
                        echo $raula["NOMBRE"];
                        echo "<br>Tramo: ";
                        echo $raula["TRAMO"];
                        echo "<br>Fecha: ";
                        echo $raula["FECHA_RESERVA"];
                        echo "<br></div>";
                    }
                    ?>
            </div>
            <div class="divform  prestamosalum inlineblock col-12-m col-12-t col-3-es">
                    <legend>Reservas de materiales activas</legend><br>
                    <?php
                    $con = crearConexionBD();
                    $rmateriales = r_materialesActivas($con, $carne);
                    foreach ($rmateriales as $rmaterial) {
                        echo "<div class='prestamo'>Material: ";
                        echo $rmaterial["DESCRIPCION"];
                        echo "<br>Tramo: ";
                        echo $rmaterial["TRAMO"];
                        echo "<br>Fecha: ";
                        echo $rmaterial["FECHA_RESERVA"];
                        echo "<br></div>";
                    }
                    ?>
                
            </div>
            </div><div class="marginp"></div>
            <div class="divform inlineblock col-12-m col-12-t col-12-es"> 
            <legend>Horario</legend><br>
                    <table class="tabh tablahorarios">
                        <tr>
                            <th>Hora</th>
                            <th>Lunes</th>
                            <th>Martes</th>
                            <th>Miércoles</th>
                            <th>Jueves</th>
                            <th>Viernes</th>
                        </tr>
                        <?php
                        for ($i = 1; $i < 7; $i++) {
                            echo "<tr><th class='td-start'>";
                            echo $i;
                            echo "</td>";
                            for ($j = 1; $j < 6; $j++) {
                                switch ($j) {
                                    case 1:
                                        $dia = "Lunes";
                                        break;
                                    case 2:
                                        $dia = "Martes";
                                        break;
                                    case 3:
                                        $dia = "Miercoles";
                                        break;
                                    case 4;
                                        $dia = "Jueves";
                                        break;
                                    case 5;
                                        $dia = "Viernes";
                                        break;
                                }
                                $entradaHorario = entradaHorario($con, $carne, $dia, $i);
                                if ($entradaHorario == null) { ?>
                                    <td onclick='if(document.getElementById("f<?=$i?><?=$j?>").value=="false"){editScheduleFieldAddForm("<?php echo $i . $j; ?>", "<?php echo $carne; ?>", "<?php echo $dia ?>", "<?php echo $i; ?>");document.getElementById("f<?=$i?><?=$j?>").setAttribute("value","true");}' id='<?=$i?><?=$j?>'>
                                    <input id="f<?=$i?><?=$j?>" type=text value="false" hidden>
                                    <button class='btn' type='button' ><img src='images/plus.png' width=15 height=15 /></button>
                                <?php } else { ?>                                
                                <div align="center">
                                    <td id="<?=$i?><?=$j?>" onclick='if(document.getElementById("f<?=$i?><?=$j?>").value=="false"){editScheduleFieldEdit("<?php echo $i . $j; ?>", "<?php echo $entradaHorario["OID_H"]; ?>", "<?php echo $entradaHorario["ASIGNATURA"]; ?>");document.getElementById("f<?=$i?><?=$j?>").setAttribute("value","true");}'></button>
                                    <input id="f<?=$i?><?=$j?>" type=text value="false" hidden>
                                    <?php echo $entradaHorario["ASIGNATURA"]; ?>
                                </div>
                                <?php } ?>
                                </td>
                            <?php }
                        echo "</tr>";
                    }
                    cerrarConexionBD($con);
                    ?>
                    </table>
                </fieldset>
            </div>
        </div>
        
    </main>
    <script>
        document.getElementById("sinicio").className = "active";
    </script>

    <?php include_once("footer.php"); ?><br>

</body>

</html>