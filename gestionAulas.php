<?php
/**
 * Este archivo contiene las funciones necesarias para recoger todos los datos necesarios
 * sobre las aulas de la base de datos.
 * @author SIGIWEB
 */

    include_once("gestionErrores.php");

    function listaAulas($con) {
        try {
            $consulta = "SELECT * FROM AULAS";
            $result = $con->query($consulta);
            return $result->fetchAll();
        } catch (PDOException $e) {
            $_SESSION["excepcion"] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function reservasAulasPorAulaTramo($con, $fecha, $aula, $tramo) {
        try {
            $consulta = "SELECT * FROM AULAS NATURAL JOIN RESERVASAULAS WHERE (NUMERO=:aula AND FECHA_RESERVA=:fecha AND TRAMO=:tramo) ORDER BY NUMERO ASC";
            $stmt = $con->prepare($consulta);
            $stmt->bindParam(":fecha", date("d/m/Y", strtotime($fecha)));
            $stmt->bindParam(":aula", $aula);
            $stmt->bindParam(":tramo", $tramo);
            $stmt->execute();
            return $stmt->fetch();
        } catch(PDOException $e) {
            $_SESSION["excepcion"] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
        
    }

    function reservasAulasPorUsuario($con, $carne) {
        try {
            $consulta = "SELECT * FROM AULAS NATURAL JOIN RESERVASAULAS WHERE (NUM_CARNE=:carne AND FECHA_RESERVA >= TO_DATE(TO_CHAR(SYSDATE, 'DD/MM/YYYY'), 'DD/MM/YYYY')) ORDER BY FECHA_RESERVA DESC";
            $stmt = $con->prepare($consulta);
            $stmt->bindParam(":carne", $carne);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            $_SESSION["excepcion"] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function obtenerReservasAulas($conexion,$val,$busqueda, $page_num, $page_size) {
        try {
            $s = "";
            if(isset($busqueda) && $busqueda!="" && $busqueda!=="0"){
                    $s.="where ";
                    $s.= "(NOMBRE like '%' || :busqueda || '%' or ";
                    $s.= "NUMERO like '%' || :busqueda || '%' or ";
                    $s.= "FECHA_RESERVA like '%' || :busqueda || '%' or ";
                    $s.= "TRAMO like '%' || :busqueda || '%' or ";
                    $s.= "NUM_CARNE like '%' || :busqueda || '%') ";
                    if($val=='yes'){
                        $s.="and ";
                    }
            } else if($val=='yes'){
                $s.="where ";
            }
            if($val=='yes'){
                $s.="FECHA_RESERVA>sysdate-1 ";
            }
            $consulta = "SELECT * FROM RESERVASAULAS NATURAL JOIN AULAS ".$s."ORDER BY FECHA_RESERVA DESC";
            $total = totalQuery($conexion, $consulta, "", "",$busqueda);
            $total_pages = ($total / $page_size);
            if( $total % $page_size > 0 ) $total_pages++; // resto de la división
            if( $page_num  > $total_pages ) $page_num = 1;
            $aulas = paginatedQuery($conexion, $consulta, $page_num, $page_size, "", "",$busqueda);
            $result["total"] = $total;
            $result["total_pages"] = $total_pages;
            $result["aulas"] = $aulas;
            return $result;
        } catch(PDOException $e) {
            $_SESSION["excepcion"] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function reservarAula($con, $carne, $numero, $fecha, $tramo) {
        try {
            $consulta = "CALL RESERVA_AULA(:carne, :numero, :fecha, :tramo)";
            $stmt = $con->prepare($consulta);
            $stmt->bindParam(":carne", $carne);
            $stmt->bindParam(":numero", $numero);
            $stmt->bindParam(":fecha", date("d/m/Y", strtotime($fecha)));
            $stmt->bindParam(":tramo", $tramo);
            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            $msg = $e->getMessage();
            $error = comprobacionError($msg);
            if($error != false) {
                $errores = array();
                array_push($errores, $error);
                $_SESSION["errores"] = $errores;
                return false;
            } else {
                $_SESSION["excepcion"] = $msg;
                header("Location: excepcion.php");
                die();
            }
        }
    }

    function cancelarReservaAula($con, $oid_ra) {
        try {
            $consulta = "DELETE FROM RESERVASAULAS WHERE OID_RA = :oidra";
            $stmt = $con->prepare($consulta);
            $stmt->bindParam(":oidra", $oid_ra);
            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            $_SESSION["excepcion"] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function r_aulasActivas($conexion, $usuario){
        try {
            $stmt = $conexion->prepare("select * from reservasaulas natural join aulas where num_carne=:usuario and fecha_reserva>sysdate-1");
            $stmt->bindParam(":usuario", $usuario);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function creaAula($con, $aula) {
        try {
            $consulta = "insert into aulas (nombre,numero) values (:nombre, :numero)";
            $stmt = $con->prepare($consulta);
            $stmt->bindParam(":nombre", $aula["nombre"]);
            $stmt->bindParam(":numero", $aula["numero"]);
            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            $_SESSION["excepcion"] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function obtenerReservasPorAula($con, $numero) {
        try {
            $consulta = "SELECT * FROM AULAS NATURAL JOIN RESERVASAULAS WHERE NUMERO=:numero";
            $stmt = $con->prepare($consulta);
            $stmt->bindParam(":numero", $numero);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            $_SESSION["excepcion"] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
        
    }

    function eliminaAula($con, $numero) {
        try {
            $consulta = "DELETE FROM AULAS WHERE NUMERO = :numero";
            $stmt = $con->prepare($consulta);
            $stmt->bindParam(":numero", $numero);
            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            $_SESSION["excepcion"] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function editaAula($con, $aula) {
        try {
            $consulta = "UPDATE AULAS SET NOMBRE = :nombre WHERE NUMERO = :numero";
            $stmt = $con->prepare($consulta);
            $stmt->bindParam(":nombre", $aula["nombre"]);
            $stmt->bindParam(":numero", $aula["numero"]);
            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            $_SESSION["excepcion"] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }
