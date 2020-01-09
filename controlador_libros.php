<?php
session_start();

if (isset($_REQUEST["ISBN"])) {
    $libro["isbn"] = $_REQUEST["ISBN"];
    $libro["titulo"] = $_REQUEST["TITULO"];
    $libro["autor"] = $_REQUEST["AUTOR"];
    $libro["cdu"] = $_REQUEST["CDU"];
    $libro["genero"] = $_REQUEST["GENERO"];
    $libro["num_copias"] = $_REQUEST["COPIAS"];

    $_SESSION["libro_controlado"] = $libro;

    if ($libro["titulo"] == "") {
        $_SESSION["excepcion"] = '<p>Los datos del libro no son correctos.</p>';
        Header("Location: excepcion.php");
        die();
    }
    if ($libro["autor"] == "") {
        $_SESSION["excepcion"] = '<p>Los datos del libro no son correctos.</p>';
        Header("Location: excepcion.php");
        die();
    }
    if ($libro["isbn"] == "") {
        $_SESSION["excepcion"] = '<p>Los datos del libro no son correctos.</p>';
        Header("Location: excepcion.php");
        die();
    } else {
        $isbnlen = strlen($libro["isbn"]);
        if ($isbnlen != 10 && $isbnlen != 14) {
            $_SESSION["excepcion"] = '<p>Los datos del libro no son correctos.</p>';
            Header("Location: excepcion.php");
            die();
        } elseif ($isbnlen == 10 && !preg_match("/^[0-9]{10}/", $libro["isbn"])) {
            $_SESSION["excepcion"] = '<p>Los datos del libro no son correctos.</p>';
            Header("Location: excepcion.php");
            die();
        } elseif ($isbnlen == 14 && !preg_match("/^[0-9]{3}-[0-9]{10}/", $libro["isbn"])) {
            $_SESSION["excepcion"] = '<p>Los datos del libro no son correctos.</p>';
            Header("Location: excepcion.php");
            die();
        }
    }
    if ($libro["cdu"] == "") {
        $_SESSION["excepcion"] = '<p>Los datos del libro no son correctos.</p>';
        Header("Location: excepcion.php");
        die();
    } elseif (!filter_var($libro["cdu"], FILTER_VALIDATE_INT)) {
        $_SESSION["excepcion"] = '<p>Los datos del libro no son correctos.</p>';
        Header("Location: excepcion.php");
        die();
    }
    if ($libro["genero"] == "") {
        $_SESSION["excepcion"] = '<p>Los datos del libro no son correctos.</p>';
        Header("Location: excepcion.php");
        die();
    }
    if ($libro["num_copias"] == "") {
        $_SESSION["excepcion"] = '<p>Los datos del libro no son correctos.</p>';
        Header("Location: excepcion.php");
        die();
    }
    if (isset($_REQUEST["editar"])) {
        Header("Location: accion_nuevo_libro.php");
    } elseif (isset($_REQUEST["borrar"])) {
        Header("Location: accion_baja_libro.php");
    } else {
        unset($_SESSION["libro_controlado"]);
        Header("Location: catalogo_libros.php");
    }
} else Header("Location: catalogo_libros.php"); ?>
