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

        $datosConsulta = consultaCatalogoLibros($conexion, $carne, $val, $busqueda, $page_num, $page_size); //Consultamos el catalogo de libros y los guardamos en una variable llamada libros
        $total = $datosConsulta["total"];
        $total_pages = $datosConsulta["total_pages"];
        $libros = $datosConsulta["libros"];

        $paginacion["pag_num"] = $page_num;
        $paginacion["pag_size"] = $page_size;
        $_SESSION["paginacion"] = $paginacion;
        cerrarConexionBD($conexion); 


        ?>
            <div>
                <table class="tabla-pag rowhover">
                    <tr>
                        <th class="start-line">Título</th>
                        <th>Autor</th>
                        <th>Género</th>
                        <th>CDU</th>
                        <th>ISBN</th>
                        <th <?php if (!$controles) echo 'class="end-line"' ?>>Nº de copias</th>
                        <?php if ($controles) echo '<th class="end-line">Controles</th>' ?>
                    </tr>
                    <?php
                    foreach ($libros as $libro) {
                        ?>
                        <tr  onclick="window.location.href='ejemplares.php?isbnej=<?php echo $libro['ISBN'] ?>'">
                            <td class="start-line"><?php echo $libro["TITULO"] ?></td>
                            <td><?php echo $libro["AUTOR"] ?></td>
                            <td><?php echo $libro["GENERO"] ?></td>
                            <td><?php echo $libro["CDU"] ?></td>
                            <td><?php echo $libro["ISBN"] ?></td>
                            <td <?php if (!$controles) echo 'class="end-line"' ?>><?php echo $libro["COPIAS"] ?></td>
                            <?php if ($controles) { ?>
                                <td class="end-line">
                                    <form method="post" action="controlador_libros.php">
                                        <input id="ISBN" name="ISBN" type="hidden" value="<?php echo $libro["ISBN"]; ?>" />
                                        <input id="TITULO" name="TITULO" type="hidden" value="<?php echo $libro["TITULO"]; ?>" />
                                        <input id="AUTOR" name="AUTOR" type="hidden" value="<?php echo $libro["AUTOR"]; ?>" />
                                        <input id="CDU" name="CDU" type="hidden" value="<?php echo $libro["CDU"]; ?>" />
                                        <input id="GENERO" name="GENERO" type="hidden" value="<?php echo $libro["GENERO"]; ?>" />
                                        <input id="COPIAS" name="COPIAS" type="hidden" value="<?php echo $libro["COPIAS"]; ?>" />
                                        <button id="editar" class="btn" name="editar" type="submit">
                                            <img src="images/edit_icon.png" alt="Editar libro" title="Editar libro" width="18px" height="18px">
                                        </button>
                                        <?php if ($libro["COPIAS"] > 0) { ?>
                                            <button id="borrar" class="btn" name="borrar" type="submit">
                                                <img src="images/delete_icon.png" alt="Borrar libro" title="Borrar libro" width="18px" height="18px">
                                            </button>
                                        <?php } ?>
                                    </form>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </table>

                                            <br>
                <form method="post" action="catalogo_libros.php">
                    <?php for ($page = 1; $page <= $total_pages; $page++) {
                        if ($page == $page_num) { ?>
                            <span class="current"><?= $page ?></span>
                        <?php       } else { ?>
                            <button class="btn" type="button" onclick="tablaLibros('busqueda=<?= $busqueda ?>&val=<?= $val ?>&page_num=<?= $page ?>&page_size=<?= $page_size ?>')"><?= $page ?></button>
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