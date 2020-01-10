<?php
/**
 * Returns the list of cuotas.
 */
require '../database.php';
require 'cuotas_funciones.php';

$response = [];
$depositos = [];

$response['status'] = 0;

$codigopersona = ($_GET['codigopersona'] !== null && $_GET['codigopersona'] != "") ? mysqli_real_escape_string($con, (int)$_GET['codigopersona']) : false;

$response['codigopersona'] = $codigopersona;

$orderby = " ORDER BY D.fechadeposito, D.numerodeposito, D.valordeposito";
$where = " WHERE DP.codigopersona = '${codigopersona}' AND D.numerodeposito <> '' ";

if(!$codigopersona)
{
  //echo json_encode($response);
  //return http_response_code(400);
  $where = " WHERE D.numerodeposito <> ''";
}

$sql = sqlDepositosByPersona($where, $orderby);

//echo "$sql";

$resultset = mysqli_query($con, $sql) or die(mysql_error());

if($resultset)
{
  $i = 0;
  while($row = mysqli_fetch_assoc($resultset))
  {
      if (empty($row['valorutilizado'])) {
          $row['valorutilizado'] = 0;
      }
      $depositos[$i] = $row;
      $i++;   
  }
  echo json_encode($depositos);
}
else {
  http_response_code(404);
}