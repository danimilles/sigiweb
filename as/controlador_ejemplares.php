<?php	
	session_start();
	
	if (isset($_REQUEST["CODIGO"])){
		$ejemplar["isbn"] = $_REQUEST["ISBN"];
		$ejemplar["estado"] = $_REQUEST["ESTADO"];
		$ejemplar["codigo"] = $_REQUEST["CODIGO"];

		$_SESSION["ejemplar_controlado"] = $ejemplar;

		if($ejemplar["isbn"] == "") {
			$_SESSION["excepcion"] = '<p>Los datos del ejemplar no son correctos.</p>';
			Header("Location: excepcion.php");
			die();
        } else {
            $isbnlen = strlen($ejemplar["isbn"]);
            if($isbnlen != 10 && $isbnlen != 14) {
                $_SESSION["excepcion"] = '<p>Los datos del ejemplar no son correctos.</p>';
				Header("Location: excepcion.php");
				die();
            } elseif($isbnlen == 10 && !preg_match("/^[0-9]{10}/", $ejemplar["isbn"])) {
                $_SESSION["excepcion"] = '<p>Los datos del ejemplar no son correctos.</p>';
				Header("Location: excepcion.php");
				die();
            } elseif($isbnlen == 14 && !preg_match("/^[0-9]{3}-[0-9]{10}/",$ejemplar["isbn"])) {
                $_SESSION["excepcion"] = '<p>Los datos del ejemplar no son correctos.</p>';
				Header("Location: excepcion.php");
				die();
            }
		}
		if($ejemplar["estado"] == "") {
            $_SESSION["excepcion"] = '<p>Los datos del ejemplar no son correctos.</p>';
			Header("Location: excepcion.php");
			die();
		}
		if(!filter_var($ejemplar["codigo"], FILTER_VALIDATE_INT)) {
			$_SESSION["excepcion"] = '<p>Los datos del ejemplar no son correctos.</p>';
			Header("Location: excepcion.php");
			die();
        }
		
        if (isset($_REQUEST["editar"])){
            Header("Location: accion_nuevo_ejemplar.php");
        }elseif (isset($_REQUEST["borrar"])){
            Header("Location: accion_baja_ejemplar.php");
        }else {
            unset($_SESSION["ejemplar_controlado"]); 
            Header("Location: ejemplares.php?isbnej=".$ejemplar["isbn"]);
        } 
	}
	else 
		Header("Location: ejemplares.php?isbnej=".$ejemplar["isbn"]);

?>