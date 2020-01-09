<?php
session_start();
include_once("gestionBD.php");
include_once("gestionUsuarios.php");
include_once("gestionMateriales.php");
include_once("gestionDepartamentos.php");

$carne = $_SESSION["login"]["usuario"]; 
if (!isset($_SESSION["login"])) { 
    Header("Location: index.php");
} else if ($carne != '00000P' && $carne != '99999P') {
    Header("Location: exito_login.php");
} else {
    if (isset($_GET["retype"]) && isset($_GET["busqueda"])) {
        $valid_es = array('mat', 'aul');
        $retype = $_GET["retype"];

        if (!preg_match("/^[0-9a-zA-ZÀ-ÿñÑº_ -]*$/", $_GET["busqueda"])) { ?>
            <p>La búsqueda contiene un carácter no permitido</p>
        <?php } else {

        if (in_array($retype, $valid_es)) {
            $conexion = crearConexionBD(); 
            $carne = $_SESSION["login"]["usuario"];
            $controles = $carne == '00000P';

            $page_num = isset($_GET["page_num"]) ? (int)$_GET["page_num"] : $paginacion["pag_num"];
            $page_size = isset($_GET["page_size"]) ? (int)$_GET["page_size"] : $paginacion["pag_size"];

            if ($page_num < 1) $page_num = 1;
            if ($page_size < 1) $page_size = 10;
            $val = $_GET["val"];
            $busqueda = $_GET["busqueda"];
            unset($_SESSION["paginacion"]);
            $pagstr = isset($page_num) ? ('&page_num=' . $page_num . '&page_size=' . $page_size) : ('');
            ?>


                <?php if ($retype == "mat") {
                    $datosConsulta = obtenerReservasMateriales($conexion, $val, $busqueda, $page_num, $page_size);
                    $total = $datosConsulta["total"];
                    $total_pages = $datosConsulta["total_pages"];
                    $reservas_materiales = $datosConsulta["materiales"];

                    $paginacion["pag_num"] = $page_num;
                    $paginacion["pag_size"] = $page_size;
                    $_SESSION["paginacion"] = $paginacion;
                    ?>

                    <div>
                        <br>
                        <table class="tabla-pag">
                            <tr>
                                <th class="start-line">Descripción</th>
                                <th>Estado</th>
                                <th>Departamento</th>
                                <th>Fecha de reserva</th>
                                <th>Hora</th>
                                <th <?php if (!$controles) echo 'class="end-line"' ?>>Usuario</th>
                                <?php if ($controles) echo '<th class="end-line">Controles</th>' ?>
                            </tr>
                            <?php foreach ($reservas_materiales as $reserva_mat) { ?>
                                <tr>
                                    <td class="start-line"><?php echo $reserva_mat["DESCRIPCION"] ?></td>
                                    <td><?php echo $reserva_mat["ESTADO"] ?></td>
                                    <td><?php echo obtenerDepartamento($conexion, $reserva_mat["OID_D"]) ?></td>
                                    <td><?php echo $reserva_mat["FECHA_RESERVA"] ?></td>
                                    <td><?php echo $reserva_mat["TRAMO"] . "ª" ?></td>
                                    <td <?php if (!$controles) echo 'class="end-line"' ?>><?php echo $reserva_mat["NUM_CARNE"] ?></td>
                                    <?php if ($controles) { ?>
                                        <td class="end-line">
                                            <form method="post" action="accion_cancelar_reserva.php">
                                                <input id="OID_RM" name="OID_RM" type="hidden" value="<?php echo $reserva_mat["OID_RM"]; ?>" />
                                                <button id="borrar" class="btn" name="borrar" type="submit">
                                                    <img src="images/delete_icon.png" alt="Cancelar reserva" width="18px" height="18px">
                                                </button>
                                            </form>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        </table>
                        <br>
                        <form method="post" action="historial_reservas.php">
                            <?php for ($page = 1; $page <= $total_pages; $page++) {
                                if ($page == $page_num) { ?>
                                    <span class="current"><?= $page ?></span>
                                <?php       } else { ?>
                                    <button type="button" class="btn" onclick="tablaHistorialReservas('retype=<?= $retype ?>&busqueda=<?= $busqueda ?>&val=<?= $val ?>&page_num=<?= $page ?>&page_size=<?= $page_size ?>')"><?= $page ?></button>
                                <?php       }
                        }
                        ?>
                            <input id="bus" name="bus" type="hidden" value="<?= $busqueda ?>">
                            <input id="val" name="val" type="hidden" value="<?= $val ?>">
                            <input id="retype" name="retype" type="hidden" value="<?= $retype ?>">
                            <input id="page_num" name="page_num" type="hidden" value="<?= $page_num ?>" /> Mostrando
                            <input id="page_size" name="page_size" type="number" min="1" max="<?= $total ?>" value="<?= $page_size ?>" autofocus="autofocus" />
                            entradas de <?= $total ?>
                            <input id="table" name="table" type="hidden" value="materiales" />
                            <input type="submit" value="Cambiar" /></form><br>
                    </div>
                <?php } else if ($retype == "aul") {
                $datosConsulta = obtenerReservasAulas($conexion, $val, $busqueda, $page_num, $page_size);
                $total = $datosConsulta["total"];
                $total_pages = $datosConsulta["total_pages"];
                $reservas_aulas = $datosConsulta["aulas"];

                $paginacion["pag_num"] = $page_num;
                $paginacion["pag_size"] = $page_size;
                $_SESSION["paginacion"] = $paginacion;
                ?>
                    <div>
                        <br>
                        <table class="tabla-pag">
                            <tr>
                                <th class="start-line">Nombre</th>
                                <th>Fecha de reserva</th>
                                <th>Hora</th>
                                <th <?php if (!$controles) echo 'class="end-line"' ?>>Usuario</th>
                                <?php if ($controles) echo '<th class="end-line">Controles</th>' ?>
                            </tr>
                            <?php foreach ($reservas_aulas as $reserva_aul) { ?>
                                <tr>
                                    <td class="start-line"><?php echo $reserva_aul["NOMBRE"] ?></td>
                                    <td><?php echo $reserva_aul["FECHA_RESERVA"] ?></td>
                                    <td><?php echo $reserva_aul["TRAMO"] . "ª" ?></td>
                                    <td <?php if (!$controles) echo 'class="end-line"' ?>><?php echo $reserva_aul["NUM_CARNE"] ?></td>
                                    <?php if ($controles) { ?>
                                        <td class="end-line">
                                            <form method="post" action="accion_cancelar_reserva.php">
                                                <input id="OID_RA" name="OID_RA" type="hidden" value="<?php echo $reserva_aul["OID_RA"]; ?>" />
                                                <button id="borrar" class="btn" title='Cancelar reserva' name="borrar" type="submit">
                                                    <img src="images/delete_icon.png" alt="Cancelar reserva" width="18px" height="18px">
                                                </button>
                                            </form>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        </table>
                        <br>
                        <form method="post" action="historial_reservas.php">
                            <?php for ($page = 1; $page <= $total_pages; $page++) {
                                if ($page == $page_num) { ?>
                                    <span class="current"><?= $page ?></span>
                                <?php       } else { ?>
                                    <button type="button" class="btn" onclick="tablaHistorialReservas('retype=<?= $retype ?>&busqueda=<?= $busqueda ?>&val=<?= $val ?>&page_num=<?= $page ?>&page_size=<?= $page_size ?>')"><?= $page ?></button>
                                <?php       }
                        }
                        ?>
                            <input id="bus" name="bus" type="hidden" value="<?= $busqueda ?>">
                            <input id="val" name="val" type="hidden" value="<?= $val ?>">
                            <input id="retype" name="retype" type="hidden" value="<?= $retype ?>">
                            <input id="page_num" name="page_num" type="hidden" value="<?= $page_num ?>" /> Mostrando
                            <input id="page_size" name="page_size" type="number" min="1" max="<?= $total ?>" value="<?= $page_size ?>" autofocus="autofocus" />
                            entradas de <?= $total ?>
                            <input id="table" name="table" type="hidden" value="aulas" />
                            <input type="submit" value="Cambiar" /></form><br>
                    </div>



                <?php }
        }
    }
}
} ?>