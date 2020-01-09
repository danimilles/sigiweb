<?php

    session_start();
    include_once("gestionLibros.php");
    include_once("gestionBD.php");

    if(isset($_SESSION["ejemplar"]) || isset($_SESSION["ejemplar_controlado"])) {
        $ejemplar["estado"] = $_REQUEST["estado"];
        $ejemplar["isbn"] = $_REQUEST["isbn"];
        $ejemplar["codigo"] = $_REQUEST["codigo"];
        
        if(isset($_SESSION["ejemplar"])) $_SESSION["ejemplar"] = $ejemplar;
        else if (isset($_SESSION["ejemplar_controlado"])) $_SESSION["ejemplar_controlado"] = $ejemplar;
        $errores = validacionErrores($ejemplar);
        if(count($errores) > 0) {
            $_SESSION["errores"] = $errores;
            Header("Location: accion_nuevo_ejemplar.php");
        } else {
            if(isset($_SESSION["ejemplar"])&&!isset($_SESSION["editando"])){
                $new = true;
                $_SESSION["newe"] = $new;    
            } else if (isset($_SESSION["editando"])){
                $new = true;
                $_SESSION["edite"] = $new;  
            }
           Header("Location: ejemplares.php?isbnej=".$ejemplar["isbn"]);
        }

    } else Header("Location: accion_nuevo_ejemplar.php");

    function validacionErrores($ejemplar) {
        $errores = array();
        if($ejemplar["estado"] == "") {
            array_push($errores, "<p>El campo 'Estado' no puede estar vacío</p>");
        }else if($ejemplar["estado"] != "Perfecto" && $ejemplar["estado"] != "Bueno" && $ejemplar["estado"] != "Malo" && $ejemplar["estado"] != "Deteriorado"){
            array_push($errores, "<p>El campo 'Estado' debe ser Bueno, Malo, Perfecto o Deteriorado</p>");
        }

        $con=crearConexionBD();
        if(isset($_SESSION["editando"])){
            if($ejemplar["codigo"] == "") {
                array_push($errores, "<p>El campo 'Codigo' no puede estar vacío</p>");
            } else if(existeEjemplar($con,$ejemplar["codigo"])){
                array_push($errores, "<p>El ejemplar con ese codigo no existe</p>");
            }
        }

        if($ejemplar["isbn"] == "") {
            array_push($errores, "<p>El campo 'ISBN' no puede estar vacío</p>");
        } else {
            $isbnlen = strlen($ejemplar["isbn"]);
            if($isbnlen != 10 && $isbnlen != 14) {
                array_push($errores, "<p>El ISBN debe tener el formato de ISBN-10(1234567890) o ISBN-13(123-1234567890)</p>");
            } elseif($isbnlen == 10 && !preg_match("/^[0-9]{10}/", $ejemplar["isbn"])) {
                array_push($errores, "<p>El ISBN-10 debe tener formato 1234567890</p>");
            } elseif($isbnlen == 14 && !preg_match("/^[0-9]{3}-[0-9]{10}/",$ejemplar["isbn"])) {
                array_push($errores, "<p>El ISBN-13 debe tener formato 123-1234567890</p>");
            }

            if(count($errores)==0){
                if (existeLibro($con, $ejemplar["isbn"])){
                    array_push($errores, "<p>El ISBN introducido no pertenece a ningun libro en la base de datos</p>");
                }
            }
            cerrarConexionBD($con);
        }


        return $errores;
    }

?>