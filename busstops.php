<?php 
require 'oauth2.php';
$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://api.montevideo.gub.uy/api/transportepublico/buses/busstops",
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

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

$arr = json_decode($response, true);
$myfile = fopen("busStopsInfoJson.txt", "w");
fwrite($myfile, $response);
fclose($myfile);
foreach($arr as $arrays){
	$busStopsIds[] = $arrays['busstopId'];
	$coordinates[] = $arrays['location']['coordinates'];
}
/*
for($i=0; $i < count($busStopsIds);$i++){
	$data = $busStopsIds[$i].", ".$coordinates[$i][0].", ".$coordinates[$i][1];
	$datanocoma = str_replace(",", "", $data);
	$datanospace = explode(" ", $datanocoma);
	$myfile = fopen("busStopsInfo.txt", "a");
	$txt = $datanospace[0]."\n".$datanospace[2]."\n".$datanospace[1]."\n";
	fwrite($myfile, $txt);
	fclose($myfile);
	/*
	echo($stopsIDs[$i]);
	echo("<br>");
	echo($stopsCoordsLat[$i]);
	echo("<br>");
	echo($stopsCoordsLon[$i]);
	echo("<br>");
	echo("<br>");
}
*/

 ?>
