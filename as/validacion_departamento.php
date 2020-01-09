<?php
    include_once("gestionBD.php");
    include_once("gestionLibros.php");
    include_once("gestionUsuarios.php");
    include_once("gestionDepartamentos.php");

    session_start();

    if(isset($_REQUEST["nombre"])) {
        $departamento["nombre"] = $_REQUEST["nombre"];
        if(isset($_SESSION["editando_departamento"])){
           $departamento["oid_d"] = $_REQUEST["oid_d"];
        }
        $_SESSION["departamento"] = $departamento;
        $errores = validacionErrores($departamento);
        if(count($errores) > 0) {
            $_SESSION["errores"] = $errores;
            Header("Location: accion_nuevo_departamento.php");
        } elseif(!isset($_SESSION["editando_departamento"])) {
            $con=crearConexionBD();
            $new = creaDepartamento($con,$departamento);
            $_SESSION["cdepartamento"]  = $new;
            unset($_SESSION["departamento"]);
            cerrarConexionBD($con);
            Header("Location: aulas_departamentos.php");
        } elseif(isset($_SESSION["editando_departamento"])){
            $con=crearConexionBD();
            $edit = editaDepartamento($con,$departamento);
            $_SESSION["exito_edit"]  = $edit;
            unset($_SESSION["departamento"]);
            unset($_SESSION["editando_departamento"]);
            unset($_SESSION["departamento_controlado"]);
            cerrarConexionBD($con);
            Header("Location: aulas_departamentos.php");
        }
    } else Header("Location: accion_nuevo_departamento.php");

    function validacionErrores($departamento) {
        $errores = array();
        if($departamento["nombre"] == "") {
            array_push($errores, "<p>El campo 'Nombre' no puede estar vacío</p>");
        }
        return $errores;
    }

?>