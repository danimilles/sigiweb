<?php	
    session_start();
    include_once("gestionBD.php");
    include_once("gestionAulas.php");	
    include_once("gestionMateriales.php");
	
	if (isset($_REQUEST["OID_RM"])||isset($_REQUEST["OID_RA"])) {
        if(isset($_REQUEST["OID_RM"])){
            $material = $_REQUEST["OID_RM"];
            unset($_SESSION["OID_RM"]);
        }elseif(isset($_REQUEST["OID_RA"])){
            $aula = $_REQUEST["OID_RA"];
            unset($_SESSION["OID_RA"]);
        }

        $conexion = crearConexionBD();
        if(isset($material)){
            $exito = cancelarReservaMaterial($conexion, $material);
        }elseif(isset($aula)){
            $exito = cancelarReservaAula($conexion, $aula);
        }		
		cerrarConexionBD($conexion);
			
        if(isset($exito)) $_SESSION["cancelar_reserva_exito"] = $exito;
        if(isset($material)){
            Header("Location: historial_reservas.php?table=materiales");
        }else{
            Header("Location: historial_reservas.php?table=aulas");
        }
	}else Header("Location: historial_reservas.php");
?>