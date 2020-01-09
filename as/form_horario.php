<?php
    session_start();

    require_once("gestionBD.php");
    require_once("gestionUsuarios.php");

    if(isset($_REQUEST["action"])) {
        $carne = isset($_REQUEST["carne"]) ? $_REQUEST["carne"] : null;
        $dia = isset($_REQUEST["dia"]) ? $_REQUEST["dia"] : null;
        $tramo = isset($_REQUEST["tramo"]) ? $_REQUEST["tramo"] : null;
        $oid = isset($_REQUEST["oid"]) ? $_REQUEST["oid"] : null;
        $asignatura = isset($_REQUEST["asignatura"]) ? $_REQUEST["asignatura"] : null;
        $action = $_REQUEST["action"];
        if($action == 'add-form') {
            echo "<form method='post' action='form_horario.php'>";
            echo "<input type='hidden' name='action' value='add'/>";
            echo "<input type='hidden' name='carne' id='carne' value='" . $carne . "'/>";
            echo "<input type='hidden' name='dia' id='dia' value='" . $dia . "'/>";
            echo "<input type='hidden' name='tramo' id='tramo' value='" . $tramo . "'/>";
            echo "<input type='text' name='asignatura' id='asignatura' required/>";
            echo "<button class='btn' type='submit'><img src='images/edit_icon.png' width=15 height=15 /></button>";
            echo "</form>";
        }
        if($action == 'edit') {
            echo "<form method='post' action='form_horario.php'>";
            echo "<input type='hidden' name='action' value='update'/>";
            echo "<input type='hidden' name='oid' id='oid' value='" . $oid . "'/>";
            echo "<input type='text' name='asignatura' id='asignatura' value='" . $asignatura . "'/>";
            echo "<button class='btn' type='submit'><img src='images/edit_icon.png' width=15 height=15 /></button>";
            echo "</form>";
        }
        if($action == 'add') {
            $con = crearConexionBD();
            $b = validacionErrores($carne, $dia, $asignatura, $tramo, $oid, true);
            if($b) {
                insertarEntradaHorario($con, $carne, $dia, $asignatura, $tramo);
            }
            cerrarConexionBD($con);
            Header("Location: exito_login.php");
        }
        if($action == 'update') {
            $con = crearConexionBD();
            $b = validacionErrores($carne, $dia, $asignatura, $tramo, $oid, false);
            if($b) {
                $asignatura == ""? eliminarEntradaHorario($con, $oid):
                actualizarEntradaHorario($con, $asignatura, $oid);
            }
            cerrarConexionBD($con);
            Header("Location: exito_login.php");
        }
    }

    function validacionErrores($carne, $dia, $asignatura, $tramo, $oid, $insertar) {
        $errores = array();
        
        if($insertar) {
            if($asignatura == "") {
                array_push($errores, '<p>No se puede insertar una asignatura vacía.</p>');
                $_SESSION["errores"] = $errores;
                Header("Location: exito_login.php");
                die();
            }

            if($carne == "") {
                array_push($errores, '<p>Los datos del formulario no son correctos.</p>');
                $_SESSION["errores"] = $errores;
                Header("Location: exito_login.php");
                die();
            } else if(!preg_match("/^[0-9]{5}[AP]{1}$/", $carne)) {
                array_push($errores, '<p>Los datos del formulario no son correctos.</p>');
                $_SESSION["errores"] = $errores;
                Header("Location: exito_login.php");
                die();
            }
    
            if($dia == "") {
                array_push($errores, '<p>Los datos del formulario no son correctos.</p>');
                $_SESSION["errores"] = $errores;
                Header("Location: exito_login.php");
                die();
            } elseif($dia != 'Lunes' && $dia != 'Martes' && $dia != 'Miercoles' && $dia != 'Jueves' && $dia != 'Viernes') {
                array_push($errores, '<p>Los datos del formulario no son correctos.</p>');
                $_SESSION["errores"] = $errores;
                Header("Location: exito_login.php");
                die();
            }
    
            if($tramo == '') {
                array_push($errores, '<p>Los datos del formulario no son correctos.</p>');
                $_SESSION["errores"] = $errores;
                Header("Location: exito_login.php");
                die();
            } elseif(!filter_var($tramo, FILTER_VALIDATE_INT)) {
                array_push($errores, '<p>Los datos del formulario no son correctos.</p>');
                $_SESSION["errores"] = $errores;
                Header("Location: exito_login.php");
                die();
            } else {
                $tramoInt = intval($tramo);
                if($tramoInt < 0 || $tramoInt > 6) {
                    array_push($errores, '<p>Los datos del formulario no son correctos.</p>');
                    $_SESSION["errores"] = $errores;
                    Header("Location: exito_login.php");
                    die();
                }
            }
        } else {
            if($oid == '') {
                array_push($errores, '<p>Los datos del formulario no son correctos.</p>');
                $_SESSION["errores"] = $errores;
                Header("Location: exito_login.php");
                die();
            } elseif(!filter_var($oid, FILTER_VALIDATE_INT)) {
                array_push($errores, '<p>Los datos del formulario no son correctos.</p>');
                $_SESSION["errores"] = $errores;
                Header("Location: exito_login.php");
                die();
            }
        }

        return true;
    }
?>