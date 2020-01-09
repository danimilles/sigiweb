<?php
session_start();
include_once("gestionBD.php");
include_once("gestionUsuarios.php");
include_once("gestionMateriales.php");
include_once("gestionDepartamentos.php");

$carne = $_SESSION["login"]["usuario"]; 
if (!isset($_SESSION["login"])) { 
    Header("Location: index.php");
} else {
    $conexion = crearConexionBD();
    $carne = $_SESSION["login"]["usuario"];
    if (!esJefeDepartamento($conexion, $carne) && $carne != '00000P') {
        Header("Location: exito_login.php");
    }

    if ($carne != '00000P') {
        $datos = perfilUsuario($conexion, $carne); 
        $oid_dep = $datos["OID_D"];
    } else {
        $oid_dep = "";
    }

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

        $datosConsulta = obtenerMateriales($conexion, $val, $busqueda, $carne, $oid_dep, $page_num, $page_size);
        $total = $datosConsulta["total"];
        $total_pages = $datosConsulta["total_pages"];
        $materiales = $datosConsulta["materiales"];

        $paginacion["pag_num"] = $page_num;
        $paginacion["pag_size"] = $page_size;
        $_SESSION["paginacion"] = $paginacion;


        ?>
            <div>
                <table class="tabla-pag">
                    <tr>
                        <th class="start-line">Descripción</th>
                        <th>Estado</th>
                        <th>Fecha de alta</th>
                        <th>Fecha de baja</th>
                        <?php if ($carne == '00000P') { ?>
                            <th>Departamento</th>
                        <?php } ?>
                        <th>Unidades</th>
                        <th class="end-line">Controles</th>
                    </tr>
                    <?php
                    foreach ($materiales as $material) {
                        ?>
                        <tr>
                            <td class="start-line"><?php echo $material["DESCRIPCION"] ?></td>
                            <td><?php echo $material["ESTADO"] ?></td>
                            <td><?php echo $material["FECHA_ALTA"] ?></td>
                            <td><?php
                                if ($material["FECHA_BAJA"] == null) {
                                    echo 'Activo';
                                } else {
                                    echo $material["FECHA_BAJA"];
                                } ?>
                            </td>
                            <?php if ($carne == '00000P') { ?>
                                <td><?php echo obtenerDepartamento($conexion, $material["OID_D"]) ?></td>
                            <?php } ?>
                            <td><?php echo $material["UNIDADES"] ?></td>
                            <td class="end-line">
                                <form method="post" action="controlador_inventario.php">
                                    <input id="DESCRIPCION" name="DESCRIPCION" type="hidden" value="<?php echo $material["DESCRIPCION"]; ?>" />
                                    <input id="ESTADO" name="ESTADO" type="hidden" value="<?php echo $material["ESTADO"]; ?>" />
                                    <input id="UNIDADES" name="UNIDADES" type="hidden" value="<?php echo $material["UNIDADES"]; ?>" />
                                    <input id="OID_D" name="OID_D" type="hidden" value="<?php echo $material["OID_D"]; ?>" />
                                    <input id="OID_M" name="OID_M" type="hidden" value="<?php echo $material["OID_M"]; ?>" />

                                    <?php if ($material["FECHA_BAJA"] == null) { ?>
                                        <button id="editar" class="btn" name="editar" title="Editar material" type="submit">
                                            <img src="images/edit_icon.png" alt="Editar material" width="18px" height="18px">
                                        </button>
                                        <button id="borrar" class="btn" name="borrar" title="Dar de baja" type="submit">
                                            <img src="images/delete_icon.png" alt="Dar de baja" width="18px" height="18px">
                                        </button>
                                    <?php } ?>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
                <br>
                <form method="post" action="inventario_dept.php">
                    <?php for ($page = 1; $page <= $total_pages; $page++) {
                        if ($page == $page_num) { ?>
                            <span class="current"><?= $page ?></span>
                        <?php       } else { ?>
                            <button type="button" class="btn" onclick="tablaInventario('busqueda=<?= $busqueda ?>&val=<?= $val ?>&page_num=<?= $page ?>&page_size=<?= $page_size ?>')"><?= $page ?></button>
                        <?php       }
                }
                ?>

                    <input id="bus" name="bus" type="hidden" value="<?= $busqueda ?>">
                    <input id="val" name="val" type="hidden" value="<?= $val ?>">
                    <input id="page_num" name="page_num" type="hidden" value="<?= $page_num ?>" /> Mostrando
                    <input id="page_size" name="page_size" type="number" min="1" max="<?= $total ?>" value="<?= $page_size ?>" autofocus="autofocus" />
                    entradas de <?= $total ?>
                    <input type="submit" value="Cambiar" />
                </form><br>
            </div>
        <?php }
}
} ?>