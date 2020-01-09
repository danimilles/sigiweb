<?php	
	session_start();
    include_once("gestionBD.php");
    include_once("gestionDepartamentos.php");
    
	if (isset($_REQUEST["OID_M"])){
        $material["descripcion"] = $_REQUEST["DESCRIPCION"];
        $material["estado"] = $_REQUEST["ESTADO"];
        $material["unidades"] = $_REQUEST["UNIDADES"];
        $conexion = crearConexionBD();
        $material["departamento"] = obtenerDepartamento($conexion, $_REQUEST["OID_D"]);
        cerrarConexionBD($conexion);
        $material["oid_m"] = $_REQUEST["OID_M"];
        
        $_SESSION["material_controlado"] = $material;
        $errores = array();
        
        if($material["descripcion"] == "") {
            array_push($errores, '<p>Los datos del material no son correctos.</p>');
            $_SESSION["errores"] = $errores;
			Header("Location: excepcion.php");
			die();
        }
        if($material["estado"] == "") {
            array_push($errores, '<p>Los datos del material no son correctos.</p>');
            $_SESSION["errores"] = $errores;
			Header("Location: excepcion.php");
			die();
        }
        if(!filter_var($material["unidades"], FILTER_VALIDATE_INT)) {
            array_push($errores, '<p>Los datos del material no son correctos.</p>');
            $_SESSION["errores"] = $errores;
			Header("Location: excepcion.php");
			die();
        }
        if(!filter_var($material["oid_m"], FILTER_VALIDATE_INT)) {
            array_push($errores, '<p>Los datos del material no son correctos.</p>');
            $_SESSION["errores"] = $errores;
			Header("Location: excepcion.php");
			die();
		}

        if (isset($_REQUEST["editar"])){
            Header("Location: accion_nuevo_material.php");
        }elseif (isset($_REQUEST["borrar"])){
            Header("Location: accion_baja_material.php");
        }else {
            unset($_SESSION["material_controlado"]); 
            Header("Location: inventario_dept.php");
        } 
	}else Header("Location: inventario_dept.php");

?>