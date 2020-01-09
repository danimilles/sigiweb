<?php
session_start();
include_once("gestionUsuarios.php");
include_once("gestionBD.php");
include_once("gestionLibros.php");
include_once("gestionEstadisticas.php");

$carne = $_SESSION["login"]["usuario"];
if (!isset($_SESSION["login"])) {
    Header("Location: index.php");
} else if ($carne != '00000P' && $carne != '99999P') {
    Header("Location: exito_login.php");
}
if (isset($_GET["estype"])) {
    $valid_es = array('alumnos', 'genero', 'sexo', 'edad', 'curso', 'sanciones');
    $estype = $_GET["estype"];

    if (in_array($estype, $valid_es)) {
        if (isset($_SESSION["paginacion"])) {
            $paginacion = $_SESSION["paginacion"];
        } else {
            $paginacion["pag_num"] = 1;
            $paginacion["pag_size"] = 10;
        }

        $page_num = isset($_GET["page_num"]) ? (int)$_GET["page_num"] : $paginacion["pag_num"];
        $page_size = isset($_GET["page_size"]) ? (int)$_GET["page_size"] : $paginacion["pag_size"];

        if ($page_num < 1) $page_num = 1;
        if ($page_size < 1) $page_size = 10;

        $conexion = crearConexionBD();
        if ($estype == 'alumnos') {
            $datosConsulta = alumnosMasActivos($conexion, $page_num, $page_size);
            $total = $datosConsulta["total"];
            $total_pages = $datosConsulta["total_pages"];
            $datos = $datosConsulta["datos"];
            $paginacion["pag_num"] = $page_num;
            $paginacion["pag_size"] = $page_size;
            $_SESSION["paginacion"] = $paginacion;
            ?>

            <table class="tabla-pag">
                <tr>
                    <th class="start-line">Apellidos</th>
                    <th>Nombre</th>
                    <th>Carne</th>
                    <th>Curso</th>
                    <th>Programa</th>
                    <th>Grupo</th>
                    <th class="end-line">N. Libros</th>
                </tr>
                <?php foreach ($datos as $dato) { ?>
                    <tr>
                        <td class="start-line"><?php echo $dato["APELLIDOS"] ?></td>
                        <td><?php echo $dato["NOMBRE"] ?> </td>
                        <td><?php echo $dato["NUM_CARNE"] ?></td>
                        <td><?php echo $dato["CURSO"] ?></td>
                        <td><?php echo $dato["PROGRAMA_ACADEMICO"] ?></td>
                        <td><?php echo $dato["GRUPO"] ?></td>
                        <td class="end-line"><?php echo $dato["NOM"] ?></td>
                    </tr>
                <?php } ?>
            </table><br>

            <form method="post" action="estadisticas.php">
                <?php for ($page = 1; $page <= $total_pages; $page++) {
                    if ($page == $page_num) { ?>
                        <span class="current"><?= $page ?></span>
                    <?php       } else { ?>
                        <button class="btn" type="button" onclick="muestraEstadistica('estype=alumnos&page_num=<?= $page ?>&page_size=<?= $page_size ?>')"><?= $page ?></button>
                    <?php       }
            }
            ?>
                <input id="tipo" name="tipo" type="hidden" value="alumnos">
                <input id="page_num" name="page_num" type="hidden" value="<?= $page_num ?>" /> Mostrando
                <input id="page_size" name="page_size" type="number" min="1" max="<?= $total ?>" value="<?= $page_size ?>" autofocus="autofocus" />
                entradas de <?= $total ?>
                <input type="submit" value="Cambiar" />
                <button id="print" class="btn btnaul" name="print" type="button" onclick="printContent('tablaes')">
                <img src="images/print_icon.png" alt="Imprimir" width="20px" height="20px">
                 </button></form>

                <br>

        <?php
    } else if ($estype == 'genero') {
        $datosConsulta = generosMasPrestados($conexion, $page_num, $page_size);
        $total = $datosConsulta["total"];
        $total_pages = $datosConsulta["total_pages"];
        $datos = $datosConsulta["datos"];
        $paginacion["pag_num"] = $page_num;
        $paginacion["pag_size"] = $page_size;
        $_SESSION["paginacion"] = $paginacion;
        ?>

            <table class="tabla-pag">
                <tr>
                    <th class="start-line">Género</th>
                    <th class="end-line">Veces prestado</th>
                </tr>
                <?php foreach ($datos as $dato) { ?>
                    <tr>
                        <td class="start-line"><?php echo $dato["GENERO"] ?></td>
                        <td class="end-line"><?php echo $dato["CUENTA"] ?></td>
                    </tr>
                <?php } ?>
            </table>

            <br>
            <form method="post" action="estadisticas.php">
                <?php for ($page = 1; $page <= $total_pages; $page++) {
                    if ($page == $page_num) { ?>
                        <span class="current"><?= $page ?></span>
                    <?php       } else { ?>
                        <button class="btn" type="button" onclick="muestraEstadistica('estype=genero&page_num=<?= $page ?>&page_size=<?= $page_size ?>')"><?= $page ?></button>
                    <?php       }
            }
            ?>
                <input id="tipo" name="tipo" type="hidden" value="genero">
                <input id="page_num" name="page_num" type="hidden" value="<?= $page_num ?>" /> Mostrando
                <input id="page_size" name="page_size" type="number" min="1" max="<?= $total ?>" value="<?= $page_size ?>" autofocus="autofocus" />
                entradas de <?= $total ?>
                <input type="submit" value="Cambiar" />
                <button id="print" class="btn btnaul" name="print" type="button" onclick="printContent('tablaes')">
                <img src="images/print_icon.png" alt="Imprimir" width="20px" height="20px">
                 </button></form><br>

        <?php } else if ($estype == 'sexo') {
        if (isset($_GET["sexo"])) {
            $valid_s = array('M', 'F');
            $sexo = $_GET["sexo"];

            if (in_array($sexo, $valid_s)) {
                $datosConsulta = librosPorSexo($conexion, $sexo, $page_num, $page_size);
                $total = $datosConsulta["total"];
                $total_pages = $datosConsulta["total_pages"];
                $datos = $datosConsulta["datos"];
                $paginacion["pag_num"] = $page_num;
                $paginacion["pag_size"] = $page_size;
                $_SESSION["paginacion"] = $paginacion;
                ?>
                    <div align="center">
                    <button type="button" class="btn" onclick="muestraEstadistica('estype=sexo&sexo=<?php echo ($sexo == 'M') ? 'F' : 'M' ?>&page_num=<?= $page_num ?>&page_size=<?= $page_size ?>')"> Sexo <?php echo ($sexo == 'M') ? 'femenino' : 'masculino' ?></button>
                    </div><br>

                    <table class="tabla-pag">
                        <tr>
                            <th class="start-line">Título</th>
                            <th>Autor</th>
                            <th>ISBN</th>
                            <th>CDU</th>
                            <th>Género</th>
                            <th class="end-line">Veces prestado</th>
                        </tr>
                        <?php foreach ($datos as $dato) { ?>
                            <tr>
                                <td class="start-line"><?php echo $dato["TITULO"] ?></td>
                                <td><?php echo $dato["AUTOR"] ?> </td>
                                <td><?php echo $dato["ISBN"] ?></td>
                                <td><?php echo $dato["CDU"] ?></td>
                                <td><?php echo $dato["GENERO"] ?></td>
                                <td class="end-line"><?php echo $dato["CUENTA"] ?></td>
                            </tr>
                        <?php } ?>
                    </table>

                    <br>
                    <form method="post" action="estadisticas.php">
                        <?php for ($page = 1; $page <= $total_pages; $page++) {
                            if ($page == $page_num) { ?>
                                <span class="current"><?= $page ?></span>
                            <?php       } else { ?>
                                <button class="btn" type="button" onclick="muestraEstadistica('estype=sexo&sexo=<?= $sexo ?>&page_num=<?= $page ?>&page_size=<?= $page_size ?>')"><?= $page ?></button>
                            <?php       }
                    }
                    ?>
                        <input id="tipo" name="tipo" type="hidden" value="sexo&sexo=<?= $sexo ?>">
                        <input id="page_num" name="page_num" type="hidden" value="<?= $page_num ?>" /> Mostrando
                        <input id="page_size" name="page_size" type="number" min="1" max="<?= $total ?>" value="<?= $page_size ?>" autofocus="autofocus" />
                        entradas de <?= $total ?>
                        <input type="submit" value="Cambiar" />
                        <button id="print" class="btn btnaul" name="print" type="button" onclick="printContent('tablaes')">
                        <img src="images/print_icon.png" alt="Imprimir" width="20px" height="20px">
                        </button></form><br>

                <?php }
        }
    } else if ($estype == 'edad') {
        if (isset($_GET["edad"])) {
            $edad = (int)$_GET["edad"];

            if (is_int($edad) && $edad < 99 && $edad > 0) {
                $datosConsulta = librosPorEdad($conexion, $edad, $page_num, $page_size);
                $total = $datosConsulta["total"];
                $total_pages = $datosConsulta["total_pages"];
                $datos = $datosConsulta["datos"];
                $paginacion["pag_num"] = $page_num;
                $paginacion["pag_size"] = $page_size;
                $_SESSION["paginacion"] = $paginacion;
                ?>
                    <div align="center">
                    <input id="edad" name="edad" type="number" min="1" max="98" value=<?= $edad ?>>
                    <button class="btn" type="button" onclick="muestraEstadistica('estype=edad&edad='+$('#edad').val()+'&page_num=<?= $page_num ?>&page_size=<?= $page_size ?>')">Buscar</button>
                    </div><br>

                    <table class="tabla-pag">
                        <tr>
                            <th class="start-line">Título</th>
                            <th>Autor</th>
                            <th>ISBN</th>
                            <th>CDU</th>
                            <th>Género</th>
                            <th class="end-line">Veces prestado</th>
                        </tr>
                        <?php foreach ($datos as $dato) { ?>
                            <tr>
                                <td class="start-line"><?php echo $dato["TITULO"] ?></td>
                                <td><?php echo $dato["AUTOR"] ?> </td>
                                <td><?php echo $dato["ISBN"] ?></td>
                                <td><?php echo $dato["CDU"] ?></td>
                                <td><?php echo $dato["GENERO"] ?></td>
                                <td class="end-line"><?php echo $dato["CUENTA"] ?></td>
                            </tr>
                        <?php } ?>
                    </table>

                    <br>
                    <form method="post" action="estadisticas.php">
                        <?php for ($page = 1; $page <= $total_pages; $page++) {
                            if ($page == $page_num) { ?>
                                <span class="current"><?= $page ?></span>
                            <?php       } else { ?>
                                <button class="btn" type="button" onclick="muestraEstadistica('estype=edad&edad=<?= $edad ?>&page_num=<?= $page ?>&page_size=<?= $page_size ?>')"><?= $page ?></button>
                            <?php       }
                    }
                    ?>
                        <input id="tipo" name="tipo" type="hidden" value="edad&edad=<?= $edad ?>">
                        <input id="page_num" name="page_num" type="hidden" value="<?= $page_num ?>" /> Mostrando
                        <input id="page_size" name="page_size" type="number" min="1" max="<?= $total ?>" value="<?= $page_size ?>" autofocus="autofocus" />
                        entradas de <?= $total ?>
                        <input type="submit" value="Cambiar" />
                        <button id="print" class="btn btnaul" name="print" type="button" onclick="printContent('tablaes')">
                        <img src="images/print_icon.png" alt="Imprimir" width="20px" height="20px">
                        </button></form><br>

                <?php
            }
        }
    } else if ($estype == 'curso') {
        if (isset($_GET["curso"])) {
            $curso = (int)$_GET["curso"];

            if (is_int($curso) && $curso < 5 && $curso > 0 && isset($_GET["programa"])) {
                $valid_p = array('ESO', 'BACHILLERATO', 'FP Básica', 'CFGM Gestión Administrativa', 'CFGS Administración y Finanzas');
                $programa = $_GET["programa"];

                if (in_array($programa, $valid_p)) {
                    $datosConsulta = librosPorCurso($conexion, $curso, $programa, $page_num, $page_size);
                    $total = $datosConsulta["total"];
                    $total_pages = $datosConsulta["total_pages"];
                    $datos = $datosConsulta["datos"];
                    $paginacion["pag_num"] = $page_num;
                    $paginacion["pag_size"] = $page_size;
                    $_SESSION["paginacion"] = $paginacion;
                    ?>
                        <div align="center">
                        <input id="curso" name="curso" type="number" min="1" max="4" value=<?= $curso ?>>
                        <select class="selectw" id="programa">
                            <option value="ESO" <?php echo ($programa == 'ESO') ? ('selected') : (''); ?>>ESO</option>
                            <option value="BACHILLERATO" <?php echo ($programa == 'BACHILLERATO') ? ('selected') : (''); ?>>Bachillerato</option>
                            <option value="CFGM Gestión Administrativa" <?php echo ($programa == 'CFGM Gestión Administrativa') ? ('selected') : (''); ?>>CFGM Gestión Administrativa</option>
                            <option value="CFGS Administración y Finanzas" <?php echo ($programa == 'CFGS Administración y Finanzas') ? ('selected') : (''); ?>>CFGS Administración y Finanzas</option>
                            <option value="FP Básica" <?php echo ($programa == 'FP Básica') ? ('selected') : (''); ?>>FP Básica</option>
                        </select>
                        <button class="btn" type="button" onclick="muestraEstadistica('estype=curso&curso='+$('#curso').val()+'&programa='+$('#programa').val()+'&page_num=<?= $page_num ?>&page_size=<?= $page_size ?>')">Buscar</button>
                        </div>
                        <br>
                        <table class="tabla-pag">
                            <tr>
                                <th class="start-line">Título</th>
                                <th>Autor</th>
                                <th>ISBN</th>
                                <th>CDU</th>
                                <th>Género</th>
                                <th class="end-line">Veces prestado</th>
                            </tr>
                            <?php foreach ($datos as $dato) { ?>
                                <tr>
                                    <td class="start-line"><?php echo $dato["TITULO"] ?></td>
                                    <td><?php echo $dato["AUTOR"] ?> </td>
                                    <td><?php echo $dato["ISBN"] ?></td>
                                    <td><?php echo $dato["CDU"] ?></td>
                                    <td><?php echo $dato["GENERO"] ?></td>
                                    <td class="end-line"><?php echo $dato["CUENTA"] ?></td>
                                </tr>
                            <?php } ?>
                        </table>

                        <br>
                        <form method="post" action="estadisticas.php">
                            <?php for ($page = 1; $page <= $total_pages; $page++) {
                                if ($page == $page_num) { ?>
                                    <span class="current"><?= $page ?></span>
                                <?php       } else { ?>
                                    <button class="btn" type="button" onclick="muestraEstadistica('estype=curso&curso=<?= $curso ?>&programa=<?= $programa ?>&page_num=<?= $page ?>&page_size=<?= $page_size ?>')"><?= $page ?></button>
                                <?php       }
                        }
                        ?>
                            <input id="tipo" name="tipo" type="hidden" value="curso&curso=<?= $curso ?>&programa=<?= $programa ?>">
                            <input id="page_num" name="page_num" type="hidden" value="<?= $page_num ?>" /> Mostrando
                            <input id="page_size" name="page_size" type="number" min="1" max="<?= $total ?>" value="<?= $page_size ?>" autofocus="autofocus" />
                            entradas de <?= $total ?>
                            <input type="submit" value="Cambiar" />
                            <button id="print" class="btn btnaul" name="print" type="button" onclick="printContent('tablaes')">
                            <img src="images/print_icon.png" alt="Imprimir" width="20px" height="20px">
                            </button></form><br>
                    <?php
                }
            }
        }
    } else if ($estype == 'sanciones') {
        $datosConsulta = alumnosSancionados($conexion, $page_num, $page_size);
        $total = $datosConsulta["total"];
        $total_pages = $datosConsulta["total_pages"];
        $datos = $datosConsulta["datos"];
        $paginacion["pag_num"] = $page_num;
        $paginacion["pag_size"] = $page_size;
        $_SESSION["paginacion"] = $paginacion;
        ?>

            <table class="tabla-pag">
                <tr>
                    <th class="start-line">Apellidos</th>
                    <th>Nombre</th>
                    <th>Carne</th>
                    <th>Curso</th>
                    <th>Programa</th>
                    <th>Grupo</th>
                    <th class="end-line">Final de sanción</th>
                </tr>
                <?php foreach ($datos as $dato) { ?>
                    <tr>
                        <td class="start-line"><?php echo $dato["APELLIDOS"] ?></td>
                        <td><?php echo $dato["NOMBRE"] ?> </td>
                        <td><?php echo $dato["NUM_CARNE"] ?></td>
                        <td><?php echo $dato["CURSO"] ?></td>
                        <td><?php echo $dato["PROGRAMA_ACADEMICO"] ?></td>
                        <td><?php echo $dato["GRUPO"] ?></td>
                        <?php
                        $da = str_split($dato["FECHA_ENTREGA"], 1);
                        $r = '20' . $da[6] . $da[7] . '-' . $da[3] . $da[4] . '-' . $da[0] . $da[1];
                        $date = new DateTime($r);
                        $date->add(new DateInterval('P7D')); ?>
                        <td class="end-line"><?php echo date_format($date, 'd/m/y') ?></td>
                    </tr>
                <?php } ?>
            </table>

            <br>
            <form method="post" action="estadisticas.php">
                <?php for ($page = 1; $page <= $total_pages; $page++) {
                    if ($page == $page_num) { ?>
                        <span class="current"><?= $page ?></span>
                    <?php       } else { ?>
                        <button class="btn" type="button" onclick="muestraEstadistica('estype=sanciones&page_num=<?= $page ?>&page_size=<?= $page_size ?>')"><?= $page ?></button>
                    <?php       }
            }
            ?>
                <input id="tipo" name="tipo" type="hidden" value="sanciones">
                <input id="page_num" name="page_num" type="hidden" value="<?= $page_num ?>" /> Mostrando
                <input id="page_size" name="page_size" type="number" min="1" max="<?= $total ?>" value="<?= $page_size ?>" autofocus="autofocus" />
                entradas de <?= $total ?>
                <input type="submit" value="Cambiar" />
                <button id="print" class="btn btnaul" name="print" type="button" onclick="printContent('tablaes')">
                <img src="images/print_icon.png" alt="Imprimir" width="20px" height="20px">
                 </button></form><br>

        <?php }
}
}
?>