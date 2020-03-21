<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function  __construct() 
	{ 
		parent:: __construct();
		$this->load->helper(array('form','url','html','path','form','cookie'));
		$this->load->library(array('email','session','form_validation','pagination','parser','encrypt'));
		error_reporting(E_ALL ^ E_NOTICE);  
		$this->load->library("layouts");
		$this->load->model(array('admin_model','common_model','emailtemplate_model','sms_model','smsadmin_model'));
		$this->load->helper('language');
		$this->lang->load('statictext', 'admin');
	} 
	
	/* * *********************************************************************
	 * * Function name : dashboard
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for admin dashboard
	 * * Date : 12 JANUARY 2018
	 * * **********************************************************************/
	public function dashboard($showtypeinc='')
	{
		//echo $showtypeinc; die;
		$this->admin_model->authCheck('admin');
		$data['error'] 						= 	'';	
                    // STUDENT COUNT
                     $whereCon['where'] = "   stubr.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
                    								 AND stubr.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
                    								 AND stubr.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
                    								 AND stubr.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";		
                     $tblName              = 'student_branch as stubr';
                            $data['ALLSTUDENTCOUNT']  = $this->admin_model->SelectStudentListData('count', $tblName, $whereCon, $shortField, '0', '0');
                            // TEACHER COUNT
                            $tblName 							= 	'users as user';
                            $con 								= 	'';
                            $whereCon['where']		 			= 	"user.franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."'
                            										 AND user.school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
                            										 AND user.branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."' 
                            										 AND user.user_type = 'Teacher'";		
                            $shortField 						= 	'user.user_f_name ASC';
                            $data['ALLSTEACHERCOUNT'] 		= 	$this->admin_model->SelectTeacherListData('count',$tblName,$whereCon,$shortField,'0','0');
                            // NOTIFICATION
                            $tblName 							= 	'sms_notification as sms_notification';
                            $con 								= 	'';
                            $whereCon['where']		 			=  "sms_notification.franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' 
                            									AND sms_notification.school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
                            									AND sms_notification.branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."'";		
                            $shortField 						= 	'UNIX_TIMESTAMP(sms_notification.creation_date) DESC';
                            $data['ALLNOTIFICATIONCOUNT'] 	= 	$this->admin_model->SelectNotificationData('count',$tblName,$whereCon,$shortField,'0','0');
                            
                            // NOTICEBOARD
                            $whereCon['where']		 			=  "snoboard.franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' 
                            									AND snoboard.school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
                            									AND snoboard.branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."'";		
                            $shortField 						= 	'UNIX_TIMESTAMP(snoboard.creation_date) DESC';
                            $tblName             				 = 'sms_notice_board as snoboard';
                            $con                 				 = '';
                            $data['ALLNOTICEBOARDCOUNT'] = $this->admin_model->SelectnoticeboardListData('count', $tblName, $whereCon, $shortField, '0', '0');
							
                            // HOLIDAYS COUNT
                            $whereCon['where']		 			= 	"h.franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' 
                            												 AND h.school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
                            												 AND h.branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."'";		
                            $shortField 						= 	'h.holiday_id DESC';
                            $tblName 							= 	'holiday as h';
                            $con 								= 	'';
                            $data['ALLHOLIDAYCOUNT'] 				= 	$this->admin_model->SelectHolidayData('count',$tblName,$whereCon,$shortField,'0','0');
                            
                    // SMS COUNT
                    $desgQuery	=  "SELECT mess.*,class.class_name,sect.class_section_name,sms_temp.sms_type,sms_temp.content,temp.login_type 
                    						FROM sms_multiple_student_msg mess 
                    						LEFT JOIN sms_templete_sms temp on mess.parent_id=temp.encrypt_id
                    						LEFT JOIN sms_classes class on temp.class_id=class.encrypt_id
                    						LEFT JOIN sms_class_section sect on temp.section_id=sect.encrypt_id
                    						LEFT JOIN sms_sms_template sms_temp on temp.template_id=sms_temp.encrypt_id 
                    						ORDER BY mess.id DESC";
					$data['ALLDATASMS']		=	$this->common_model->get_data_by_query('multiple',$desgQuery);
					

					 // SMS MASTER COUNT
					 
						$data['ALLADMINSMSCOUNT'] 				= 	$this->smsadmin_model->SelectsmsSUBAdminDatafordashboard();
						//echo "<pre>"; print_r($data['ALLSUPERADMINSMSCOUNT']); die;

								$slug_url			= 	'balance.php';
								$postData = array(
									'authkey' => RESSLER_AUTH_KEY,
									'route' => "B",
								);
							$returnuserid   	=   add_user($postData,$slug_url);
							$returndata   		=  (json_decode($returnuserid));
							$data['ALLSUPERADMINSMSCOUNT'] 					= 	$returndata;
							
                    		$this->layouts->set_title('Dashboard');
                    		$this->layouts->admin_view('dashboard',array(),$data);
                    	}	// END OF FUNCTION
                            
        
    /***********************************************************************
	** Function name : get_event
	** Developed By : Ashish UMrao
	** Purpose  : This function used for get events
	** Date : 13 March 2020
	************************************************************************/
	public function get_event(){    
		$events 			= 	array();
		
		$start				=	$this->input->post('start');
		$showtypeinc       =    "Holidays";
		$e 					= 	array();
		if($showtypeinc == 'Examination'):
			$e['id'] 			= 	1;
			$e['title'] 		= 	'testing';
			$e['eventtype']		= 	"Examination";
			$e['start'] 		= 	"2019-01-13T17:00:00";
			$e['end'] 			= 	"2019-01-13T20:00:00";
			$e['allDay'] 		= 	false;
			array_push($events, $e);
		elseif($showtypeinc == 'Holidays'):
            $holidayQuery       = "SELECT holiday_id,purpose,startdate,enddate FROM sms_holiday 
								 	 WHERE UNIX_TIMESTAMP(startdate) >= '".strtotime($start)."'
                                     AND franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
									 AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
									 AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
									 AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
									 AND status = 'Y'";
     		$holidaydata 		= $this->common_model->get_data_by_query('multiple', $holidayQuery);    
				//echo "<pre>"; print_r($holidaydata); die;	
			if($holidaydata <> ""): foreach($holidaydata as $holidayinfo):
				$e['id'] 			= 	$holidayinfo['holiday_id'];
				$e['title']			= 	"Holidays";
				$e['eventtype'] 	= 	$holidayinfo['purpose'];
				$e['start'] 		= 	removeTimeFromDate($holidayinfo['startdate'])."T01:00:00";
				$e['end'] 			= 	removeTimeFromDate($holidayinfo['enddate'])."T24:00:00";
				$e['allDay'] 		= 	true;
			array_push($events, $e);
			endforeach; endif;
		elseif($showtypeinc == 'Events'):
			$eventQuery       		= 	"SELECT event_id,purpose,from_date,to_date, sms_event.time FROM sms_event 
								 	 	WHERE UNIX_TIMESTAMP(from_date) >= '".strtotime($start)."'
                                     	AND franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
									 	AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
									 	AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
									 	AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
									 	AND status = 'Y'";
     		$eventdata 				= $this->common_model->get_data_by_query('multiple', $eventQuery);
			if($eventdata <> ""): foreach($eventdata as $eventinfo): //echo "<pre>"; print_r($eventdata); die;	
				$e['id'] 			= 	$eventinfo['event_id'];
				$e['title']			= 	"Events";
				$e['eventtype'] 	= 	$holidayinfo['purpose'];
				$e['start'] 		= 	removeTimeFromDate($eventinfo['from_date'])."T".$eventinfo['time'].':00';
				$e['end'] 			= 	removeTimeFromDate($eventinfo['to_date'])."T".$eventinfo['time'].':00';
				$e['allDay'] 		= 	false;
				array_push($events, $e);
			endforeach; endif;
		endif;
		
		echo json_encode($events); die;
	}	
	
	/***********************************************************************
	** Function name : get_event_data
	** Developed By : Ashish UMrao
	** Purpose  : This function used for get schedule
	** Date : 13 MArch 2020
	************************************************************************/
	public function get_event_data(){ 
		//if($this->input->post('id') && $this->input->post('eventtype')):
			// $id						=	1;
			 $calEvent					=	"Holidays";
			 $data['calEvent']			=	$calEvent;
			$date						=	$this->input->post('date');
			$eventtype					=	$this->input->post('eventtype');
			
			$finalDate    = addTimeInDate($date);
			//echo $finalDate; die;
			if($calEvent == 'Examination'):
				
				elseif($calEvent == 'Events'):
				$data['title']		=	'Events';
				$eventQuery       		= 	"SELECT event_id,purpose,from_date,to_date, sms_event.time FROM sms_event 
								 	 	WHERE (from_date) = '".($finalDate)."'
                                     	AND franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
									 	AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
									 	AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
									 	AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
									 	AND status = 'Y'";
				 $data['showdata']	=	$this->common_model->get_data_by_query('multiple', $eventQuery);    
				 $this->layouts->admin_view('student_classes_event',array(),$data,'onlyview');

				 elseif($calEvent == 'Holidays'):
					$data['title']		=	'Holiday details';
					$holidayQuery       = 	"SELECT holiday_id,purpose,startdate,enddate FROM sms_holiday 
									 	 WHERE startdate ='".$date."'
										  AND franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
										 AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
										 AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
										 AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
										 AND status = 'Y'";
	     			$data['showdata']	=	$this->common_model->get_data_by_query('multiple', $holidayQuery);    
				 	$this->layouts->admin_view('student_classes',array(),$data,'onlyview');
			endif; 
	}      
	
	public function getEncrptStaticData(){
		echo manojEncript('1');
	}

	/*****************************************************************************
	 * ***************************************************************************
	 * ***************************************************************************
	 */

	/***********************************************************************
	** Function name : get_event
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get events
	** Date : 14 OCTOBER 2017
	************************************************************************/
	// public function get_event_exam(){    //echo "AAA"; die;
	// 	$events 			= 	array();
	// 	$start				=	$this->input->post('start');
	// 	$e 					= 	array();
		
    //         $holidayQuery       = "SELECT id,name,startdate,enddate FROM sms_exam_term 
	// 							 	 WHERE UNIX_TIMESTAMP(startdate) >= '".strtotime($start)."'
    //                                  AND franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
	// 								 AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
	// 								 AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
	// 								 AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
	// 								 AND status = 'Y'";
    //  		$holidaydata 		= $this->common_model->get_data_by_query('multiple', $holidayQuery);    
	// 			//echo "<pre>"; print_r($holidaydata); die;	
	// 		if($holidaydata <> ""): foreach($holidaydata as $holidayinfo):
	// 			$e['id'] 			= 	$holidayinfo['holiday_id'];
	// 			$e['title']			= 	"Exam";
	// 			$e['eventtype'] 	= 	$holidayinfo['purpose'];
	// 			$e['start'] 		= 	removeTimeFromDate($holidayinfo['startdate'])."T01:00:00";
	// 			$e['end'] 			= 	removeTimeFromDate($holidayinfo['enddate'])."T24:00:00";
	// 			$e['allDay'] 		= 	true;
	// 		array_push($events, $e);
	// 		endforeach; endif;
		
	// 	echo json_encode($events); die;
	// }

}