<?php 
include_once("utilities.php");
/**
 * Biblioteca de funciones para obtener estadísticas de la base de datos.
 * 
 * @author SIGIWEB
 */


function alumnosMasActivos($conexion, $page_num, $page_size) {
    try {
        $consulta = "SELECT num_carne,apellidos,nombre,programa_academico,curso,grupo,COUNT(*) AS nom FROM prestamos 
        NATURAL JOIN alumnos NATURAL JOIN usuarios
        GROUP BY num_carne,apellidos,nombre,programa_academico,curso,grupo ORDER BY nom DESC";

        $total = totalQuery($conexion, $consulta, "", "","");
        $total_pages = ($total / $page_size);

        if( $total % $page_size > 0 ) $total_pages++; // resto de la división
        if( $page_num  > $total_pages ) $page_num = 1;

        $datos = paginatedQuery($conexion, $consulta, $page_num, $page_size, "", "","");
        
        $result["total"] = $total;
        $result["total_pages"] = $total_pages;
        $result["datos"] = $datos;

        return $result;
    } catch (PDOException $e) {
        $_SESSION['excepcion'] = $e->GetMessage();
		Header("Location: excepcion.php");
    }
}

function alumnosSancionados($conexion,$page_num, $page_size){
    try {

        $consulta = "SELECT num_carne,nombre,apellidos,dni,fechanacimiento,sexo,email,telefono,
        fecha_entrega,programa_academico,curso,grupo FROM usuarios NATURAL JOIN alumnos NATURAL JOIN prestamos
        WHERE fecha_validez_carne>sysdate AND fecha_entrega>fecha_fin and (fecha_fin+7)>sysdate ORDER BY apellidos ASC";

        $total = totalQuery($conexion, $consulta, "", "", "");
        $total_pages = ($total / $page_size);
        if( $total % $page_size > 0 ) $total_pages++; // resto de la división
        if( $page_num  > $total_pages ) $page_num = 1;

        $datos = paginatedQuery($conexion, $consulta, $page_num, $page_size, "", "", "");
        
        $result["total"] = $total;
        $result["total_pages"] = $total_pages;
        $result["datos"] = $datos;

        return $result;
    } catch (PDOException $e) {
        $_SESSION['excepcion'] = $e->GetMessage();
        Header("Location: excepcion.php");
    }
}

function librosPorSexo($conexion, $sexo, $page_num, $page_size) {
    try {
        if($sexo=='M'){
            $consulta = "SELECT isbn, titulo, autor, genero, cdu, COUNT(*) AS cuenta FROM usuarios NATURAL JOIN alumnos NATURAL JOIN prestamos
            NATURAL JOIN ejemplares NATURAL JOIN libros
            WHERE sexo = 'M' GROUP BY isbn, titulo, autor, genero, cdu ORDER BY cuenta DESC";
        }else if ($sexo=='F'){
            $consulta = "SELECT isbn, titulo, autor, genero, cdu, COUNT(*) AS cuenta FROM usuarios NATURAL JOIN alumnos NATURAL JOIN prestamos
            NATURAL JOIN ejemplares NATURAL JOIN libros
            WHERE sexo = 'F' GROUP BY isbn, titulo, autor, genero, cdu ORDER BY cuenta DESC";
        } 

        $total = totalQuery($conexion, $consulta, "" , "","");
        $total_pages = ($total / $page_size);

        if( $total % $page_size > 0 ) $total_pages++; // resto de la división
        if( $page_num  > $total_pages ) $page_num = 1;

        $datos = paginatedQuery($conexion, $consulta, $page_num, $page_size, "","","");
        
        $result["total"] = $total;
        $result["total_pages"] = $total_pages;
        $result["datos"] = $datos;

        return $result;
    } catch (PDOException $e) {
        $_SESSION['excepcion'] = $e->GetMessage();
		Header("Location: excepcion.php");
    }
}

function librosMasPrestados($conexion, $page_num, $page_size) {
    try {
        $consulta = "SELECT isbn, titulo, autor, cdu, genero, COUNT(*) AS cuenta 
        FROM libros NATURAL JOIN ejemplares NATURAL JOIN prestamos 
        NATURAL JOIN alumnos GROUP BY isbn, titulo, autor, cdu, genero ORDER BY cuenta DESC";
            

        $total = totalQuery($conexion, $consulta, "", "","");
        $total_pages = ($total / $page_size);

        if( $total % $page_size > 0 ) $total_pages++; // resto de la división
        if( $page_num  > $total_pages ) $page_num = 1;

        $datos = paginatedQuery($conexion, $consulta, $page_num, $page_size, "", "","");
        
        $result["total"] = $total;
        $result["total_pages"] = $total_pages;
        $result["datos"] = $datos;

        return $result;
    } catch (PDOException $e) {
        $_SESSION['excepcion'] = $e->GetMessage();
		Header("Location: excepcion.php");
    }
}
function generosMasPrestados($conexion, $page_num, $page_size) {
    try {
        $consulta = "SELECT genero, COUNT(*) AS cuenta FROM libros NATURAL JOIN ejemplares NATURAL JOIN prestamos 
        GROUP BY genero ORDER BY cuenta DESC";
            

        $total = totalQuery($conexion, $consulta, "", "","");
        $total_pages = ($total / $page_size);

        if( $total % $page_size > 0 ) $total_pages++; // resto de la división
        if( $page_num  > $total_pages ) $page_num = 1;

        $datos = paginatedQuery($conexion, $consulta, $page_num, $page_size, "", "","");
        
        $result["total"] = $total;
        $result["total_pages"] = $total_pages;
        $result["datos"] = $datos;

        return $result;
    } catch (PDOException $e) {
        $_SESSION['excepcion'] = $e->GetMessage();
		Header("Location: excepcion.php");
    }
}

function librosPorEdad($conexion, $edadn, $page_num, $page_size) {
    try {
        $edad=(int)$edadn;

        if(is_int($edad) && $edad<99 && $edad>0){
            $consulta = "SELECT isbn, titulo, autor, genero, cdu, COUNT(*) AS cuenta
            FROM usuarios NATURAL JOIN alumnos NATURAL JOIN prestamos
            NATURAL JOIN ejemplares NATURAL JOIN libros
            WHERE (sysdate-fechanacimiento)/365 BETWEEN (".$edad."-1) AND (".$edad."+1)
            GROUP BY isbn, titulo, autor, genero, cdu ORDER BY cuenta DESC";
        }

        $total = totalQuery($conexion, $consulta, "", "","");
        $total_pages = ($total / $page_size);

        if( $total % $page_size > 0 ) $total_pages++; // resto de la división
        if( $page_num  > $total_pages ) $page_num = 1;

        $datos = paginatedQuery($conexion, $consulta, $page_num, $page_size, "", "","");
        
        $result["total"] = $total;
        $result["total_pages"] = $total_pages;
        $result["datos"] = $datos;

        return $result;
    } catch (PDOException $e) {
        $_SESSION['excepcion'] = $e->GetMessage();
		Header("Location: excepcion.php");
    }
}

function librosPorCurso($conexion, $curson, $programa, $page_num, $page_size) {
    try {
        $curso = (int)$curson;
        if(is_int($curso) && ($programa=='BACHILLERATO' || $programa=='CFGM Gestión Administrativa' || $programa=='FP Básica' || $programa=='ESO' ||  $programa=='CFGS Administración y Finanzas')){
            $consulta = "SELECT isbn, titulo, autor, genero, cdu, COUNT(*) AS cuenta FROM usuarios 
            NATURAL JOIN alumnos NATURAL JOIN prestamos NATURAL JOIN ejemplares NATURAL JOIN libros
            WHERE programa_academico = '".$programa."' AND curso =".$curso."  GROUP BY isbn, titulo, autor, genero, cdu ORDER BY cuenta DESC";
        }

        $total = totalQuery($conexion, $consulta, "", "","");
        $total_pages = ($total / $page_size);

        if( $total % $page_size > 0 ) $total_pages++; // resto de la división
        if( $page_num  > $total_pages ) $page_num = 1;

        $datos = paginatedQuery($conexion, $consulta, $page_num, $page_size, "", "","");
        
        $result["total"] = $total;
        $result["total_pages"] = $total_pages;
        $result["datos"] = $datos;

        return $result;
    } catch (PDOException $e) {
        $_SESSION['excepcion'] = $e->GetMessage();
		Header("Location: excepcion.php");
    }
}

?>