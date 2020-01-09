<?php	
    session_start();
    include_once("gestionBD.php");
    include_once("gestionLibros.php");	
	
	if (isset($_SESSION["libro_controlado"])) {
		$libro = $_SESSION["libro_controlado"];
		unset($_SESSION["libro_controlado"]);
        $errores = validacionErrores($libro);
        if(count($errores) > 0) {
            $_SESSION["baja_libro_exito"] = false;
            Header("Location: catalogo_libros.php");
        }
		$conexion = crearConexionBD();		
        $ejemplaresDelLibro = consultaEjemplaresSinPag($conexion,$libro["isbn"]);
        $exito = true;
        foreach($ejemplaresDelLibro as $ejemplar){
            $borrado = bajaEjemplar($conexion, $ejemplar["CODIGO"]);
            if(!$borrado){
                $exito = false;
            }
        }
		cerrarConexionBD($conexion);
			
        $_SESSION["baja_libro_exito"] = $exito;
        Header("Location: catalogo_libros.php");
	}
    else Header("Location: catalogo_libros.php");
    
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
        if($book["num_copias"] == "") {
            array_push($errores, "<p>El campo 'Número de copias' no puede estar vacío</p>");
        } elseif(!filter_var($book["num_copias"], FILTER_VALIDATE_INT)) {
            array_push($errores, "<p>El número de copias debe ser un número</p>");
        }
        return $errores;
    }
?>