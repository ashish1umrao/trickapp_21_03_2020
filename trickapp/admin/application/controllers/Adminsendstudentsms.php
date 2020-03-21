<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminsendstudentsms extends CI_Controller {

	public function  __construct()  
	{ 
		parent:: __construct();
		$this->load->helper(array('form','url','html','path','form','cookie'));
		$this->load->library(array('email','session','form_validation','pagination','parser','encrypt'));
		error_reporting(E_ALL ^ E_NOTICE);  
		$this->load->library("layouts");
		$this->load->model(array('admin_model','common_model','emailtemplate_model','sms_model'));
		$this->load->helper('language');
		$this->lang->load('statictext', 'admin');
	} 
	
	/* * *********************************************************************
	 * * Function name : SMS Templete
	 * * Developed By : Ashish UMrao
	 * * Purpose  : This function used for subjectlist
	 * * Date : 14 JUNE 2019
	 * * **********************************************************************/
	public function index()
	{	
		$this->admin_model->authCheck('admin','view_data');
		$this->admin_model->get_permission_type($data);
		$data['error'] 						= 	'';
		
		if($this->input->get('searchValue')):
			$sValue							=	$this->input->get('searchValue');
			$whereCon['like']		 		= 	"(tsms.subject_name LIKE '%".$sValue."%' OR tsms.subject_head_short_name LIKE '%".$sValue."%')";
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchValue'] 			= 	'';
		endif;
		
		$whereCon['where']		 			= 	"tsms.franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."'
												 AND tsms.school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
												 AND tsms.branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."'
												 AND tsms.board_id = '".$this->session->userdata('SMS_ADMIN_BOARD_ID')."'";		
		$shortField 						= 	'tsms.id ASC';
		
		$this->load->library('pagination');
		$config['base_url'] 				= 	$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index';
		$this->session->set_userdata('smstempleteAdminData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$config['suffix']					= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'templete_sms as tsms';
		$con 								= 	'';
		$config['total_rows'] 				= 	$this->admin_model->SelectSMStemplateDatashow('count',$tblName,$whereCon,$shortField,'0','0');
		//print "<pre>"; print_r($config['total_rows']); die;
		if($this->input->get('showLength') == 'All'):
			$config['per_page']	 			= 	$config['total_rows'];
			$data['perpage'] 				= 	$this->input->get('showLength');  
		elseif($this->input->get('showLength')):
			$config['per_page']	 			= 	$this->input->get('showLength'); 
			$data['perpage'] 				= 	$this->input->get('showLength'); 
		else:
			$config['per_page']	 			= 	SHOW_NO_OF_DATA;
			$data['perpage'] 				= 	SHOW_NO_OF_DATA; 
		endif;
		$config['uri_segment'] = getUrlSegment();
       $this->pagination->initialize($config);

       if ($this->uri->segment(getUrlSegment())):
           $page = $this->uri->segment(getUrlSegment());
       else:
           $page = 0;
       endif;
		$data['forAction'] 					= 	$config['base_url']; 
		if($config['total_rows']):
			$first							=	($page)+1;
			$data['first']					=	$first;
			$last							=	(($page)+$data['perpage'])>$config['total_rows']?$config['total_rows']:(($page)+$data['perpage']);
			$data['noOfContent']			=	'Showing '.$first.'-'.$last.' of '.$config['total_rows'].' items';
		else:
			$data['first']					=	1;
			$data['noOfContent']			=	'';
		endif;
		//$data['ALLDATA']		=	$this->common_model->get_data_by_query('multiple',$desgQuery);
		$data['ALLDATA'] 					= 	$this->admin_model->SelectSMStemplateDatashow('data',$tblName,$whereCon,$shortField,$config['per_page'],$page); 

		//print "<pre>"; print_r($data['ALLDATA']); die;
		$this->layouts->set_title('Manage Sms Templete');
		$this->layouts->admin_view('smstemplete/index',array(),$data);		
	}	// END OF FUNCTION
	

	/* * *********************************************************************
	 * * Function name : addeditdata
	 * * Developed By : Jitendra Singh 
	 * * Purpose  : This function used for add edit data
	 * * Date : 30 APRIL 2019
	 * * **********************************************************************/
	public function addeditdata($editid='')
	{	
		//print "<pre>"; print_r($this->session->userdata()); die;
		$data['error'] 	= 	'';		
		$desgQuery		=	"SELECT encrypt_id, name
							FROM sms_set_assessment
							WHERE school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
							AND branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."' 
							AND board_id = '".$this->session->userdata('SMS_ADMIN_BOARD_ID')."' 
							AND status='Y'";

		$data['SHEADDATA']	=	$this->common_model->get_data_by_query('multiple',$desgQuery);
		$desgQuery			=	"SELECT encrypt_id, name
								FROM sms_exam_term
								WHERE school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
								AND branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."' 
								AND board_id = '".$this->session->userdata('SMS_ADMIN_BOARD_ID')."' 
								AND status='Y'";

		$data['SHEADDATA1']			=	$this->common_model->get_data_by_query('multiple',$desgQuery);
		//$config['base_url'] 				= 	$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index';
		//$this->session->set_userdata('smstempleteAdminData',currentFullUrl());
		$smstemplategQuery	  =	 "SELECT encrypt_id, sms_type FROM sms_sms_template WHERE status = 'A'";
		$data['SMSTEMPLATE']  =	 $this->common_model->get_data_by_query('multiple',$smstemplategQuery);
		
		$subQuery          = "SELECT encrypt_id, class_name FROM sms_classes
							WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
							AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
							AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
							AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";
        $data['CLASSDATA'] = $this->common_model->get_data_by_query('multiple', $subQuery);
		
		$data['error'] 				= 	'';
		if($editid):
			$this->admin_model->authCheck('admin','edit_data');
			$data['EDITDATA']		=	$this->common_model->get_data_by_encryptId('sms_templete_sms',$editid);
		else:
			$this->admin_model->authCheck('admin','add_data');
		endif;
		if($this->input->post('SaveChanges')):	
		//echo "<pre>"; print_r($this->session->userdata()); die;
			$error					=	'NO';
			$this->form_validation->set_rules('class_id', 'class name', 'trim|required');
			$this->form_validation->set_rules('section_id', 'Section name', 'trim|required');
			if($this->form_validation->run() && $error == 'NO'): 
				$param['class_id'] 				= addslashes($this->input->post('class_id'));
				$param['section_id'] 			= addslashes($this->input->post('section_id'));
				$param['template_id'] 			= addslashes($this->input->post('template_id'));
				$param['login_type'] 			= $this->session->userdata('SMS_ADMIN_TYPE');	
				$param['from_id']	 			= $this->session->userdata('SMS_ADMIN_ID');
				$number							= "8979795865";
				$name							= "Jiendra";		
			if($this->input->post('CurrentDataID') ==''):
					$param['franchise_id']		=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
					$param['school_id']			=	$this->session->userdata('SMS_ADMIN_SCHOOL_ID');
					$param['branch_id']			=	$this->session->userdata('SMS_ADMIN_BRANCH_ID');
					$param['board_id']			=	$this->session->userdata('SMS_ADMIN_BOARD_ID');
					$param['creation_date']		=	currentDateTime();
					$param['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$param['status']			=	'Y';
			//echo "<pre>"; print_r($param); die;
				   	$alastInsertId				=	$this->common_model->add_data('sms_templete_sms',$param);					
					$Uparam['encrypt_id']		=	manojEncript($alastInsertId);
					$Uwhere['id']				=	$alastInsertId;
					$ParentID 					= 	$Uparam['encrypt_id']; 
					$this->common_model->edit_data_by_multiple_cond('sms_templete_sms',$Uparam,$Uwhere);
					$this->session->set_flashdata('alert_success',lang('addsuccess'));
				else:
					$subjectHeadId				=	$this->input->post('CurrentDataID');			
					$this->common_model->edit_data('sms_templete_sms',$param,$subjectHeadId);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;

				 $STUDENTID				=	$this->input->post('student_id');
				 if($STUDENTID <> ""):
				 	foreach($STUDENTID as $STUDENTINFO): //echo "<pre>";print_r($STUDENTINFO); die;			
				 		 $typeparam['to_id']		=	$STUDENTINFO;
						 $typeparam['status']		=	'A';
						 $typeparam['creation_date']=	currentDateTime();						 
						 $typeparam['created_by']	=	$this->session->userdata('SMS_ADMIN_ID');

						  $subQuery  = "SELECT stupar.*  ,par.user_f_name ,par.user_m_name,par.user_f_name,par.user_l_name ,par.user_email ,  par.user_email ,
                    par.user_mobile ,par.user_phone,par.user_password ,det.user_pic , det.user_income ,det.user_occupation,det.user_income,det.user_education ,  det.user_age,
					det.user_pan_card ,det.user_adhaar_card,COALESCE(stu.student_f_name,' ',stu.student_m_name,' ',stu.student_l_name) as student_name			
                    FROM sms_student_parent as stupar
					LEFT JOIN sms_users as par ON stupar.parent_id=par.encrypt_id
                    LEFT JOIN sms_user_detils as  det ON stupar.parent_id=det.user_id
                    LEFT JOIN sms_students as stu on stupar.student_qunique_id=stu.student_qunique_id
				 	WHERE stupar.student_qunique_id = '" . $typeparam['to_id'] . "' 
					AND stupar.parent_type = 'Father'";
					//print $subQuery; die;
					   $data['FEDITDATA'] = $this->common_model->get_data_by_query('single', $subQuery);
					$ContactNumber = $data['FEDITDATA']['user_mobile'];	
					$name = $data['FEDITDATA']['student_name'];

					$smsTemplateQuery	=	"SELECT * FROM sms_sms_template WHERE encrypt_id='".$param['template_id']."'";
					$smsTemplateData	=	$this->common_model->getDataByQuery('single',$smsTemplateQuery);

					$SMSTEMPLATE = $smsTemplateData['content'];
					//print $SMSTEMPLATE; die;

					$template1	= sms_template($SMSTEMPLATE,"{name}",$name); 		
					$template2  = sms_template($template1,"{number}",$ContactNumber); 
					$template3  = sms_template($template2,"{school name}",$this->session->userdata('SMS_ADMIN_BRANCH_NAME')); 
					   	
					   	$data['SMSDATA']	=	$this->sms_model->send_sms($ContactNumber,$template3);
//					   	print "<pre>"; print_r($data['SMSDATA']->MessageData[0]->MessageId); die;
					   	$MsgID = $data['SMSDATA']->MessageData[0]->MessageId;
						 $alastInsertId = $this->common_model->add_data('sms_multiple_student_msg',$typeparam);	
						 $Uparam['encrypt_id']		=	manojEncript($alastInsertId);
						 $Uparam['Number']			=	$ContactNumber;
						 $Uparam['MessageId']		=	$MsgID;
						 $Uparam['message']			=	$template3;
						 $Uparam['parent_id']		=	$ParentID;
						 $Uwhere['id']				=	$alastInsertId;						 
						 $this->common_model->edit_data_by_multiple_cond('sms_multiple_student_msg',$Uparam,$Uwhere);	
				 	endforeach;
				 endif;
				redirect(correctLink('smstempleteAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
			endif;
		endif;

		$smsTemplateQuery	=	"SELECT * FROM sms_sms_template WHERE 
		school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
		AND branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."' 
		AND board_id = '".$this->session->userdata('SMS_ADMIN_BOARD_ID')."' 
		AND status='A' ORDER BY template_id DESC";
//		print $studentQuery; die;
		$smsTemplateData	=	$this->common_model->getDataByQuery('multiple',$smsTemplateQuery);
		//print "<pre>"; print_r($smsTemplateData); die;
		$data['TEMPLATEDATA'] = $smsTemplateData;
		$this->layouts->set_title('Edit SMS Template');
		$this->layouts->admin_view('smstemplete/addeditdata',array(),$data);
	}	// END OF FUNCTION	
	
	
	
	/***********************************************************************
	** Function name : changestatus
	** Developed By : Jitendra Singh 
	** Purpose  : This function used for change status
	** Date : 30 APRIL 2019
	************************************************************************/
	function changestatus($changeStatusId='',$statusType='')
	{  
		$this->admin_model->authCheck('admin','edit_data');
		
		$param['status']		=	$statusType;
		$this->common_model->edit_data('sms_set_assessment',$param,$changeStatusId);
		
		$this->session->set_flashdata('alert_success',lang('statussuccess'));
		
		redirect(correctLink('smstempleteAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
	}

	function getStudentList(){
		//echo "okk"; die;
		 $class_id = $this->input->post('class_id');
		 $section_id = $this->input->post('section_id');	
		// $class_id = "TUFOT0oxNktVTUFS";	
		// $section_id = "TUFOT0oyMktVTUFS";
		$studentQuery	=	"SELECT stu.* FROM sms_student_class class 
		INNER JOIN sms_students stu on class.student_qunique_id=stu.student_qunique_id 
		WHERE class.class_id='".$class_id."' AND class.section_id='".$section_id."' 
		AND class.school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
		AND class.branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."' 
		AND class.board_id = '".$this->session->userdata('SMS_ADMIN_BOARD_ID')."' 
		AND class.status='Y'
		ORDER BY stu.student_id DESC";
//		print $studentQuery; die;
		$studentData	=	$this->common_model->getDataByQuery('multiple',$studentQuery);
		// /print "<pre>"; print_r($studentData); die;

		$count = $this->common_model->getDataByQuery('count',$studentQuery);
		//echo $count; die;
		if($count>0){		
		echo   '<select name="student_id[]" multiple id="langOpt3">';
		foreach ($studentData as $key => $value) {
			echo '<option value='.$value['student_qunique_id'].'>'.$value['student_f_name'].'</option>';
		}                
         echo   '</select>';         
 ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
 <script src="http://demos.codexworld.com/includes/js/bootstrap.js"></script>       
 <script src="http://demos.codexworld.com/multi-select-dropdown-list-with-checkbox-jquery/multiselect/jquery.multiselect.js"></script>
 <script>
$('#langOpt3').multiselect({
    columns: 1,
    placeholder: 'Select Student',
    search: true,
    selectAll: true
});
</script>
 <?php

		}
		
 ?>

 <?PHP
		//print $count; die;
		/*
		if($count>0){
		$response = array();
		foreach ($studentData as $key => $value) {
		$StudentArr = array('encrypt_id'=>$value['encrypt_id'],
							'student_f_name'=>$value['student_f_name']);
			array_push($response, $StudentArr);
		}
//		print "<pre>"; print_r(json_encode($response)); die;
		$data = json_encode($response);	
		echo $data;	
		}else{
			echo $count;
		}	*/		
	}
	

	function SendSMS(){
		$data['SMSDATA']	=	$this->sms_model->send_sms('9616871586',"Test Message Send By Jitendra Singh");

	}	


	function getPreviewSMS(){
		$template_id = $this->input->post('template_id');
		$smsTemplateQuery	=	"SELECT * FROM sms_sms_template WHERE encrypt_id='".$template_id."'";
		$smsTemplateData	=	$this->common_model->getDataByQuery('single',$smsTemplateQuery);

		$SMSTEMPLATE = $smsTemplateData['content'];			
		echo $SMSTEMPLATE; die;
	}
	
	function sendPushNotification(){
		$url = "https://fcm.googleapis.com/fcm/send";
		$token = "f8GmYOMwVLM:APA91bE6PpHquHfZQPc--SyY3JYZLR3aa1Y34-Zxc9riuddkcbaph-YQYegxIoH80nqrulN1hNb1s9YM-n3SLyKPMroTYA3iXdpS0T6Le3fbqkP6x5guTGf4Lq0qiyg7VXgwMVfC-LCH";
		$serverKey = 'AIzaSyDhfIfvFHWv6TT5jBnHjVR67nEyk6aYclU';
		
		$title = "Hello Jitendra Singh School Orissa";
		$body = "Body of the message";
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
	}
}
