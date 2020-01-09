<?php
session_start();
include_once("gestionBD.php");
include_once("gestionUsuarios.php");
include_once("gestionLibros.php");

if (!isset($_SESSION["login"])) {
    Header("Location: index.php");
} else {
    $carne = $_SESSION["login"]["usuario"];
    if (substr($carne, 5) != 'A') {
        Header("Location: exito_login.php");
    }
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
            <hr class="principal">
    <br>
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

            <div class="row col-12-m">
                <div class="divform margini prestamosalum inlineblock col-12-m col-12-t col-3-es">
                   
                        <legend>Préstamos activos</legend><br>
                        <?php
                        $con = crearConexionBD();
                        $prestamos = prestamosActivos($con, $carne);
                        foreach ($prestamos as $prestamo) {
                            echo "<div class='prestamo'>";
                            echo 'Título: '. $prestamo["TITULO"];
                            echo "<br>";
                            echo 'Autor: '. $prestamo["AUTOR"];
                            echo "<br>";
                            echo 'Código: '.$prestamo["CODIGO"];
                            echo "<br>";
                            echo 'Devolver: '.$prestamo["FECHA_FIN"];
                            echo "<br></div><br>";
                        }
                        ?>
                </div>
            <div class="divform inlineblock col-12-m col-12-t col-8-es"> 
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
                    </table><br>
            </div>
        </div>
    </div>
    </main>
    <script>
        document.getElementById("sinicio").className = "active";
    </script>

    <?php include_once("footer.php"); ?><br>

</body>

</html>