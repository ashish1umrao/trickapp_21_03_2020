<?php
$postData 			= 	getApiRequestData();
//print "<pre>"; print_r($postData); die;

//echo "okk"; die;
$url = "https://fcm.googleapis.com/fcm/send";
$registrationIds = $postData['deviceID'];  // This is changable
//print "<pre>"; print_r($registrationIds); die;

define('API_ACCESS_KEY', 'AAAAcwE6aUc:APA91bGZAah1P6NdyJLML0kFEAoUxXQ1hXlIrswyp96-6Ew9LTOX8aL99MZCeoYOVpfzzIxdQ5eEYKWbz_Mu9KrCUu_Z5QNoPHPNsr3opiWccGVRfcjtpMqbg7oWuozOKH3bWjZ6KM5x');
//define('API_ACCESS_KEY', 'ae202642ac1e41f0');

$data			=	array('Role'=>'Notification',
						  'body'=>$postData['message']);
$message 		= 	array('title'=>$postData['title'],
						  'icon'=>'myicon',
						  'sound'=>'sound.caf'
						  );
$deviceType		= "Andriod";


function getApiRequestData() {
			$jsonData = file_get_contents( 'php://input' );
			$return = json_decode($jsonData,true); 
			
			//$time_zone = ( isset( $return["time_zone"] ) && trim( $return["time_zone"] ) ) ? trim ( $return["time_zone"] ) : '';
			//setLocalTimeZone( $time_zone );
			return ( is_array( $return ) ) ? $return : array( );
}

sendNotificationToUserFunction($registrationIds,$message,$data,$deviceType);

function sendNotificationToUserFunction($registrationIds='',$message='',$data=array(),$deviceType=''){
	
	if(!empty($registrationIds) && !empty($message) && !empty($data) && !empty($deviceType)):
			
			if($deviceType == 'Andriod'):
				$message['sound']	=	'notifsound.wav';
				$fields 	= 	array('to'=>$registrationIds,'notification'=>$message,'data'=>$data);
				$headers 	= 	array('Authorization: key='.API_ACCESS_KEY,'Content-Type:application/json');
			elseif($deviceType == 'IOS'):
				$fields 	= 	array('to'=>$registrationIds,'notification'=>$message,'data'=>$data);
				$headers 	= 	array('Authorization: key='.API_ACCESS_KEY,'Content-Type:application/json');
			endif;
			#Send Reponse To FireBase Server	
			$ch = curl_init();
			curl_setopt( $ch,CURLOPT_URL,'https://fcm.googleapis.com/fcm/send');
			curl_setopt( $ch,CURLOPT_POST,true);
			curl_setopt( $ch,CURLOPT_HTTPHEADER,$headers);
			curl_setopt( $ch,CURLOPT_RETURNTRANSFER,true);
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER,false);
			curl_setopt( $ch,CURLOPT_POSTFIELDS,json_encode($fields));
			$result = curl_exec($ch);
			//print "<pre>"; print_r($result); die;
			$Result = json_encode($result);
			$ResultArr = json_decode($Result);
			print $ResultArr; die;
			curl_close($ch);
			#Echo Result Of FireBase Server
			return $result;
		endif;
}
