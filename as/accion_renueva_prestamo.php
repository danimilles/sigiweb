<?php	
    session_start();
    include_once("gestionBD.php");
	include_once("gestionLibros.php");	
	
	if (isset($_SESSION["prestamo_controlado"])) {
		$prestamo = $_SESSION["prestamo_controlado"];
		unset($_SESSION["prestamo_controlado"]);

		$conexion = crearConexionBD();		
        $exito = renuevaPrestamo($conexion,$prestamo["oid_p"]);
        cerrarConexionBD($conexion);
			
        $_SESSION["renueva_prestamo_exito"] = $exito;
        Header("Location: historial_prestamos.php");
	}
	else Header("Location: historial_prestamos.php");
?>