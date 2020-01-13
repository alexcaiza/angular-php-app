<?php
/**
 * Returns the list of depositos.
 */
require '../database.php';
require 'depositosfunciones.php';

$postdata = file_get_contents("php://input");
    
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
}

$sql = sqlDEPOSITOS($where, $orderby);

//echo "sql: $sql";

$resultset = mysqli_query($con, $sql) or die(mysql_error());

if($resultset)
{
  $i = 0;
  while($row = mysqli_fetch_assoc($resultset))
  {
      $depositos[$i] = $row;
	 /* 
	$depositos[$i]['codigodeposito'] = $row['codigodeposito'];
    $depositos[$i]['numerodeposito'] = $row['numerodeposito'];
    $depositos[$i]['codigopersona'] = $row['codigopersona'];
	$depositos[$i]['fechadeposito'] = $row['fechadeposito'];
	$depositos[$i]['valordeposito'] = $row['valordeposito'];
	$depositos[$i]['tipodeposito'] = $row['tipodeposito'];
	
	$depositos[$i]['cedula'] = $row['cedula'];
	$depositos[$i]['primernombre'] = $row['primernombre'];
	$depositos[$i]['segundonombre'] = $row['segundonombre'];
	$depositos[$i]['primerapellido'] = $row['primerapellido'];
	$depositos[$i]['segundoapellido'] = $row['segundoapellido'];
	*/
	
    $i++;   
  }
  echo json_encode($depositos);
}
else {
  http_response_code(404);
}