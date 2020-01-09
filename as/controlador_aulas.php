<?php

    session_start();

    require_once("gestionBD.php");
    require_once("gestionAulas.php");

    if(isset($_REQUEST["NUM_CARNE"])) {
        if(isset($_REQUEST["OID_RA"])) $aula["OID_RA"] = $_REQUEST["OID_RA"];
        $aula["NUMERO"] = $_REQUEST["NUMERO"];
        $aula["FECHA_RESERVA"] = $_REQUEST["FECHA_RESERVA"];
        $aula["TRAMO"] = $_REQUEST["TRAMO"];
        $aula["NUM_CARNE"] = $_REQUEST["NUM_CARNE"];

        $_SESSION["aula"] = $aula;
        $errores = array();

        if(isset($_REQUEST["OID_RA"])) {
            if($aula["OID_RA"] == "") {
                array_push($errores, '<p>Los datos del aula no son correctos.</p>');
                $_SESSION["errores"] = $errores;
                Header("Location: reserva_aulas.php");
                die();
            } else if(!filter_var($aula["OID_RA"], FILTER_VALIDATE_INT)) {
                array_push($errores, '<p>Los datos del aula no son correctos.</p>');
                $_SESSION["errores"] = $errores;
                Header("Location: reserva_aulas.php");
                die();
            }
        }
        if($aula["NUMERO"] == "") {
            array_push($errores, '<p>Los datos del aula no son correctos.</p>');
            $_SESSION["errores"] = $errores;
            Header("Location: reserva_aulas.php");
            die();
        } else if(!filter_var($aula["NUMERO"], FILTER_VALIDATE_INT)) {
            array_push($errores, '<p>Los datos del aula no son correctos.</p>');
            $_SESSION["errores"] = $errores;
            Header("Location: reserva_aulas.php");
            die();
        }
        if($aula["FECHA_RESERVA"] == "") {
            array_push($errores, '<p>Los datos del aula no son correctos.</p>');
            $_SESSION["errores"] = $errores;
            Header("Location: reserva_aulas.php");
            die();
        } else if(!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $aula["FECHA_RESERVA"]) && 
        !preg_match("/^[0-9]{2}\/[0-9]{2}\/[0-9]{2}$/", $aula["FECHA_RESERVA"])) {
            array_push($errores, '<p>Los datos del aula no son correctos.</p>');
            $_SESSION["errores"] = $errores;
            Header("Location: reserva_aulas.php");
            die();
        }
        if($aula["TRAMO"] == "") {
            array_push($errores, '<p>Los datos del aula no son correctos.</p>');
            $_SESSION["errores"] = $errores;
            Header("Location: reserva_aulas.php");
            die();
        } else if(!filter_var($aula["TRAMO"], FILTER_VALIDATE_INT)) {
            array_push($errores, '<p>Los datos del aula no son correctos.</p>');
            $_SESSION["errores"] = $errores;
            Header("Location: reserva_aulas.php");
            die();
        } else {
            $tramo = intval($aula["TRAMO"]);
            if($tramo < 0 || $tramo > 6) {
                array_push($errores, '<p>Los datos del aula no son correctos.</p>');
                $_SESSION["errores"] = $errores;
                Header("Location: reserva_aulas.php");
                die();
            }
        }
        if($aula["NUM_CARNE"] == "") {
            array_push($errores, '<p>Los datos del aula no son correctos.</p>');
            $_SESSION["errores"] = $errores;
            Header("Location: reserva_aulas.php");
            die();
        } else if(!preg_match("/^[0-9]{5}[AP]{1}$/", $aula["NUM_CARNE"])) {
            array_push($errores, '<p>Los datos del aula no son correctos.</p>');
            $_SESSION["errores"] = $errores;
            Header("Location: reserva_aulas.php");
            die();
        }

        if(preg_match("/^[0-9]{2}\/[0-9]{2}\/[0-9]{2}$/", $aula["FECHA_RESERVA"])) {
            $fechaSplit = explode('/', $aula["FECHA_RESERVA"]);
            $fechaSplit[2] = '20'.$fechaSplit[2];
            $temp = $fechaSplit[0];
            $fechaSplit[0] = $fechaSplit[2];
            $fechaSplit[2] = $temp;
            $fecha = implode('-', $fechaSplit);
        } else $fecha = $aula["FECHA_RESERVA"];

        $day_week = intval(date('w', strtotime($fecha)));

        if($day_week == 0 || $day_week == 6) {
            array_push($errores, '<p>Se ha intentado reservar un aula un fin de semana, lo cual no está permitido. Sentimos las molestias.</p>');
            $_SESSION["errores"] = $errores;
            Header("Location: reserva_aulas.php");
            die();
        }
        
        if(isset($_REQUEST["cancelar"])) { 
            $con = crearConexionBD();
            cancelarReservaAula($con, $aula["OID_RA"]);
            $_SESSION["mensaje"] = "La reserva se ha cancelado correctamente.";
            cerrarConexionBD($con);
            $_SESSION["fecha_ant"] = date('Y-m-d', strtotime($fecha));
        }elseif(isset($_REQUEST["reservar"])) {
            $con = crearConexionBD();
            $b = reservarAula($con, $aula["NUM_CARNE"], $aula["NUMERO"], $fecha, $aula["TRAMO"]);
            if($b) $_SESSION["mensaje"] = "Se ha realizado la reserva correctamente.";
            cerrarConexionBD($con);
            $_SESSION["fecha_ant"] = date('Y-m-d', strtotime($fecha));
        } else Header("Location: excepcion.php");

        Header("Location: reserva_aulas.php");

    } else Header("Location: index.php");
?>