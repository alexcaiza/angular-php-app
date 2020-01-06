<?php
/**
 * Returns the list of games.
 */
require '../database.php';

$cedula = ($_GET['cedula'] !== null && $_GET['cedula'] !== "") ? mysqli_real_escape_string($con, $_GET['cedula']) : false;

if(!$cedula)
{
  return http_response_code(400);
}

$personas = [];    
$persona = null;
$sql = "SELECT codigopersona, primernombre, segundonombre, primerapellido, segundoapellido, cedula FROM personas WHERE `cedula` ='{$cedula}'";

//echo "<br> cedula: $cedula";
//echo "<br> sql: $sql";

if($resultset = mysqli_query($con, $sql))
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
  
  if (count($personas) > 0) {
	  $persona = $personas[0];
  }
    
  echo json_encode($persona);
}
else
{
  http_response_code(404);
}