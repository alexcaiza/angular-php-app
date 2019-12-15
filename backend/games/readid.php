<?php
/**
 * Returns the list of games.
 */
require 'database.php';

$id = ($_GET['id'] !== null && (int)$_GET['id'] > 0)? mysqli_real_escape_string($con, (int)$_GET['id']) : false;

if(!$id)
{
  return http_response_code(400);
}
    
$game = null;
$sql = "SELECT id, name, price FROM games WHERE `id` ='{$id}'";

if($result = mysqli_query($con, $sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
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