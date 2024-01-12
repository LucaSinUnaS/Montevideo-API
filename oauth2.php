<?php 
//Environment variables

require_once realpath(__DIR__ . "/vendor/autoload.php");

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
    
    $client_id = $_ENV['CLIENT_ID']; // -> Tu propio client ID de https://api.montevideo.gub.uy/
    $client_secret = $_ENV['CLIENT_SECRET']; // -> Tu propio client secret de https://api.montevideo.gub.uy/

    $curl = curl_init();

    $params = [
        CURLOPT_URL => "https://mvdapi-auth.montevideo.gub.uy/auth/realms/pci/protocol/openid-connect/token",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_POST => 1,
        CURLOPT_NOBODY => false,
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded",
            "accept: */*",
            "accept-encoding: gzip, deflate",
        ),
        CURLOPT_POSTFIELDS => http_build_query(array( 'client_id' => $client_id, 'client_secret' => $client_secret, 'grant_type' => 'client_credentials' ))
    ];

    curl_setopt_array($curl, $params);
    $response = curl_exec($curl);


    curl_close($curl);
    $arr = json_decode($response, true);
    $accessToken = $arr['access_token'];
    //echo($arr['access_token']);
 ?>