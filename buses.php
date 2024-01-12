<?php 
require 'oauth2.php';

$idBus = $_GET['id'];

$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://api.montevideo.gub.uy/api/transportepublico/buses?busstopId=".$idBus,
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
$response = json_decode($response,true);

$c = 0;

$json = [];

foreach($response as $arrays){
  $arr = array('busLine'=>$arrays['line'],'lat'=>$arrays['location']['coordinates'][1],'lon'=>$arrays['location']['coordinates'][0],'subline'=>$arrays['subline']);
  $json += array('bus'.$c=>$arr);
  $c++;
}

$json = json_encode($json);

print_r($json);

 ?>
