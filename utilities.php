<?php

    function paginatedQuery($con, $query, $page_num, $page_size, $num_carne, $oid_d, $busqueda) {
       try {
            $first = ($page_num - 1) * $page_size + 1;
            $last = $page_num * $page_size;
            $paged_query = "SELECT * FROM ( ".
            "SELECT ROWNUM RNUM, AUX.* FROM ( ".
            $query . " ) ".
            "AUX WHERE ROWNUM <= :last ) ".
            "WHERE RNUM >= :first";
            $stmt = $con->prepare( $paged_query );
            $stmt->bindParam( ':first', $first );
            $stmt->bindParam( ':last', $last );
            if(!empty($num_carne) && $num_carne != '00000P' && $num_carne != '99999P') $stmt->bindParam( ':carne', $num_carne );
            if(!empty($oid_d)) $stmt->bindParam( ':oid_d', $oid_d );
            if(!empty($busqueda)) $stmt->bindParam( ':busqueda', $busqueda );
            $stmt->execute();   
            return $stmt;
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function totalQuery( $conn, $query, $num_carne, $oid_d, $busqueda) {
        try {
            $total_query = "SELECT COUNT(*) AS TOTAL FROM ($query)";
            $stmt = $conn->prepare( $total_query );
            if(!empty($num_carne) && $num_carne != '00000P' && $num_carne != '99999P') $stmt->bindParam( ':carne', $num_carne );
            if(!empty($oid_d)) $stmt->bindParam( ':oid_d', $oid_d );
            if(!empty($busqueda)) $stmt->bindParam( ':busqueda', $busqueda );
            $stmt->execute();
            $result = $stmt->fetch();
            $total = $result['TOTAL'];
            return (int) $total;
        } catch(PDOException $e) {
            $_SESSION['excepcion'] = $e->GetMessage();
            header("Location: excepcion.php");
            die();
        }
    }

    function paginatedQueryEj($con, $query, $page_num, $page_size, $isbn,$busqueda) {
        try {
             $first = ($page_num - 1) * $page_size + 1;
             $last = $page_num * $page_size;
             $paged_query = "SELECT * FROM ( ".
             "SELECT ROWNUM RNUM, AUX.* FROM ( ".
             $query . " ) ".
             "AUX WHERE ROWNUM <= :last ) ".
             "WHERE RNUM >= :first";
             $stmt = $con->prepare( $paged_query );
             $stmt->bindParam( ':first', $first );
             $stmt->bindParam( ':last', $last );
             $stmt->bindParam( ':isbn', $isbn );
             if(!empty($busqueda)) $stmt->bindParam( ':busqueda', $busqueda );
             $stmt->execute();
             return $stmt;
         } catch(PDOException $e) {
             $_SESSION['excepcion'] = $e->GetMessage();
             header("Location: excepcion.php");
             die();
         }
     }
 
     function totalQueryEj( $conn, $query, $isbn,$busqueda) {
         try {
             $total_query = "SELECT COUNT(*) AS TOTAL FROM ($query)";
             $stmt = $conn->prepare( $total_query );
             $stmt->bindParam( ':isbn', $isbn );
             if(!empty($busqueda)) $stmt->bindParam( ':busqueda', $busqueda );
             $stmt->execute();
             $result = $stmt->fetch();
             $total = $result['TOTAL'];
             return (int) $total;
         } catch(PDOException $e) {
             $_SESSION['excepcion'] = $e->GetMessage();
             header("Location: excepcion.php");
             die();
         }
     }
?>