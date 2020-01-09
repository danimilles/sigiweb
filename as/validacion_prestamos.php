<?php
    include_once("gestionBD.php");
    include_once("gestionLibros.php");
    include_once("gestionUsuarios.php");
    session_start();

    if(isset($_SESSION["prestamo"])) {
        $prestamo["codigo"] = $_REQUEST["codigo"];
        $prestamo["ncarne"] = $_REQUEST["ncarne"];

        $_SESSION["prestamo"] = $prestamo;
        $errores = validacionErrores($prestamo);
        if(count($errores) > 0) {
            $_SESSION["errores"] = $errores;
            Header("Location: exito_login.php");
        } else {
        $con=crearConexionBD();
        $b = prestaLibro($con, $prestamo["codigo"], $prestamo["ncarne"]);
        unset($_SESSION["prestamo"]);
        cerrarConexionBD($con);
        if($b) $_SESSION["exito"] = "<p>El ejemplar se ha prestado correctamente</p>";
        Header("Location: exito_login.php");
        }
    } else Header("Location: exito_login.php");

    function validacionErrores($prestamo) {
        $errores = array();
        $con=crearConexionBD();
        if($prestamo["codigo"] == "") {
            array_push($errores, "<p>El campo 'Código' no puede estar vacío</p>");
        } else if(existeEjemplar($con,$prestamo["codigo"])){
            array_push($errores, "<p>El ejemplar con ese código no existe</p>");
        }

        if($prestamo["ncarne"] == "") {
            array_push($errores, "<p>El campo 'Número carné' no puede estar vacío</p>");
        } else if(existeUsuario($con,$prestamo["ncarne"])){
            array_push($errores, "<p>El carné con ese número no existe</p>");
        } else if ($prestamo["ncarne"] == "99999P" || $prestamo["ncarne"] == "00000P"){
            array_push($errores, "<p>El usuario introducido no puede reservar libros</p>");
        }
        cerrarConexionBD($con);
        return $errores;
    }

?>