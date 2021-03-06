<?php
session_start();
include_once("gestionBD.php");
include_once("gestionLibros.php");

$carne = $_SESSION["login"]["usuario"];
if (!isset($_SESSION["login"])) { 
    Header("Location: index.php");
} else {
    $conexion = crearConexionBD();
    $controles = $carne == '00000P' || $carne == '99999P';
    $isbnej = $_GET["isbnej"];

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
            <p>La búsqueda contiene un carácter no permitido</p>
        <?php } else {
        $val = $_GET["val"];
        $busqueda = $_GET["busqueda"];
        unset($_SESSION["paginacion"]);

        $datosConsulta = consultaEjemplares($conexion, $carne, $val, $busqueda, $isbnej, $page_num, $page_size); //Consultamos el catalogo de ejemplares y los guardamos en una variable llamada ejemplares
        $total = $datosConsulta["total"];
        $total_pages = $datosConsulta["total_pages"];
        $ejemplares = $datosConsulta["ejemplares"];

        $paginacion["pag_num"] = $page_num;
        $paginacion["pag_size"] = $page_size;
        $_SESSION["paginacion"] = $paginacion;
        cerrarConexionBD($conexion); 


        ?>
            <div>
                <table class="tabla-pag">
                    <tr>
                        <th class="start-line">Código</th>
                        <th>Estado</th>
                        <?php if ($controles) echo '<th>Fecha de baja</th>' ?>
                        <th <?php if (!$controles) echo 'class="end-line"' ?>>Disponible</th>
                        <?php if ($controles) echo '<th class="end-line">Controles</th>' ?>
                    </tr>
                    <?php
                    foreach ($ejemplares as $ejemplar) {
                        ?>
                        <tr>
                            <td class="start-line"><?php echo $ejemplar["CODIGO"] ?></td>
                            <td><?php echo $ejemplar["ESTADO"] ?></td>
                            <?php if ($controles) {
                                if ($ejemplar["FECHA_BAJA"] == null) {
                                    echo '<td> Activo </td>';
                                } else {
                                    echo '<td>' . $ejemplar["FECHA_BAJA"] . '</td>';
                                }
                            } ?>

                            <td <?php if (!$controles) echo 'class="end-line"' ?>><?php if ($ejemplar["DISPONIBLE"] == '1') {
                                                                                        echo 'Sí';
                                                                                    } else {
                                                                                        echo 'No';
                                                                                    } ?></td>
                            <?php if ($controles) { ?>
                                <td class="end-line">
                                    <form method="post" action="controlador_ejemplares.php">
                                        <input id="ISBN" name="ISBN" type="hidden" value="<?php echo $ejemplar["ISBN"]; ?>" />
                                        <input id="CODIGO" name="CODIGO" type="hidden" value="<?php echo $ejemplar["CODIGO"]; ?>" />
                                        <input id="ESTADO" name="ESTADO" type="hidden" value="<?php echo $ejemplar["ESTADO"]; ?>" />
                                        <input id="DISPONIBLE" name="DISPONIBLE" type="hidden" value="<?php echo $ejemplar["DISPONIBLE"]; ?>" />
                                        <?php if ($ejemplar["FECHA_BAJA"] == null) { ?>
                                            <button id="editar" class="btn" name="editar" type="submit">
                                                <img src="images/edit_icon.png" alt="Editar ejemplar" width="18px" height="18px">
                                            </button>
                                            <button id="borrar" class="btn" name="borrar" type="submit">
                                                <img src="images/delete_icon.png" alt="Borrar ejemplar" width="18px" height="18px">
                                            </button>
                                        <?php } ?>
                                    </form>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </table>
                <br>                            
                <form method="post" action="ejemplares.php">
                    <?php for ($page = 1; $page <= $total_pages; $page++) {
                        if ($page == $page_num) { ?>
                            <span class="current"><?= $page ?></span>
                        <?php       } else { ?>
                            <button type="button" class="btn" onclick="tablaEjemplares('isbnej=<?= $isbnej ?>&busqueda=<?= $busqueda ?>&val=<?= $val ?>&page_num=<?= $page ?>&page_size=<?= $page_size ?>')"><?= $page ?></button>
                        <?php       }
                }
                ?>

                    <input id="bus" name="bus" type="hidden" value="<?= $busqueda ?>">
                    <input id="val" name="val" type="hidden" value="<?= $val ?>">
                    <input id="page_num" name="page_num" type="hidden" value="<?= $page_num ?>" /> Mostrando
                    <input id="page_size" name="page_size" type="number" min="1" max="<?= $total ?>" value="<?= $page_size ?>" autofocus="autofocus" />
                    entradas de <?= $total ?>
                    <input id="isbnej" name="isbnej" type="text" value="<?= $isbnej ?>" hidden />
                    <input type="submit" value="Cambiar" /></form><br>
            </div>
        <?php }
}
} ?>