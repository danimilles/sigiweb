<?php	
    session_start();
    include_once("gestionBD.php");
	include_once("gestionLibros.php");	
	
	if (isset($_SESSION["ejemplar_controlado"])) {
		$ejemplar = $_SESSION["ejemplar_controlado"];
		unset($_SESSION["ejemplar_controlado"]);

		$conexion = crearConexionBD();		
        $exito = bajaEjemplar($conexion, $ejemplar["codigo"]);
		cerrarConexionBD($conexion);
			
        $_SESSION["baja_ejemplar_exito"] = $exito;
        Header("Location: ejemplares.php?isbnej=".$ejemplar["isbn"]);
	}else Header("Location: ejemplares.php?isbnej=".$ejemplar["isbn"]);
?>