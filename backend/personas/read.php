<?php
/**
 * Returns the list of depositos.
 */
require '../database.php';
    
$personas = [];
$sql = "";
$sql .= " SELECT ";
$sql .= "   codigopersona, primernombre, segundonombre, primerapellido, segundoapellido, cedula ";
$sql .= " FROM PERSONAS";

//echo "sql: $sql";

$resultset = mysqli_query($con, $sql) or die(mysql_error());

if($resultset)
{
  $i = 0;
  while($row = mysqli_fetch_assoc($resultset))
  {
    $personas[$i]['codigopersona']    = $row['codigopersona'];
    $personas[$i]['primernombre'] = $row['primernombre'];
    $personas[$i]['segundonombre'] = $row['segundonombre'];
	$personas[$i]['primerapellido'] = $row['primerapellido'];
	$personas[$i]['segundoapellido'] = $row['segundoapellido'];
	$personas[$i]['cedula'] = $row['cedula'];
    $i++;
  }
    
  echo json_encode($personas);
}
else
{
  http_response_code(404);
}