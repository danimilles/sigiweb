<?php 

include_once("utilities.php");
include_once("gestionErrores.php");
/**
 * Biblioteca de funciones para gestionar los materiales de la base de datos.
 * 
 * @author SIGIWEB
 */

function obtenerMateriales($conexion, $val,$busqueda,$carne, $oid_d, $page_num, $page_size){
    try {
        $s = "";
        if(isset($busqueda) && $busqueda!="" && $busqueda!=="0"){
            if($carne == '00000P'){
                $s.="where ";
                $s.= "(NOMBRE like '%' || :busqueda || '%' or ";
            } else if($carne != '00000P'){
                $s.="and (";
            }
            $s.= "DESCRIPCION like '%' || :busqueda || '%' or ";
            $s.= "FECHA_BAJA like '%' || :busqueda || '%' or ";
            $s.= "FECHA_ALTA like '%' || :busqueda || '%' or ";
            $s.= "ESTADO like '%' || :busqueda || '%') ";
            if($val=='yes'){
                $s.="and ";
            }
        } else if($val=='yes' && $carne == '00000P'){
            $s.="where ";
        } else if($val=='yes' && $carne != '00000P'){
            $s.="and ";
        }
        if($val=='yes'){
            $s.="fecha_baja is null ";
        }

        if($carne == '00000P'){
            $consulta = "SELECT * FROM MATERIALES natural join departamentos ".$s."GROUP BY OID_D, DESCRIPCION, ESTADO, FECHA_ALTA, FECHA_BAJA, UNIDADES, OID_M, NOMBRE, JEFE_DEPARTAMENTO";
        }else{
            $consulta = "SELECT * FROM MATERIALES WHERE oid_d=:oid_d ".$s;
        }
        $total = totalQuery($conexion, $consulta, "", $oid_d,$busqueda);
        $total_pages = ($total / $page_size);
        if( $total % $page_size > 0 ) $total_pages++; // resto de la división
        if( $page_num  > $total_pages ) $page_num = 1;
        $inventario = paginatedQuery($conexion, $consulta, $page_num, $page_size, "", $oid_d,$busqueda);
        $result["total"] = $total;
        $result["total_pages"] = $total_pages;
        $result["materiales"] = $inventario;
        return $result;
    } catch (PDOException $e) {
        $_SESSION['excepcion'] = $e->GetMessage();
        Header("Location: excepcion.php");
        die();
    }
}

function listaMateriales($conexion, $busqueda, $page_num, $page_size){
    try {
        $s = "";
        if(isset($busqueda) && $busqueda!="" && $busqueda!=="0"){
            $s.="and ";
            $s.= "(DESCRIPCION like '%' || :busqueda || '%' or ";
            $s.= "ESTADO like '%' || :busqueda || '%' or ";
            $s.= "FECHA_ALTA like '%' || :busqueda || '%' or ";
            $s.= "NOMBRE like '%' || :busqueda || '%') ";
        }
        $consulta = "SELECT * FROM MATERIALES natural join departamentos where fecha_baja is null ".$s."GROUP BY OID_D, DESCRIPCION, ESTADO, FECHA_ALTA, FECHA_BAJA, UNIDADES, OID_M,JEFE_DEPARTAMENTO,NOMBRE";
        $total = totalQuery($conexion, $consulta, "", "",$busqueda);
        $total_pages = ($total / $page_size);
        if( $total % $page_size > 0 ) $total_pages++; // resto de la división
        if( $page_num  > $total_pages ) $page_num = 1;
        $materiales = paginatedQuery($conexion, $consulta, $page_num, $page_size, "", "",$busqueda);
        $result["total"] = $total;
        $result["total_pages"] = $total_pages;
        $result["materiales"] = $materiales;
        return $result;
    } catch (PDOException $e) {
        $_SESSION['excepcion'] = $e->GetMessage();
        Header("Location: excepcion.php");
        die();
    }
}

function nuevoMaterial($conexion, $material, $oid_d){
    try {
        $consulta = "INSERT INTO MATERIALES (DESCRIPCION,ESTADO,UNIDADES,OID_D) VALUES (:descr,:est,:uds,:oid_d)";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(":descr", $material["descripcion"]);
        $stmt->bindParam(":est", $material["estado"]);
        $stmt->bindParam(":uds", $material["unidades"]);
        $stmt->bindParam(":oid_d", $oid_d);
        $stmt->execute();
        return true;
    } catch(PDOException $e) {
        return false;
    }
}

function reservasMaterialesPorUsuario($con, $carne) {
    try {
        $consulta = "SELECT * FROM MATERIALES NATURAL JOIN RESERVASMATERIALES WHERE (NUM_CARNE=:carne AND FECHA_RESERVA >= TO_DATE(TO_CHAR(SYSDATE, 'DD/MM/YYYY'), 'DD/MM/YYYY')) ORDER BY FECHA_RESERVA DESC";
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

function obtenerReservasMateriales($conexion,$val,$busqueda, $page_num, $page_size) {
    try {
        $s = "";
        if(isset($busqueda) && $busqueda!="" && $busqueda!=="0"){
            $s.="where ";
            $s.= "(DESCRIPCION like '%' || :busqueda || '%' or ";
            $s.= "ESTADO like '%' || :busqueda || '%' or ";
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
        $consulta = "SELECT * FROM RESERVASMATERIALES NATURAL JOIN MATERIALES ".$s."ORDER BY FECHA_RESERVA DESC";
        $total = totalQuery($conexion, $consulta, "", "",$busqueda);
        $total_pages = ($total / $page_size);
        if( $total % $page_size > 0 ) $total_pages++; // resto de la división
        if( $page_num  > $total_pages ) $page_num = 1;
        $materiales = paginatedQuery($conexion, $consulta, $page_num, $page_size, "", "",$busqueda);
        $result["total"] = $total;
        $result["total_pages"] = $total_pages;
        $result["materiales"] = $materiales;
        return $result;
    } catch(PDOException $e) {
        $_SESSION["excepcion"] = $e->GetMessage();
        header("Location: excepcion.php");
        die();
    }
}

function reservasMaterialesPorTramo($con, $fecha, $material, $tramo) {
    try {
        $consulta = "SELECT * FROM MATERIALES NATURAL JOIN RESERVASMATERIALES WHERE (OID_M = :oid_m AND FECHA_RESERVA=:fecha AND TRAMO=:tramo) ORDER BY OID_M ASC";
        $stmt = $con->prepare($consulta);
        $stmt->bindParam(":fecha", date("d/m/Y", strtotime($fecha)));
        $stmt->bindParam(":oid_m", $material);
        $stmt->bindParam(":tramo", $tramo);
        $stmt->execute();
        return $stmt->fetch();
    } catch(PDOException $e) {
        $_SESSION['excepcion'] = $e->GetMessage();
        Header("Location: excepcion.php");
        die();
    }
}

function reservarMaterial($con, $carne, $material, $fecha, $tramo) {
    try {
        $consulta = "CALL RESERVA_MATERIAL(:carne, :oid_m, :fecha, :tramo)";
        $stmt = $con->prepare($consulta);
        $stmt->bindParam(":carne", $carne);
        $stmt->bindParam(":oid_m", $material);
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

function cancelarReservaMaterial($con, $oid_rm) {
    try {
        $consulta = "DELETE FROM RESERVASMATERIALES WHERE OID_RM = :oid_rm";
        $stmt = $con->prepare($consulta);
        $stmt->bindParam(":oid_rm", $oid_rm);
        $stmt->execute();
        return true;
    } catch(PDOException $e) {
        $_SESSION['excepcion'] = $e->GetMessage();
        Header("Location: excepcion.php");
        die();
    }
}

function r_materialesActivas($conexion, $usuario){
    try {
        $stmt = $conexion->prepare(
            "select * from reservasmateriales natural join materiales where num_carne=:usuario and fecha_reserva>sysdate-1");
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

function obtenerMaterialesPorDepartamento($conexion, $oid_d){
    try {
        $consulta = "SELECT * FROM MATERIALES WHERE oid_d=:oid_d";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(":oid_d", $oid_d);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        $_SESSION['excepcion'] = $e->GetMessage();
        Header("Location: excepcion.php");
        die();
    }
}

function reservasMaterialesPorMaterial($con, $oidMaterial) {
    try {
        $consulta = "SELECT * FROM RESERVASMATERIALES WHERE OID_M = :oid_m";
        $stmt = $con->prepare($consulta);
        $stmt->bindParam(":oid_m", $oidMaterial);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        $_SESSION['excepcion'] = $e->GetMessage();
        Header("Location: excepcion.php");
        die();
    }
}

function eliminarMaterial($con, $oid_m) {
    try {
        $consulta = "DELETE FROM MATERIALES WHERE OID_M = :oid_m";
        $stmt = $con->prepare($consulta);
        $stmt->bindParam(":oid_m", $oid_m);
        $stmt->execute();
        return true;
    } catch(PDOException $e) {
        $_SESSION['excepcion'] = $e->GetMessage();
        Header("Location: excepcion.php");
        die();
    }
}

function editarMaterial($con, $material) {
    try {
        $consulta = "UPDATE materiales SET DESCRIPCION = :descr, ESTADO = :est, UNIDADES = :uds, OID_D = :oid_d WHERE OID_M = :oid_m";
        $stmt = $con->prepare($consulta);
        $stmt->bindParam(":descr", $material["descripcion"]);
        $stmt->bindParam(":est", $material["estado"]);
        $stmt->bindParam(":uds", $material["unidades"]);
        $stmt->bindParam(":oid_d", $material["oid_d"]);
        $stmt->bindParam(":oid_m", $material["oid_m"]);
        $stmt->execute();
        return true;
    } catch(PDOException $e) {
        $_SESSION["excepcion"] = $e->GetMessage();
        header("Location: excepcion.php");
        die();
    }
}

function bajaMaterial($con, $oid_m) {
    try {
        $consulta = "CALL BAJA_MATERIAL(:oid_m)";
        $stmt = $con->prepare($consulta);
        $stmt->bindParam(":oid_m", $oid_m);
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
?>