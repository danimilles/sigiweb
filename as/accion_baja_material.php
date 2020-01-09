<?php	
    session_start();
    include_once("gestionBD.php");
	include_once("gestionMateriales.php");	
	
	if (isset($_SESSION["material_controlado"])) {
		$material = $_SESSION["material_controlado"];
		unset($_SESSION["material_controlado"]);

        $conexion = crearConexionBD();
        $reservasDelMaterial = reservasMaterialesPorMaterial($conexion, $material["oid_m"]);
        $exito = true;
        foreach($reservasDelMaterial as $reserva){
            $success = cancelarReservaMaterial($conexion, $reserva["OID_RM"]);
            if(!$success){
                $exito = false;
            }
        }
        $exito2 = bajaMaterial($conexion, $material["oid_m"]);		
		cerrarConexionBD($conexion);
			
        $_SESSION["baja_material_exito"] = $exito&&$exito2;
        Header("Location: inventario_dept.php");
	}else Header("Location: inventario_dept.php");
?>