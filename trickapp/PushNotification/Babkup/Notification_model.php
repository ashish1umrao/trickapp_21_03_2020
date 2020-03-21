<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Notification_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct(); 
		$this->load->database(); 
	}
	
	#########################################################################################################################
	################################################		USER SECTION 		#############################################
	#########################################################################################################################

	/* * *********************************************************************
	 * * Function name : sendNotificationToUserFunction 
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function use for send Notification To User Function
	 * * Date : 11 JUNE 2018
	 * * **********************************************************************/
	public function sendNotificationToUserFunction($registrationIds='',$message='',$data=array(),$deviceType='') {
		
		if(!empty($registrationIds) && !empty($message) && !empty($data) && !empty($deviceType)):
			
			if($deviceType == 'Andriod'):
				$message['sound']	=	'notifsound.wav';
				$fields 	= 	array('to'=>$registrationIds,'notification'=>$message,'data'=>$data);
				$headers 	= 	array('Authorization: key='.CABBEE_API_ACCESS_KEY,'Content-Type:application/json');
			elseif($deviceType == 'IOS'):
				$fields 	= 	array('to'=>$registrationIds,'notification'=>$message,'data'=>$data);
				$headers 	= 	array('Authorization: key='.CABBEE_IOS_API_ACCESS_KEY,'Content-Type:application/json');
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
			curl_close($ch);
			#Echo Result Of FireBase Server
			return $result;
		endif;
	}	// END OF FUNCTION	

	/***********************************************************************
	** Function name : sendBRConfirmationNotificationToUser
	** Developed By : Manoj Kumar
	** Purpose  : This is use for send Booking Request Confirmation Notification To User
	** Date : 25 JUNE 2018
	************************************************************************/
	function sendBRConfirmationNotificationToUser($registrationIds='',$rideId='',$deviceType='',$notisficationArray=array()) {  
		if($registrationIds && $rideId && $deviceType):
			$data			=	array('notCatId'=>'1',  //5
				                      'rideId'=>$rideId,
				                   	  'estimatedFair'=>$notisficationArray['estimatedFair'],
				                   	  'driverId'=>$notisficationArray['driverId'],
				                   	  'driverName'=>$notisficationArray['driverName'],
				                   	  'driverMobile'=>$notisficationArray['driverMobile'],
				                   	  'vehicleNumber'=>$notisficationArray['vehicleNumber'],
				                   	  'vehicleCategory'=>$notisficationArray['vehicleCategory'],
				                   	  'vehicleMake'=>$notisficationArray['vehicleMake'],
				                   	  'vehicleType'=>$notisficationArray['vehicleType'],
				                   	  'vehicleCapacity'=>$notisficationArray['vehicleCapacity'],
				                   	  'vehicleWheelChair'=>$notisficationArray['vehicleWheelChair'],
				                   	  'vehicleFleet'=>$notisficationArray['vehicleFleet'],
				                   	  'vehicleCabNumber'=>$notisficationArray['vehicleCabNumber']);
			$message 		= 	array('body'=>$data,//'Your request has been accepted by our driver.',
						 	 		  'title'=>'Your request has been accepted by driver.',
             	         	 		  'icon'=>'myicon',
              	         	 		  'sound'=>'sound.caf'
          				 	 		  );
			$returnMessage	=	$this->sendNotificationToUserFunction($registrationIds,$message,$data,$deviceType);
			return $returnMessage;
		endif;
	}

	/***********************************************************************
	** Function name : sendDriverMoveNotificationToUser
	** Developed By : Manoj Kumar
	** Purpose  : This is use for send Driver Move Notification To User
	** Date : 26 SEPTEMBER 2018
	************************************************************************/
	function sendDriverMoveNotificationToUser($registrationIds='',$rideId='',$deviceType='') {  
		if($registrationIds && $rideId && $deviceType):
			$data			=	array('notCatId'=>'1','rideId'=>$rideId);
			$message 		= 	array('body'=>$data,//'Our driver is on the way for pickup as per booking schedule.',
						 	 		  'title'=>'Driver is on the way.',
             	         	 		  'icon'=>'myicon',
              	         	 		  'sound'=>'sound.caf'
          				 	 		  );
			$returnMessage	=	$this->sendNotificationToUserFunction($registrationIds,$message,$data,$deviceType);
			return $returnMessage;
		endif;
	}

	/***********************************************************************
	** Function name : sendCurrentRideCancelNotificationToUser
	** Developed By : Manoj Kumar
	** Purpose  : This is use for send Current Ride Cancel Notification To User
	** Date : 25 JUNE 2018
	************************************************************************/
	function sendCurrentRideCancelNotificationToUser($registrationIds='',$rideId='',$deviceType='') {  
		if($registrationIds && $rideId && $deviceType):
			$data			=	array('notCatId'=>'4','rideId'=>$rideId);   //2
			$message 		= 	array('body'=>$data,//'Your ride canceled by our driver.',
						 	 		  'title'=>'Your ride has been canceled by driver.',
             	         	 		  'icon'=>'myicon',
              	         	 		  'sound'=>'sound.caf'
          				 	 		  );
			$returnMessage	=	$this->sendNotificationToUserFunction($registrationIds,$message,$data,$deviceType);
			return $returnMessage;
		endif;
	}

	/***********************************************************************
	** Function name : sendDriverReachedPickupNotificationToUser
	** Developed By : Manoj Kumar
	** Purpose  : This is use for send Driver Reached Pickup Notification To User
	** Date : 05 AUGUST 2018
	************************************************************************/
	function sendDriverReachedPickupNotificationToUser($registrationIds='',$rideId='',$deviceType='') {  
		if($registrationIds && $rideId && $deviceType):
			$data			=	array('notCatId'=>'2','rideId'=>$rideId); //1
			$message 		= 	array('body'=>$data,//'Our driver have reached to pickup point. Please contact to driver',
						 	 		  'title'=>'Driver has reached the pickup location.',
             	         	 		  'icon'=>'myicon',
              	         	 		  'sound'=>'sound.caf'
          				 	 		  );
			$returnMessage	=	$this->sendNotificationToUserFunction($registrationIds,$message,$data,$deviceType);
			return $returnMessage;
		endif;
	}

	/***********************************************************************
	** Function name : sendDriverStartRideNotificationToUser
	** Developed By : Manoj Kumar
	** Purpose  : This is use for send Driver Start Ride Notification To User
	** Date : 03 NOVEMBER 2018
	************************************************************************/
	function sendDriverStartRideNotificationToUser($registrationIds='',$rideId='',$deviceType='') {  
		if($registrationIds && $rideId && $deviceType):
			$data			=	array('notCatId'=>'3','rideId'=>$rideId);  //1
			$message 		= 	array('body'=>$data,//'Your ride started',
						 	 		  'title'=>'Your ride has started.',
             	         	 		  'icon'=>'myicon',
              	         	 		  'sound'=>'sound.caf'
          				 	 		  );
			$returnMessage	=	$this->sendNotificationToUserFunction($registrationIds,$message,$data,$deviceType);
			return $returnMessage;
		endif;
	}

	/***********************************************************************
	** Function name : sendDriverRideCompletedNotificationToUser
	** Developed By : Manoj Kumar
	** Purpose  : This is use for send Driver Ride Completed Notification To User
	** Date : 03 NOVEMBER 2018
	************************************************************************/
	function sendDriverRideCompletedNotificationToUser($registrationIds='',$rideId='',$deviceType='') {  
		if($registrationIds && $rideId && $deviceType):
			$data			=	array('notCatId'=>'5','rideId'=>$rideId);  //2
			$message 		= 	array('body'=>$data,//'Your ride is complete',
						 	 		  'title'=>'Your ride has completed.',
             	         	 		  'icon'=>'myicon',
              	         	 		  'sound'=>'sound.caf'
          				 	 		  );
			$returnMessage	=	$this->sendNotificationToUserFunction($registrationIds,$message,$data,$deviceType);
			return $returnMessage;
		endif;
	}
	
	#########################################################################################################################
	################################################		DRIVER SECTION 		#############################################
	#########################################################################################################################
	/* * *********************************************************************
	 * * Function name : sendNotificationToDriverFunction 
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function use for send Notification To Driver Function
	 * * Date : 11 JUNE 2018
	 * * **********************************************************************/
	public function sendNotificationToDriverFunction($registrationIds='',$message=array(),$data=array(),$deviceType='') {
		if(!empty($registrationIds) && !empty($message) && !empty($data) && !empty($deviceType)):
			
			if($deviceType == 'Andriod'):
				$message['sound']	=	'notifsound.wav';
				$fields 	= 	array('to'=>$registrationIds,'notification'=>$message,'data'=>$data);
				$headers 	= 	array('Authorization: key='.CABBEE_DRIVER_API_ACCESS_KEY,'Content-Type:application/json');
			elseif($deviceType == 'IOS'):
				$fields 	= 	array('to'=>$registrationIds,'notification'=>$message,'data'=>$data);
				$headers 	= 	array('Authorization: key='.CABBEE_IOS_DRIVER_API_ACCESS_KEY,'Content-Type:application/json');
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
			curl_close($ch);
			#Echo Result Of FireBase Server
			return $result;
		endif;
	}	// END OF FUNCTION	
	
	/***********************************************************************
	** Function name : sendBookingNotificationToDriver
	** Developed By : Manoj Kumar
	** Purpose  : This is use for send Booking Notification To Driver
	** Date : 11 JUNE 2018
	************************************************************************/
	function sendBookingNotificationToDriver($registrationIds='',$rideId='',$deviceType='') {  
		if($registrationIds && $rideId && $deviceType):
			$data			=	array('notCatId'=>'1','rideId'=>$rideId);
			$message 		= 	array('body'=>$data,//'New booking request',
						 	 		  'title'=>'New booking request.',
             	         	 		  'icon'=>'myicon',
              	         	 		  'sound'=>'sound.caf'
          				 	 		  );
			$returnMessage	=	$this->sendNotificationToDriverFunction($registrationIds,$message,$data,$deviceType);
			return $returnMessage;
		endif;
	}

	/***********************************************************************
	** Function name : sendCurrentRideCancelNotificationToDriver
	** Developed By : Manoj Kumar
	** Purpose  : This is use for send Current Ride Cancel Notification To Driver
	** Date : 25 JUNE 2018
	************************************************************************/
	function sendCurrentRideCancelNotificationToDriver($registrationIds='',$rideId='',$deviceType='') {  
		if($registrationIds && $rideId && $deviceType):
			$data			=	array('notCatId'=>'2','rideId'=>$rideId);
			$message 		= 	array('body'=>$data,//'This ride cancel by user.',
						 	 		  'title'=>'This ride cancel by user.',
             	         	 		  'icon'=>'myicon',
              	         	 		  'sound'=>'sound.caf',
          				 	 		  );
			$returnMessage	=	$this->sendNotificationToDriverFunction($registrationIds,$message,$data,$deviceType);
			return $returnMessage;
		endif;
	}

	/***********************************************************************
	** Function name : sendCRidePaymentSuccessNotisToDriver
	** Developed By : Manoj Kumar
	** Purpose  : This is use for send Current Ride Payment Success Notis To Driver
	** Date : 20 DECEMBER 2018
	************************************************************************/
	function sendCRidePaymentSuccessNotisToDriver($registrationIds='',$rideId='',$deviceType='') {  
		if($registrationIds && $rideId && $deviceType):
			$data			=	array('notCatId'=>'1','rideId'=>$rideId);
			$message 		= 	array('body'=>$data,//'New booking request',
						 	 		  'title'=>'User have submit payment.',
             	         	 		  'icon'=>'myicon',
              	         	 		  'sound'=>'sound.caf'
          				 	 		  );
			$returnMessage	=	$this->sendNotificationToDriverFunction($registrationIds,$message,$data,$deviceType);
			return $returnMessage;
		endif;
	}
}	
?>