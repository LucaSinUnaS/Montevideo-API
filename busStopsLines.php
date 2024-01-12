<?php 
$idBus = $_GET['id'];
$response = file_get_contents('http://www.montevideo.gub.uy/transporteRest/lineas/'.$idBus);
$response = json_decode($response);
$arr = $response->{'lineas'};
//echo($arr);

foreach($arr as $objects){
	echo($objects->{'descripcion'}.", ");
	}

 ?>