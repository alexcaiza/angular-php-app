<?php
require '../database.php';
require 'cuotasfunciones.php';

// Get the posted data.
$postdata = file_get_contents("php://input");

$response = [];

if(isset($postdata) && !empty($postdata))
{
  // Extract the data.
  $request = json_decode($postdata);
  $response_code = null;
  
  // Validate.
  if(!createVALIDATION($con, $request)) {
	$response['mensaje'] = "Existen campos vacios para registrar el deposito";
	$response_code = 400;    
  }
  else {
      $depositoSearch = buscarDepositoByNumeroDocumento($con, $request);
      if ($depositoSearch != null) {
          $response['status'] = 0;
          $response['mensaje'] = "El deposito con el numero ".sanitize($con, trim($request->numerodeposito))." ya esta registrado";
          $response_code = 400;
      }
      else {
          $response = createDEPOSITO($con, $request);
          
          if($response != null && $response['status'] == 1) {
              $response_code = 201;
          }
          else {
              $response_code = 422;
          }
      }   
  }

  $response['response_code'] = $response_code;
  
  echo json_encode($response);

  //return http_response_code($response_code);

}