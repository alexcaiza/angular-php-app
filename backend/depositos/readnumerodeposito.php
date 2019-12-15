<?php
/**
 * Returns the list of games.
 */
require '../database.php';

$response = [];

$response['status'] = 0;

$numerodeposito = ($_GET['numerodeposito'] !== null && $_GET['numerodeposito'] != "")? mysqli_real_escape_string($con, (int)$_GET['numerodeposito']) : false;

$response['numerodeposito'] = $numerodeposito;

if(!$numerodeposito)
{
  echo json_encode($response);
  return http_response_code(400);
}
    
$game = null;
$sql = "SELECT id, name, price FROM games WHERE `id` ='{$id}'";

if($resultset = mysqli_query($con, $sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($resultset))
  {
    $games[$i]['id']    = $row['id'];
    $games[$i]['name'] = $row['name'];
    $games[$i]['price'] = $row['price'];
    $i++;
  }
    
  echo json_encode($games[0]);
}
else
{
  http_response_code(404);
}