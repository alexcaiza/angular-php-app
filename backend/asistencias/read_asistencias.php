<?php
/**
 * Returns the list of asistencias.
 */
require '../database.php';
require 'funciones_asistencias.php';

$response = [];

$reuniones = [];
$params = null;

$response['status'] = 0;

// Get the posted data.
$postdata = file_get_contents("php://input");

try {
    
    if(isset($postdata) && !empty($postdata)) {
        // Extract the data.
        $params = json_decode($postdata);
    }
        
    $sql = sqlReunionesLotes($params);
    
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
                $reuniones[$i] = $row;
                $i++;
            }
            
            if ($i <= 0) {
                $response['mensaje'] = "No existe registros con los datos de busqueda";
            }
            
            $response['data'] = $reuniones;
        }
        else {
            return http_response_code(404);
        }
    }
    
} catch (Exception $e) {
    $response['status'] = 0;
    $response['mensaje'] = $e->getMessage();
}

echo json_encode($response);