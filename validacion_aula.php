<?php
    include_once("gestionBD.php");
    include_once("gestionLibros.php");
    include_once("gestionUsuarios.php");
    session_start();

    if(isset($_REQUEST["numero"])) {
        $naula["nombre"] = $_REQUEST["nombre"];
        $naula["numero"] = $_REQUEST["numero"];

        $_SESSION["naula"] = $naula;
        $errores = validacionErrores($naula);
        if(count($errores) > 0) {
            $_SESSION["errores"] = $errores;
            Header("Location: accion_nuevo_aula.php");
        } elseif(!isset($_SESSION["editando_aula"])) {
            $con=crearConexionBD();
            $new = creaAula($con,$naula);
            $_SESSION["caula"]  = $new;
            unset($_SESSION["naula"]);
            cerrarConexionBD($con);
            Header("Location: aulas_departamentos.php");
        } elseif(isset($_SESSION["editando_aula"])){
            $con=crearConexionBD();
            $edit = editaAula($con,$naula);
            $_SESSION["exito_edit_aula"] = $edit;
            unset($_SESSION["naula"]);
            unset($_SESSION["editando_aula"]);
            unset($_SESSION["aula_controlada"]);
            cerrarConexionBD($con);
            Header("Location: aulas_departamentos.php");
        }
    } else Header("Location: accion_nuevo_aula.php");

    function validacionErrores($naula) {
        $errores = array();
        $con=crearConexionBD();
        if($naula["nombre"] == "") {
            array_push($errores, "<p>El campo 'Nombre' no puede estar vacío</p>");
        }

        if($naula["numero"] == "") {
            array_push($errores, "<p>El campo 'Numero' no puede estar vacío</p>");
        } else if(!preg_match("/^[0-9]+$/", $naula["numero"])){
            array_push($errores, "<p>El campo 'Numero' debe ser un entero positivo</p>");
        }
        cerrarConexionBD($con);
        return $errores;
    }

?>