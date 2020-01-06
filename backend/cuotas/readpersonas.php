<?php
/**
 * Returns the list of cuotas.
 */
require '../database.php';
require 'cuotas_funciones.php';
    
$personas = [];

$where = "";
$orderby = "";

$sql = sqlPersonas($where, $orderby);

//echo "$sql";

$resultset = mysqli_query($con, $sql) or die(mysql_error());

if($resultset)
{
  $i = 0;
  while($row = mysqli_fetch_assoc($resultset))
  {
      $personas[$i] = $row;
      $i++;   
  }
  echo json_encode($personas);
}
else {
  http_response_code(404);
}