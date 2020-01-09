<?php
session_start();
include_once("gestionBD.php");
include_once("gestionUsuarios.php");
include_once("gestionMateriales.php");
include_once("gestionDepartamentos.php");

if (isset($_SESSION["history"]) && $_SESSION["history"] != "inventario") {
    unset($_SESSION["material"]);
};
$page = "inventario";
$_SESSION["history"] = $page;
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

    if (isset($_SESSION["material"])) {
        $datosForm = $_SESSION["material"];
        $materialNuevo["descripcion"] = $datosForm["descripcion"];
        $materialNuevo["estado"] = $datosForm["estado"];
        $materialNuevo["unidades"] = $datosForm["unidades"];
        if (!empty($datosForm["departamento"])) {
            $admin_dept = obtenerOidDepartamento($conexion, $datosForm["departamento"]);
        }
        unset($_SESSION["material"]);
        unset($_SESSION["errores"]);
        if ($_SESSION["newm"]) {
            if ($carne == '00000P' && isset($admin_dept)) {
                $m = nuevoMaterial($conexion, $materialNuevo, $admin_dept);
            } else if ($carne != '00000P' && $_SESSION["newm"]) {
                $m = nuevoMaterial($conexion, $materialNuevo, $oid_dep);
            }
            unset($_SESSION["newm"]);
        }
    }

    if (isset($_SESSION["editando_material"])) unset($_SESSION["editando_material"]);
    if (isset($_SESSION["material_controlado"])) unset($_SESSION["material_controlado"]);

    if (isset($_SESSION["paginacion"])) {
        $paginacion = $_SESSION["paginacion"];
    } else {
        $paginacion["pag_num"] = 1;
        $paginacion["pag_size"] = 10;
    }

    $page_num = isset($_POST["page_num"]) ? (int)$_POST["page_num"] : $paginacion["pag_num"];
    $page_size = isset($_POST["page_size"]) ? (int)$_POST["page_size"] : $paginacion["pag_size"];

    if ($page_num < 1) $page_num = 1;
    if ($page_size < 1) $page_size = 10;

    unset($_SESSION["paginacion"]);
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php if ($carne == '00000P') { ?>
        <title>Lista de materiales</title>
    <?php } else { ?>
        <title>Inventario</title>
    <?php } ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/funciones.js" charset="utf-8"></script>
</head>

<body>

    <?php include_once("header.php") ?><br>
    <?php include_once("sidenav.php");
    $pagstr = isset($page_num) ? ('&page_num=' . $page_num . '&page_size=' . $page_size) : (''); ?>
    <main>
        <div id="main">

            <?php if ($carne == '00000P') { ?>
                <h2>Lista de materiales</h2>
            <?php } else { ?>
                <h2>Inventario de <?php echo obtenerDepartamento($conexion, $oid_dep); ?></h2>
            <?php } ?>

            <hr class="principal"><br>
            <?php
            if (isset($m)) {
                if ($m) { ?>
                    <div class="exito">
                        <p>El material se ha añadido correctamente.</p>
                    </div>
                <?php } else { ?>
                    <div class="fallo">
                        <p>El material no se ha podido insertar en la base de datos. Por favor, inténtelo de nuevo.</p>
                    </div>
                <?php }
        } ?>

            <?php
            if (isset($_SESSION["exito_edit_material"])) {
                $editMatExito = $_SESSION["exito_edit_material"];
                unset($_SESSION["exito_edit_material"]);
                if ($editMatExito) { ?>
                    <div class="exito">
                        <p>El material ha sido editado correctamente.</p>
                    </div>
                <?php } else { ?>
                    <div class="fallo">
                        <p>El material no se ha podido editar en la base de datos. Por favor, inténtelo más tarde.</p>
                    </div>
                <?php }
        } ?>

            <?php
            if (isset($_SESSION["baja_material_exito"])) {
                $bajaMatExito = $_SESSION["baja_material_exito"];
                unset($_SESSION["baja_material_exito"]);
                if ($bajaMatExito) { ?>
                    <div class="exito">
                        <p>El material se ha dado de baja correctamente.</p>
                    </div>
                <?php } else {
                $errores = $_SESSION["errores"];
                foreach ($errores as $error) { ?>
                        <div class="fallo">
                            <p><?php echo $error ?></p>
                        </div>
                    <?php }
            }
        } ?>

            <div align="center">
            <button type="button" class="btn" onclick="window.location.href='accion_nuevo_material.php'">Nuevo material</button>
            </div>
            <br>
            <div class="divform">
                <form oninput="tablaInventario('busqueda='+$('#bus').val()+'&val='+((document.getElementById('val').checked==true)?'yes':'')+'<?= $pagstr ?>'); return false; ">
                    <img id="icon_busq" src="images/search_icon.png" alt="Buscar" width="18px" height="18px">
                    <input type='text' placeholder="Buscar..." id='bus' onpaste="return false;" onkeypress="return validaBusqueda(event)" value='<?php echo (isset($_POST["bus"]) ? $_POST["bus"] : ""); ?>'>
                    <input type="checkbox" id="val" name="val" value="val" <?php echo (isset($_POST["val"]) && $_POST["val"] == 'yes' ? 'checked' : ''); ?>>Mostrar materiales activos
                </form>
                <br>
                <div id="tabla"></div>
            </div>
            <script>
                tablaInventario('busqueda=' + $('#bus').val() + '&val=' + ((document.getElementById('val').checked == true) ? 'yes' : '') + '<?= $pagstr ?>');
            </script>
        </div>
    </main>

    <script>
        document.getElementById("sinventario").className = "active"
    </script>

    <br><?php include_once("footer.php") ?>

</body>

</html>
<?php cerrarConexionBD($conexion);
?>