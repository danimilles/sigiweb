<?php	
	session_start();
	
	if (isset($_REQUEST["NUMERO"])){
		$aula["numero"] = $_REQUEST["NUMERO"];
        $aula["nombre"] = $_REQUEST["NOMBRE"];
        $_SESSION["aula_controlada"] = $aula;
        $errores = array();
        
        if(!filter_var($aula["numero"], FILTER_VALIDATE_INT)) {
            array_push($errores, '<p>Los datos del aula no son correctos.</p>');
            $_SESSION["errores"] = $errores;
            Header("Location: aulas_departamentos.php");
            die();
        } else if($aula["nombre"] == "") {
            array_push($errores, '<p>Los datos del aula no son correctos.</p>');
            $_SESSION["errores"] = $errores;
            Header("Location: aulas_departamentos.php");
            die();
        }
			
        if (isset($_REQUEST["editar"])){
            Header("Location: accion_nuevo_aula.php");
        }elseif (isset($_REQUEST["borrar"])){
            Header("Location: accion_eliminar_aula.php");
        }else {
            unset($_SESSION["aula_controlada"]); 
            Header("Location: aulas_departamentos.php");
        } 
	}
	else 
		Header("Location: aulas_departamentos.php");

?>