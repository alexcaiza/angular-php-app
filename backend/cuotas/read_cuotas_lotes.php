<?php
/**
 * Returns the list of cuotas.
 */
require '../database.php';
require 'cuotas_funciones.php';

$response = [];

$cuotas = [];

$response['status'] = 0;

$codigocuota = ($_GET['codigocuota'] !== null && $_GET['codigocuota'] != "") ? mysqli_real_escape_string($con, (int)$_GET['codigocuota']) : false;

$response['codigocuota'] = $codigocuota;


if(!$codigocuota)
{
    return http_response_code(400);
}

$sql = sqlCuotasLotes($codigocuota);

//echo "$sql";
$response['sql'] = $sql;

$resultset = mysqli_query($con, $sql) or die(mysql_error());

if (mysql_errno()) {
    $response['mysql_errno'] = mysql_errno($con);
    $response['mysql_error'] = mysql_error($con);
    $response['status'] = 0;
} 
else {
    $response['status'] = 1;
    if($resultset)
    {
        $i = 0;
        while($row = mysqli_fetch_assoc($resultset))
        {
            $cuotas[$i] = $row;
            $i++;
        }
        
        if ($i <= 0) {
            $response['mensaje'] = "No existe cuotas con el codigocuota '${codigocuota}'";
        }
        
        $response['data'] = $cuotas;
        
        echo json_encode($response);
    }
    else {
        return http_response_code(404);
    }
}