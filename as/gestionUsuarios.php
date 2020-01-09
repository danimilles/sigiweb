<?php

include_once("utilities.php");
include_once("gestionAulas.php");
include_once("gestionDepartamentos.php");
include_once("mails.php");

/**
 * Biblioteca de funciones para gestionar los usuarios de la base de datos.
 * 
 * @author SIGIWEB
 */


    function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //Declaramos la contraseña como un array
        $alphaLength = strlen($alphabet) - 1; //Guardamos la longitud de $alphabet-1 en una variable
        // Obtenemos cada carácter haciendo una llamada a rand, que genera un número aleatorio de 0 a 
        // $alphaLength. El número lo usa para coger un carácter de $alphabet y guardarlo en $pass
        $lowercase = rand(0, 9);
        $uppercase = rand(0, 9);
        $number = rand(0, 9);
        if($lowercase == $uppercase) $uppercase = ($uppercase + 1) % 10;
        if($lowercase == $number) $number = ($number + 1) % 10;
        if($uppercase == $number) $number = ($number + 1) % 10;
        for ($i = 0; $i < 10; $i++) {
            if($i == $lowercase) $n = rand(0, 25);
            elseif($i == $uppercase) $n = rand(26, 51);
            elseif($i == $number) $n = rand(51, $alphaLength);
            else $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //Convierte el array en un String
    }

    function altaUsuario($conexion, $usuario) {
        try {
            if($usuario["tipoUsuario"] == "alumno") {
                $passwd = randomPassword();
                $hash = password_hash($passwd, PASSWORD_DEFAULT);
                $consulta = "CALL ALTA_ALUMNO(:pass, :nom, :ap, :dni, :fec, :sexo, :em, :tlf, :prAc, :cur, :grupo)";
                $stmt = $conexion->prepare($consulta);
                $stmt->bindParam(":pass", $hash);
                $stmt->bindParam(":nom", $usuario["nombre"]);
                $stmt->bindParam(":ap", $usuario["apellidos"]);
                $stmt->bindParam(":dni", $usuario["dni"]);
                $stmt->bindParam(":fec", date("d/m/Y", strtotime($usuario["fechaNacimiento"])));
                $stmt->bindParam(":sexo", $usuario["sexo"]);
                $stmt->bindParam(":em", $usuario["email"]);
                $stmt->bindParam(":tlf", $usuario["telefono"]);
                $stmt->bindParam(":prAc", $usuario["progAcademico"]);
                $stmt->bindParam(":cur", $usuario["curso"]);
                $stmt->bindParam(":grupo", $usuario["grupo"]);

                $stmt->execute();

                $carne = carneDeEmail($conexion, $usuario["email"]);

                //Email de bienvenida
                sendWelcomeMail($usuario["email"], $usuario["nombre"], $carne, $passwd);

                return true;
            } else {
                $passwd= randomPassword();
                $hash = password_hash($passwd, PASSWORD_DEFAULT);
                $consulta = "CALL ALTA_PROFESOR(:pass, :nom, :ap, :dni, :fec, :sexo, :em, :tlf, :dept)";
                $stmt = $conexion->prepare($consulta);
                $stmt->bindParam(":pass", $hash);
                $stmt->bindParam(":nom", $usuario["nombre"]);
                $stmt->bindParam(":ap", $usuario["apellidos"]);
                $stmt->bindParam(":dni", $usuario["dni"]);
                $stmt->bindParam(":fec", date("d/m/Y", strtotime($usuario["fechaNacimiento"])));
                $stmt->bindParam(":sexo", $usuario["sexo"]);
                $stmt->bindParam(":em", $usuario["email"]);
                $stmt->bindParam(":tlf", $usuario["telefono"]);
                $stmt->bindParam(":dept", $usuario["departamento"]);
                $stmt->execute();

                $carne = carneDeEmail($conexion, $usuario["email"]);

                //Email de bienvenida
                sendWelcomeMail($usuario["email"], $usuario["nombre"], $carne, $passwd);

                return true;
            }
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function consultarUsuario($conexion, $usuario, $password) {
        try {
            $carneValido = comprobarCarneValido($conexion, $usuario);
            if(!$carneValido) return false;
            if(filter_var($usuario, FILTER_VALIDATE_EMAIL)) {
                $consulta = "SELECT pass FROM USUARIOS WHERE email = :usuario";
            } else {
                $consulta = "SELECT pass FROM USUARIOS WHERE num_carne = :usuario";
            }
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":usuario", $usuario);
            $stmt->execute();
            $hash = $stmt->fetchColumn();
            return password_verify($password, $hash);
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function carneDeEmail($conexion, $email) {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        } else {
            try {
                $consulta = "SELECT num_carne FROM USUARIOS WHERE email = :em";
                $stmt = $conexion->prepare($consulta);
                $stmt->bindParam(":em", $email);
                $stmt->execute();
                $result = $stmt->fetchColumn();
                return $result;
            } catch(PDOException $e) {
                $_SESSION['excepcion'] = $e->GetMessage();
                header("Location: excepcion.php");
                die();
            }
        }
    }

    function cambiarPass($conexion, $usuario, $newPass) {
        try {
            $hash = password_hash($newPass, PASSWORD_DEFAULT);
            $consulta = "UPDATE USUARIOS SET pass = :newPass WHERE num_carne = :carne";
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":newPass", $hash);
            $stmt->bindParam(":carne", $usuario);
            $stmt->execute();
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
                header("Location: excepcion.php");
                die();
        }
    }

    function recuperarPass($con, $email, $dni) {
        try {
            $consulta = "SELECT COUNT(*) FROM USUARIOS WHERE email = :em AND dni = :dni";
            $stmt = $con->prepare($consulta);
            $stmt->bindParam(":em", $email);
            $stmt->bindParam(":dni", $dni);
            $stmt->execute();
            $result = $stmt->fetchColumn();
            return $result;
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function perfilUsuario($conexion, $usuario) {
        try {
            if(substr($usuario,5)=="A"){
                $consulta = "SELECT * FROM USUARIOS natural join alumnos WHERE num_carne = :usuario";
            } else {
                $consulta = "SELECT * FROM USUARIOS natural join profesores WHERE num_carne = :usuario";
            }
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":usuario", $usuario);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result;
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function cambiaTelefono($conexion, $usuario, $telefono){
        try {
            $consulta = "update USUARIOS set telefono = :telefono WHERE num_carne = :usuario";
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":usuario", $usuario);
            $stmt->bindParam(":telefono", $telefono);
            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function cambiaEmail($conexion, $usuario, $email){
        try {
            $consulta = "update USUARIOS set email = :email WHERE num_carne = :usuario";
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":usuario", $usuario);
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function cambiaFoto($conexion, $usuario, $foto){
        try {
            $stmt = $conexion->prepare(
                "update usuarios set foto = EMPTY_BLOB() where num_carne = ? RETURN foto INTO ?");
            $fp = fopen($_FILES[$foto]["tmp_name"], "rb");
            $stmt->bindParam(1, $usuario);
            $stmt->bindParam(2, $fp, PDO::PARAM_LOB);
            $conexion->beginTransaction();
            $stmt->execute();
            $conexion->commit();
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function esJefeDepartamento($conexion, $usuario){
        try {
            $stmt = $conexion->prepare(
                "select jefe_departamento from departamentos where jefe_departamento=:usuario");
            $stmt->bindParam(":usuario", $usuario);
            $stmt->execute();
            $result = $stmt->fetchColumn()!=null;
            return $result;
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }
    
    function setJefeDepartamento($conexion, $carne){
        try {
            $consulta = "CALL ASIGNAR_JEFE_DEPARTAMENTO(:carne)";
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":carne", $carne);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    function listaUsuarios($conexion,$ustype,$val,$busqueda,$page_num, $page_size){
        try {
            $s = "";
            if(isset($busqueda) && $busqueda!="" && $busqueda!=="0"){
                $s.="where ";
                $s.= "(NUM_CARNE like '%' || :busqueda || '%' or ";
                $s.= "APELLIDOS like '%' || :busqueda || '%' or ";
                $s.= "NOMBRE like '%' || :busqueda || '%' or ";
                $s.= "DNI like '%' || :busqueda || '%' or ";
                $s.= "EMAIL like '%' || :busqueda || '%') ";
                if($val=='yes' || $ustype=='alum' || $ustype=="prof"){
                    $s.="and ";
                }
            } else if($val=='yes' || $ustype=='alum' || $ustype=="prof"){
                $s.="where ";
            }
            if($val=='yes'){
                $s.="FECHA_VALIDEZ_CARNE>sysdate-1 ";
                if($ustype=='alum' || $ustype=="prof"){
                    $s.="and ";
                }
            }
            if($ustype=='alum'){
                $s.="substr(num_carne, 6) = 'A' ";
            }
            if($ustype=='prof'){
                $s.="substr(num_carne, 6) = 'P' ";
            }
            $consulta = "SELECT * FROM USUARIOS ".$s."ORDER BY APELLIDOS";

            $total = totalQuery($conexion, $consulta, "", "",$busqueda);
            $total_pages = ($total / $page_size);
            if( $total % $page_size > 0 ) $total_pages++; // resto de la división
            if( $page_num  > $total_pages ) $page_num = 1;

            $usuarios = paginatedQuery($conexion, $consulta, $page_num, $page_size, "", "",$busqueda);

            $result["total"] = $total;
            $result["total_pages"] = $total_pages;
            $result["usuarios"] = $usuarios;

            return $result;
        } catch (PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            Header("Location: excepcion.php");
            die();
        }
    }


    function existeUsuario($conexion, $usuario){
        try {
            $stmt = $conexion->prepare(
                "select * from usuarios where num_carne=:carne");
            $stmt->bindParam(":carne", $usuario);
            $stmt->execute();
            $result = $stmt->fetch();
            return empty($result);
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function entradaHorario($conexion,$carne,$dia,$tramo){
        try {
            $stmt = $conexion->prepare(
                "select * from horarios where num_carne=:carne and dia=:dia and tramo=:tramo");
            $stmt->bindParam(":carne", $carne);
            $stmt->bindParam(":dia", $dia);
            $stmt->bindParam(":tramo", $tramo);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result;
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        } 
    }

    function insertarEntradaHorario($con, $carne, $dia, $asignatura, $tramo) {
        try {
            $consulta = "INSERT INTO horarios VALUES(null, :carne, :dia, :asignatura, :tramo)";
            $stmt = $con->prepare($consulta);
            $stmt->bindParam(":carne", $carne);
            $stmt->bindParam(":dia", $dia);
            $stmt->bindParam(":asignatura", $asignatura);
            $stmt->bindParam(":tramo", $tramo);
            $stmt->execute();
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function actualizarEntradaHorario($con, $asignatura, $oid) {
        try {
            $consulta = "UPDATE horarios SET ASIGNATURA = :asignatura WHERE OID_H = :oidh";
            $stmt = $con->prepare($consulta);
            $stmt->bindParam(":asignatura", $asignatura);
            $stmt->bindParam(":oidh", $oid);
            $stmt->execute();
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function eliminarEntradaHorario($con, $oid) {
        try {
            $consulta = "DELETE FROM horarios WHERE OID_H = :oidh";
            $stmt = $con->prepare($consulta);
            $stmt->bindParam(":oidh", $oid);
            $stmt->execute();
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function bajaUsuario($con, $carne) {
        try {
            $consulta = 'UPDATE USUARIOS SET FECHA_VALIDEZ_CARNE = sysdate-1 WHERE NUM_CARNE = :carne';
            $stmt = $con->prepare($consulta);
            $date = date("d/m/Y");
            $stmt->bindParam(":fecha", $date);
            $stmt->bindParam(":carne", $carne);
            $stmt->execute();
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function renovarCarneUsuario($con, $carne) {
        try {
            $consulta = 'CALL RENUEVA_CARNE(:carne)';
            $stmt = $con->prepare($consulta);
            $stmt->bindParam(":carne", $carne);
            $stmt->execute();
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function promocionarAlumno($con, $carne, $programaAcademico, $curso, $grupo) {
        try {
            $consulta = 'CALL PROMOCIONAR_CURSO(:carne, :prog, :curso, :grupo)';
            $stmt = $con->prepare($consulta);
            $stmt->bindParam(":carne", $carne);
            $stmt->bindParam(":prog", $programaAcademico);
            $stmt->bindParam(":curso", $curso);
            $stmt->bindParam(":grupo", $grupo);
            $stmt->execute();
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function actualizarDatosUsuario($con, $carne, $datos) {
        try {
            $consulta = 'UPDATE USUARIOS SET NOMBRE = :nombre, APELLIDOS = :apellidos, DNI = :dni, '
            .'FECHANACIMIENTO = :fechanac, SEXO = :sexo, EMAIL = :email, TELEFONO = :telefono WHERE NUM_CARNE = :numcarne';
            $stmt = $con->prepare($consulta);
            $stmt->bindParam(":nombre", $datos["NOMBRE"]);
            $stmt->bindParam(":apellidos", $datos["APELLIDOS"]);
            $stmt->bindParam(":dni", $datos["DNI"]);
            $stmt->bindParam(":fechanac", date("d/m/Y", strtotime($datos["FECHANACIMIENTO"])));
            $stmt->bindParam(":sexo", $datos["SEXO"]);
            $stmt->bindParam(":email", $datos["EMAIL"]);
            $stmt->bindParam(":telefono", $datos["TELEFONO"]);
            $stmt->bindParam(":numcarne", $carne);
            $stmt->execute();

            if(substr($carne,5)=="A") {
                actualizaDatosAlumno($con, $carne, $datos);
            } else {
                actualizaDatosProfesor($con, $carne, $datos);
            }
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function actualizaDatosAlumno($con, $carne, $datos) {
        try {
            $consulta = 'UPDATE ALUMNOS SET PROGRAMA_ACADEMICO = :progacad, CURSO = :curso, GRUPO = :grupo WHERE NUM_CARNE = :numcarne';
            $stmt = $con->prepare($consulta);
            $stmt->bindParam(":progacad", $datos["PROGRAMA_ACADEMICO"]);
            $stmt->bindParam(":curso", $datos["CURSO"]);
            $stmt->bindParam(":grupo", $datos["GRUPO"]);
            $stmt->bindParam(":numcarne", $carne);
            $stmt->execute();
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function actualizaDatosProfesor($con, $carne, $datos) {
        try {
            $oid_d = obtenerOidDepartamento($con, $datos["departamento"]);
            $consulta = 'UPDATE PROFESORES SET OID_D = :oid_d WHERE NUM_CARNE = :numcarne';
            $stmt = $con->prepare($consulta);
            $stmt->bindParam(":oid_d", $oid_d);
            $stmt->bindParam(":numcarne", $carne);
            $stmt->execute();

            if($datos['jefe'] == 'SI') {
                setJefeDepartamento($con, $carne);
            } else {
                $consulta = 'UPDATE departamentos SET jefe_departamento = null WHERE oid_d = :oid_d';
                $stmt = $con->prepare($consulta);
                $stmt->bindParam(":oid_d", $oid_d);
                $stmt->execute();
            }
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function obtenerProfesoresDeDepartamento($con, $oid_d) {
        try {
            $consulta = "SELECT * FROM PROFESORES WHERE OID_D = :oid_d";
            $stmt = $con->prepare($consulta);
            $stmt->bindParam(":oid_d", $oid_d);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            Header("Location: excepcion.php");
            die();
        }
    }
    function quitaProfesorDeDepartamento($con, $carne) {
        try {
            $consulta = "UPDATE PROFESORES SET OID_D = null WHERE NUM_CARNE = :carne";
            $stmt = $con->prepare($consulta);
            $stmt->bindParam(":carne", $carne);
            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            Header("Location: excepcion.php");
            die();
        }
    }

    function comprobarCarneValido($con, $usuario) {
        try {
            $carne = carneDeEmail($con, $usuario);
            if($carne!='00000P' && $carne!='99999P'){
                $consulta = "CALL CARNE_VALIDO(:carne)";
                $stmt = $con->prepare($consulta);
                $stmt->bindParam(":carne", $carne);
                $stmt->execute();
            }
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