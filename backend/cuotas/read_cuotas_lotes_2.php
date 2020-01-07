<?php
/**
 * Returns the list of cuotas.
 */
require '../database.php';
require 'cuotas_funciones.php';

$response = [];

$cuotas = [];

$response['status'] = 0;

// Get the posted data.
$postdata = file_get_contents("php://input");

$response = [];

try {
    if(isset($postdata) && !empty($postdata))
    {
        // Extract the data.
        $params = json_decode($postdata);
        
        // Sanitize.

        if(!$params) {
            return http_response_code(400);
        }
        
        $sql = sqlCuotasLotes2($params);
        
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
                    $cuotas[$i] = $row;
                    $i++;
                }
                
                if ($i <= 0) {
                    $response['mensaje'] = "No existe datos con los datos de busqueda";
                }
                
                $response['data'] = $cuotas;
                
                echo json_encode($response);
            }
            else {
                return http_response_code(404);
            }
        }
    }
} catch (Exception $e) {
    $response['status'] = 0;
    $response['mensaje'] = $e->getMessage();
}