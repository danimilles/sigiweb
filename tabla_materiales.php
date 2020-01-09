<?php
session_start();
include_once("gestionBD.php");
include_once("gestionMateriales.php");
include_once("gestionDepartamentos.php");
$carne = $_SESSION["login"]["usuario"]; 
if (!isset($_SESSION["login"])) { 
    Header("Location: index.php");
} else if (substr($carne, 5) == 'A') Header("Location: exito_login.php");
else {

    $conexion = crearConexionBD(); 

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
        $busqueda = $_GET["busqueda"];
        unset($_SESSION["paginacion"]);

        $datosConsulta = listaMateriales($conexion, $busqueda, $page_num, $page_size); 
        $total = $datosConsulta["total"];
        $total_pages = $datosConsulta["total_pages"];
        $materiales = $datosConsulta["materiales"];

        $paginacion["pag_num"] = $page_num;
        $paginacion["pag_size"] = $page_size;
        $_SESSION["paginacion"] = $paginacion;

        ?>
            <div>
                <table class="hg tabla-pag">
                    <tr>
                        <th class="start-line">Descripción</th>
                        <th>Estado</th>
                        <th>Departamento</th>
                        <th>Fecha de alta</th>
                        <th>Unidades</th>
                        <th class="end-line">Controles</th>
                    </tr>
                    <?php
                    foreach ($materiales as $material) {
                        ?>
                        <tr>
                            <td class="start-line"><?php echo $material["DESCRIPCION"] ?></td>
                            <td><?php echo $material["ESTADO"] ?></td>
                            <td><?php echo obtenerDepartamento($conexion, $material["OID_D"]) ?></td>
                            <td><?php echo $material["FECHA_ALTA"] ?></td>
                            <td class="end-line"><?php echo $material["UNIDADES"] ?></td>
                            <td>
                                <form action="reserva_materiales.php" method="get">
                                    <input type="hidden" id="OID_M" name="oid_m" value="<?php echo $material['OID_M']; ?>" />
                                    <input type="hidden" id="DESCRIPCION" name="descripcion" value="<?php echo $material['DESCRIPCION']; ?>" />
                                    <input type="submit" value="Reservar" />
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>

                
                <br><form method="post" action="materiales.php">
                    <?php for ($page = 1; $page <= $total_pages; $page++) {
                        if ($page == $page_num) { ?>
                            <span class="current"><?= $page ?></span>
                        <?php       } else { ?>
                            <button type="button" class="btn" onclick="tablaMateriales('busqueda=<?= $busqueda ?>&page_num=<?= $page ?>&page_size=<?= $page_size ?>')"><?= $page ?></button>
                        <?php       }
                }
                ?>

                    <input id="bus" name="bus" type="hidden" value="<?= $busqueda ?>">
                    <input id="page_num" name="page_num" type="hidden" value="<?= $page_num ?>" /> Mostrando
                    <input id="page_size" name="page_size" type="number" min="1" max="<?= $total ?>" value="<?= $page_size ?>" autofocus="autofocus" />
                    entradas de <?= $total ?>
                    <input type="submit" value="Cambiar" />
                </form><br>
                
            </div>
        <?php }
}
} ?>