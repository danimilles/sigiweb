<?php	
	session_start();
	
	if (isset($_REQUEST["CODIGO"])){
        $prestamo["codigo"] = $_REQUEST["CODIGO"];
        $prestamo["oid_p"] = $_REQUEST["OID_P"];
		$_SESSION["prestamo_controlado"] = $prestamo;
        
        if($prestamo["codigo"] == "") {
			$_SESSION["excepcion"] = '<p>Los datos del préstamo no son correctos.</p>';
			Header("Location: excepcion.php");
			die();
        } elseif(!filter_var($prestamo["codigo"], FILTER_VALIDATE_INT)) {
        	$_SESSION["excepcion"] = '<p>Los datos del préstamo no son correctos.</p>';
			Header("Location: excepcion.php");
			die();
        }
        if($prestamo["oid_p"] == "") {
			$_SESSION["excepcion"] = '<p>Los datos del préstamo no son correctos.</p>';
			Header("Location: excepcion.php");
			die();
        } elseif(!filter_var($prestamo["oid_p"], FILTER_VALIDATE_INT)) {
        	$_SESSION["excepcion"] = '<p>Los datos del préstamo no son correctos.</p>';
			Header("Location: excepcion.php");
			die();
        }

        if (isset($_REQUEST["renovar"])){
            Header("Location: accion_renueva_prestamo.php");
        }elseif (isset($_REQUEST["devolver"])){
            Header("Location: accion_devuelve_prestamo.php");
        }else {
            unset($_SESSION["libro_controlado"]); 
            Header("Location: historial_prestamos.php");
        } 
	}
	else Header("Location: historial_prestamos.php");
