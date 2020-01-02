<?php
/**
 * Returns the list of cuotas.
 */
require '../database.php';
require 'cuotasfunciones.php';

$response = [];
$lotes = [];

$response['status'] = 0;

$codigopersona = ($_GET['codigopersona'] !== null && $_GET['codigopersona'] != "") ? mysqli_real_escape_string($con, (int)$_GET['codigopersona']) : false;

$response['codigopersona'] = $codigopersona;

$orderby = " ORDER BY codigoreferencia";
$where = " WHERE codigopersona = '${codigopersona}'";

if(!$codigopersona)
{
  //echo json_encode($response);
  //return http_response_code(400);
  $where = "";
}

$sql = sqlLotesByPersona($where, $orderby);

//echo "$sql";

$resultset = mysqli_query($con, $sql) or die(mysql_error());

if($resultset)
{
  $i = 0;
  while($row = mysqli_fetch_assoc($resultset))
  {
      $lotes[$i] = $row;
      $i++;   
  }
  echo json_encode($lotes);
}
else {
  http_response_code(404);
}