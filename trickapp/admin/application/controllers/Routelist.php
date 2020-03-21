<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Routelist extends CI_Controller {

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
	 * * Function name : classlist
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for classlist
	 * * Date : 26 MARCH 2018
	 * * **********************************************************************/
	public function index()
	{		
		$this->admin_model->authCheck('admin','view_data');
		$this->admin_model->get_permission_type($data);
		$data['error'] 						= 	'';
		
		if($this->input->get('searchValue')):
			$sValue							=	$this->input->get('searchValue');
			$whereCon['like']		 		= 	"(route.route_name LIKE '%".$sValue."%' OR route.route_short_name LIKE '%".$sValue."%' OR roudet.stop_name LIKE '%".$sValue."%')";
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchValue'] 			= 	'';
		endif;
		
		$whereCon['where']		 			= 	"route.franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' 
												 AND route.school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."'
												 AND route.branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."'";		
		$shortField 						= 	'route.route_name ASC';
		
		$this->load->library('pagination');
		$config['base_url'] 				= 	$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index';
		$this->session->set_userdata('routeListAdminData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$config['suffix']					= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'route as route';
		$con 								= 	'';
		$config['total_rows'] 				= 	$this->admin_model->SelectRouteListData('count',$tblName,$whereCon,$shortField,'0','0');
		
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
		
		$data['ALLDATA'] 					= 	$this->admin_model->SelectRouteListData('data',$tblName,$whereCon,$shortField,$config['per_page'],$page); 

		$this->layouts->set_title('Manage route details');
		$this->layouts->admin_view('routelist/index',array(),$data);
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name : addeditdata
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for add edit data
	 * * Date : 26 MARCH 2018
	 * * **********************************************************************/
	public function addeditdata($editid='')
	{		
		$data['error'] 				= 	'';
		
		if($editid):
			$this->admin_model->authCheck('admin','edit_data');
			$data['EDITDATA']		=	$this->common_model->get_data_by_encryptId('route',$editid);
			
			$periodQuery			=	"SELECT route_detail_id, encrypt_id, stop_name, stop_latitude, stop_longitude, pickup_time, drop_time, fee
										 FROM sms_route_detail WHERE route_id = '".$editid."'";  
			$data['RDETAILDATA']	=	$this->common_model->get_data_by_query('multiple',$periodQuery);
		else:
			$this->admin_model->authCheck('admin','add_data');
		endif;
		
		if($this->input->post('SaveChanges')):	
			$this->form_validation->set_rules('route_name', 'Route name', 'trim|required');
			$this->form_validation->set_rules('route_short_name', 'Route short name', 'trim|required');
			
			$TotalRouteCount 		= 	$this->input->post('TotalRouteCount');
			if($TotalRouteCount):  
				for($i=1; $i <= $TotalRouteCount; $i++):
					$this->form_validation->set_rules('route_detail_id_'.$i, 'Route Id', 'trim');
					$this->form_validation->set_rules('stop_name_'.$i, 'Stop name', 'trim|required');
					$this->form_validation->set_rules('stop_latitude_'.$i, 'Stop latitude', 'trim');
					$this->form_validation->set_rules('stop_longitude_'.$i, 'Stop longitude', 'trim');
					$this->form_validation->set_rules('pickup_time_'.$i, 'Pickup time', 'trim|required');
					$this->form_validation->set_rules('drop_time_'.$i, 'Drop time', 'trim|required');
					$this->form_validation->set_rules('fee_'.$i, 'Fee', 'trim|required');

					$data['route_detail_id_'.$i]	=	$this->input->post('route_detail_id_'.$i);
					$data['stop_name_'.$i]			=	$this->input->post('stop_name_'.$i);
					$data['stop_latitude_'.$i]		=	$this->input->post('stop_latitude_'.$i);
					$data['stop_longitude_'.$i]		=	$this->input->post('stop_longitude_'.$i);
					$data['pickup_time_'.$i]		=	$this->input->post('pickup_time_'.$i);
					$data['drop_time_'.$i]			=	$this->input->post('drop_time_'.$i);
					$data['fee_'.$i]				=	$this->input->post('fee_'.$i);
				endfor;
				$this->form_validation->set_rules('TotalRouteCount', 'lang:Total Route', 'trim');
			endif;
			
			if($this->form_validation->run()):   
			
				$param['route_name']			= 	addslashes($this->input->post('route_name'));
				$param['route_short_name']		= 	addslashes($this->input->post('route_short_name'));
				
				if($this->input->post('CurrentDataID') ==''):
					$param['franchise_id']		=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
					$param['school_id']			=	$this->session->userdata('SMS_ADMIN_SCHOOL_ID');
					$param['branch_id']			=	$this->session->userdata('SMS_ADMIN_BRANCH_ID');
					$param['creation_date']		=	currentDateTime();
					$param['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$param['status']			=	'Y';
                                            $param['session_year']			=	CURRENT_SESSION;
					$alastInsertId				=	$this->common_model->add_data('route',$param);
					
					$Uparam['encrypt_id']		=	manojEncript($alastInsertId);
					$Uwhere['route_id']			=	$alastInsertId;
					$this->common_model->edit_data_by_multiple_cond('route',$Uparam,$Uwhere);
					$this->session->set_flashdata('alert_success',lang('addsuccess'));
					
					$routeId					=	$Uparam['encrypt_id'];
					
					if($TotalRouteCount):  
						for($i=1; $i <= $TotalRouteCount; $i++):
							if($this->input->post('stop_name_'.$i) && $this->input->post('pickup_time_'.$i) && $this->input->post('drop_time_'.$i) && $this->input->post('fee_'.$i)):
								$RDparams['route_id']				=	$routeId;
								$RDparams['stop_name']				=	addslashes($this->input->post('stop_name_'.$i));
								$RDparams['stop_latitude']			=	addslashes($this->input->post('stop_latitude_'.$i));
								$RDparams['stop_longitude']			=	addslashes($this->input->post('stop_longitude_'.$i));
								$RDparams['pickup_time']			=	addslashes($this->input->post('pickup_time_'.$i));
								$RDparams['drop_time']				=	addslashes($this->input->post('drop_time_'.$i));
								$RDparams['fee']					=	addslashes($this->input->post('fee_'.$i));
                                                                    $RDparams['session_year']			=	CURRENT_SESSION;
								$RDlastInsertId						=	$this->common_model->add_data('route_detail',$RDparams);
								
								$RDUparam['encrypt_id']				=	manojEncript($RDlastInsertId);
								$RDUwhere['route_detail_id']		=	$RDlastInsertId;
								$this->common_model->edit_data_by_multiple_cond('route_detail',$RDUparam,$RDUwhere);
							endif;
						endfor;
					endif;
					
				else:
					$routeId					=	$this->input->post('CurrentDataID');
					$param['update_date']		=	currentDateTime();
					$param['updated_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$this->common_model->edit_data('route',$param,$routeId);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
					
					$routedataarray			=	array();
					if($TotalRouteCount):  
						for($i=1; $i <= $TotalRouteCount; $i++):
							if($this->input->post('stop_name_'.$i) && $this->input->post('pickup_time_'.$i) && $this->input->post('drop_time_'.$i) && $this->input->post('fee_'.$i)):
								if($this->input->post('route_detail_id_'.$i)):
									$routeDetailId						=	$this->input->post('route_detail_id_'.$i);
									
									$RDUUparams['stop_name']			=	addslashes($this->input->post('stop_name_'.$i));
									$RDUUparams['stop_latitude']		=	addslashes($this->input->post('stop_latitude_'.$i));
									$RDUUparams['stop_longitude']		=	addslashes($this->input->post('stop_longitude_'.$i));
									$RDUUparams['pickup_time']			=	addslashes($this->input->post('pickup_time_'.$i));
									$RDUUparams['drop_time']			=	addslashes($this->input->post('drop_time_'.$i));
									$RDUUparams['fee']					=	addslashes($this->input->post('fee_'.$i));
									
									$RDUUwhere['encrypt_id']			=	$routeDetailId;
									$RDUUwhere['route_id']				=	$routeId;
									$this->common_model->edit_data_by_multiple_cond('route_detail',$RDUUparams,$RDUUwhere);
									
									array_push($routedataarray,$routeDetailId);
								else:
									$RDparams['route_id']				=	$routeId;
									$RDparams['stop_name']				=	addslashes($this->input->post('stop_name_'.$i));
									$RDparams['stop_latitude']			=	addslashes($this->input->post('stop_latitude_'.$i));
									$RDparams['stop_longitude']			=	addslashes($this->input->post('stop_longitude_'.$i));
									$RDparams['pickup_time']			=	addslashes($this->input->post('pickup_time_'.$i));
									$RDparams['drop_time']				=	addslashes($this->input->post('drop_time_'.$i));
									$RDparams['fee']					=	addslashes($this->input->post('fee_'.$i));
                                                                         $RDparams['session_year']			=	CURRENT_SESSION;
									$RDlastInsertId						=	$this->common_model->add_data('route_detail',$RDparams);
									
									$RDUparam['encrypt_id']				=	manojEncript($RDlastInsertId);
									$RDUwhere['route_detail_id']		=	$RDlastInsertId;
									$this->common_model->edit_data_by_multiple_cond('route_detail',$RDUparam,$RDUwhere);
								endif;
							endif;
						endfor;
						if($data['RDETAILDATA'] <> "" && count($routedataarray)>0):
							foreach($data['RDETAILDATA'] as $RDETAILINFO):
								if(!in_array($RDETAILINFO['encrypt_id'],$routedataarray)):
									$this->common_model->delete_data('route_detail',$RDETAILINFO['encrypt_id']);
								endif;
							endforeach;
						endif;						
					endif;	
					
				endif;
				
				redirect(correctLink('routeListAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
			endif;
		endif;
		
		$this->layouts->set_title('Edit route details');
		$this->layouts->admin_view('routelist/addeditdata',array(),$data);
	}	// END OF FUNCTION	
	
	/***********************************************************************
	** Function name : changestatus
	** Developed By : Manoj Kumar
	** Purpose  : This function used for change status
	** Date : 26 MARCH 2018
	************************************************************************/
	function changestatus($changeStatusId='',$statusType='')
	{  
		$this->admin_model->authCheck('admin','edit_data');
		
		$param['status']		=	$statusType;
		$this->common_model->edit_data('route',$param,$changeStatusId);
		
		$this->session->set_flashdata('alert_success',lang('statussuccess'));
		
		redirect(correctLink('routeListAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
	}
	
	/* * *********************************************************************
	 * * Function name : assignvehicle
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for assign vehicle
	 * * Date : 26 MARCH 2018
	 * * **********************************************************************/
	public function assignvehicle($editid='')
	{		
		$data['error'] 				= 	'';
		
		if($editid):
			$this->admin_model->authCheck('admin','edit_data');
               
			$data['ROUTEDATA']		=	$this->common_model->get_data_by_encryptId('route',$editid);
			
			$assignQuery			=	"SELECT route_assign_id,encrypt_id,vehicle_id,driver_id,conductor_id,attendant_id
										 FROM sms_route_assign WHERE route_id = '".$editid."' ORDER BY vehicle_id ASC"; 
			$data['EDITDATA']		=	$this->common_model->get_data_by_query('single',$assignQuery);
			
			$AsVelQuery         	= 	"SELECT vehicle_id FROM sms_route_assign WHERE status = 'Y'";
			$AssVelIds			 	= 	$this->common_model->get_ids_in_array('vehicle_id', $AsVelQuery);
                        
                         if($AssVelIds):
                           foreach ($AssVelIds   AS $key => $value):
                        
                           if($value == $data['EDITDATA']['vehicle_id']):
                               unset($AssVelIds[$key]) ;
                           endif;
                           
                           endforeach;
                           
                       endif;
                        
			
			$velQuery				=	"SELECT vel.encrypt_id, vel.vehicle_no, velt.vehicle_type_name
										 FROM sms_vehicle AS vel
										 LEFT JOIN sms_vehicle_types as velt ON vel.vehicle_type=velt.encrypt_id
										 WHERE vel.franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' 
										 AND vel.school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."'
										 AND vel.branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."'";
                        
                        
                        
                        
				if($AssVelIds):							 
					$velQuery		.=	"AND vel.encrypt_id NOT IN ('".implode("','",$AssVelIds)."')";
			 	endif;					 
			$velQuery				.=	"ORDER  BY vehicle_no ASC";  
			$data['VEHICLEDATA']	=	$this->common_model->get_data_by_query('multiple',$velQuery);
			
			$AsDriQuery         	= 	"SELECT driver_id FROM sms_route_assign WHERE status = 'Y'";
			$AssDriIds			 	= 	$this->common_model->get_ids_in_array('driver_id', $AsDriQuery);
                       if($AssDriIds):
                           foreach ($AssDriIds   AS $key => $value):
                        
                           if($value == $data['EDITDATA']['driver_id']):
                               unset($AssDriIds[$key]) ;
                           endif;
                           
                           endforeach;
                           
                       endif;

			$driQuery				=	"SELECT encrypt_id,user_f_name,user_m_name,user_l_name,user_email
										 FROM sms_users 
										 WHERE franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' 
										 AND school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."'
										 AND branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."'
										 AND user_type = 'Driver' AND status = 'A'    "; 
                        
				if($AssDriIds):							 
					$driQuery		.=	"AND encrypt_id NOT IN ('".implode("','",$AssDriIds)."')";
			 	endif;							 
			$driQuery				.=	"ORDER BY user_f_name ASC";
                      
			$data['DRIVERDATA']	=	$this->common_model->get_data_by_query('multiple',$driQuery);
			
			$AsConQuery         	= 	"SELECT conductor_id FROM sms_route_assign WHERE status = 'Y'";
			$AssConIds			 	= 	$this->common_model->get_ids_in_array('conductor_id', $AsConQuery);
			
                         if($AssConIds):
                           foreach ($AssConIds   AS $key => $value):
                        
                           if($value == $data['EDITDATA']['conductor_id']):
                               unset($AssConIds[$key]) ;
                           endif;
                           
                           endforeach;
                           
                       endif;
                        
                        
			$conducQuery			=	"SELECT encrypt_id,user_f_name,user_m_name,user_l_name,user_email
										 FROM sms_users 
										 WHERE franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' 
										 AND school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."'
										 AND branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."'
										 AND user_type = 'Conductor' AND status = 'A'"; 
				if($AssConIds):							 
					$conducQuery	.=	"AND encrypt_id NOT IN ('".implode("','",$AssConIds)."')";
			 	endif;							 
			$conducQuery			.=	"ORDER BY user_f_name ASC";
			$data['CONDUCTORDATA']	=	$this->common_model->get_data_by_query('multiple',$conducQuery);
			
			$AsAttenQuery         	= 	"SELECT attendant_id FROM sms_route_assign WHERE status = 'Y'";
			$AssAttenIds			= 	$this->common_model->get_ids_in_array('attendant_id', $AsAttenQuery);
			  if($AssAttenIds):
                           foreach ($AssAttenIds   AS $key => $value):
                        
                           if($value == $data['EDITDATA']['attendant_id']):
                               unset($AssAttenIds[$key]) ;
                           endif;
                           
                           endforeach;
                           
                       endif;
			$attendQuery			=	"SELECT encrypt_id,user_f_name,user_m_name,user_l_name,user_email,user_type
										 FROM sms_users 
										 WHERE franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' 
										 AND school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."'
										 AND branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."'
										 AND (user_type = 'Teacher' OR user_type = 'Attendant') AND status = 'A'"; 
				if($AssAttenIds):							 
					$attendQuery	.=	"AND encrypt_id NOT IN ('".implode("','",$AssAttenIds)."')";
			 	endif;							 
			$attendQuery			.=	"ORDER BY user_f_name ASC";
			$data['ATTENDANTDATA']	=	$this->common_model->get_data_by_query('multiple',$attendQuery);
		else:
			$this->admin_model->authCheck('admin','add_data');
		endif;
		
		if($this->input->post('SaveChanges')):	
			$this->form_validation->set_rules('vehicle_id', 'Vehicle', 'trim|required');
			$this->form_validation->set_rules('driver_id', 'Driver', 'trim|required');
			$this->form_validation->set_rules('conductor_id', 'Conductor', 'trim|required');
			$this->form_validation->set_rules('attendant_id', 'Attendanct', 'trim|required');
			
			if($this->form_validation->run()):   
			
				$param['vehicle_id']			= 	addslashes($this->input->post('vehicle_id'));
				$param['driver_id']				= 	addslashes($this->input->post('driver_id'));
				$param['conductor_id']			= 	addslashes($this->input->post('conductor_id'));
				$param['attendant_id']			= 	addslashes($this->input->post('attendant_id'));
				
				if($this->input->post('CurrentDataID') ==''):
					$param['route_id']			=	addslashes($this->input->post('route_id'));
					$param['creation_date']		=	currentDateTime();
					$param['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
                                         $param['session_year']			=	CURRENT_SESSION;
					$param['status']			=	'Y';
                                            $param['session_year']			=	CURRENT_SESSION;
					$alastInsertId				=	$this->common_model->add_data('route_assign',$param);
					
					$Uparam['encrypt_id']		=	manojEncript($alastInsertId);
					$Uwhere['route_assign_id']	=	$alastInsertId;
					$this->common_model->edit_data_by_multiple_cond('route_assign',$Uparam,$Uwhere);
					$this->session->set_flashdata('alert_success',lang('addsuccess'));
					
				else:
					$assignId					=	$this->input->post('CurrentDataID');
					$param['update_date']		=	currentDateTime();
					$param['updated_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$this->common_model->edit_data('route_assign',$param,$assignId);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
					
				endif;
				
				redirect(correctLink('routeListAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
			endif;
		endif;
	
		$this->layouts->set_title('Assign vehicle details');
		$this->layouts->admin_view('routelist/assignvehicle',array(),$data);
	}	// END OF FUNCTION	
	
	/***********************************************************************
	** Function name : getviewdata
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get view data.
	** Date : 27 MARCH 2018
	************************************************************************/
	function getviewdata($viewid='')
	{
		$this->admin_model->authCheck('admin','view_data');
		$html					=	'';
		if($viewid):
			$viewId						=	$viewid; 
			$data['ROUTEDATA']			=	$this->common_model->get_data_by_encryptId('route',$viewId);
			if($data['ROUTEDATA'] <> ""):
				$assignQuery			=	"SELECT stop_name,stop_latitude,stop_longitude,pickup_time,drop_time,fee
											 FROM sms_route_detail WHERE route_id = '".$viewId."' ORDER BY route_detail_id ASC"; 
				$data['ROUTEDDATA']		=	$this->common_model->get_data_by_query('multiple',$assignQuery);
				
				$assignQuery			=	"SELECT routa.route_assign_id,routa.encrypt_id,
											 routa.vehicle_id,vel.vehicle_no,velt.vehicle_type_name,vel.no_of_seat,vel.max_seat_extend,
											 routa.driver_id,CONCAT(duser.user_f_name,' ',duser.user_m_name,' ',duser.user_l_name) as driver_name,duser.user_email as driver_email,
											 routa.conductor_id,CONCAT(cuser.user_f_name,' ',cuser.user_m_name,' ',cuser.user_l_name) as conductor_name,cuser.user_email as conductor_email,
											 routa.attendant_id,CONCAT(auser.user_f_name,' ',auser.user_m_name,' ',auser.user_l_name) as attendant_name,auser.user_email as attendant_email
											 FROM sms_route_assign as routa 
											 LEFT JOIN sms_vehicle AS vel ON routa.vehicle_id=vel.encrypt_id
											 LEFT JOIN sms_vehicle_types as velt ON vel.vehicle_type=velt.encrypt_id
											 LEFT JOIN sms_users as duser  ON routa.driver_id=duser.encrypt_id
											 LEFT JOIN sms_users as cuser  ON routa.conductor_id=cuser.encrypt_id
											 LEFT JOIN sms_users as auser  ON routa.attendant_id=auser.encrypt_id
											 WHERE routa.route_id = '".$viewId."' ORDER BY routa.vehicle_id ASC"; 
				$data['ASSIGNDATA']		=	$this->common_model->get_data_by_query('single',$assignQuery);
				
				$this->layouts->admin_view('routelist/getviewdata',array(),$data);
			else:
				redirect(correctLink('routeListAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
			endif;  
		else:
			redirect(correctLink('routeListAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
		endif;
	}
}
