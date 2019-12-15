<?php
/**
 * Returns the list of depositos.
 */
require '../database.php';
    
$depositos = [];
$sql = "";
$sql .= " SELECT ";
//$sql .= "   DEP.codigodeposito, DEP.numerodeposito, DEP.codigopersona, DEP.fechadeposito, DEP.valordeposito, ";
$sql .= "   PER.cedula, PER.primernombre, PER.segundonombre, PER.primerapellido, PER.segundoapellido ";
$sql .= " FROM DEPOSITOS DEP INNER JOIN PERSONAS PER ON PER.CODIGOPERSONA = DEP.CODIGOPERSONA";

//echo "sql: $sql";

$result = mysqli_query($con, $sql) or die(mysql_error());

if($result)
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
	  /*
	$depositos[$i]['codigodeposito'] = $row['codigodeposito'];
    $depositos[$i]['numerodeposito'] = $row['numerodeposito'];
    $depositos[$i]['codigopersona'] = $row['codigopersona'];
	$depositos[$i]['fechadeposito'] = $row['fechadeposito'];
	$depositos[$i]['valordeposito'] = $row['valordeposito'];
	*/
	$depositos[$i]['cedula'] = $row['cedula'];
	$depositos[$i]['primernombre'] = $row['primernombre'];
	$depositos[$i]['segundonombre'] = $row['segundonombre'];
	$depositos[$i]['primerapellido'] = $row['primerapellido'];
	$depositos[$i]['segundoapellido'] = $row['segundoapellido'];
    $i++;
  }
  echo json_encode($depositos);
}
else
{
  http_response_code(404);
}