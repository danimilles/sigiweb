3<?php	
	session_start();
	
	if (isset($_REQUEST["OID_D"])){
		$departamento["oid_d"] = $_REQUEST["OID_D"];
        $departamento["nombre"] = $_REQUEST["NOMBRE"];
        
        $_SESSION["departamento_controlado"] = $departamento;
        $errores = array();
        
        if(!filter_var($departamento["oid_d"], FILTER_VALIDATE_INT)) {
            array_push($errores, '<p>Los datos del departamento no son correctos.</p>');
            $_SESSION["errores"] = $errores;
            Header("Location: aulas_departamentos.php");
            die();
        } else if($departamento["nombre"] == "") {
            array_push($errores, '<p>Los datos del departamento no son correctos.</p>');
            $_SESSION["errores"] = $errores;
            Header("Location: aulas_departamentos.php");
            die();
        }
        if (isset($_REQUEST["editar"])){
            Header("Location: accion_nuevo_departamento.php");
        }elseif (isset($_REQUEST["borrar"])){
            Header("Location: accion_eliminar_departamento.php");
        }else {
            unset($_SESSION["departamento_controlado"]); 
            Header("Location: aulas_departamentos.php");
        } 
	}
	else 
		Header("Location: aulas_departamentos.php");

?>