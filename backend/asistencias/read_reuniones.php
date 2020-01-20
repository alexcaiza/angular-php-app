<?php
/**
 * Returns the list of cuotas.
 */
require '../database.php';
require 'funciones_asistencias.php';
    
$reuniones = [];

$where = " WHERE R.estado = '1' ";
$orderby = " ORDER BY R.ordenreunion";

$textSearch = "";

if (isset($_GET['textSearch'])) {
    $textSearch = ($_GET['textSearch'] !== null && $_GET['textSearch'] != "") ? mysqli_real_escape_string($con, (string) $_GET['textSearch']) : false;
}

if(isset($textSearch) && !empty($textSearch) && $textSearch != "undefined" && $textSearch != "") {
    $where = " AND R.nombrereunion LIKE '%{$textSearch}%'";
}

$sql = sqlReuniones($where, $orderby);

//echo "$sql";

$resultset = mysqli_query($con, $sql) or die(mysql_error());

if($resultset)
{
  $i = 0;
  while($row = mysqli_fetch_assoc($resultset))
  {
      $reuniones[$i] = $row;
      $i++;   
  }
  echo json_encode($reuniones);
}
else {
  http_response_code(404);
}