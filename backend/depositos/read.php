<?php
/**
 * Returns the list of depositos.
 */
require '../database.php';
require 'depositosfunciones.php';

$postdata = file_get_contents("php://input");
    
$response = [];
$response['status'] = 0;

$depositos = [];

$where = "";
$orderby = " ORDER BY DEP.fechadeposito DESC";

if(isset($postdata) && !empty($postdata)) {
    // Extract the data.
    $params = json_decode($postdata);
}

$where = " WHERE 1=1 ";

if (isset($params)) {
    if (isset($params->primernombre) && $params->primernombre) {
        $where .= " AND PER.PRIMERNOMBRE LIKE '%{$params->primernombre}%'";
    }
    
    if (isset($params->primerapellido) && $params->primerapellido) {
        $where .= " AND PER.PRIMERAPELLIDO LIKE '%{$params->primerapellido}%'";
    }
    
    if (isset($params->cedula) && $params->cedula) {
        $where .= " AND PER.CEDULA LIKE '%{$params->cedula}%'";
    }
    
    if (isset($params->numerodeposito) && $params->numerodeposito) {
        $where .= " AND DEP.numerodeposito LIKE '%{$params->numerodeposito}%'";
    }
}

$sql = sqlDEPOSITOS($where, $orderby);

$response['sql'] = $sql;

//echo "sql: $sql";

$resultset = mysqli_query($con, $sql) or die(mysql_error());

if($resultset) {
    $response['status'] = 1;
    $i = 0;
    while($row = mysqli_fetch_assoc($resultset)) {
      $depositos[$i] = $row;
      $i++;   
    }
    $response['depositos'] = $depositos;
    
    echo json_encode($response);
}
else {
  http_response_code(404);
}