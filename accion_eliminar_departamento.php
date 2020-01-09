<?php	
    session_start();
    include_once("gestionBD.php");
    include_once("gestionDepartamentos.php");
    include_once("gestionMateriales.php");	
    include_once("gestionUsuarios.php");
	
	if (isset($_SESSION["departamento_controlado"])) {
		$departamento = $_SESSION["departamento_controlado"];
		unset($_SESSION["departamento_controlado"]);

        $conexion = crearConexionBD();
        $materialesDelDepartamento = obtenerMaterialesPorDepartamento($conexion, $departamento["oid_d"]);
        $profesoresDelDepartamento = obtenerProfesoresDeDepartamento($conexion, $departamento["oid_d"]);
        $exito = true;
        foreach($materialesDelDepartamento as $material){
            $reservasDeLosMateriales = reservasMaterialesPorMaterial($conexion, $material["OID_M"]);
            foreach($reservasDeLosMateriales as $reserva){
                $success = cancelarReservaMaterial($conexion, $reserva["OID_RM"]);
                if(!$success){
                    $exito = false;
                }
            }
        }
        $exito2 = true;
        foreach($materialesDelDepartamento as $materialDept){
            $success2 = eliminarMaterial($conexion, $materialDept["OID_M"]);
            if(!$success2){
                $exito2 = false;
            }
        }

        $exito3 = true;
        foreach($profesoresDelDepartamento as $profesor){
            $success3 = quitaProfesorDeDepartamento($conexion, $profesor["NUM_CARNE"]);
            if(!$success3){
                $exito3 = false;
            }
        }
        $exito4 = eliminarDepartamento($conexion, $departamento["oid_d"]);
		cerrarConexionBD($conexion);
			
        $_SESSION["baja_departamento_exito"] = $exito&&$exito2&&$exito3&&$exito4;
        Header("Location: aulas_departamentos.php");
	}else Header("Location: aulas_departamentos.php");
?>