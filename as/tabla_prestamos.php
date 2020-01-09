<?php
session_start();
include_once("gestionBD.php");
include_once("gestionLibros.php");

$carne = $_SESSION["login"]["usuario"]; 
if (!isset($_SESSION["login"])) {
    Header("Location: index.php");
} else {
    $botonRenovar = $carne == '99999P' || $carne == '00000P' || substr($carne, 5) == 'A';
    $botonDevolver = $carne == '99999P' || $carne == '00000P';

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

    if (isset($_GET["busqueda"])) {
        if (!preg_match("/^[0-9a-zA-ZÀ-ÿñÑº_ -]*$/", $_GET["busqueda"])) { ?>
            <p>La búsqueda contiene un caracter no permitido</p>
        <?php } else {
        $val = $_GET["val"];
        $busqueda = $_GET["busqueda"];
        unset($_SESSION["paginacion"]);

        $conexion = crearConexionBD(); 

        $datosConsulta = historialDePrestamos($conexion, $val, $busqueda, $carne, $page_num, $page_size);
        $total = $datosConsulta["total"];
        $total_pages = $datosConsulta["total_pages"];
        $prestamos = $datosConsulta["prestamos"];

        $paginacion["pag_num"] = $page_num;
        $paginacion["pag_size"] = $page_size;
        $_SESSION["paginacion"] = $paginacion;
        cerrarConexionBD($conexion); 


        ?>
            <div>
                <table class="tabla-pag">
                    <tr>
                        <th class="start-line">Ejemplar</th>
                        <th>Código</th>
                        <th>Fecha de reserva</th>
                        <th>Fecha límite de entrega</th>
                        <th <?php if ($carne != "00000P" && $carne != "99999P" && !$botonDevolver && !$botonRenovar) echo 'class="end-line"' ?>>Fecha de entrega</th>
                        <?php if ($carne == "00000P" || $carne == "99999P") { ?>
                            <th>Usuario</th>
                        <?php } ?>
                        <?php if ($botonDevolver || $botonRenovar) { ?>
                            <th class="end-line">Controles</th>
                        <?php } ?>
                    </tr>
                    <?php foreach ($prestamos as $prestamo) {
                        $noEntregado = $prestamo["FECHA_ENTREGA"] == null;
                        $fechaPrincipio = DateTime::createFromFormat('d/m/y',$prestamo["FECHA_INICIO"]);
                        $fechaUltima = DateTime::createFromFormat('d/m/y', $prestamo["FECHA_FIN"]);
                        $fechaAux = $fechaPrincipio;
                        $fechaAux->add(new DateInterval('P15D'));
                        $noRenovado = $fechaUltima == $fechaAux;
                        $conFin = $prestamo["FECHA_FIN"] != null;
                        ?>
                        <tr>
                            <td class="start-line"><?php echo $prestamo["TITULO"] . ", " . $prestamo["AUTOR"] ?></td>
                            <td><?php echo $prestamo["CODIGO"] ?></td>
                            <td><?php echo $prestamo["FECHA_INICIO"] ?></td>
                            <td><?php if ($prestamo["FECHA_FIN"] == null) {
                                    echo 'Sin límite';
                                } else {
                                    echo $prestamo["FECHA_FIN"];
                                } ?></td>
                            <td <?php if ($carne != "00000P" && $carne != "99999P" && !$botonDevolver && !$botonRenovar) echo 'class="end-line"' ?>><?php if ($prestamo["FECHA_ENTREGA"] == null) {
                                                                                                                                                echo 'No entregado';
                                                                                                                                            } else {
                                                                                                                                                echo $prestamo["FECHA_ENTREGA"];
                                                                                                                                            } ?></td>
                            <?php if ($carne == "00000P" || $carne == "99999P") { ?>
                                <td><?php echo $prestamo["NUM_CARNE"] ?></td>
                            <?php } ?>
                            <?php if ($botonDevolver && $noEntregado || $botonRenovar && $conFin && $noEntregado && $noRenovado) { ?>
                                <td class="end-line-button">
                                    <form method="post" action="controlador_prestamos.php">
                                        <input id="CODIGO" name="CODIGO" type="hidden" value="<?php echo $prestamo["CODIGO"]; ?>" />
                                        <input id="OID_P" name="OID_P" type="hidden" value="<?php echo $prestamo["OID_P"]; ?>" />
                                        <?php if ($botonRenovar && $conFin && $noEntregado && $noRenovado) { ?>
                                            <button id="renovar" class="btn" name="renovar" type="submit">
                                                <img src="images/renovar_prest.png" alt="Renovar préstamo" title="Renovar préstamo" width="18px" height="18px">
                                            </button>
                                        <?php } ?>
                                        <?php if ($botonDevolver && $noEntregado) { ?>
                                            <button id="devolver" class="btn" name="devolver" type="submit">
                                                <img src="images/dev_prestamo.png" alt="Devolver préstamo" title="Devolver préstamo" width="18px" height="18px">
                                            </button>
                                        <?php } ?>
                                    </form>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </table>
                                            <br>
                <form method="post" action="historial_prestamos.php">
                    <?php for ($page = 1; $page <= $total_pages; $page++) {
                        if ($page == $page_num) { ?>
                            <span class="current"><?= $page ?></span>
                        <?php       } else { ?>
                            <button type="button" class="btn" onclick="tablaPrestamos('busqueda=<?= $busqueda ?>&val=<?= $val ?>&page_num=<?= $page ?>&page_size=<?= $page_size ?>')"><?= $page ?></button>
                        <?php       }
                }
                ?>
                    <input id="bus" name="bus" type="hidden" value="<?= $busqueda ?>">
                    <input id="val" name="val" type="hidden" value="<?= $val ?>">
                    <input id="page_num" name="page_num" type="hidden" value="<?= $page_num ?>" /> Mostrando
                    <input id="page_size" name="page_size" type="number" min="1" max="<?= $total ?>" value="<?= $page_size ?>" autofocus="autofocus" />
                    entradas de <?= $total ?>
                    <input type="submit" value="Cambiar" /></form><br>
            </div>
        <?php }
}
} ?>