<?php
session_start();
include_once("gestionBD.php");
include_once("gestionUsuarios.php");
$carne = $_SESSION["login"]["usuario"]; 
if (!isset($_SESSION["login"])) { 
    Header("Location: index.php");
} else if ($carne != '00000P' && $carne != '99999P') { 
    Header("Location: exito_login.php");
} else {
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
        $val = $_GET["val"];
        $busqueda = $_GET["busqueda"];
        $ustype = $_GET["ustype"];
        unset($_SESSION["paginacion"]);

        $datosConsulta = listaUsuarios($conexion, $ustype, $val, $busqueda, $page_num, $page_size); 
        $total = $datosConsulta["total"];
        $total_pages = $datosConsulta["total_pages"];
        $usuarios = $datosConsulta["usuarios"];

        $paginacion["pag_num"] = $page_num;
        $paginacion["pag_size"] = $page_size;
        $_SESSION["paginacion"] = $paginacion;
        cerrarConexionBD($conexion); 


        ?>

            <div>
                <table class="tabla-pag">
                    <tr>
                        <th class="start-line">Carné</th>
                        <th>Apellidos</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>DNI</th>
                        <th>Fecha validez carné</th>
                        <th class="end-line">Controles</th>
                    </tr>
                    <?php
                    foreach ($usuarios as $usuario) {
                        ?>
                        <tr>
                            <td class="start-line"><?php echo $usuario["NUM_CARNE"] ?></td>
                            <td><?php echo $usuario["APELLIDOS"] ?></td>
                            <td><?php echo $usuario["NOMBRE"] ?></td>
                            <td><?php echo $usuario["EMAIL"] ?></td>
                            <td><?php echo $usuario["DNI"] ?></td>
                            <td class="end-line"><?php echo $usuario["FECHA_VALIDEZ_CARNE"] ?></td>
                            <td class="end-line-button">
                                <form method='post' action='controlador_usuarios.php'>
                                    <input type='hidden' id='num_carne' name='num_carne' value='<?php echo $usuario["NUM_CARNE"]; ?>' />
                                    <button class="btn" id='editar' name='editar' title='Editar usuario' alt='Editar usuario' type='submit'><img src='images/edit_icon.png' width="18px" height="18px" /></button>
                                    <button class="btn" id='baja' name='baja' title='Dar de baja' alt='Dar de baja' type='submit'><img src='images/delete_icon.png' width="18px" height="18px" /></button>
                                    <button class="btn" id='renovar' name='renovar' title='Renovar carné' alt='Renovar carné' type='submit'><img src='images/user_renew_icon.png' width="18px" height="18px" /></button>
                                    <?php if (substr($usuario["NUM_CARNE"], 5) == 'A') { ?>
                                        <button class="btn" id='promocionar' name='promocionar' title='Promocionar alumno' alt='Promocionar alumno' type='submit'><img src='images/promote_icon.png' width="18px" height="18px" /></button>
                                    <?php } ?>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table><br>
                <form method="post" action="lista_usuarios.php">
                    <?php for ($page = 1; $page <= $total_pages; $page++) {
                        if ($page == $page_num) { ?>
                            <span class="current"><?= $page ?></span>
                        <?php       } else { ?>
                            <button type="button" class="btn" onclick="tablaUsuarios('busqueda=<?= $busqueda ?>&val=<?= $val ?>&ustype=<?= $ustype ?>&page_num=<?= $page ?>&page_size=<?= $page_size ?>')"><?= $page ?></button>
                        <?php       }
                }
                ?>
                    <input id="bus" name="bus" type="hidden" value="<?= $busqueda ?>">
                    <input id="ustype" name="ustype" type="hidden" value="<?= $ustype ?>">
                    <input id="val" name="val" type="hidden" value="<?= $val ?>">
                    <input id="page_num" name="page_num" type="hidden" value="<?= $page_num ?>" /> Mostrando
                    <input id="page_size" name="page_size" type="number" min="1" max="<?= $total ?>" value="<?= $page_size ?>" autofocus="autofocus" />
                    entradas de <?= $total ?>
                    <input type="submit" value="Cambiar" /></form><br>
            </div>
        <?php }
}
} ?>