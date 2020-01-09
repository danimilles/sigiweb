<?php 
include_once("utilities.php");
/**
 * Biblioteca de funciones para gestionar los libros de la base de datos.
 * 
 * @author SIGIWEB
 */

function consultaCatalogoLibros($conexion,$carne,$val,$busqueda, $page_num, $page_size) {
    try {
        if($carne!='00000P' && $carne!='99999P'){
            $val='yes';
        }
        $s = "";
        if(isset($busqueda) && $busqueda!="" && $busqueda!=="0"){
            $s.="where ";
            $s.= "(ISBN like '%' || :busqueda || '%' or ";
            $s.= "AUTOR like '%' || :busqueda || '%' or ";
            $s.= "TITULO like '%' || :busqueda || '%' or ";
            $s.= "CDU like '%' || :busqueda || '%' or ";
            $s.= "GENERO like '%' || :busqueda || '%') ";
            if($val=='yes'){
                $s.="and ";
            }
        } else if($val=='yes'){
            $s.="where ";
        }
        if($val=='yes'){
            $s.="copias>0 ";
        }

        $consulta = "SELECT * FROM LIBROS ".$s."ORDER BY TITULO";

        $total = totalQuery($conexion, $consulta, "", "",$busqueda);
        $total_pages = ($total / $page_size);

        if( $total % $page_size > 0 ) $total_pages++; // resto de la división
        if( $page_num  > $total_pages ) $page_num = 1;

        $libros = paginatedQuery($conexion, $consulta, $page_num, $page_size, "", "",$busqueda);
        
        $result["total"] = $total;
        $result["total_pages"] = $total_pages;
        $result["libros"] = $libros;

        return $result;
    } catch (PDOException $e) {
        $_SESSION['excepcion'] = $e->GetMessage();
		Header("Location: excepcion.php");
    }
}


function consultaEjemplares($conexion,$carne,$val,$busqueda, $isbn, $page_num, $page_size) {
    try {
        if($carne!='00000P' && $carne!='99999P'){
            $val='yes';
        }
        $s = "";
        if(isset($busqueda) && $busqueda!="" && $busqueda!=="0"){
            $s.="where ";
            $s.= "(CODIGO like '%' || :busqueda || '%' or ";
            $s.= "ESTADO like '%' || :busqueda || '%' or ";
            $s.= "DISPONIBLE like '%' || :busqueda || '%') ";
            $s.="and ";
        } else{
            $s.="where ";
        }
        if($val=='yes'){
            $s.="fecha_baja is null and ";
        }
        
        $consulta = "SELECT * FROM ejemplares ".$s."isbn=:isbn ORDER BY codigo";
        $total = totalQueryEj($conexion, $consulta, $isbn,$busqueda);
        $total_pages = ($total / $page_size);

        if( $total % $page_size > 0 ) $total_pages++; // resto de la división
        if( $page_num  > $total_pages ) $page_num = 1;

        $ejemplares = paginatedQueryEj($conexion, $consulta, $page_num, $page_size, $isbn,$busqueda);
        
        $result["total"] = $total;
        $result["total_pages"] = $total_pages;
        $result["ejemplares"] = $ejemplares;

        return $result;
    } catch (PDOException $e) {
        $_SESSION['excepcion'] = $e->GetMessage();
		Header("Location: excepcion.php");
    }
}

function historialDePrestamos($conexion,$val,$busqueda, $carne, $page_num, $page_size) {
    try { 
        $s = "";
        if(isset($busqueda) && $busqueda!="" && $busqueda!=="0"){
            $s.="and ";
            $s.= "(PRESTAMOS.CODIGO like '%' || :busqueda || '%' or ";
            $s.= "PRESTAMOS.NUM_CARNE like '%' || :busqueda || '%' or ";
            $s.= "LIBROS.AUTOR like '%' || :busqueda || '%' or ";
            $s.= "LIBROS.TITULO like '%' || :busqueda || '%' or ";
            $s.= "FECHA_INICIO like '%' || :busqueda || '%' or ";
            $s.= "FECHA_ENTREGA like '%' || :busqueda || '%' or ";
            $s.= "FECHA_FIN like '%' || :busqueda || '%') ";
            if($val=='yes'){
                $s.="and ";
            }
        } else if($val=='yes'){
            $s.="and ";
        }
        if($val=='yes'){
            $s.="fecha_entrega is null ";
        }
        if($carne == '00000P' || $carne == '99999P') {
            $consulta = "SELECT LIBROS.TITULO, LIBROS.AUTOR, PRESTAMOS.FECHA_INICIO, PRESTAMOS.FECHA_FIN, PRESTAMOS.FECHA_ENTREGA, PRESTAMOS.NUM_CARNE, PRESTAMOS.CODIGO, PRESTAMOS.OID_P "
            ."FROM PRESTAMOS, EJEMPLARES, LIBROS WHERE "
            ."(EJEMPLARES.ISBN=LIBROS.ISBN AND PRESTAMOS.CODIGO=EJEMPLARES.CODIGO) ".$s."ORDER BY prestamos.fecha_inicio DESC";
            
        } else {
            $consulta = "SELECT LIBROS.TITULO, LIBROS.AUTOR, PRESTAMOS.FECHA_INICIO, PRESTAMOS.FECHA_FIN, PRESTAMOS.FECHA_ENTREGA, PRESTAMOS.NUM_CARNE, PRESTAMOS.CODIGO, PRESTAMOS.OID_P "
            ."FROM PRESTAMOS, EJEMPLARES, LIBROS WHERE (PRESTAMOS.NUM_CARNE = :carne AND "
            ."EJEMPLARES.ISBN=LIBROS.ISBN AND PRESTAMOS.CODIGO=EJEMPLARES.CODIGO) ".$s."ORDER BY prestamos.fecha_inicio DESC";
        }
        $total = totalQuery($conexion, $consulta, $carne, "",$busqueda);
        $total_pages = ($total / $page_size);

        if( $total % $page_size > 0 ) $total_pages++; // resto de la división
        if( $page_num  > $total_pages ) $page_num = 1;

        $prestamos = paginatedQuery($conexion, $consulta, $page_num, $page_size, $carne, "",$busqueda);
        
        $result["total"] = $total;
        $result["total_pages"] = $total_pages;
        $result["prestamos"] = $prestamos;

        return $result;
    } catch (PDOException $e) {
        $_SESSION['excepcion'] = $e->GetMessage();
		Header("Location: excepcion.php");
    }
}

function nuevoLibro($con, $libro) {
    try {
        $consulta = "CALL ADD_LIBRO(:num_lib, :isbn, :titulo, :autor, :cdu, :genero, :estado)";
        $stmt = $con->prepare($consulta);
        $stmt->bindParam(":num_lib", $libro["num_copias"]);
        $stmt->bindParam(":isbn", $libro["isbn"]);
        $stmt->bindParam(":titulo", $libro["titulo"]);
        $stmt->bindParam(":autor", $libro["autor"]);
        $stmt->bindParam(":cdu", $libro["cdu"]);
        $stmt->bindParam(":genero", $libro["genero"]);
        $stmt->bindParam(":estado", $libro["estado"]);
        $stmt->execute();
        return true;
    } catch(PDOException $e) {
        return false;
    }
}

function nuevoEjemplar($con, $ejemplar) {
    try {
        $consulta = "insert into ejemplares (isbn,estado) values (:isbn,:estado)";
        $stmt = $con->prepare($consulta);
        $stmt->bindParam(":estado", $ejemplar["estado"]);
        $stmt->bindParam(":isbn", $ejemplar["isbn"]);
        $stmt->execute();
        return true;
    } catch(PDOException $e) {
        return false;
    }
}

function prestaLibro($conexion, $codigo, $usuario){
    try {
        $stmt = $conexion->prepare(
            "call presta_libro(:carne,:codigo)");
        $stmt->bindParam(":carne", $usuario);
        $stmt->bindParam(":codigo", $codigo);
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


function existeEjemplar($conexion, $codigo){
    try {
        $stmt = $conexion->prepare(
            "select * from ejemplares where codigo=:codigo");
        $stmt->bindParam(":codigo", $codigo);
        $stmt->execute();
        $result = $stmt->fetch();
        return empty($result);
    } catch(PDOException $e) {
        $_SESSION['excepcion'] = $e->GetMessage();
        header("Location: excepcion.php");
    }
}

function prestamosActivos($conexion, $usuario){
    try {
        $stmt = $conexion->prepare(
            "select titulo,autor,codigo,fecha_fin,fecha_entrega from prestamos natural join ejemplares natural join libros where prestamos.num_carne=:usuario and prestamos.fecha_entrega IS NULL");
        $stmt->bindParam(":usuario", $usuario);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    } catch(PDOException $e) {
        $_SESSION['excepcion'] = $e->GetMessage();
        header("Location: excepcion.php");
    }
}
function modificarLibro($conexion, $libroEditado) {
	try {
		$stmt=$conexion->prepare("UPDATE LIBROS SET TITULO=:tit, AUTOR=:aut, CDU=:cdu, GENERO=:gen WHERE ISBN = :isbn");
		$stmt->bindParam(':tit', $libroEditado["titulo"]);
        $stmt->bindParam(':aut', $libroEditado["autor"]);
        $stmt->bindParam(':cdu', $libroEditado["cdu"]);
        $stmt->bindParam(':gen', $libroEditado["genero"]);
        $stmt->bindParam(':isbn', $libroEditado["isbn"]);
		$stmt->execute();
		return true;
	} catch(PDOException $e) {
		return false;
    }
}

function modificarEjemplar($conexion, $ejemplar) {
	try {
		$stmt=$conexion->prepare("UPDATE ejemplares SET estado=:estado WHERE CODIGO = :codigo");
        $stmt->bindParam(':estado', $ejemplar["estado"]);
        $stmt->bindParam(':codigo', $ejemplar["codigo"]);
		$stmt->execute();
		return true;
	} catch(PDOException $e) {
		return false;
    }
}


function obtieneLibro($conexion, $isbn){
    try {
        $stmt = $conexion->prepare(
            "select * from libros where isbn=:isbn");
        $stmt->bindParam(":isbn", $isbn);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result;
    } catch(PDOException $e) {
        $_SESSION['excepcion'] = $e->GetMessage();
        header("Location: excepcion.php");
    }
}

function existeLibro($conexion, $isbn){
    try {
        $stmt = $conexion->prepare(
            "select * from libros where isbn=:isbn");
        $stmt->bindParam(":isbn", $isbn);
        $stmt->execute();
        $result = $stmt->fetch();
        return empty($result);
    } catch(PDOException $e) {
        $_SESSION['excepcion'] = $e->GetMessage();
        header("Location: excepcion.php");
    }
}

function consultaEjemplaresSinPag($conexion, $isbn) {
    try {
        $consulta = "SELECT * FROM ejemplares WHERE fecha_baja is null and isbn=:isbn";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(":isbn", $isbn);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    } catch (PDOException $e) {
        $_SESSION['excepcion'] = $e->GetMessage();
		Header("Location: excepcion.php");
    }
}

function bajaEjemplar($con, $codigoEjemplar) {
    try {
        $consulta = "CALL BAJA_LIBRO(:codigo_ejemplar)";
        $stmt = $con->prepare($consulta);
        $stmt->bindParam(":codigo_ejemplar", $codigoEjemplar);
        $stmt->execute();
        return true;
    } catch(PDOException $e) {
        return false;
    }
}

function devuelveLibro($con, $codigo) {
    try {
        $consulta = "CALL DEVUELVE_LIBRO(:codigo)";
        $stmt = $con->prepare($consulta);
        $stmt->bindParam(":codigo", $codigo);
        return $stmt->execute();
    } catch(PDOException $e) {
        $_SESSION['excepcion'] = $e->GetMessage();
        header("Location: excepcion.php");
        die();
    }
}

function renuevaPrestamo($con, $codigo) {
    try {
        $consulta = "CALL RENUEVA_PRESTAMO(:codigo)";
        $stmt = $con->prepare($consulta);
        $stmt->bindParam(":codigo", $codigo);
        return $stmt->execute();
    } catch(PDOException $e) {
        $_SESSION['excepcion'] = $e->GetMessage();
        header("Location: excepcion.php");
        die();
    }
}

function estaDisponible($con, $codigo) {
    try {
        $consulta = "SELECT DISPONIBLE FROM EJEMPLARES WHERE codigo = :codigo";
        $stmt = $con->prepare($consulta);
        $stmt->bindParam(":codigo", $codigo);
        $stmt->execute();
        $result = $stmt->fetchColumn();
        return $result;
    } catch(PDOException $e) {
        $_SESSION['excepcion'] = $e->GetMessage();
        header("Location: excepcion.php");
        die();
    }
}

?>