<?php	
    session_start();
    include_once("gestionBD.php");
	include_once("gestionAulas.php");	
	
	if (isset($_SESSION["aula_controlada"])) {
		$aula = $_SESSION["aula_controlada"];
		unset($_SESSION["aula_controlada"]);

        $conexion = crearConexionBD();
        $reservasDelAula = obtenerReservasPorAula($conexion, $aula["numero"]);
        $exito = true;
        foreach($reservasDelAula as $reserva){
            $sucess = cancelarReservaAula($conexion, $reserva["OID_RA"]);
            if(!$sucess){
                $exito = false;
            }
        }
        $exito2 = eliminaAula($conexion, $aula["numero"]);		
		cerrarConexionBD($conexion);
			
        $_SESSION["baja_aula_exito"] = $exito&&$exito2;
        Header("Location: aulas_departamentos.php");
	}else Header("Location: aulas_departamentos.php");
?>