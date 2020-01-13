<?php
/**
 * Returns the list of cuotas.
 */
require '../database.php';
require 'cuotas_funciones.php';

$response = [];

$personas = [];

$where = "";
$orderby = " ORDER BY P.PRIMERAPELLIDO ";

$response['status'] = 0;

$postdata = file_get_contents("php://input");

try {
    $params = null;
    
    if(isset($postdata) && !empty($postdata)) {
        // Extract the data.
        $params = json_decode($postdata);
    }
        
    $where = " WHERE 1=1 ";
    
    if (isset($params)) {
        if (isset($params->primernombre) && $params->primernombre) {
            $where .= " AND P.PRIMERNOMBRE LIKE '%{$params->primernombre}%'";
        }
        
        if (isset($params->primerapellido) && $params->primerapellido) {
            $where .= " AND P.PRIMERAPELLIDO LIKE '%{$params->primerapellido}%'";
        }
        
        if (isset($params->cedula) && $params->cedula) {
            $where .= " AND P.CEDULA LIKE '%{$params->cedula}%'";
        }
    }
    
    $sql = sqlPersonas($where, $orderby);
    
    //echo "$sql";
    $response['sql'] = $sql;
    
    $resultset = mysqli_query($con, $sql) or die(mysql_error());
    
    if (mysql_errno()) {
        $response['mysql_errno'] = mysql_errno($con);
        $response['mysql_error'] = mysql_error($con);
        $response['status'] = 0;
    }
    else {
        $response['status'] = 1;
        if($resultset)
        {
            $i = 0;
            while($row = mysqli_fetch_assoc($resultset))
            {
                $personas[$i] = $row;
                $i++;
            }
            
            if ($i <= 0) {
                $response['mensaje'] = "No existe datos con los datos de busqueda";
            }
            
            $response['data'] = $personas;
        }
        else {
            return http_response_code(404);
        }
    }
    
} catch (Exception $e) {
    $response['status'] = 0;
    $response['mensaje'] = $e->getMessage();
}

echo json_encode($personas);