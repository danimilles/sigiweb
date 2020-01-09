<?php include_once("gestionUsuarios.php");
include_once("gestionBD.php"); ?>

<div id='menu' class="closed">

    <?php if (!isset($_SESSION["login"])) { ?>
        <br>
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="http://www.iesazahar.es/">IES Azahar</a>

    <?php } else { ?>
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <p class="head"><strong><?php echo $carne; ?></strong></p>
        <a id="sinicio" href="exito_login.php">Inicio</a>
        <a id="scatalogo" href="catalogo_libros.php">Catálogo de libros</a>
        <a id="sprestamos" href="historial_prestamos.php">Historial de préstamos</a>

        <?php if (substr($carne, -1) == "P" && ($carne != '00000P' && $carne != '99999P')) { ?>
            <a id="saulas" href="reserva_aulas.php">Reserva de aulas</a>
            <a id="smateriales" href="materiales.php">Reserva de materiales</a>
        <?php } ?>

        <?php
        if ((substr($carne, -1) == "P") && $_SESSION["login"]["jefeDept"] ||  $carne == '00000P') { ?>
            <a id="sinventario" href="inventario_dept.php">Inventario</a>
        <?php } ?>

        <?php if ($carne == '00000P' || $carne == '99999P') { ?>
            <a id="sestadisticas" href="estadisticas.php">Estadísticas</a>
            <a id="susuarios" href="lista_usuarios.php">Lista de usuarios</a>
            <a id="sreservas" href="historial_reservas.php">Historial de reservas</a>
        <?php } ?>

        <?php if ($carne == '00000P') { ?>
            <a id="sauldep" href="aulas_departamentos.php">Aulas y departamentos</a>
        <?php } ?>

        <?php if ($carne != '00000P' && $carne != '99999P') { ?>
            <a id="sperfil" href="perfil.php">Perfil</a>
        <?php } ?>
        <div id="last"></div>
        <br><br>
        <a id="logout" href="logout.php"><img src="images/logout.png" /><br>Cerrar sesión</a>
    <?php } ?>
</div>