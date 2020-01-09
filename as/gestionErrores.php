<?php
/**
 * Este archivo contiene las funciones necesarias para gestionar los errores
 * posibles provocados por la base de datos.
 * @author SIGIWEB
 */
function mensajeError($errorCode)
{
    $result = "";
    switch ($errorCode) {
        case 20001:
            $result = "Un profesor no puede renovar un préstamo ya que no tiene fecha de devolución";
            break;
        case 20002:
            $result = 'El préstamo ya se ha renovado una vez';
            break;
        case 20003:
            $result = 'El formato del ISBN no es correcto';
            break;
        case 20004:
            $result = 'No se pueden tener prestados más de 3 ejemplares al mismo tiempo';
            break;
        case 20005:
            $result = 'El ejemplar no está disponible para ser prestado';
            break;
        case 20006:
            $result = 'El libro no puede darse de baja porque está prestado';
            break;
        case 20007:
            $result = 'El alumno no ha devuelto un préstamo pasado de fecha';
            break;
        case 20008:
            $result = 'El alumno está sancionado por no haber devuelto un préstamo';
            break;
        case 20009:
            $result = 'El carné del usuario ha vencido';
            break;
        case 20010:
            $result = 'No se puede realizar la reserva, ya que se está intentando reservar para un día anterior a la fecha de hoy';
            break;
        case 20011:
            $result = 'El material no se puede reservar porque ya ha sido reservado para el día y el tramo en el que se está intentando reservar';
            break;
        case 20012:
            $result = 'El aula no se puede reservar porque ya ha sido reservado para el día y el tramo en el que se está intentando reservar';
            break;
        case 20013:
            $result = 'El material no se puede dar de baja ya que está reservado';
            break;
        case 20015:
            $result = 'Un alumno no puede reservar materiales o aulas';
            break;
        default:
            $result = false;
    }
    return $result;
}

function comprobacionError($msg)
{
    $result = "";
    $trace = strstr($msg, "General error:");
    if ($trace != false) {
        $split = explode("OCIStmtExecute:", $trace);
        $split2 = explode(":", $split[0]);
        $err = trim($split2[1]);
        $errorCode = intval($err);
        if ($errorCode >= 20000 && $errorCode <= 20999) {
            $result = mensajeError($errorCode);
        } else $result = false;
    } else $result = false;
    return $result;
}
