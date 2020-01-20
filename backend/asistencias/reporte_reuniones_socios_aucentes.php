<?php
/**
 * Returns the list of asistencias.
 */
require '../database.php';
require 'funciones_asistencias.php';

$response = [];

$asistencias = [];

$params = null;

$response['status'] = 0;

$where = " WHERE 1=1 ";

$orderby = " ORDER BY p2.primerapellido, p2.primernombre, t2.ordenreunion ";

// Get the posted data.
$postdata = file_get_contents("php://input");

$totalizado = false;

try {
    
    if(isset($postdata) && !empty($postdata)) {
        // Extract the data.
        $params = json_decode($postdata);
        
        if (isset($params)) {
            
            // WHERE
            if (isset($params->codigocuota) && $params->codigocuota) {
                $where .= " AND t2.CODIGOCUOTA = '{$params->codigocuota}'";
            }
            if (isset($params->codigolote) && $params->codigolote) {
                $where .= " AND t2.CODIGOLOTE = '{$params->codigolote}'";
            }
            if (isset($params->codigopersona) && $params->codigopersona) {
                $where .= " AND t2.CODIGOPERSONA = '{$params->codigopersona}'";
            }
            if (isset($params->primerapellido) && $params->primerapellido) {
                $params->primerapellido = strtoupper($params->primerapellido);
                $where .= " AND p2.PRIMERAPELLIDO LIKE '%{$params->primerapellido}%'";
            }
            if (isset($params->primernombre) && $params->primernombre) {
                $params->primernombre = strtoupper($params->primernombre);
                $where .= " AND p2.PRIMERNOMBRE LIKE '%{$params->primernombre}%'";
            }
            
            // ORDER BY
            if (isset($params->totalizado) && $params->totalizado == true) {
                $totalizado = true;
            }
        }
    }
    
    $where .= " AND (a2.valorasistencia = 'A' or a2.valorasistencia is null)";
    
    if ($totalizado == true) {
        //ORDER BY
        $orderby = " ORDER BY p2.primerapellido, p2.primernombre";
        //SQL
        $sql = sqlReporteReunionesSociosAusentesTotalizado($where, $orderby);
    } else {
        //ORDER BY
        $orderby = " ORDER BY p2.primerapellido, p2.primernombre, t2.ordenreunion ";
        //SQL
        $sql = sqlReporteReunionesSociosAusentes($where, $orderby);
    }
    
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
                $asistencias[$i] = $row;
                $i++;
            }
            
            if ($i <= 0) {
                $response['mensaje'] = "No existe registros con los datos de busqueda";
            }
            
            $response['data'] = $asistencias;
        }
        else {
            return http_response_code(404);
        }
    }
    
} catch (Exception $e) {
    $response['status'] = 0;
    $response['mensaje'] = $e->getMessage();
}

echo json_encode($response);