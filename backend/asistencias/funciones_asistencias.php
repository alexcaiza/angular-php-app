<?php

/**
 * Returns the list of cuotas.
 */
function sqlReuniones($where, $orderby) {
    $sql = "";
    $sql .= " SELECT ";
    $sql .= "   R.codigoreunion, R.nombrereunion, R.fechareunion, R.estado, R.usuarioregistro, R.fecharegistro, R.ordenreunion ";
    $sql .= " FROM reuniones R";
    $sql .= $where;
    $sql .= $orderby;
    return $sql;
}

function sqlReporteReunionesSociosAusentes($where, $orderby) {
    $sql = "";
    $sql .= " select";
    $sql .= "   t2.codigoreunion, t2.nombrereunion, t2.valormulta, t2.ordenreunion, t2.codigolote, t2.codigopersona, t2.codigoreferencia, t2.codigoreferenciaanterior,";
    $sql .= "   p2.primerapellido, p2.primernombre, p2.cedula, ";
    $sql .= "   a2.valorasistencia";
    $sql .= " from";
    $sql .= " (";
    $sql .= "   select r2.codigoreunion, r2.nombrereunion, r2.valormulta, r2.ordenreunion, l2.codigolote, l2.codigopersona, l2.codigoreferencia, l2.codigoreferenciaanterior";
    $sql .= "   from reuniones r2 inner join lotes l2";
    $sql .= "   where r2.estado = '1'";
    $sql .= " ) as t2";
    $sql .= " left join asistencias a2 on a2.codigoreunion = t2.codigoreunion and a2.codigolote = t2.codigolote";
    $sql .= " inner join personas p2 on p2.codigopersona = t2.codigopersona";
    $sql .= " ";
    $sql .= " ";
    $sql .= $where;
    $sql .= $orderby;
    return $sql;
}

function sqlReporteReunionesSociosAusentesTotalizado($where, $orderby) {
    $sql = "";
    $sql .= " select";
    $sql .= "   SUM(t2.valormulta) as valormulta, ";
    $sql .= "   t2.codigolote, t2.codigopersona, t2.codigoreferencia, t2.codigoreferenciaanterior,";
    $sql .= "   p2.primerapellido, p2.primernombre, p2.cedula ";
    $sql .= " from";
    $sql .= " (";
    $sql .= "   select r2.codigoreunion, r2.nombrereunion, r2.valormulta, r2.ordenreunion, l2.codigolote, l2.codigopersona, l2.codigoreferencia, l2.codigoreferenciaanterior";
    $sql .= "   from reuniones r2 inner join lotes l2";
    $sql .= "   where r2.estado = '1'";
    $sql .= " ) as t2";
    $sql .= " left join asistencias a2 on a2.codigoreunion = t2.codigoreunion and a2.codigolote = t2.codigolote";
    $sql .= " inner join personas p2 on p2.codigopersona = t2.codigopersona";
    $sql .= " ";
    $sql .= " ";
    $sql .= $where;
    $sql .= " GROUP BY";
    $sql .= "   t2.codigolote, t2.codigopersona, t2.codigoreferencia, t2.codigoreferenciaanterior,";
    $sql .= "   p2.primerapellido, p2.primernombre, p2.cedula ";
    $sql .= $orderby;
    return $sql;
}

/**
 * Returns the list of cuotas de cada uno de los lotes.
 */
function sqlReunionesLotes($params) {
    $sql = "";
    $sql .= " SELECT ";
    $sql .= "   T.*, a.valorasistencia ";
    $sql .= " FROM";
    $sql .= " (";
    $sql .= "   SELECT r.codigoreunion, r.nombrereunion, r.fecharegistro, r.ordenreunion, l.codigolote, l.codigoreferencia, l.codigoreferenciaanterior, p.codigopersona, p.primernombre, p.primerapellido, p.cedula";
    $sql .= "   FROM reuniones r";
    $sql .= "   INNER JOIN lotes l";
    $sql .= "   INNER JOIN personas p on p.codigopersona = l.codigopersona";
    $sql .= " ) AS T" ;
    $sql .= " LEFT JOIN asistencias a on a.codigoreunion = t.codigoreunion and a.codigolote = t.codigolote and a.codigopersona = t.codigopersona" ;
    $sql .= " WHERE 1=1";
    if (isset($params) && $params != null) {
        if (isset($params->codigoreunion) && $params->codigoreunion) {
            $sql .= " AND T.codigoreunion = '{$params->codigoreunion}'";
        }
        if (isset($params->codigolote) && $params->codigolote) {
            $sql .= " AND T.CODIGOLOTE = '{$params->codigolote}'";
        }
        if (isset($params->codigopersona) && $params->codigopersona) {
            $sql .= " AND T.CODIGOPERSONA = '{$params->codigopersona}'";
        }
        if (isset($params->primernombre) && $params->primernombre) {
            $params->primernombre = strtoupper($params->primernombre);
            $sql .= " AND T.PRIMERNOMBRE LIKE '%{$params->primernombre}%'";
        }
        if (isset($params->primerapellido) && $params->primerapellido) {
            $params->primerapellido = strtoupper($params->primerapellido);
            $sql .= " AND T.PRIMERAPELLIDO LIKE '%{$params->primerapellido}%'";
        }
        if (isset($params->cedula) && $params->cedula) {
            $params->cedula = strtoupper($params->cedula);
            $sql .= " AND T.CEDULA LIKE '%{$params->cedula}%'";
        }
    }
    $sql .= " ORDER BY";
    $sql .= "   T.primerapellido, T.primernombre, T.ordenreunion, T.NOMBREREUNION";
    return $sql;
}