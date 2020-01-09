<?php	
    session_start();
    include_once("gestionBD.php");
	include_once("gestionLibros.php");	
	
	if (isset($_SESSION["prestamo_controlado"])) {
		$prestamo = $_SESSION["prestamo_controlado"];
		unset($_SESSION["prestamo_controlado"]);

		$conexion = crearConexionBD();		
        $exito = devuelveLibro($conexion,$prestamo["codigo"]);
        cerrarConexionBD($conexion);
			
        $_SESSION["devuelve_prestamo_exito"] = $exito;
        Header("Location: historial_prestamos.php");
	}
	else Header("Location: historial_prestamos.php");
?>