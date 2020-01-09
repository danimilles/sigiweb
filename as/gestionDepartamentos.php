<?php

/**
 * Este archivo contiene las funciones necesarias para recoger todos los datos necesarios
 * sobre los departamentos de la base de datos.
 * @author SIGIWEB
 */

    function todosLosDepartamentos($conexion) {
        try {
            $consulta = "SELECT * FROM DEPARTAMENTOS";
            $stmt = $conexion->query($consulta);
            return $stmt;
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }
    function obtenerDepartamento($conexion, $oid){
        try {
            $stmt = $conexion->prepare("SELECT NOMBRE FROM DEPARTAMENTOS WHERE OID_D=:oid_d");
            $stmt->bindParam(":oid_d", $oid);
            $stmt->execute();
            $result = $stmt->fetchColumn();
            return $result;
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }
    function obtenerOidDepartamento($conexion, $nombre){
        try {
            $stmt = $conexion->prepare("SELECT OID_D FROM DEPARTAMENTOS WHERE NOMBRE=:nombre");
            $stmt->bindParam(":nombre", $nombre);
            $stmt->execute();
            $result = $stmt->fetchColumn();
            return $result;
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function creaDepartamento($con, $departamento) {
        try {
            $consulta = "insert into departamentos (nombre,jefe_departamento) values (:nombre, null)";
            $stmt = $con->prepare($consulta);
            $stmt->bindParam(":nombre", $departamento["nombre"]);
            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            $_SESSION["excepcion"] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function eliminarDepartamento($con, $oid_d) {
        try {
            $consulta = "DELETE FROM DEPARTAMENTOS WHERE OID_D = :oid_d";
            $stmt = $con->prepare($consulta);
            $stmt->bindParam(":oid_d", $oid_d);
            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            Header("Location: excepcion.php");
            die();
        }
    }

    function editaDepartamento($con, $departamento) {
        try {
            $consulta = "UPDATE departamentos SET NOMBRE = :nombre WHERE OID_D = :oid_d";
            $stmt = $con->prepare($consulta);
            $stmt->bindParam(":nombre", $departamento["nombre"]);
            $stmt->bindParam(":oid_d", $departamento["oid_d"]);
            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            $_SESSION["excepcion"] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }
