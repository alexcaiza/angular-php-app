<?php

/**
 * Returns the list of cuotas.
 */
function sqlCuotas($where, $orderby) {
  $sql = "";
  $sql .= " SELECT ";
  $sql .= "   CUO.codigocuota, CUO.descripcioncuota, CUO.valorcuota, CUO.fechainicio, CUO.fechafin ";
  $sql .= " FROM cuotas CUO";
  $sql .= $where;
  $sql .= $orderby;
  return $sql;
}

/**
 * Returns the list of personas.
 */
function sqlPersonas($where, $orderby) {
  $sql = "";
  $sql .= " SELECT ";
  $sql .= "   codigopersona, primernombre, segundonombre, primerapellido, segundoapellido, cedula ";
  $sql .= " FROM PERSONAS";
  $sql .= $where;
  $sql .= $orderby;
  return $sql;
}

/**
 * Returns the list of lotes.
 */
function sqlLotesByPersona($where, $orderby) {
  $sql = "";
  $sql .= " SELECT ";
  $sql .= "   codigopersona, codigolote, codigoreferencia ";
  $sql .= " FROM LOTES";
  $sql .= $where;
  $sql .= $orderby;
  return $sql;
}

/**
 * Returns the list of lotes.
 */
function sqlDepositosByPersona($where, $orderby) {
  $sql = "";
  $sql .= " SELECT ";
  $sql .= "   codigopersona, codigodeposito, numerodeposito, valordeposito, fechadeposito ";
  $sql .= " FROM DEPOSITOS";
  $sql .= $where;
  $sql .= $orderby;
  return $sql;
}

function insertPagoCuotaLote($con, $request) {
  // Sanitize.
  $codigocuota = mysqli_real_escape_string($con, trim($request->codigocuota));
  $codigodeposito = mysqli_real_escape_string($con, trim($request->codigodeposito));
  $codigolote = mysqli_real_escape_string($con, trim($request->codigolote));
  $valorpagocuotalote = mysqli_real_escape_string($con, trim($request->valorpagocuotalote));

  $estado = "1";
  $ts = time();
  $fecharegistro = date('Y-m-d', $ts);
    
  // Store.
  $sql = "";
  $sql .= "INSERT INTO `PAGOCUOTALOTE`(`codigopagocuotalote`,`codigocuota`,`codigodeposito`,`codigolote`,`valorpagocuotalote`,`estado`,`fecharegistro`) ";
  $sql .= "VALUES (null,'{$codigocuota}','{$codigodeposito}','{$codigolote}','{$valorpagocuotalote}','{$estado}','{$fecharegistro}')";
  
  //echo "sql: $sql";
  $response = [];
  $response['status'] = 0;

  $response['sql'] = $sql;
  
  $resulset = mysqli_query($con, $sql);

  if (mysql_errno()) {
    $response['mysql_errno'] = mysql_errno($con);
    $response['mysql_error'] = mysql_error($con);
    $response['status'] = 0;
  } 
  else {
    if($resulset) {
      $pagocuotalote = [
          'codigopagocuotalote' => mysqli_insert_id($con),
          'codigocuota' => $codigocuota,
          'codigodeposito' => $codigodeposito,
          'codigolote' => $codigolote,
          'valorpagodeposito' => $valorpagocuotalote,
          'estado' => $estado,
          'fecharegistro' => $fecharegistro
      ];
      
      $response['pagocuotalote'] = $pagocuotalote;
      $response['status'] = 1;
    }
  }
  return $response;
}