<?php
session_start();
include_once("gestionBD.php");
include_once("gestionLibros.php");

if (!isset($_SESSION["login"])) {
    Header("Location: index.php");
} else {
    $carne = $_SESSION["login"]["usuario"];

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
    <title>Historial de préstamos</title>
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
            <h2>Historial de préstamos</h2>
            <hr class="principal"><br>

            <?php
            if (isset($_SESSION["renueva_prestamo_exito"])) {
                $renuevaPrestamoExito = $_SESSION["renueva_prestamo_exito"];
                unset($_SESSION["renueva_prestamo_exito"]);
                if ($renuevaPrestamoExito) { ?>
                    <div class="exito">
                        <p>El préstamo se ha renovado correctamente.</p>
                    </div><br />
                <?php } else { ?>
                    <div class="fallo">
                        <p>El préstamo no se ha podido renovar en la base de datos. Por favor, vuelva a intentarlo más tarde.</p>
                    </div><br />
                <?php }
        } ?>

            <?php
            if (isset($_SESSION["devuelve_prestamo_exito"])) {
                $devuelvePrestamoExito = $_SESSION["devuelve_prestamo_exito"];
                unset($_SESSION["devuelve_prestamo_exito"]);
                if ($devuelvePrestamoExito) { ?>
                    <div class="exito">
                        <p>El préstamo se ha devuelto correctamente.</p>
                    </div><br />
                <?php } else { ?>
                    <div class="fallo">
                        <p>El préstamo no se ha podido devolver correctamente. Por favor, vuelva a intentarlo más tarde.</p>
                    </div><br />
                <?php }
        } ?>
            <div class="divform">
                <form oninput="tablaPrestamos('busqueda='+$('#bus').val()+'&val='+((document.getElementById('val').checked==true)?'yes':'')+'<?= $pagstr ?>'); return false; ">
                    <img id="icon_busq" src="images/search_icon.png" alt="Buscar" width="18px" height="18px">
                    <input type='text' placeholder="Buscar..." id='bus' onpaste="return false;" onkeypress="return validaBusqueda(event)" value='<?php echo (isset($_POST["bus"]) ? $_POST["bus"] : ""); ?>'>
                    <input type="checkbox" id="val" name="val" value="val" <?php echo (isset($_POST["val"]) && $_POST["val"] == 'yes' ? 'checked' : ''); ?>>Mostrar préstamos activos
                </form>
                <br>
                <div id="tabla"></div>
            </div>
            <script>
                tablaPrestamos('busqueda=' + $('#bus').val() + '&val=' + ((document.getElementById('val').checked == true) ? 'yes' : '') + '<?= $pagstr ?>');
            </script>
        </div>
    </main>
    <script>
        document.getElementById("sprestamos").className = "active";
    </script>

    <?php include_once("footer.php") ?>

</body>

</html>