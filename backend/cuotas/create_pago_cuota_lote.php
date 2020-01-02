<?php
require '../database.php';
require 'cuotasfunciones.php';

// Get the posted data.
$postdata = file_get_contents("php://input");

$response = [];

try {
    if(isset($postdata) && !empty($postdata))
    {
        // Extract the data.
        $request = json_decode($postdata);
        $response_code = null;
      
        // Validate.
      
        $response = insertPagoCuotaLote($con, $request);
        
        if($response != null && $response['status'] == 1) {
            $response_code = 201;
        }
        else {
            $response_code = 422;
        }
    
        $response['response_code'] = $response_code;
      
        echo json_encode($response);
    
      //return http_response_code($response_code);
    
    }
} catch (Exception $e) {
    $response['status'] = 0;
    $response['mensaje'] = $e->getMessage();    
}