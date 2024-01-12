<?php 
require 'oauth2.php';
//$stopID = $_GET['id'];
$stopID = "3966";
	$curlBuses = curl_init();

	curl_setopt_array($curlBuses, [
	  CURLOPT_URL => "https://api.montevideo.gub.uy/api/transportepublico/buses/busstops/".$stopID."/upcomingbuses",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",
	  CURLOPT_POSTFIELDS => "",
	  CURLOPT_COOKIE => "f5avraaaaaaaaaaaaaaaa_session_=NEHJHNHFGGHHKGMMOKMJJLLJNJMMIKCHNDKCOGMGGHICGLJGFIJNHEBFPANLNNOIPPFDDLFJAMPOFBHKEBEANLEKCFOLCMMGACCOJOEPGMBMILOFCPOJDPPCAMONKMLO",
	  CURLOPT_HTTPHEADER => [
	    "Authorization: Bearer ".$accessToken,
	    "Content-Type: application/json"
	  ],
	]);

	$responseBuses = curl_exec($curlBuses);
	$errBuses = curl_error($curlBuses);

	curl_close($curlBuses);

	print_r(json_decode($responseBuses,true)['message']);



 ?>
