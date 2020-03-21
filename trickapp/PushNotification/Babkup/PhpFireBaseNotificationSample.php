<?php
$url = "https://fcm.googleapis.com/fcm/send";
$registrationIds = "fw2gdgo38qo:APA91bG2A6I4r7wxD5nw_F4rAGrBk7wtg604jC08iCU4AtFzek0lG2CCzxBwYNKJF8zcHPzLGHIZlNV9ljCOVadBKZnK5-A8SU8vEYLxoa12pWj7scLEsX22jM6BRGdnXhfJjjJ9aeyP";  // This is changable
define('API_ACCESS_KEY', 'AIzaSyAoj6-sFDpRfhDJ17G4836-f9n1_rD3OcU');
//define('API_ACCESS_KEY', 'ae202642ac1e41f0');

$data			=	array('Role'=>'Notification',
						  'body'=>'TrickAPP Move on Syllabus Module');
$message 		= 	array('title'=>'TrickAPP Application Notification',
						  'icon'=>'myicon',
						  'sound'=>'sound.caf'
						  );
$deviceType		= "Andriod";
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
			print "<pre>"; print_r($result); die;
			curl_close($ch);
			#Echo Result Of FireBase Server
			return $result;
		endif;
}
