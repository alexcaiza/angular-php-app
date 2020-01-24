<?php

/**
 * Returns the list of depositos.
 */

function sqlDEPOSITOS($where, $orderby) {
  $sql = "";
  $sql .= " SELECT ";
  $sql .= "   DEP.codigodeposito, DEP.numerodeposito, DEP.codigopersona, DEP.fechadeposito, DEP.valordeposito, DEP.tipodeposito, ";
  $sql .= "   sumar_pagos_lote(DEP.codigodeposito, 'DEP') as valorpagadodeposito, ";
  $sql .= "   PER.cedula, PER.primernombre, PER.segundonombre, PER.primerapellido, PER.segundoapellido ";
  $sql .= " FROM DEPOSITOS DEP ";
  $sql .= " INNER JOIN PERSONAS PER ON PER.CODIGOPERSONA = DEP.CODIGOPERSONA ";
  $sql .= $where;
  $sql .= $orderby;
  return $sql;
}

function buscarDepositoByNumeroDocumento($con, $request) {
    
    $numerodeposito = sanitize($con, trim($request->numerodeposito));
    
    $deposito = null;
    
    $where = " WHERE DEP.numerodeposito = '{$numerodeposito}'";
    $orderby = "";
    
    $sql = sqlDEPOSITOS($where, $orderby);
    
    $resultset = mysqli_query($con, $sql) or die(mysql_error());
    
    if($resultset)
    {
        $i = 0;
        while($row = mysqli_fetch_assoc($resultset))
        {
            $deposito = $row;            
            $i++;
        }
    }
    return $deposito;
}

function sanitize($con, $var){
  $return = mysqli_real_escape_string($con, $var);
  return $return;
}

function createVALIDATION($con, $request) {

  // Sanitize.
  $numerodeposito = sanitize($con, trim($request->numerodeposito));
  $codigopersona =  sanitize($con, trim($request->codigopersona));
  $fechadeposito =  sanitize($con, trim($request->fechadeposito));
  $valordeposito =  sanitize($con, trim($request->valordeposito));
  $tipodeposito =   sanitize($con, trim($request->tipodeposito));

  // Validate.
  if($numerodeposito === '' 
	|| $codigopersona === '' || (int)$codigopersona < 0
	|| $fechadeposito === '' 
	|| $valordeposito === '' || (float)$valordeposito < 0
	|| $tipodeposito === '' 
	) {
		return false;
  }
  return true;
}

function createDEPOSITO($con, $request) {
  // Sanitize.
  $numerodeposito = mysqli_real_escape_string($con, trim($request->numerodeposito));
  $codigopersona = mysqli_real_escape_string($con, trim($request->codigopersona));
  $fechadeposito = mysqli_real_escape_string($con, trim($request->fechadeposito));
  $valordeposito = mysqli_real_escape_string($con, trim($request->valordeposito));
  $tipodeposito = mysqli_real_escape_string($con, trim($request->tipodeposito));
    
  // Store.
  $sql = "";
  $sql .= "INSERT INTO `DEPOSITOS`(`codigodeposito`,`numerodeposito`,`codigopersona`,`fechadeposito`,`valordeposito`,`tipodeposito`) ";
  $sql .= "VALUES (null,'{$numerodeposito}','{$codigopersona}','{$fechadeposito}','{$valordeposito}','{$tipodeposito}')";
  
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
      $deposito = [
          'codigodeposito' => mysqli_insert_id($con),
          'numerodeposito' => $numerodeposito,
          'codigopersona' => $codigopersona,
          'fechadeposito' => $fechadeposito,
          'valordeposito' => $valordeposito,
          'tipodeposito' => $tipodeposito
      ];
      
      $response['deposito'] = $deposito;
      $response['status'] = 1;
    }
  }
  return $response;
}

/**
 * Metodo para registrar datos en la tabla depositospersonas
 * 
 */
function createDepositoPersona($con, $codigodeposito, $codigopersona) {
    // Sanitize.
    $codigodeposito = mysqli_real_escape_string($con, trim($codigodeposito));
    $codigopersona = mysqli_real_escape_string($con, trim($codigopersona));
    
    // Store table depositospersonas.
    $sql = "";
    $sql .= " INSERT INTO `depositospersonas`(`codigodeposito`,`codigopersona`,`estado`) ";
    $sql .= " VALUES ('{$codigodeposito}','{$codigopersona}','1')";
    
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
            $depositopersona = [
                'codigodeposito' => $codigodeposito,
                'codigopersona' => $codigopersona
            ];
            
            $response['depositopersona'] = $depositopersona;
            $response['status'] = 1;
        }
    }
    return $response;
}