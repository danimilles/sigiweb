<?php
    session_start();
    include_once("gestionBD.php");
    include_once("gestionUsuarios.php");

    if(!isset($_SESSION["login"])){
        Header("Location: index.php");
    } else {
        $errores = array();
        $carne = $_REQUEST["num_carne"];
        if($carne == "") {
            array_push($errores, '<p>Los datos del aula no son correctos.</p>');
            $_SESSION["errores"] = $errores;
            Header("Location: lista_usuarios.php");
            die();
        } else if(!preg_match("/^[0-9]{5}[AP]{1}$/", $carne)) {
            array_push($errores, '<p>Los datos del aula no son correctos.</p>');
            $_SESSION["errores"] = $errores;
            Header("Location: lista_usuarios.php");
            die();
        }

        if(isset($_REQUEST["num_carne"], $_REQUEST["editar"])) {
            $_SESSION["num_carne"] = $carne;
            Header("Location: accion_editar_perfil.php");
        } else if(isset($_REQUEST["num_carne"], $_REQUEST["baja"])) {
            $con = crearConexionBD();
            bajaUsuario($con, $carne);
            cerrarConexionBD($con);
            $_SESSION["exito"] = "<p>El usuario ha sido dado de baja correctamente.</p>";
            Header("Location: lista_usuarios.php");
        } else if(isset($_REQUEST["num_carne"], $_REQUEST["renovar"])) {
            $con = crearConexionBD();
            renovarCarneUsuario($con, $carne);
            cerrarConexionBD($con);
            $_SESSION["exito"] = "<p>El usuario se ha renovado correctamente.</p>";
            Header("Location: lista_usuarios.php");
        } else if(isset($_REQUEST["num_carne"], $_REQUEST["promocionar"])) {
            $_SESSION["num_carne"] = $carne;
            Header("Location: accion_promocionar_curso.php");
        } else {

        }
    }
