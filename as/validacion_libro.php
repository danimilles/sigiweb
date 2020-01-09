<?php

    session_start();

    if(isset($_SESSION["book"]) || isset($_SESSION["libro_controlado"])) {
        $libro["titulo"] = $_REQUEST["titulo"];
        $libro["autor"] = $_REQUEST["autor"];
        $libro["isbn"] = $_REQUEST["isbn"];
        $libro["cdu"] = $_REQUEST["cdu"];
        $libro["genero"] = $_REQUEST["genero"];
        $libro["estado"] = $_REQUEST["estado"];
        $libro["num_copias"] = $_REQUEST["num_copias"];
        
        if(isset($_SESSION["book"])) $_SESSION["book"] = $libro;
        else if (isset($_SESSION["libro_controlado"])) $_SESSION["libro_controlado"] = $libro;
        $errores = validacionErrores($libro);
        if(count($errores) > 0) {
            $_SESSION["errores"] = $errores;
            Header("Location: accion_nuevo_libro.php");
        } else {
            if(isset($_SESSION["book"])&&!isset($_SESSION["editando"])){
                $new = true;
                $_SESSION["newb"] = $new;    
            } else if (isset($_SESSION["editando"])){
                $new = true;
                $_SESSION["editb"] = $new;  
            }
            Header("Location: catalogo_libros.php");}

    } else Header("Location: accion_nuevo_libro.php");

    function validacionErrores($book) {
        $errores = array();
        if($book["titulo"] == "") {
            array_push($errores, "<p>El campo 'Título' no puede estar vacío</p>");
        }
        if($book["autor"] == "") {
            array_push($errores, "<p>El campo 'Autor' no puede estar vacío</p>");
        }
        if($book["isbn"] == "") {
            array_push($errores, "<p>El campo 'ISBN' no puede estar vacío</p>");
        } else {
            $isbnlen = strlen($book["isbn"]);
            if($isbnlen != 10 && $isbnlen != 14) {
                array_push($errores, "<p>El ISBN debe tener el formato de ISBN-10(1234567890) o ISBN-13(123-1234567890)</p>");
            } elseif($isbnlen == 10 && !preg_match("/^[0-9]{10}/", $book["isbn"])) {
                array_push($errores, "<p>El ISBN-10 debe tener formato 1234567890</p>");
            } elseif($isbnlen == 14 && !preg_match("/^[0-9]{3}-[0-9]{10}/",$book["isbn"])) {
                array_push($errores, "<p>El ISBN-13 debe tener formato 123-1234567890</p>");
            }
        }
        if($book["cdu"] == "") {
            array_push($errores, "<p>El campo 'CDU' no puede estar vacío</p>");
        } elseif(!filter_var($book["cdu"], FILTER_VALIDATE_INT)) {
            array_push($errores, "<p>El CDU debe ser un número</p>");
        }
        if($book["genero"] == "") {
            array_push($errores, "<p>El campo 'Género' no puede estar vacío</p>");
        }
        if(!isset($_SESSION["editando"])){
            if($book["estado"] == "") {
                array_push($errores, "<p>El campo 'Estado' no puede estar vacío</p>");
            }
        }
        if(!isset($_SESSION["editando"])){
            if($book["num_copias"] == "") {
                array_push($errores, "<p>El campo 'Número de copias' no puede estar vacío</p>");
            } elseif(!filter_var($book["num_copias"], FILTER_VALIDATE_INT)) {
                array_push($errores, "<p>El número de copias debe ser un número</p>");
            }
        }
        return $errores;
    }

?>