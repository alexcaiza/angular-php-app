<?php

/**
 * Returns the list of cuotas.
 */
function sqlCuotas($where, $orderby) {
  $sql = "";
  $sql .= " SELECT ";
  $sql .= "   CUO.codigocuota, CUO.descripcioncuota, CUO.valorcuota, CUO.fechainicio, CUO.fechafin, CUO.ordencuota ";
  $sql .= " FROM cuotas CUO";
  $sql .= $where;
  $sql .= $orderby;
  return $sql;
}

/**
 * Returns the list of cuotas de cada uno de los lotes.
 */
function sqlCuotasLotes($codigocuota) {
    $sql = "";
    $sql .= " SELECT ";
    $sql .= "   T1.codigolote, T1.codigoreferencia, T1.descripcioncuota, T1.codigocuota, T1.valorcuota, T1.ordencuota, ";
    $sql .= "   S.codigopersona, S.cedula, S.primerapellido, S.segundoapellido, S.primernombre, S.segundonombre, ";
    $sql .= "   sum(P.valorpagocuotalote) as valorpagocuotalote ";
    $sql .= " FROM";
    $sql .= "   (select L.codigolote, L.codigoreferencia, L.codigopersona, C.descripcioncuota, C.codigocuota, C.valorcuota, C.ordencuota from LOTES L, cuotas C ) AS T1";
    $sql .= "   LEFT JOIN pagocuotalote P ON P.codigocuota = T1.codigocuota and P.codigolote = T1.codigolote";
    $sql .= "   LEFT JOIN personas S ON S.codigopersona = T1.codigopersona";
    $sql .= " WHERE T1.CODIGOCUOTA = '${codigocuota}'";
    $sql .= " GROUP BY";
    $sql .= "   T1.codigolote, T1.codigoreferencia, T1.descripcioncuota, T1.codigocuota, T1.valorcuota, T1.ordencuota, ";
    $sql .= "   S.codigopersona, S.cedula, S.primerapellido, S.segundoapellido, S.primernombre, S.segundonombre ";
    $sql .= " ORDER BY";
    $sql .= "   S.primerapellido, S.segundoapellido, S.primernombre, S.segundonombre, T1.codigoreferencia, T1.ordencuota";
    return $sql;
}

/**
 * Returns the list of cuotas de cada uno de los lotes.
 */
function sqlCuotasLotes2($params) {
    $sql = "";
    $sql .= " SELECT ";
    $sql .= "   T1.codigolote, T1.codigoreferencia, T1.descripcioncuota, T1.codigocuota, T1.valorcuota, T1.ordencuota, ";
    $sql .= "   S.codigopersona, S.cedula, S.primerapellido, S.segundoapellido, S.primernombre, S.segundonombre, ";
    $sql .= "   sum(P.valorpagocuotalote) as valorpagocuotalote ";
    $sql .= " FROM";
    $sql .= "   (select L.codigolote, L.codigoreferencia, L.codigopersona, C.descripcioncuota, C.codigocuota, C.valorcuota, C.ordencuota from LOTES L, cuotas C ) AS T1";
    $sql .= "   LEFT JOIN pagocuotalote P ON P.codigocuota = T1.codigocuota and P.codigolote = T1.codigolote";
    $sql .= "   LEFT JOIN personas S ON S.codigopersona = T1.codigopersona";
    $sql .= " WHERE 1=1";
    if (isset($params->codigocuota) && $params->codigocuota) {
        $sql .= " AND T1.CODIGOCUOTA = '{$params->codigocuota}'";
    }
    if (isset($params->codigolote) && $params->codigolote) {
        $sql .= " AND T1.CODIGOLOTE = '{$params->codigolote}'";
    }
    if (isset($params->codigopersona) && $params->codigopersona) {
        $sql .= " AND S.CODIGOPERSONA = '{$params->codigopersona}'";
    }
    if (isset($params->apellidosocio) && $params->apellidosocio) {
        $params->apellidosocio = strtoupper($params->apellidosocio);
        $sql .= " AND S.PRIMERAPELLIDO LIKE '%{$params->apellidosocio}%'";
    }
    if (isset($params->nombresocio) && $params->nombresocio) {
        $params->nombresocio = strtoupper($params->nombresocio);
        $sql .= " AND S.PRIMERNOMBRE LIKE '%{$params->nombresocio}%'";
    }
    $sql .= " GROUP BY";
    $sql .= "   T1.codigolote, T1.codigoreferencia, T1.descripcioncuota, T1.codigocuota, T1.valorcuota, T1.ordencuota, ";
    $sql .= "   S.codigopersona, S.cedula, S.primerapellido, S.segundoapellido, S.primernombre, S.segundonombre ";
    $sql .= " ORDER BY";
    $sql .= "   S.primerapellido, S.segundoapellido, S.primernombre, S.segundonombre, T1.codigoreferencia, T1.ordencuota";
    return $sql;
}

/**
 * Returns the list of personas.
 */
function sqlPersonas($where, $orderby) {
  $sql = "";
  $sql .= " SELECT ";
  $sql .= "   P.codigopersona, P.primernombre, P.segundonombre, P.primerapellido, P.segundoapellido, P.cedula, ";
  $sql .= "   L.codigoreferencia, L.manzana, L.numerolote, ";
  $sql .= "   L.codigoreferenciaanterior, L.manzanaanterior, L.numeroloteanterior ";
  $sql .= " FROM PERSONAS P ";
  $sql .= " LEFT JOIN LOTES L ON L.codigopersona = P.codigopersona ";
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
  $sql .= "   L.codigopersona, L.codigolote, L.codigoreferencia, P.primernombre, P.segundonombre, P.primerapellido, P.segundoapellido ";
  $sql .= " FROM LOTES L";
  $sql .= " INNER JOIN PERSONAS P ON P.codigopersona = L.codigopersona";
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
  $sql .= "   D.codigopersona, D.codigodeposito, D.numerodeposito, D.valordeposito, D.fechadeposito, ";
  $sql .= "   (select SUM(P.valorpagocuotalote) from pagocuotalote P where P.codigodeposito = D.codigodeposito) as valorutilizado ";
  $sql .= " FROM DEPOSITOS D ";
  $sql .= " INNER JOIN DEPOSITOSPERSONAS DP ON DP.codigodeposito = D.codigodeposito ";
  $sql .= $where;
  $sql .= $orderby;
  return $sql;
}

function sqlPagoCuotaLoteSum($where, $orderby) {
  $sql = "";
  $sql .= " SELECT SUM(valorpagocuotalote) AS valorpagocuotalote FROM pagocuotalote P ";
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