<?php
/**
 * Returns the list of cuotas.
 */
require '../database.php';
require 'cuotas_funciones.php';
    
$cuotas = [];

$where = "";
$orderby = " ORDER BY CUO.descripcioncuota";

$textSearch = ($_GET['textSearch'] !== null && $_GET['textSearch'] != "") ? mysqli_real_escape_string($con, (string) $_GET['textSearch']) : false;

if(isset($textSearch) && !empty($textSearch) && $textSearch != "undefined") {
    $where = " WHERE CUO.descripcioncuota LIKE '%{$textSearch}%'";
}

$sql = sqlCuotas($where, $orderby);

//echo "$sql";

$resultset = mysqli_query($con, $sql) or die(mysql_error());

if($resultset)
{
  $i = 0;
  while($row = mysqli_fetch_assoc($resultset))
  {
      $cuotas[$i] = $row;
      $i++;   
  }
  echo json_encode($cuotas);
}
else {
  http_response_code(404);
}