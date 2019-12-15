<?php
/**
 * Returns the list of games.
 */
require 'database.php';
    
$games = [];
$sql = "SELECT id, name, price FROM games";

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
    
  echo json_encode($games);
}
else
{
  http_response_code(404);
}