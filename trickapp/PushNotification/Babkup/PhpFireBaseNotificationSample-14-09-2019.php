<?php
/*
#API access key from Google API's Console
    //define( 'API_ACCESS_KEY', 'AIzaSyBOXEKXZmcC0CCJLWdx2IBKoBZvGI-Rs0M' );
    define( 'API_ACCESS_KEY', 'AIzaSyCVCWPiKUW3F0LfGf5mZRDmrldmOFWuW1Q' );
    //$registrationIds = 'd7wGl1RXBpM:APA91bHXCcZy3v9dKxV455FNHanZmoqn4tckZilZcdl0LTLk2xCuj9y13uvSjAJf1FTaRg2BMHT1Qxmwlo3ZRPOwnk42BQYEfjC_ohrVQleQ9ro1iqMG4x9o3kYuSxrvlcjQB3RMw0Ji';
    $registrationIds = 'cWTY67bOPH4:APA91bGAUM6gWAoMUQB3AVy_7WzXcrSS2RXdhhDTgfEonjk6u_dSjPb5kyWzcllp1vC_FYUXktClB34hDcKSZcW8s6HX5YIPE2pgoPj53_fTdEtmr_l_ajXR46ZPc9qspX0rEF-p4WZx';
#prep the bundle
     $msg = array
          (
				'body' 	=> 'Testing by Manoj',
				'title'	=> 'Puch notification',
             	'icon'	=> 'myicon', //Default Icon
              	'sound' => 'mySound' //Default sound
          );
	$fields = array
			(
				'to'		=> $registrationIds,	
				'notification'	=> $msg,
				'priority'=>'high'
			);
	
	
	$headers = array
			(
				'Authorization: key=' . API_ACCESS_KEY,
				'Content-Type: application/json'
			);
#Send Reponse To FireBase Server	
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
#Echo Result Of FireBase Server
echo $result;*/


$url = "https://fcm.googleapis.com/fcm/send";
//IOS user
//$token = "AAAAhjTdxfI:APA91bH7m3XvZNU0GaqmhSn3jmVHiBTkt1yQE0neIJ-BBwXTt7tp1kMvf8OrummZ4g5bzpdqbgvu8mkDCuMJnPlpWVIDVRkHAzfbjdw6ESgol43PHxipNwdhDAAgRi4hn0B8qE85KXZIlo94TJaf-A4PXjr_-dOwxA";
//$serverKey = 'AIzaSyBdHcQoHB0HCR7lSv81Vtkq1G7H1kufnCo';
//IOS driver
//$token = "cAu3Ln4r7YY:APA91bE53_Th4pXiv8ZX3s5LCfnY6jrkxhy66fv0fp0If11iX_UDZRGTdjEg3ijslvst2AjV0e5OQOsYzFvBLlHcSqT-G8eyvrAf-1qzqfqevzJB1YhSM6_d8FLADTVawCA2wI1t07AO";
//$serverKey = 'AIzaSyDbUN4JQUGYxOaFU3ZC7O2BmlF9kBr6aaA';

$token = "dUGO9Kq-_l0:APA91bF8QwdfKhNtKJTU6I26FKe6OwwzM6KsKnYXIoqIXw6X3xFfq2fPfoGXU7Rwmw-Co8eenu1KkFoM1dAIDl6vtQQGz7DmlKe1YCJbvoThkzF1fUOsFmk0Cz7-Q_IxO0ZKbaFYHpoP";
$serverKey = 'AIzaSyAoj6-sFDpRfhDJ17G4836-f9n1_rD3OcU';

$title = "Jai Modi Ji";
$body = "Jai Mata di Bande Matram";

$notification = array('title' =>$title , 'text' => $body, 'sound' => 'default', 'badge' => '1');
$arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
$json = json_encode($arrayToSend);
$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Authorization: key='. $serverKey;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST,

"POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
//Send the request
$response = curl_exec($ch);
//Close request
if ($response === FALSE) {
die('FCM Send Error: ' . curl_error($ch));
}
curl_close($ch);