<?php
/**
 * Este archivo contiene las funciones necesarias para conectarse
 * a la base de datos.
 * @author SIGIWEB
 */
    function crearConexionBD() {
        $host="oci:dbname=oracledb:1521/xe;charset=UTF8";
	    $usuario="SIGI";
	    $password="practica";
	    try {
		    $conexion=new PDO($host,$usuario,$password,array(PDO::ATTR_PERSISTENT => true));
    	    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    return $conexion;
	    } catch (PDOException $e){
		    $_SESSION['excepcion'] = $e->GetMessage();
		    header("Location: excepcion.php");
	    }
    }

    function cerrarConexionBD($conexion) {
        $conexion = null;
    }
