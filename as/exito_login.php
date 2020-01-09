<?php
session_start();
include_once("gestionBD.php");
unset($_SESSION["num_carne"]);
if (!isset($_SESSION["login"])) {
    Header("Location: index.php");
} else {
    $conexion = crearConexionBD();
    $carne = $_SESSION["login"]["usuario"];
    if ($carne == '00000P') {
        Header("Location: inicioAdmin.php");
    } else if ($carne == '99999P') {
        Header("Location: inicioBibliotecario.php");
    } else if (substr($carne, 5) == 'A') {
        Header("Location: inicioAlumno.php");
    } else if (substr($carne, 5) == 'P') {
        Header("Location: inicioProfesor.php");
    } else {
        Header("Location: index.php");
    }
}
