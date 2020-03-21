<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roomtypelist extends CI_Controller {

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
	 * * Function name : Teacherlist
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for Teacherlist
	 * * Date : 18 JANUARY 2018
	 * * **********************************************************************/
	public function index()
	{	
		$this->admin_model->authCheck('admin','view_data');
		$this->admin_model->get_permission_type($data);
		$data['error'] 						= 	'';
		
		if($this->input->get('searchValue')):
			$sValue							=	$this->input->get('searchValue');
			$whereCon['like']		 		= 	"(room_type_name LIKE '%".$sValue."%' 
			                   					  OR room_capacity LIKE '%".$sValue."%' 
												  OR room_fee_monthly LIKE '%".$sValue."%' 
												  OR room_fee_yearly LIKE '%".$sValue."%' 
												)";
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchValue'] 			= 	'';
		endif;
		
		$whereCon['where']		 			= 	"hostel_room_type.franchise_id = '".$this->session->userdata('SMS_HOSTEL_ADMIN_FRANCHISE_ID')."'
												 AND hostel_room_type.school_id = '".$this->session->userdata('SMS_HOSTEL_ADMIN_SCHOOL_ID')."' 
												 AND hostel_room_type.branch_id = '".$this->session->userdata('SMS_HOSTEL_ADMIN_BRANCH_ID')."' AND hostel_room_type.hostel_id = '".$this->session->userdata('SMS_HOSTEL_ADMIN_HOSTEL_ID')."'";		
		$shortField 						= 	'hostel_room_type.room_type_name ASC';
		
		$this->load->library('pagination');
		$config['base_url'] 				= 	$this->session->userdata('SMS_HOSTEL_ADMIN_PATH').$this->router->fetch_class().'/index';
		$this->session->set_userdata('roomtypeAdminData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$config['suffix']					= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'hostel_room_type';
		$con 								= 	'';
		$config['total_rows'] 				= 	$this->admin_model->SelectRoomTypeListData('count',$tblName,$whereCon,$shortField,'0','0');
		
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
	   $config['uri_segment'] = 5;
        $this->pagination->initialize($config);

        if ($this->uri->segment(5)):
            $page = $this->uri->segment(5);
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
		
		$data['ALLDATA'] 					= 	$this->admin_model->SelectRoomTypeListData('data',$tblName,$whereCon,$shortField,$config['per_page'],$page); 

		$this->layouts->set_title('Manage room type details');
		$this->layouts->admin_view('roomtypelist/index',array(),$data);
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name : addeditdata
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for add edit data
	 * * Date : 19 JANUARY 2018
	 * * **********************************************************************/
	public function addeditdata($editid='')
	{	$data['userId'] =  	$editid ;			
		$data['error'] 				= 	'';
		
		$subQuery					=	"SELECT encrypt_id, facility_name FROM sms_hostel_room_facility
									 	";
		$data['FACILITYDATA']		=	$this->common_model->get_data_by_query('multiple',$subQuery);  
		
		if($editid):
			$this->admin_model->authCheck('admin','edit_data');
			$data['EDITDATA']		=	$this->common_model->get_data_by_encryptId('hostel_room_type',$editid);
			$subFeQuery				=	"SELECT facility_id FROM sms_hostel_room_type_facility WHERE room_type_id = '".$editid."'";
			$data['FACILITYIDS']		=	$this->common_model->get_ids_in_array('facility_id',$subFeQuery);
			
			
			
			
		else:
			$this->admin_model->authCheck('admin','add_data');
		endif;
		
		if($this->input->post('SaveChanges')):
			$error					=	'NO';
			
		
			$this->form_validation->set_rules('facility_id[]', 'Facility', 'trim|required');
			
			$this->form_validation->set_rules('room_type_name', 'Room type', 'trim|required');
                      $this->form_validation->set_rules('room_capacity', 'Capacity', 'trim|required');
                         $this->form_validation->set_rules('room_fee_monthly', 'Fee Monthly', 'trim|required');
                            $this->form_validation->set_rules('room_fee_yearly', 'Fee yearly', 'trim|required');
			
			
			if($this->form_validation->run() && $error == 'NO'):  //echo '<pre>'; print_r($_POST); die;
			
			
			
				$param['room_type_name']			= 	ucwords(addslashes($this->input->post('room_type_name')));
				$param['room_capacity']			= 	addslashes($this->input->post('room_capacity'));
                                $param['room_fee_monthly']			= 	addslashes($this->input->post('room_fee_monthly'));
                                $param['room_fee_yearly']			= 	addslashes($this->input->post('room_fee_yearly'));
				
				
				
				if($this->input->post('CurrentDataID') ==''):
					//////////////	Teacher table
					$param['franchise_id']		=	$this->session->userdata('SMS_HOSTEL_ADMIN_FRANCHISE_ID');
					$param['school_id']		=	$this->session->userdata('SMS_HOSTEL_ADMIN_SCHOOL_ID');
					$param['branch_id']		=	$this->session->userdata('SMS_HOSTEL_ADMIN_BRANCH_ID');
                                        	$param['hostel_id']		=	$this->session->userdata('SMS_HOSTEL_ADMIN_HOSTEL_ID');
					
					$param['creation_date']	=	currentDateTime();
					$param['created_by']		=	$this->session->userdata('SMS_HOSTEL_ADMIN_ID');
					$param['status']			=	'Y';
                                            $param['session_year']			=	CURRENT_SESSION;
					$ulastInsertId				=	$this->common_model->add_data('hostel_room_type',$param);
					
					$TUparam['encrypt_id']		=	manojEncript($ulastInsertId);
					$TUwhere['room_type_id']			=	$ulastInsertId;
					$this->common_model->edit_data_by_multiple_cond('hostel_room_type',$TUparam,$TUwhere);
					
					$roomTypeId						=	$TUparam['encrypt_id'];
					
					
				else:
					$roomTypeId						=	$this->input->post('CurrentDataID');
					
					
					$param['update_date']		=	currentDateTime();
					$param['updated_by']		=	$this->session->userdata('SMS_HOSTEL_ADMIN_ID');
					$this->common_model->edit_data('hostel_room_type',$param,$roomTypeId);
					
					
					
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;
				
				if($this->input->post('facility_id') && $roomTypeId):
					$facilityid					=	$this->input->post('facility_id');
					$this->common_model->delete_particular_data('hostel_room_type_facility','room_type_id',$roomTypeId);
					foreach($facilityid as $fasids):
						$TSparam['room_type_id']		=	$roomTypeId;
						$TSparam['facility_id']		=	$fasids;
						$TSparam['creation_date']	=	currentDateTime();
						$TSparam['created_by']		=	$this->session->userdata('SMS_HOSTEL_ADMIN_ID');
						$TSparam['status']			=	'Y';
                                                 $TSparam['session_year']			=	CURRENT_SESSION;
						$uslastInsertId				=	$this->common_model->add_data('hostel_room_type_facility',$TSparam);
						
						$TSUparam['encrypt_id']		=	manojEncript($uslastInsertId);
						$TSUwhere['room_type_facility_id']	=	$uslastInsertId;
						$this->common_model->edit_data_by_multiple_cond('hostel_room_type_facility',$TSUparam,$TSUwhere);
					endforeach;
				endif;				
				
				 
			redirect(correctLink('roomtypeAdminData',$this->session->userdata('SMS_HOSTEL_ADMIN_PATH').$this->router->fetch_class().'/index'));
                                 endif;
		endif;
		
		$this->layouts->set_title('Edit SMS_HOSTEL_ADMIN_PATH details');
		$this->layouts->admin_view('roomtypelist/addeditdata',array(),$data);
	}	// END OF FUNCTION	
	
	/***********************************************************************
	** Function name : changestatus
	** Developed By : Manoj Kumar
	** Purpose  : This function used for change status
	** Date : 16 JANUARY 2018
	************************************************************************/
	function changestatus($changeStatusId='',$statusType='')
	{  
		$this->admin_model->authCheck('admin','edit_data');
		
		$param['status']		=	$statusType;
		$this->common_model->edit_data('hostel_room_type',$param,$changeStatusId);
		
		$this->session->set_flashdata('alert_success',lang('statussuccess'));
		
	
		redirect(correctLink('hostelAdminData',$this->session->userdata('SMS_HOSTEL_ADMIN_PATH').$this->router->fetch_class().'/index'));
	}
	
	/***********************************************************************
	** Function name : get_view_data
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get view data.
	** Date : 18 JANUARY 2018
	************************************************************************/
	function get_view_data()
	{
		$html					=	'';
		if($this->input->post('viewid')):
			$viewId				=	$this->input->post('viewid'); 
			$viewData			=	$this->common_model->get_data_by_encryptId('hostel_room_type',$viewId);
                   
			if($viewData <> ""):
				$subSeQuery		=	"SELECT s.facility_name FROM sms_hostel_room_type_facility as ts LEFT JOIN sms_hostel_room_facility as s ON ts.facility_id=s.encrypt_id WHERE room_type_id = '".$viewId."'";
				$FACILITYIDS		=	$this->common_model->get_data_by_query('multiple',$subSeQuery);
				$subjectName	=	'';
				if($FACILITYIDS <> ""): $i=0;
					foreach($FACILITYIDS as $SUBJECTdataS):
						$subjectName	.=	$i>0?', ':'';
						$subjectName	.=	$SUBJECTdataS['facility_name'];
						$i++;
					endforeach;
				endif;
				
				
				
				$html			.=	'<table class="table border-none">
								  <tbody>
								    
									<tr>
                                                                         <td align="left" width="20%"><strong>Room Type</strong></td>
									  <td align="left" width="30%">'.stripslashes($viewData['room_type_name']).'</td>
                                                                               <td align="left" width="20%"><strong>Room Capacity</strong></td>
									  <td align="left" width="30%">'.stripslashes($viewData['room_capacity']).' person</td>
									 
									 
									</tr>
									
									<tr>
                                                                         <td align="left" width="20%"><strong>Available Bed</strong></td>
									  <td align="left" width="30%">'.stripslashes($viewData['available_bed']).'  &#8377;</td>
									  <td align="left" width="20%"><strong>Yearly Fee</strong></td>
									  <td align="left" width="30%">'.stripslashes($viewData['room_fee_yearly']).' &#8377;</td>
									 
									</tr>
									<tr>
									  <td align="left" width="20%"><strong>Facility</strong></td>
									  <td align="left" width="30%">'.stripslashes($subjectName).'</td>
									  <td align="left" width="20%">&nbsp;</td>
									  <td align="left" width="30%">&nbsp;</td>
									</tr>
									
									<tr>
									  <td align="left" width="20%"><strong>Status</strong></td>
									  <td align="left" width="30%">'.showStatus($viewData['status']).'</td>
									  <td align="left" width="20%">&nbsp;</td>
									  <td align="left" width="30%">&nbsp;</td>
									</tr>';
				$html			.=	'</tbody>
								</table>';
			endif;
		endif;
		echo $html; die;
	}
	
	
        
}
