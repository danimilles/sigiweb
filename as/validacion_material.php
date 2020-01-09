<?php

    session_start();
    include_once("gestionBD.php");
    include_once("gestionMateriales.php");
    include_once("gestionDepartamentos.php");
    if(isset($_REQUEST["descripcion"])) {
        $carne = $_SESSION["login"]["usuario"];
        $material["descripcion"] = $_REQUEST["descripcion"];
        $material["estado"] = $_REQUEST["estado"];
        $material["unidades"] = $_REQUEST["unidades"];
        $material["departamento"] = $_REQUEST["departamento"];
        if(isset($_SESSION["editando_material"])){
            $material["oid_m"] = $_REQUEST["oid_m"];
        }

        $_SESSION["material"] = $material;
        $errores = validacionErrores($material, $carne);
        if(count($errores) > 0) {
            $_SESSION["errores"] = $errores;
            Header("Location: accion_nuevo_material.php");
        }elseif(isset($_SESSION["editando_material"])){
            $con=crearConexionBD();
            $material["oid_d"] = obtenerOidDepartamento($con, $material["departamento"]);
            $edit = editarMaterial($con,$material);
            $_SESSION["exito_edit_material"]  = $edit;
            unset($_SESSION["material"]);
            unset($_SESSION["editando_material"]);
            unset($_SESSION["material_controlado"]);
            cerrarConexionBD($con);
            Header("Location: inventario_dept.php");
        }else{
            $new = true;
            $_SESSION["newm"] = $new;
            Header("Location: inventario_dept.php");
        }

    } else Header("Location: accion_nuevo_material.php");

    function validacionErrores($material, $carne) {
        $errores = array();
        if($material["descripcion"] == "") {
            array_push($errores, "<p>El campo 'Descripción' no puede estar vacío</p>");
        }
        if($material["estado"] == "") {
            array_push($errores, "<p>El campo 'Estado' no puede estar vacío</p>");
        }
        if($material["unidades"] == "") {
            array_push($errores, "<p>El campo 'Unidades' no puede estar vacío</p>");
        } else if(!filter_var($material["unidades"], FILTER_VALIDATE_INT)) {
            array_push($errores, "<p>Las unidades deben ser un número</p>");
        }
        if($carne == '00000P' && $material["departamento"] == "") {
            array_push($errores, "<p>El campo 'Departamento' no puede estar vacío</p>");
        }
        return $errores;
    }

?>