<?php
require 'database.php';

$sql = "";

try {
	// Get the posted data.
	$postdata = file_get_contents("php://input");

	if(isset($postdata) && !empty($postdata))
	{
	  // Extract the data.
	  $request = json_decode($postdata);
		
	  // Validate.
	  if ((int)$request->id < 1 || trim($request->name) == '' || (float)$request->price < 0) {
		return http_response_code(400);
	  }
		
	  // Sanitize.
	  $id    = mysqli_real_escape_string($con, (int)$request->id);
	  $name = mysqli_real_escape_string($con, trim($request->name));
	  $price = mysqli_real_escape_string($con, (float)$request->price);

	  // Update.
	  $sql = "UPDATE `games` SET `name`='$name',`price`='$price' WHERE `id` = '{$id}' LIMIT 1";
	  
	  $result = mysqli_query($con, $sql);
	  
	  if($result === false) {
			throw new Exception(mysql_error($con));
	  }

	  if($result) {
		//http_response_code(204);
		echo json_encode($request);
	  }
	  else {
		return http_response_code(422);
	  }  
	}
}
catch (Exception $e) {
	$errores = [];
	$errores['error'] = $e->getMessage();
	$errores['sql'] = $sql;
	echo json_encode($errores);
}
