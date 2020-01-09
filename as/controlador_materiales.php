<?php

    session_start();

    require_once("gestionBD.php");
    require_once("gestionMateriales.php");

    if(isset($_REQUEST["NUM_CARNE"])) {
        if(isset($_REQUEST["OID_RM"])) $material["OID_RM"] = $_REQUEST["OID_RM"];
        $material["OID_M"] = $_REQUEST["OID_M"];
        $material["FECHA_RESERVA"] = $_REQUEST["FECHA_RESERVA"];
        $material["TRAMO"] = $_REQUEST["TRAMO"];
        $material["NUM_CARNE"] = $_REQUEST["NUM_CARNE"];
    
        $_SESSION["material"] = $material;
        $errores = array();

        if(isset($_REQUEST["OID_RM"])) {
            if($material["OID_RM"] == "") {
                array_push($errores, '<p>Los datos del material no son correctos.</p>');
                $_SESSION["errores"] = $errores;
                Header("Location: materiales.php");
                die();
            } else if(!filter_var($material["OID_RM"], FILTER_VALIDATE_INT)) {
                array_push($errores, '<p>Los datos del material no son correctos.</p>');
                $_SESSION["errores"] = $errores;
                Header("Location: materiales.php");
                die();
            }
        }
        if($material["OID_M"] == "") {
            array_push($errores, '<p>Los datos del material no son correctos.</p>');
            $_SESSION["errores"] = $errores;
            Header("Location: materiales.php");
            die();
        } else if(!filter_var($material["OID_M"], FILTER_VALIDATE_INT)) {
            array_push($errores, '<p>Los datos del material no son correctos.</p>');
            $_SESSION["errores"] = $errores;
            Header("Location: materiales.php");
            die();
        }
        if($material["FECHA_RESERVA"] == "") {
            array_push($errores, '<p>Los datos del material no son correctos.</p>');
            $_SESSION["errores"] = $errores;
            Header("Location: materiales.php");
            die();
        } else if(!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $material["FECHA_RESERVA"]) && 
        !preg_match("/^[0-9]{2}\/[0-9]{2}\/[0-9]{2}$/", $material["FECHA_RESERVA"])) {
            array_push($errores, '<p>Los datos del material no son correctos.</p>');
            $_SESSION["errores"] = $errores;
            Header("Location: materiales.php");
            die();
        }
        if($material["TRAMO"] == "") {
            array_push($errores, '<p>Los datos del material no son correctos.</p>');
            $_SESSION["errores"] = $errores;
            Header("Location: materiales.php");
            die();
        } else if(!filter_var($material["TRAMO"], FILTER_VALIDATE_INT)) {
                array_push($errores, '<p>Los datos del material no son correctos.</p>');
                $_SESSION["errores"] = $errores;
            Header("Location: materiales.php");
            die();
        } else
            $tramo = intval($material["TRAMO"]);
            if($tramo < 0 || $tramo > 6) {
                array_push($errores, '<p>Los datos del material no son correctos.</p>');
                $_SESSION["errores"] = $errores;
                Header("Location: materiales.php");
                die();
        }
        if($material["NUM_CARNE"] == "") {
                array_push($errores, '<p>Los datos del material no son correctos.</p>');
                $_SESSION["errores"] = $errores;
            Header("Location: materiales.php");
            die();
        } else if(!preg_match("/^[0-9]{5}[AP]{1}$/", $material["NUM_CARNE"])) {
                array_push($errores, '<p>Los datos del material no son correctos.</p>');
                $_SESSION["errores"] = $errores;
            Header("Location: materiales.php");
            die();
        }
        $day_week = intval(date('w', strtotime($material["FECHA_RESERVA"])));
        if($day_week == 0 || $day_week == 6) {
            $_SESSION["errores"] = '<p>Se ha intentado reservar un material un fin de semana, lo cual no está permitido. Sentimos las molestias.</p>';
            Header("Location: materiales.php");
            die();
        }
        
        if(isset($_REQUEST["cancelar"])) { 
            $con = crearConexionBD();
            cancelarReservaMaterial($con, $material["OID_RM"]);
            $_SESSION["mensaje"] = "La reserva se ha cancelado correctamente.";
            cerrarConexionBD($con);
        }elseif(isset($_REQUEST["reservar"])) {
            $con = crearConexionBD();
            $b = reservarMaterial($con, $material["NUM_CARNE"], $material["OID_M"], $material["FECHA_RESERVA"], $material["TRAMO"]);
            if($b) $_SESSION["mensaje"] = "Se ha realizado la reserva correctamente.";
            cerrarConexionBD($con);
        } else Header("Location: excepcion.php");

        Header("Location: materiales.php");

    } else Header("Location: index.php");
