<?php

require 'database.php';

// Extract, validate and sanitize the id.
$id = ($_GET['id'] !== null && (int)$_GET['id'] > 0)? mysqli_real_escape_string($con, (int)$_GET['id']) : false;

if(!$id)
{
  return http_response_code(400);
}

// Delete.
$sql = "DELETE FROM `games` WHERE `id` ='{$id}' LIMIT 1";

if(mysqli_query($con, $sql))
{
  http_response_code(204);
}
else
{
  $errores = [];
  $errores['error'] = mysql_error($con);
  $errores['sql'] = $sql;
  $errores['http_response_code'] = http_response_code(422);
  echo json_encode($errores);
  //return http_response_code(422);
}