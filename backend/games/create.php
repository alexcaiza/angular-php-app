<?php
require 'database.php';

// Get the posted data.
$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata))
{
  // Extract the data.
  $request = json_decode($postdata);
	

  // Validate.
  if(trim($request->name) === '' || (float)$request->price < 0)
  {
    return http_response_code(400);
  }
	
  // Sanitize.
  $name = mysqli_real_escape_string($con, trim($request->name));
  $price = mysqli_real_escape_string($con, (float)$request->price);
    

  // Store.
  $sql = "INSERT INTO `games`(`id`,`name`,`price`) VALUES (null,'{$name}','{$price}')";

  if(mysqli_query($con,$sql))
  {
    http_response_code(201);
    $game = [
      'name' => $name,
      'price' => $price,
      'id'    => mysqli_insert_id($con)
    ];
    echo json_encode($game);
  }
  else
  {
    http_response_code(422);
  }
}