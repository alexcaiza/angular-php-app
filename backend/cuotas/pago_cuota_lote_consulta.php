<?php
/**
 * Returns the list of cuotas.
 */
require '../database.php';
require 'cuotas_funciones.php';

$response = [];
$depositos = [];

$response['status'] = 0;

$codigolote = ($_GET['codigolote'] !== null && $_GET['codigolote'] != "") ? mysqli_real_escape_string($con, (int)$_GET['codigolote']) : false;
$codigocuota = ($_GET['codigocuota'] !== null && $_GET['codigocuota'] != "") ? mysqli_real_escape_string($con, (int)$_GET['codigocuota']) : false;

$response['codigolote'] = $codigolote;
$response['codigocuota'] = $codigocuota;

$where = " WHERE codigolote = '${codigolote}' AND codigocuota = '${codigocuota}' ";
$orderby = "";

if(!$codigolote || !$codigocuota)
{
    return http_response_code(400);
}

$sql = sqlPagoCuotaLoteSum($where, $orderby);

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
        //$data=mysql_fetch_assoc($resultset);
        $data=$resultset->fetch_assoc();
        
        if ($data) {
            $response['data'] = $data;
        } else {
            $response['mensaje'] = "No existe pagos de la cuota";
        }
        
        echo json_encode($response);
    }
    else {
        return http_response_code(404);
    }
}