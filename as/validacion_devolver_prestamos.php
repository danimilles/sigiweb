<?php
    include_once("gestionBD.php");
    include_once("gestionLibros.php");
    session_start();

    if(isset($_SESSION["prestamo_devolver"])) {
        $prestamo["codigo_devolver"] = $_REQUEST["codigo_devolver"];
        $_SESSION["prestamo_devolver"] = $prestamo;
        $errores = validacionErrores($prestamo);
        if(count($errores) > 0) {
            $_SESSION["errores"] = $errores;
            Header("Location: exito_login.php");
        } else {
        $con=crearConexionBD();
        $a = devuelveLibro($con, $prestamo["codigo_devolver"]);
        cerrarConexionBD($con);
        $_SESSION["exito"] = "<p>El ejemplar se ha devuelto correctamente</p>";
        unset($_SESSION["prestamo_devolver"]);
        Header("Location: exito_login.php");
        }
    } else Header("Location: exito_login.php");

    function validacionErrores($prestamo) {
        $errores = array();
        $con=crearConexionBD();
        if($prestamo["codigo_devolver"] == "") {
            array_push($errores, "<p>El campo 'Código' no puede estar vacío</p>");
        } else if(existeEjemplar($con,$prestamo["codigo_devolver"])){
            array_push($errores, "<p>El ejemplar con ese código no existe</p>");
        } else if(estaDisponible($con,$prestamo["codigo_devolver"])) {
            array_push($errores, "<p>El ejemplar con ese código no está prestado</p>");
        }
        cerrarConexionBD($con);
        return $errores;
    }

?>