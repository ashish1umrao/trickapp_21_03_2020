<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	
	var $postdata;
	var $user_agent;
	var $request_url;  
	var $method_name;
	
	public function  __construct() 
	{ 
		parent:: __construct();
		error_reporting(E_ALL ^ E_NOTICE);  
		$this->lang->load('statictext','api');
		
		$this->user_agent 		= 	$_SERVER['HTTP_USER_AGENT'];
		$this->request_url 		= 	$_SERVER['REDIRECT_URL'];
		$this->method_name 		= 	$_SERVER['REDIRECT_QUERY_STRING'];
	} 	

	/* * *********************************************************************
	 * * Function name : checkbranchcode
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for check branch code
	 * * Date : 06 DECEMBER 2018
	 * * **********************************************************************/
	public function checkbranchcode()
	{	
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();
		$returnData 						= 	array();
		if(requestAuthenticate(APIKEY)):  
			if($this->input->post('branchCode') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('BRANCH_CODE_EMPTY'),$result);
			else:
				$branchCode					=	trim($this->input->post('branchCode'));	

				$branchQuery				=	"SELECT admin_name
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					
					$result['branchCode']	=	$branchCode;						
					
					echo outPut(lang('SUCESS_STATUS'),lang('SUCESS_MESSAGE'),$result);
				else:
					echo outPut(lang('ERROR_STATUS'),lang('INVALID_BRANCH_CODE'),$result);
				endif;
			endif;
		else:
			echo outPut(lang('ERROR_STATUS'),lang('INVALID_APIKEY'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : getlogintype
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get login type
	 * * Date : 12 DECEMBER 2018
	 * * **********************************************************************/
	public function getlogintype()
	{	
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 						= 	array();
		$returnData 					= 	array();
		if(requestAuthenticate(APIKEY)):  
			$loginTypeQuery				=	"SELECT name
											 FROM ".getTablePrefix()."app_login_type";
			$loginTypeData				=	$this->common_model->getDataByQuery('multiple',$loginTypeQuery);
			if($loginTypeData <> ""):  
				
				$result['loginTypeList']=	$loginTypeData;						
				
				echo outPut(lang('SUCESS_STATUS'),lang('SUCESS_MESSAGE'),$result);
			else:
				echo outPut(lang('ERROR_STATUS'),lang('ERROR_MESSAGE'),$result);
			endif;
		else:
			echo outPut(lang('ERROR_STATUS'),lang('INVALID_APIKEY'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : userauthenticate
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for user authenticate
	 * * Date : 07 DECEMBER 2018
	 * * **********************************************************************/
	public function userauthenticate()
	{	
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();
		$returnData 						= 	array();
		if(requestAuthenticate(APIKEY)):  
			if($this->input->post('branchCode') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('BRANCH_CODE_EMPTY'),$result);
			elseif($this->input->post('userType') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_TYPE_EMPTY'),$result);
			elseif($this->input->post('userEmail') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_EMAIL_EMPTY'),$result);
			elseif($this->input->post('userPassword') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_PASSWORD_EMPTY'),$result);
			elseif($this->input->post('deviceId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('DEVICE_ID_EMPTY'),$result);
				elseif($this->input->post('deviceType') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('DEVICE_TYPE_EMPTY'),$result);
			else:
				$branchCode					=	trim($this->input->post('branchCode'));
				$userType					=	trim($this->input->post('userType'));	
				$userEmail					=	trim($this->input->post('userEmail'));	
				$userPassword				=	trim($this->input->post('userPassword'));	
				$deviceId					=	trim($this->input->post('deviceId'));	
				$deviceType					=	trim($this->input->post('deviceType'));		

				$branchQuery				=	"SELECT brd.admin_franchise_id as franchise_id, 
												 schd.encrypt_id as school_id, schd.admin_name as school_name,
												 brd.encrypt_id as branch_id, brd.branch_code as branch_code, brd.admin_name as branch_name
												 FROM ".getTablePrefix()."admin as brd 
												 LEFT JOIN ".getTablePrefix()."admin as schd ON  brd.admin_school_id=schd.encrypt_id
												 WHERE brd.branch_code = '".$branchCode."' AND brd.admin_type = 'Branch'
												 AND brd.admin_level = '3' AND brd.status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					
					$userQuery				=	"SELECT encrypt_id, employee_id, user_f_name, user_m_name, user_l_name, user_email,
					                             user_password, user_phone, user_type, user_last_login, status
												 FROM ".getTablePrefix()."users 
												 WHERE user_email = '".$userEmail."' AND user_type = '".$userType."'";					
					$userData				=	$this->common_model->getDataByQuery('single',$userQuery);
					//print "<pre>"; print_r($userData); die;
					if($userData <> ""):  
						$decryptUserPassword	=	$this->user_model->decryptsPassword($userData['user_password']);
						if($decryptUserPassword == $userPassword):
							if($userData['status'] == 'A'):

								$uParam['device_id']		=	$deviceId;
								$uParam['device_type']		=	$deviceType;
								$uParam['user_last_login']	=	currentDateTime();
								$this->common_model->editData('users',$uParam,'encrypt_id',$userData['encrypt_id']);

								$returnData['schoolId']		=	$branchData['school_id']?$branchData['school_id']:'';
								$returnData['schoolName']	=	$branchData['school_name']?stripslashes($branchData['school_name']):'';
								$returnData['branchId']		=	$branchData['branch_id']?$branchData['branch_id']:'';
								$returnData['branchCode']	=	$branchData['branch_code']?stripslashes($branchData['branch_code']):'';
								$returnData['branchName']	=	$branchData['branch_name']?stripslashes($branchData['branch_name']):'';
								
								$returnData['userId']		=	$userData['encrypt_id']?$userData['encrypt_id']:'';
								$returnData['EmployeeId']	=	$userData['employee_id']?$userData['employee_id']:'';
								$returnData['userFName']	=	$userData['user_f_name']?stripslashes($userData['user_f_name']):'';
								$returnData['userMName']	=	$userData['user_m_name']?stripslashes($userData['user_m_name']):'';
								$returnData['userLName']	=	$userData['user_l_name']?stripslashes($userData['user_l_name']):'';
								$returnData['userEmail']	=	$userData['user_email']?stripslashes($userData['user_email']):'';
								$returnData['userPhone']	=	$userData['user_phone']?stripslashes($userData['user_phone']):'';
								$returnData['userLastLogin']=	$userData['user_last_login']?date('M, d Y',strtotime($userData['user_last_login'])):'';

								$returnData['userType']		=	$userData['user_type']?$userData['user_type']:'';
								if($returnData['userType'] == 'Teacher'):
									$boardQuery				=	"SELECT bord.encrypt_id, bord.board_name, bord.board_short_name
																 FROM ".getTablePrefix()."teacher_subject as tsub
																 LEFT JOIN ".getTablePrefix()."subject as sub ON tsub.subject_id=sub.encrypt_id
																 LEFT JOIN ".getTablePrefix()."boards as bord ON sub.board_id=bord.encrypt_id
																 WHERE tsub.teacher_id = '".$returnData['userId']."' AND tsub.session_year = '".CURRENT_SESSION."'";
									$boardData				=	$this->common_model->getDataByQuery('multiple',$boardQuery);
									if($boardData <> ""):
										$returnData['boardId']	=	$boardData[0]['encrypt_id'];
										$returnData['boardName']=	$boardData[0]['board_name'];
										$returnData['boardList']=	$boardData;
									else:
										$returnData['boardId']	=	'';
										$returnData['boardName']=	'';
										$returnData['boardList']=	'';
									endif;
									$returnData['childId']		=	'';
									$returnData['childName']	=	'';
									$returnData['childClassId']	=	'';
									$returnData['childClass']	=	'';
									$returnData['childSectionId']=	'';
									$returnData['childSection']	=	'';
									$returnData['childList']	=	array();
								elseif($returnData['userType'] == 'Parent'):
									$returnData['boardId']	=	'';
									$returnData['boardName']=	'';
									$returnData['boardList']=	array();
									$childQuery				=	"SELECT stu.student_qunique_id, stu.student_f_name, stu.student_m_name, stu.student_l_name, 
																 clss.encrypt_id as class_id, clss.class_name,
																 sec.encrypt_id as section_id, sec.class_section_name as section_name
																 FROM ".getTablePrefix()."student_parent as spar
																 LEFT JOIN ".getTablePrefix()."students as stu ON spar.student_qunique_id=stu.student_qunique_id
																 LEFT JOIN ".getTablePrefix()."student_class as scls ON stu.student_qunique_id=scls.student_qunique_id
																 LEFT JOIN ".getTablePrefix()."classes as clss ON scls.class_id=clss.encrypt_id
																 LEFT JOIN ".getTablePrefix()."class_section as sec ON scls.section_id=sec.encrypt_id
																 WHERE spar.parent_id = '".$returnData['userId']."' AND spar.session_year = '".CURRENT_SESSION."'
																 GROUP BY stu.student_qunique_id";
									$childData				=	$this->common_model->getDataByQuery('multiple',$childQuery);
									if($childData <> ""):
										$returnData['childId']		=	$childData[0]['student_qunique_id'];
										$returnData['childName']	=	$childData[0]['student_f_name'].' '.$childData[0]['student_l_name'];
										$returnData['childClassId']	=	$childData[0]['class_id'];
										$returnData['childClass']	=	$childData[0]['class_name'];
										$returnData['childSectionId']=	$childData[0]['section_id'];
										$returnData['childSection']	=	$childData[0]['section_name'];
										$returnData['childList']	=	$childData;
									else:
										$returnData['childId']		=	'';
										$returnData['childName']	=	'';
										$returnData['childClassId']	=	'';
										$returnData['childClass']	=	'';
										$returnData['childSectionId']=	'';
										$returnData['childSection']	=	'';
										$returnData['childList']	=	[];
									endif;
								endif;
								$result['userData']			=	$returnData;						
						
								echo outPut(lang('SUCESS_STATUS'),lang('SUCESS_MESSAGE'),$result);
							else:
								echo outPut(lang('ERROR_STATUS'),lang('ACCOUNT_BLOCK'),$result);
							endif;
						else:
							echo outPut(lang('ERROR_STATUS'),lang('USER_CREDENTIAL_INCORRECT'),$result);
						endif;	
					else:
						echo outPut(lang('ERROR_STATUS'),lang('USER_CREDENTIAL_INCORRECT'),$result);
					endif;
				else:
					echo outPut(lang('ERROR_STATUS'),lang('INVALID_BRANCH_CODE'),$result);
				endif;
			endif;
		else:
			echo outPut(lang('ERROR_STATUS'),lang('INVALID_APIKEY'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : updatedeviceid
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for update device id
	 * * Date : 07 DECEMBER 2018
	 * * **********************************************************************/
	public function updatedeviceid()
	{	
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();
		$returnData 						= 	array();
		if(requestAuthenticate(APIKEY)):  
			if($this->input->post('branchCode') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('BRANCH_CODE_EMPTY'),$result);
			elseif($this->input->post('userType') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_TYPE_EMPTY'),$result);
			elseif($this->input->post('userId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_ID_EMPTY'),$result);
			elseif($this->input->post('deviceId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('DEVICE_ID_EMPTY'),$result);
			elseif($this->input->post('deviceType') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('DEVICE_TYPE_EMPTY'),$result);
			else:
				$branchCode					=	trim($this->input->post('branchCode'));
				$userType					=	trim($this->input->post('userType'));
				$userId						=	trim($this->input->post('userId'));	
				$deviceId					=	trim($this->input->post('deviceId'));
				$deviceType					=	trim($this->input->post('deviceType'));				

				$branchQuery				=	"SELECT brd.admin_franchise_id as franchise_id, 
												 schd.encrypt_id as school_id, schd.admin_name as school_name,
												 brd.encrypt_id as branch_id, brd.branch_code as branch_code, brd.admin_name as branch_name
												 FROM ".getTablePrefix()."admin as brd 
												 LEFT JOIN ".getTablePrefix()."admin as schd ON  brd.admin_school_id=schd.encrypt_id
												 WHERE brd.branch_code = '".$branchCode."' AND brd.admin_type = 'Branch'
												 AND brd.admin_level = '3' AND brd.status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""): 
					$userQuery				=	"SELECT encrypt_id, employee_id, user_f_name, user_m_name, user_l_name, user_email,
					                             user_phone, user_type, user_last_login, status
												 FROM ".getTablePrefix()."users 
												 WHERE encrypt_id = '".$userId."' AND user_type = '".$userType."'";
					$userData				=	$this->common_model->getDataByQuery('single',$userQuery);
					if($userData <> ""):  

						$uParam['device_id']		=	$deviceId;
						$uParam['device_type']		=	$deviceType;
						$this->common_model->editData('users',$uParam,'encrypt_id',$userData['encrypt_id']);

						$returnData['schoolId']		=	$branchData['school_id']?$branchData['school_id']:'';
						$returnData['schoolName']	=	$branchData['school_name']?stripslashes($branchData['school_name']):'';
						$returnData['branchId']		=	$branchData['branch_id']?$branchData['branch_id']:'';
						$returnData['branchCode']	=	$branchData['branch_code']?stripslashes($branchData['branch_code']):'';
						$returnData['branchName']	=	$branchData['branch_name']?stripslashes($branchData['branch_name']):'';
						$returnData['userType']		=	$userData['user_type']?$userData['user_type']:'';
						$returnData['userId']		=	$userData['encrypt_id']?$userData['encrypt_id']:'';
						$returnData['EmployeeId']	=	$userData['employee_id']?$userData['employee_id']:'';
						$returnData['userFName']	=	$userData['user_f_name']?stripslashes($userData['user_f_name']):'';
						$returnData['userMName']	=	$userData['user_m_name']?stripslashes($userData['user_m_name']):'';
						$returnData['userLName']	=	$userData['user_l_name']?stripslashes($userData['user_l_name']):'';
						$returnData['userEmail']	=	$userData['user_email']?stripslashes($userData['user_email']):'';
						$returnData['userPhone']	=	$userData['user_phone']?stripslashes($userData['user_phone']):'';
						$returnData['userLastLogin']=	$userData['user_last_login']?date('M, d Y',strtotime($userData['user_last_login'])):'';

						$result['userData']			=	$returnData;						
				
						echo outPut(lang('SUCESS_STATUS'),lang('SUCESS_MESSAGE'),$result);
					else:
						echo outPut(lang('ERROR_STATUS'),lang('INVALID_USER_ID'),$result);
					endif;
				else:
					echo outPut(lang('ERROR_STATUS'),lang('INVALID_BRANCH_CODE'),$result);
				endif;
			endif;
		else:
			echo outPut(lang('ERROR_STATUS'),lang('INVALID_APIKEY'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : getdashboarddata
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get dashboard data
	 * * Date : 12 DECEMBER 2018
	 * * **********************************************************************/
	public function getdashboarddata()
	{	
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 						= 	array();
		$returnData 					= 	array();
		if(requestAuthenticate(APIKEY)):  
			if($this->input->post('userType') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_TYPE_EMPTY'),$result);
			else:
				$userType				=	trim($this->input->post('userType'));

				$menuQuery				=	"SELECT short_name, name, image
											FROM ".getTablePrefix()."app_dashboard_data
											WHERE type = '".$userType."' ORDER BY show_order ASC";
				$menuData				=	$this->common_model->getDataByQuery('multiple',$menuQuery);
				if($menuData <> ""):  
					
					$result['menuList']	=	$menuData;						
					
					echo outPut(lang('SUCESS_STATUS'),lang('SUCESS_MESSAGE'),$result);
				else:
					echo outPut(lang('ERROR_STATUS'),lang('INVALID_USER_TYPE'),$result);
				endif;
			endif;
		else:
			echo outPut(lang('ERROR_STATUS'),lang('INVALID_APIKEY'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : getprofiledata
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get profile data
	 * * Date : 07 DECEMBER 2018
	 * * **********************************************************************/
	public function getprofiledata()
	{	
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();
		$returnData 						= 	array();
		if(requestAuthenticate(APIKEY)):  
			if($this->input->post('branchCode') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('BRANCH_CODE_EMPTY'),$result);
			elseif($this->input->post('userType') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_TYPE_EMPTY'),$result);
			elseif($this->input->post('userId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_ID_EMPTY'),$result);
			else:
				$branchCode					=	trim($this->input->post('branchCode'));
				$userType					=	trim($this->input->post('userType'));
				$userId						=	trim($this->input->post('userId'));	

				$branchQuery				=	"SELECT brd.encrypt_id as branch_id
												 FROM ".getTablePrefix()."admin as brd 
												 WHERE brd.branch_code = '".$branchCode."' AND brd.admin_type = 'Branch'
												 AND brd.admin_level = '3' AND brd.status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""): 
					$userQuery				=	"SELECT usr.encrypt_id, usr.user_f_name, usr.user_m_name, usr.user_l_name, usr.user_email, usr.user_phone,
												 usradd.user_c_address, usradd.user_c_locality, usradd.user_c_city, usradd.user_c_state, usradd.user_c_zipcode
												 FROM ".getTablePrefix()."users as usr
												 LEFT JOIN ".getTablePrefix()."user_address as usradd ON usr.encrypt_id=usradd.user_id
												 WHERE usr.encrypt_id = '".$userId."' AND usr.user_type = '".$userType."'";
					$userData				=	$this->common_model->getDataByQuery('single',$userQuery);
					if($userData <> ""):  

						$returnData['userId']		=	$userData['encrypt_id']?$userData['encrypt_id']:'';
						$returnData['userFName']	=	$userData['user_f_name']?stripslashes($userData['user_f_name']):'';
						$returnData['userMName']	=	$userData['user_m_name']?stripslashes($userData['user_m_name']):'';
						$returnData['userLName']	=	$userData['user_l_name']?stripslashes($userData['user_l_name']):'';
						$returnData['userEmail']	=	$userData['user_email']?stripslashes($userData['user_email']):'';
						$returnData['userPhone']	=	$userData['user_phone']?stripslashes($userData['user_phone']):'';
						$returnData['userAddress']	=	$userData['user_c_address']?stripslashes($userData['user_c_address']):'';
						$returnData['userLocality']	=	$userData['user_c_locality']?stripslashes($userData['user_c_locality']):'';
						$returnData['userCity']		=	$userData['user_c_city']?stripslashes($userData['user_c_city']):'';
						$returnData['userState']	=	$userData['user_c_state']?stripslashes($userData['user_c_state']):'';
						$returnData['userZipcode']	=	$userData['user_c_zipcode']?stripslashes($userData['user_c_zipcode']):'';

						$result['userData']			=	$returnData;						
				
						echo outPut(lang('SUCESS_STATUS'),lang('SUCESS_MESSAGE'),$result);
					else:
						echo outPut(lang('ERROR_STATUS'),lang('INVALID_USER_ID'),$result);
					endif;
				else:
					echo outPut(lang('ERROR_STATUS'),lang('INVALID_BRANCH_CODE'),$result);
				endif;
			endif;
		else:
			echo outPut(lang('ERROR_STATUS'),lang('INVALID_APIKEY'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : updateprofiledata
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for update profile data
	 * * Date : 07 DECEMBER 2018
	 * * **********************************************************************/
	public function updateprofiledata()
	{	
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();
		$returnData 						= 	array();
		if(requestAuthenticate(APIKEY)):  
			if($this->input->post('branchCode') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('BRANCH_CODE_EMPTY'),$result);
			elseif($this->input->post('userType') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_TYPE_EMPTY'),$result);
			elseif($this->input->post('userId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_ID_EMPTY'),$result);
			elseif($this->input->post('userFName') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_FNAME_EMPTY'),$result);
			elseif($this->input->post('userMName') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_MNAME_EMPTY'),$result);
			elseif($this->input->post('userLName') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_LNAME_EMPTY'),$result);
			elseif($this->input->post('userEmail') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_EMAIL_EMPTY'),$result);
			elseif($this->input->post('userPhone') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_PHONE_EMPTY'),$result);
			elseif($this->input->post('userAddress') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_ADDRESS_EMPTY'),$result);
			elseif($this->input->post('userLocality') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_LOCALITY_EMPTY'),$result);
			elseif($this->input->post('userCity') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_CITY_EMPTY'),$result);
			elseif($this->input->post('userState') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_STATE_EMPTY'),$result);
			elseif($this->input->post('userZipcode') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_ZIPCODE_EMPTY'),$result);
			else:
				$branchCode					=	trim($this->input->post('branchCode'));
				$userType					=	trim($this->input->post('userType'));
				$userId						=	trim($this->input->post('userId'));	

				$branchQuery				=	"SELECT brd.encrypt_id as branch_id
												 FROM ".getTablePrefix()."admin as brd 
												 WHERE brd.branch_code = '".$branchCode."' AND brd.admin_type = 'Branch'
												 AND brd.admin_level = '3' AND brd.status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""): 
					$userQuery				=	"SELECT usr.encrypt_id
												 FROM ".getTablePrefix()."users as usr
												 WHERE usr.encrypt_id = '".$userId."' AND usr.user_type = '".$userType."'";
					$userData				=	$this->common_model->getDataByQuery('single',$userQuery);
					if($userData <> ""):  

						$uParam['user_f_name']		=	trim($this->input->post('userFName'));
						$uParam['user_m_name']		=	trim($this->input->post('userMName'));
						$uParam['user_l_name']		=	trim($this->input->post('userLName'));
						$uParam['user_email']		=	trim($this->input->post('userEmail'));
						$uParam['user_phone']		=	trim($this->input->post('userPhone'));
						$this->common_model->editData('users',$uParam,'encrypt_id',$userData['encrypt_id']);

						$uAParam['user_c_address']	=	trim($this->input->post('userAddress'));
						$uAParam['user_c_locality']	=	trim($this->input->post('userLocality'));
						$uAParam['user_c_city']		=	trim($this->input->post('userCity'));
						$uAParam['user_c_state']	=	trim($this->input->post('userState'));
						$uAParam['user_c_zipcode']	=	trim($this->input->post('userZipcode'));
						$this->common_model->editData('user_address',$uAParam,'user_id',$userData['encrypt_id']);

						$user1Query				=	"SELECT usr.encrypt_id, usr.user_f_name, usr.user_m_name, usr.user_l_name, usr.user_email, usr.user_phone,
													 usradd.user_c_address, usradd.user_c_locality, usradd.user_c_city, usradd.user_c_state, usradd.user_c_zipcode
													 FROM ".getTablePrefix()."users as usr
													 LEFT JOIN ".getTablePrefix()."user_address as usradd ON usr.encrypt_id=usradd.user_id
													 WHERE usr.encrypt_id = '".$userId."' AND usr.user_type = '".$userType."'";
						$user1Data				=	$this->common_model->getDataByQuery('single',$user1Query);

						$returnData['userId']		=	$user1Data['encrypt_id']?$user1Data['encrypt_id']:'';
						$returnData['userFName']	=	$user1Data['user_f_name']?stripslashes($user1Data['user_f_name']):'';
						$returnData['userMName']	=	$user1Data['user_m_name']?stripslashes($user1Data['user_m_name']):'';
						$returnData['userLName']	=	$user1Data['user_l_name']?stripslashes($user1Data['user_l_name']):'';
						$returnData['userEmail']	=	$user1Data['user_email']?stripslashes($user1Data['user_email']):'';
						$returnData['userPhone']	=	$user1Data['user_phone']?stripslashes($user1Data['user_phone']):'';
						$returnData['userAddress']	=	$user1Data['user_c_address']?stripslashes($user1Data['user_c_address']):'';
						$returnData['userLocality']	=	$user1Data['user_c_locality']?stripslashes($user1Data['user_c_locality']):'';
						$returnData['userCity']		=	$user1Data['user_c_city']?stripslashes($user1Data['user_c_city']):'';
						$returnData['userState']	=	$user1Data['user_c_state']?stripslashes($user1Data['user_c_state']):'';
						$returnData['userZipcode']	=	$user1Data['user_c_zipcode']?stripslashes($user1Data['user_c_zipcode']):'';

						$result['userData']			=	$returnData;						
				
						echo outPut(lang('SUCESS_STATUS'),lang('SUCESS_MESSAGE'),$result);
					else:
						echo outPut(lang('ERROR_STATUS'),lang('INVALID_USER_ID'),$result);
					endif;
				else:
					echo outPut(lang('ERROR_STATUS'),lang('INVALID_BRANCH_CODE'),$result);
				endif;
			endif;
		else:
			echo outPut(lang('ERROR_STATUS'),lang('INVALID_APIKEY'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : getgallerylist
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get gallery list
	 * * Date : 14 DECEMBER 2018
	 * * **********************************************************************/
	public function getgallerylist()
	{	
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();
		$returnData 						= 	array();
		if(requestAuthenticate(APIKEY)):  
			if($this->input->post('branchCode') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('BRANCH_CODE_EMPTY'),$result);
			elseif($this->input->post('userType') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_TYPE_EMPTY'),$result);
			elseif($this->input->post('userId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_ID_EMPTY'),$result);
			else:  
				$branchCode					=	trim($this->input->post('branchCode'));	
				$userType					=	trim($this->input->post('userType'));	
				$userId						=	trim($this->input->post('userId'));	

				$branchQuery				=	"SELECT admin_name, admin_franchise_id as franchiseId, 
												 admin_school_id as schoolId, encrypt_id as branchId
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					$userQuery				=	"SELECT usr.encrypt_id
												 FROM ".getTablePrefix()."users as usr
												 WHERE usr.encrypt_id = '".$userId."' AND usr.user_type = '".$userType."'";
					$userData				=	$this->common_model->getDataByQuery('single',$userQuery);
					if($userData <> ""): 
						$galleryQuery			=	"SELECT gall.encrypt_id as galleryId, gall.gallery_name as galleryName, 
													 gall.current_year as galleryYear, REPLACE(gallimg.image_name,'/thumb', '') as galleryImage
											 		 FROM ".getTablePrefix()."gallery AS gall
											 		 LEFT JOIN ".getTablePrefix()."gallery_image AS gallimg ON gall.encrypt_id=gallimg.gallery_id
											 		 WHERE gall.franchise_id = '".$branchData['franchiseId']."' AND gall.school_id = '".$branchData['schoolId']."' 
											 		 AND gall.branch_id = '".$branchData['branchId']."' AND gall.session_year = '".CURRENT_SESSION."' 
											 		 GROUP BY gallimg.gallery_id";
						$galleryData			=	$this->common_model->getDataByQuery('multiple',$galleryQuery);
						if($galleryData <> ""):  
							$result['galleryList']=	$galleryData;						
							echo outPut(lang('SUCESS_STATUS'),lang('SUCESS_MESSAGE'),$result);
						else:
							echo outPut(lang('ERROR_STATUS'),lang('ERROR_MESSAGE'),$result);
						endif;
					else:
						echo outPut(lang('ERROR_STATUS'),lang('INVALID_USER_ID'),$result);
					endif;
				else:
					echo outPut(lang('ERROR_STATUS'),lang('INVALID_BRANCH_CODE'),$result);
				endif;
			endif;
		else:
			echo outPut(lang('ERROR_STATUS'),lang('INVALID_APIKEY'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : getgallerydetails
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get gallery details
	 * * Date : 14 DECEMBER 2018
	 * * **********************************************************************/
	public function getgallerydetails()
	{	
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();
		$returnData 						= 	array();
		if(requestAuthenticate(APIKEY)):  
			if($this->input->post('branchCode') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('BRANCH_CODE_EMPTY'),$result);
			elseif($this->input->post('userType') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_TYPE_EMPTY'),$result);
			elseif($this->input->post('userId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_ID_EMPTY'),$result);
			elseif($this->input->post('galleryId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('GALLERY_ID_EMPTY'),$result);
			else:  
				$branchCode					=	trim($this->input->post('branchCode'));	
				$userType					=	trim($this->input->post('userType'));	
				$userId						=	trim($this->input->post('userId'));	
				$galleryId					=	trim($this->input->post('galleryId'));	

				$branchQuery				=	"SELECT admin_name, admin_franchise_id as franchiseId, 
												 admin_school_id as schoolId, encrypt_id as branchId
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					$userQuery				=	"SELECT usr.encrypt_id
												 FROM ".getTablePrefix()."users as usr
												 WHERE usr.encrypt_id = '".$userId."' AND usr.user_type = '".$userType."'";
					$userData				=	$this->common_model->getDataByQuery('single',$userQuery);
					if($userData <> ""): 
						$galleryQuery			=	"SELECT gall.encrypt_id as galleryId, gall.gallery_name as galleryName, 
													 gall.current_year as galleryYear
											 		 FROM ".getTablePrefix()."gallery AS gall
											 		 WHERE gall.encrypt_id = '".$galleryId."' AND gall.franchise_id = '".$branchData['franchiseId']."' 
											 		 AND gall.school_id = '".$branchData['schoolId']."' 
											 		 AND gall.branch_id = '".$branchData['branchId']."' AND gall.session_year = '".CURRENT_SESSION."'";
						$galleryData			=	$this->common_model->getDataByQuery('single',$galleryQuery);
						if($galleryData <> ""):  
							$gallImgQuery			=	"SELECT REPLACE(gallimg.image_name,'/thumb', '') as image
												 		 FROM ".getTablePrefix()."gallery_image AS gallimg
												 		 WHERE gallimg.gallery_id = '".$galleryData['galleryId']."'";
							$gallImgData			=	$this->common_model->getDataByQuery('multiple',$gallImgQuery);
							$result['galleryId']	=	$galleryData['galleryId'];
							$result['galleryName']	=	$galleryData['galleryName'];
							$result['galleryYear']	=	$galleryData['galleryYear'];
							$result['galleryImage']	=	$gallImgData;						
							echo outPut(lang('SUCESS_STATUS'),lang('SUCESS_MESSAGE'),$result);
						else:
							echo outPut(lang('ERROR_STATUS'),lang('ERROR_MESSAGE'),$result);
						endif;
					else:
						echo outPut(lang('ERROR_STATUS'),lang('INVALID_USER_ID'),$result);
					endif;
				else:
					echo outPut(lang('ERROR_STATUS'),lang('INVALID_BRANCH_CODE'),$result);
				endif;
			endif;
		else:
			echo outPut(lang('ERROR_STATUS'),lang('INVALID_APIKEY'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : getnewslist
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get news list
	 * * Date : 14 DECEMBER 2018
	 * * **********************************************************************/
	public function getnewslist()
	{	
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();
		$returnData 						= 	array();
		if(requestAuthenticate(APIKEY)):  
			if($this->input->post('branchCode') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('BRANCH_CODE_EMPTY'),$result);
			elseif($this->input->post('userType') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_TYPE_EMPTY'),$result);
			elseif($this->input->post('userId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_ID_EMPTY'),$result);
			else:  
				$branchCode					=	trim($this->input->post('branchCode'));	
				$userType					=	trim($this->input->post('userType'));	
				$userId						=	trim($this->input->post('userId'));	

				$branchQuery				=	"SELECT admin_name, admin_franchise_id as franchiseId, 
												 admin_school_id as schoolId, encrypt_id as branchId
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					$userQuery				=	"SELECT usr.encrypt_id
												 FROM ".getTablePrefix()."users as usr
												 WHERE usr.encrypt_id = '".$userId."' AND usr.user_type = '".$userType."'";
					$userData				=	$this->common_model->getDataByQuery('single',$userQuery);
					if($userData <> ""): 
						$newsQuery			=	"SELECT news.encrypt_id as newsId, news.news_title as newsTitle, 
											     news.current_year as newsYear, REPLACE(news.news_image,'/thumb', '') as newsImage
										 		 FROM ".getTablePrefix()."news AS news
										 		 WHERE news.franchise_id = '".$branchData['franchiseId']."' AND news.school_id = '".$branchData['schoolId']."' 
										 		 AND news.branch_id = '".$branchData['branchId']."' AND news.session_year = '".CURRENT_SESSION."'";
						$newsData			=	$this->common_model->getDataByQuery('multiple',$newsQuery);
						if($newsData <> ""):  
							$result['newsList']=	$newsData;						
							echo outPut(lang('SUCESS_STATUS'),lang('SUCESS_MESSAGE'),$result);
						else:
							echo outPut(lang('ERROR_STATUS'),lang('ERROR_MESSAGE'),$result);
						endif;
					else:
						echo outPut(lang('ERROR_STATUS'),lang('INVALID_USER_ID'),$result);
					endif;
				else:
					echo outPut(lang('ERROR_STATUS'),lang('INVALID_BRANCH_CODE'),$result);
				endif;
			endif;
		else:
			echo outPut(lang('ERROR_STATUS'),lang('INVALID_APIKEY'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : getnewsdetails
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get news details
	 * * Date : 14 DECEMBER 2018
	 * * **********************************************************************/
	public function getnewsdetails()
	{	
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();
		$returnData 						= 	array();
		if(requestAuthenticate(APIKEY)):  
			if($this->input->post('branchCode') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('BRANCH_CODE_EMPTY'),$result);
			elseif($this->input->post('userType') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_TYPE_EMPTY'),$result);
			elseif($this->input->post('userId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_ID_EMPTY'),$result);
			elseif($this->input->post('newsId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('NEWS_ID_EMPTY'),$result);
			else:  
				$branchCode					=	trim($this->input->post('branchCode'));	
				$userType					=	trim($this->input->post('userType'));	
				$userId						=	trim($this->input->post('userId'));	
				$newsId						=	trim($this->input->post('newsId'));	

				$branchQuery				=	"SELECT admin_name, admin_franchise_id as franchiseId, 
												 admin_school_id as schoolId, encrypt_id as branchId
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					$userQuery				=	"SELECT usr.encrypt_id
												 FROM ".getTablePrefix()."users as usr
												 WHERE usr.encrypt_id = '".$userId."' AND usr.user_type = '".$userType."'";
					$userData				=	$this->common_model->getDataByQuery('single',$userQuery);
					if($userData <> ""): 
						$newsQuery			=	"SELECT news.encrypt_id as newsId, news.news_title as newsTitle,
												 news.news_description as newsContent, 
											     news.current_year as newsYear, REPLACE(news.news_image,'/thumb', '') as newsImage
										 		 FROM ".getTablePrefix()."news AS news
										 		 WHERE news.encrypt_id = '".$newsId."' AND news.franchise_id = '".$branchData['franchiseId']."' 
										 		 AND news.school_id = '".$branchData['schoolId']."' 
										 		 AND news.branch_id = '".$branchData['branchId']."' AND news.session_year = '".CURRENT_SESSION."'";
						$newsData			=	$this->common_model->getDataByQuery('single',$newsQuery);
						if($newsData <> ""):  
							$result['newsData']=	$newsData;						
							echo outPut(lang('SUCESS_STATUS'),lang('SUCESS_MESSAGE'),$result);
						else:
							echo outPut(lang('ERROR_STATUS'),lang('ERROR_MESSAGE'),$result);
						endif;
					else:
						echo outPut(lang('ERROR_STATUS'),lang('INVALID_USER_ID'),$result);
					endif;
				else:
					echo outPut(lang('ERROR_STATUS'),lang('INVALID_BRANCH_CODE'),$result);
				endif;
			endif;
		else:
			echo outPut(lang('ERROR_STATUS'),lang('INVALID_APIKEY'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : getleavestypelist
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get leaves type list
	 * * Date : 17 DECEMBER 2018
	 * * **********************************************************************/
	public function getleavestypelist()
	{	
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();
		$returnData 						= 	array();
		if(requestAuthenticate(APIKEY)):  
			if($this->input->post('branchCode') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('BRANCH_CODE_EMPTY'),$result);
			elseif($this->input->post('userType') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_TYPE_EMPTY'),$result);
			elseif($this->input->post('userId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_ID_EMPTY'),$result);
			else:  
				$branchCode					=	trim($this->input->post('branchCode'));	
				$userType					=	trim($this->input->post('userType'));	
				$userId						=	trim($this->input->post('userId'));	

				$branchQuery				=	"SELECT admin_name, admin_franchise_id as franchiseId, 
												 admin_school_id as schoolId, encrypt_id as branchId
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					$userQuery				=	"SELECT usr.encrypt_id
												 FROM ".getTablePrefix()."users as usr
												 WHERE usr.encrypt_id = '".$userId."' AND usr.user_type = '".$userType."'";
					$userData				=	$this->common_model->getDataByQuery('single',$userQuery);
					if($userData <> ""): 
						$lTypeQuery			=	"SELECT ltype.encrypt_id as leavesTypeId, ltype.leave_type as leavesType, 
											     ltype.user_type as userType
										 		 FROM ".getTablePrefix()."leave_type AS ltype
										 		 WHERE ltype.franchise_id = '".$branchData['franchiseId']."' AND ltype.school_id = '".$branchData['schoolId']."' 
										 		 AND ltype.branch_id = '".$branchData['branchId']."'  AND ltype.user_type = '".$userType."'
										 		 AND ltype.session_year = '".CURRENT_SESSION."'";
						$lTypeData			=	$this->common_model->getDataByQuery('multiple',$lTypeQuery);
						if($lTypeData <> ""):  
							$result['leavesTypeList']=	$lTypeData;						
							echo outPut(lang('SUCESS_STATUS'),lang('SUCESS_MESSAGE'),$result);
						else:
							echo outPut(lang('ERROR_STATUS'),lang('ERROR_MESSAGE'),$result);
						endif;
					else:
						echo outPut(lang('ERROR_STATUS'),lang('INVALID_USER_ID'),$result);
					endif;
				else:
					echo outPut(lang('ERROR_STATUS'),lang('INVALID_BRANCH_CODE'),$result);
				endif;
			endif;
		else:
			echo outPut(lang('ERROR_STATUS'),lang('INVALID_APIKEY'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : sendleaverequest
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for send leave request
	 * * Date : 17 DECEMBER 2018
	 * * **********************************************************************/
	public function sendleaverequest()
	{	
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();
		$returnData 						= 	array();
		if(requestAuthenticate(APIKEY)):  
			if($this->input->post('branchCode') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('BRANCH_CODE_EMPTY'),$result);
			elseif($this->input->post('userType') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_TYPE_EMPTY'),$result);
			elseif($this->input->post('userId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_ID_EMPTY'),$result);
			elseif($this->input->post('leavesTypeId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('LEAVE_TYPE_ID_EMPTY'),$result);

			/*elseif($this->input->post('fromDate') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('FROM_DATE_EMPTY'),$result);
			elseif($this->input->post('toDate') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('TO_DATE_EMPTY'),$result);*/

			elseif($this->input->post('fromDate') == '' && $this->input->post('fromLeave') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('FROM_DATE_EMPTY'),$result);
			elseif($this->input->post('toDate') == '' && $this->input->post('toLeave') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('TO_DATE_EMPTY'),$result);

			elseif($this->input->post('leaveReason') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('LEAVE_REASON_EMPTY'),$result);
			elseif($this->input->post('addressOnLeave') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('ADDRESS_ON_LEAVE_EMPTY'),$result);
			elseif($this->input->post('contactOnLeave') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('CONTACT_ON_LEAVE_EMPTY'),$result);
			else:  
				$branchCode					=	trim($this->input->post('branchCode'));	
				$userType					=	trim($this->input->post('userType'));	
				$userId						=	trim($this->input->post('userId'));	

				$leavesTypeId				=	trim($this->input->post('leavesTypeId'));		
				$fromDate					=	trim($this->input->post('fromDate'));
				$fromLeave					=	trim($this->input->post('fromLeave'));	
				$toDate						=	trim($this->input->post('toDate'));	
				$toLeave					=	trim($this->input->post('toLeave'));

				$leaveReason				=	trim($this->input->post('leaveReason'));
				$addressOnLeave				=	trim($this->input->post('addressOnLeave'));
				$contactOnLeave				=	trim($this->input->post('contactOnLeave'));

				$childId					=	trim($this->input->post('childId'));

				$branchQuery				=	"SELECT admin_name, admin_franchise_id as franchiseId, 
												 admin_school_id as schoolId, encrypt_id as branchId
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					$userQuery				=	"SELECT usr.encrypt_id
												 FROM ".getTablePrefix()."users as usr
												 WHERE usr.encrypt_id = '".$userId."' AND usr.user_type = '".$userType."'";
					$userData				=	$this->common_model->getDataByQuery('single',$userQuery);
					if($userData <> ""): 
						if($userType == 'Parent' && $this->input->post('childId') == ''):
							echo outPut(lang('ERROR_STATUS'),lang('CHILD_ID_EMPTY'),$result);
						else:
							$param['franchise_id']   			= 	$branchData['franchiseId'];
							$param['school_id']   				= 	$branchData['schoolId'];
	                        $param['branch_id']                 = 	$branchData['branchId'];
	                        $param['leave_type_id']             = 	$leavesTypeId;
	                        $param['user_id']                  	= 	$userId;
	                        if($userType == 'Parent'):
								$param['child_id']              = 	$childId;
							endif;
	                        $param['user_type']                 = 	$userType;
	                        $param['from_date']                 = 	$fromDate;
	                        $param['from_leave']                = 	$fromLeave;
	                        $param['to_date']                   = 	$toDate;
	                        $param['to_leave']                  = 	$toLeave;
	                        $param['leave_reason']              = 	$leaveReason;
	                        $param['address_on_leave']          = 	$addressOnLeave;
	                        $param['contact_on_leave']          = 	$contactOnLeave;
	                        $param['leave_status']              = 	'Request';
							$param['creation_date']          	= 	currentDateTime();
	                        $param['created_by']              	= 	$userId;
	                        $param['session_year']            	=   CURRENT_SESSION;
	                        $param['status']              		= 	'Y';
	                        $ulastInsertId                    	= 	$this->common_model->addData('leave', $param);
	                        $TUparam['encrypt_id']            	= 	manojEncript($ulastInsertId);
	                        $TUwhere['leave_id'] 				= 	$ulastInsertId;
	                        $this->common_model->editDataByMultipleCondition('leave', $TUparam, $TUwhere);

	                        echo outPut(lang('SUCESS_STATUS'),lang('SEND_LEAVE_REQUEST_SUCCESSFULLY'),$result);
	                    endif;
					else:
						echo outPut(lang('ERROR_STATUS'),lang('INVALID_USER_ID'),$result);
					endif;
				else:
					echo outPut(lang('ERROR_STATUS'),lang('INVALID_BRANCH_CODE'),$result);
				endif;
			endif;
		else:
			echo outPut(lang('ERROR_STATUS'),lang('INVALID_APIKEY'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : getrequestedleave
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get requested leave
	 * * Date : 17 DECEMBER 2018
	 * * **********************************************************************/
	public function getrequestedleave()
	{	
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();
		$returnData 						= 	array();
		if(requestAuthenticate(APIKEY)):  
			if($this->input->post('branchCode') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('BRANCH_CODE_EMPTY'),$result);
			elseif($this->input->post('userType') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_TYPE_EMPTY'),$result);
			elseif($this->input->post('userId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_ID_EMPTY'),$result);
			else:  
				$branchCode					=	trim($this->input->post('branchCode'));	
				$userType					=	trim($this->input->post('userType'));	
				$userId						=	trim($this->input->post('userId'));	

				$childId					=	trim($this->input->post('childId'));

				$branchQuery				=	"SELECT admin_name, admin_franchise_id as franchiseId, 
												 admin_school_id as schoolId, encrypt_id as branchId
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					$userQuery				=	"SELECT usr.encrypt_id
												 FROM ".getTablePrefix()."users as usr
												 WHERE usr.encrypt_id = '".$userId."' AND usr.user_type = '".$userType."'";
					$userData				=	$this->common_model->getDataByQuery('single',$userQuery);
					if($userData <> ""): 
						if($userType == 'Teacher'):
							$leaveQuery			=	"SELECT ltype.leave_type as leavesType, leave.user_type as userType,
													 leave.from_date as fromDate, leave.from_leave as fromLeave, leave.to_date as toDate, leave.to_leave as toLeave,
													 leave.leave_reason as leaveReason, leave.address_on_leave as addressOnLeave, leave.contact_on_leave as contactOnLeave,
													 leave.leave_status as leaveStatus, leave.app_reason as approvedReason, leave.app_date as approvedDate
											 		 FROM ".getTablePrefix()."leave AS leave
											 		 LEFT JOIN ".getTablePrefix()."leave_type AS ltype ON leave.leave_type_id=ltype.encrypt_id
											 		 WHERE leave.franchise_id = '".$branchData['franchiseId']."' AND leave.school_id = '".$branchData['schoolId']."' 
											 		 AND leave.branch_id = '".$branchData['branchId']."' AND leave.user_type = '".$userType."'
											 		 AND leave.user_id = '".$userId."' AND leave.session_year = '".CURRENT_SESSION."'";
							$leaveData			=	$this->common_model->getDataByQuery('multiple',$leaveQuery);
							if($leaveData <> ""):  
								$result['leavesList']=	$leaveData;						
								echo outPut(lang('SUCESS_STATUS'),lang('SUCESS_MESSAGE'),$result);
							else:
								echo outPut(lang('ERROR_STATUS'),lang('ERROR_MESSAGE'),$result);
							endif;
						elseif($userType == 'Parent'):
							if($this->input->post('childId') == ''):
								echo outPut(lang('ERROR_STATUS'),lang('CHILD_ID_EMPTY'),$result);
							else:
								$leaveQuery		=	"SELECT ltype.leave_type as leavesType, leave.user_type as userType,
													 leave.from_date as fromDate, leave.from_leave as fromLeave, leave.to_date as toDate, leave.to_leave as toLeave,
													 leave.leave_reason as leaveReason, leave.address_on_leave as addressOnLeave, leave.contact_on_leave as contactOnLeave,
													 leave.leave_status as leaveStatus, leave.app_reason as approvedReason, leave.app_date as approvedDate
											 		 FROM ".getTablePrefix()."leave AS leave
											 		 LEFT JOIN ".getTablePrefix()."leave_type AS ltype ON leave.leave_type_id=ltype.encrypt_id
											 		 WHERE leave.franchise_id = '".$branchData['franchiseId']."' AND leave.school_id = '".$branchData['schoolId']."' 
											 		 AND leave.branch_id = '".$branchData['branchId']."' AND leave.user_type = '".$userType."'
											 		 AND leave.user_id = '".$userId."'AND leave.child_id = '".$childId."' AND leave.session_year = '".CURRENT_SESSION."'";
								$leaveData		=	$this->common_model->getDataByQuery('multiple',$leaveQuery);
								if($leaveData <> ""):  
									$result['leavesList']=	$leaveData;						
									echo outPut(lang('SUCESS_STATUS'),lang('SUCESS_MESSAGE'),$result);
								else:
									echo outPut(lang('ERROR_STATUS'),lang('ERROR_MESSAGE'),$result);
								endif;
							endif;
	                    endif;
					else:
						echo outPut(lang('ERROR_STATUS'),lang('INVALID_USER_ID'),$result);
					endif;
				else:
					echo outPut(lang('ERROR_STATUS'),lang('INVALID_BRANCH_CODE'),$result);
				endif;
			endif;
		else:
			echo outPut(lang('ERROR_STATUS'),lang('INVALID_APIKEY'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : getcalenderdata
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get calender data
	 * * Date : 07 JANUARY 2019
	 * * **********************************************************************/
	public function getcalenderdata()
	{	
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();
		$returnData 						= 	array();
		if(requestAuthenticate(APIKEY)):  
			if($this->input->post('branchCode') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('BRANCH_CODE_EMPTY'),$result);
			elseif($this->input->post('userType') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_TYPE_EMPTY'),$result);
			elseif($this->input->post('userId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_ID_EMPTY'),$result);
			elseif($this->input->post('startDate') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('DATE_EMPTY'),$result);
			elseif($this->input->post('boardId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('BOARD_ID_EMPTY'),$result);
			else:  
				$branchCode					=	trim($this->input->post('branchCode'));	
				$userType					=	trim($this->input->post('userType'));	
				$userId						=	trim($this->input->post('userId'));	
				$startDate					=	trim($this->input->post('startDate'));	
				$boardId					=	trim($this->input->post('boardId'));	

				$branchQuery				=	"SELECT admin_name, admin_franchise_id as franchiseId, 
												 admin_school_id as schoolId, encrypt_id as branchId
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					$userQuery				=	"SELECT usr.encrypt_id
												 FROM ".getTablePrefix()."users as usr
												 WHERE usr.encrypt_id = '".$userId."' AND usr.user_type = '".$userType."'";
					$userData				=	$this->common_model->getDataByQuery('single',$userQuery);
					if($userData <> ""): 
						$eventData[0]['eventId'] 		= 	1;
						$eventData[0]['eventTitle'] 	= 	'Testing';
						$eventData[0]['eventVenue'] 	= 	'';
						$eventData[0]['eventMessage'] 	= 	'';
						$eventData[0]['eventAbout'] 	= 	'';
						$eventData[0]['eventType']		= 	"Examination";
						$eventData[0]['eventStart'] 	= 	"2019-01-13T17:00:00";
						$eventData[0]['eventEnd'] 		= 	"2019-01-13T20:00:00";
						$holidayQuery				=	"SELECT holiday_id as eventId, purpose as eventTitle,
														 '' as eventVenue, '' as eventMessage,
														 '' as eventAbout, 'Holidays' as eventType,
														 DATE_FORMAT(startdate, '%d %m %Y') as eventStart, DATE_FORMAT(enddate, '%d %m %Y') as eventEnd
												 		 FROM ".getTablePrefix()."holiday
												 		 WHERE UNIX_TIMESTAMP(startdate) >= '".strtotime($startDate)."'
					                                     AND franchise_id = '".$branchData['franchiseId']."'
														 AND school_id = '".$branchData['schoolId']."'
														 AND branch_id = '".$branchData['branchId']."'
														 AND board_id = '".$boardId."'
														 AND status = 'Y'";
						$holidaydata				=	$this->common_model->getDataByQuery('multiple',$holidayQuery);
						if($holidaydata <> ""):
							$eventData   		=	array_merge($eventData,$holidaydata);
						endif;
						if($userType=='Teacher'):
							$eventsQuery			=	"SELECT event_id as eventId, purpose as eventTitle,
														 venue as eventVenue, message as eventMessage,
														 about_event as eventAbout, 'Events' as eventType,
														 CONCAT(DATE_FORMAT(from_date, '%d %m %Y'),' ',sms_event.time) as eventStart, 
														 CONCAT(DATE_FORMAT(to_date, '%d %m %Y'),' ',sms_event.time) as eventEnd 
														 FROM ".getTablePrefix()."event 
												 	 	 WHERE UNIX_TIMESTAMP(from_date) >= '".strtotime($startDate)."'
				                                     	 AND franchise_id = '".$branchData['franchiseId']."'
														 AND school_id = '".$branchData['schoolId']."'
														 AND branch_id = '".$branchData['branchId']."'
														 AND board_id = '".$boardId."'
														 AND (visibility = 'All' OR visibility = 'Teacher')
														 AND status = 'Y'";
							$eventsdata				=	$this->common_model->getDataByQuery('multiple',$eventsQuery);
						elseif($userType=='Parent'):
							$eventsQuery			=	"SELECT event_id as eventId, purpose as eventTitle,
														 venue as eventVenue, message as eventMessage,
														 about_event as eventAbout, 'Events' as eventType,
														 CONCAT(DATE_FORMAT(from_date, '%d %m %Y'),' ',sms_event.time) as eventStart, 
														 CONCAT(DATE_FORMAT(to_date, '%d %m %Y'),' ',sms_event.time) as eventEnd 
														 FROM ".getTablePrefix()."event 
												 	 	 WHERE UNIX_TIMESTAMP(from_date) >= '".strtotime($startDate)."'
				                                     	 AND franchise_id = '".$branchData['franchiseId']."'
														 AND school_id = '".$branchData['schoolId']."'
														 AND branch_id = '".$branchData['branchId']."'
														 AND board_id = '".$boardId."'
														 AND (visibility = 'All' OR visibility = 'Parent')
														 AND status = 'Y'";
							$eventsdata				=	$this->common_model->getDataByQuery('multiple',$eventsQuery);
						endif;
						if($eventsdata <> ""):
							$eventData   		=	array_merge($eventData,$eventsdata);
						endif;
						if($eventData):  
							$result['eventData']=	$eventData;						
							echo outPut(lang('SUCESS_STATUS'),lang('SUCESS_MESSAGE'),$result);
						else:
							echo outPut(lang('ERROR_STATUS'),lang('ERROR_MESSAGE'),$result);
						endif;
					else:
						echo outPut(lang('ERROR_STATUS'),lang('INVALID_USER_ID'),$result);
					endif;
				else:
					echo outPut(lang('ERROR_STATUS'),lang('INVALID_BRANCH_CODE'),$result);
				endif;
			endif;
		else:
			echo outPut(lang('ERROR_STATUS'),lang('INVALID_APIKEY'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : sendfeedback
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for send feedback
	 * * Date : 08 JANUARY 2019
	 * * **********************************************************************/
	public function sendfeedback()
	{	
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();
		$returnData 						= 	array();
		if(requestAuthenticate(APIKEY)):  
			if($this->input->post('branchCode') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('BRANCH_CODE_EMPTY'),$result);
			elseif($this->input->post('userType') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_TYPE_EMPTY'),$result);
			elseif($this->input->post('userId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_ID_EMPTY'),$result);
			elseif($this->input->post('boardId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('BOARD_ID_EMPTY'),$result);
			elseif($this->input->post('classId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('CLASS_ID_EMPTY'),$result);
			elseif($this->input->post('sectionId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('SECTION_ID_EMPTY'),$result);
			elseif($this->input->post('date') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('DATE_EMPTY'),$result);
			elseif($this->input->post('time') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('TIME_EMPTY'),$result);
			else:  
				$branchCode					=	trim($this->input->post('branchCode'));	
				$userType					=	trim($this->input->post('userType'));	
				$userId						=	trim($this->input->post('userId'));	
				$boardId					=	trim($this->input->post('boardId'));	

				$classId					=	trim($this->input->post('classId'));	
				$sectionId					=	trim($this->input->post('sectionId'));	
				$date						=	trim($this->input->post('date'));	
				$time						=	trim($this->input->post('time'));

				$branchQuery				=	"SELECT admin_name, admin_franchise_id as franchiseId, 
												 admin_school_id as schoolId, encrypt_id as branchId
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					$userQuery				=	"SELECT usr.encrypt_id
												 FROM ".getTablePrefix()."users as usr
												 WHERE usr.encrypt_id = '".$userId."' AND usr.user_type = '".$userType."'";
					$userData				=	$this->common_model->getDataByQuery('single',$userQuery);
					if($userData <> ""): 
						if($userType == 'Teacher'):
							if($this->input->post('studentId') == ''):
								echo outPut(lang('ERROR_STATUS'),lang('STUDENT_ID_EMPTY'),$result);
							elseif($this->input->post('message') == ''):
								echo outPut(lang('ERROR_STATUS'),lang('MESSAGE_EMPTY'),$result);
							else:
								$studentId							=	trim($this->input->post('studentId'));
								$message							=	trim($this->input->post('message'));

								$param['franchise_id']   			= 	$branchData['franchiseId'];
								$param['school_id']   				= 	$branchData['schoolId'];
		                        $param['branch_id']                 = 	$branchData['branchId'];
		                        $param['board_id']            		= 	$boardId;
		                        
		                        $param['sender_type']              	= 	'Teacher';
		                        $param['sender_id']          		= 	$userId;

		                        $param['to_class_id']          		= 	$classId;
		                        $param['to_section_id']          	= 	$sectionId;
		                        $param['to_student_id']          	= 	$studentId;
		                        $param['message']          			= 	addslashes($message);

								$param['creation_date']          	= 	$date.' '.$time;
		                        $param['created_by']              	= 	$userId;

		                        $param['status']              		= 	'Y';
		                        $ulastInsertId                    	= 	$this->common_model->addData('feedback', $param);
		                        $TUparam['encrypt_id']            	= 	manojEncript($ulastInsertId);
		                        $TUwhere['feedback_id'] 			= 	$ulastInsertId;
		                        $this->common_model->editDataByMultipleCondition('feedback', $TUparam, $TUwhere);

		                        $result['feedbackId']				=	$TUparam['encrypt_id'];
		                        echo outPut(lang('SUCESS_STATUS'),lang('FEEDBACK_SENT'),$result);
		                    endif;
						elseif($userType == 'Parent'):
							if($this->input->post('childId') == ''):
								echo outPut(lang('ERROR_STATUS'),lang('CHILD_ID_EMPTY'),$result);
							elseif($this->input->post('teacherId') == ''):
								echo outPut(lang('ERROR_STATUS'),lang('TEACHER_ID_EMPTY'),$result);
							elseif($this->input->post('subjectId') == ''):
								echo outPut(lang('ERROR_STATUS'),lang('SUBJECT_ID_EMPTY'),$result);
							elseif($this->input->post('wayOfTeaching') == ''):
								echo outPut(lang('ERROR_STATUS'),lang('WAY_OF_TEACHING_EMPTY'),$result);
							elseif($this->input->post('knowledgeAboutSubject') == ''):
								echo outPut(lang('ERROR_STATUS'),lang('KNOWLEDGE_ABOUT_SUBJECT_EMPTY'),$result);
							elseif($this->input->post('ClassCoOrdination') == ''):
								echo outPut(lang('ERROR_STATUS'),lang('CLASS_CO_ORDINATION_EMPTY'),$result);
							elseif($this->input->post('KnowledgeSharing') == ''):
								echo outPut(lang('ERROR_STATUS'),lang('KNOWLEDGE_SHARING_EMPTY'),$result);
							else:
								$childId							=	trim($this->input->post('childId'));
								$teacherId							=	trim($this->input->post('teacherId'));
								$subjectId							=	trim($this->input->post('subjectId'));
								$wayOfTeaching						=	trim($this->input->post('wayOfTeaching'));
								$knowledgeAboutSubject				=	trim($this->input->post('knowledgeAboutSubject'));
								$ClassCoOrdination					=	trim($this->input->post('ClassCoOrdination'));
								$KnowledgeSharing					=	trim($this->input->post('KnowledgeSharing'));

								$param['franchise_id']   			= 	$branchData['franchiseId'];
								$param['school_id']   				= 	$branchData['schoolId'];
		                        $param['branch_id']                 = 	$branchData['branchId'];
		                        $param['board_id']            		= 	$boardId;
		                        
		                        $param['sender_type']              	= 	'Parent';
		                        $param['sender_id']          		= 	$childId;
		                        $param['sender_class_id']          	= 	$classId;
		                        $param['sender_section_id']         = 	$sectionId;

		                        $param['to_class_id']          		= 	$classId;
		                        $param['to_section_id']          	= 	$sectionId;
		                        $param['to_teacher_id']          	= 	$teacherId;
		                        $param['to_subject_id']          	= 	$subjectId;
		                        $param['way_of_tech']          		= 	$wayOfTeaching;
		                        $param['kan_abot_subject']          = 	$knowledgeAboutSubject;
		                        $param['class_co_ordinat']          = 	$ClassCoOrdination;
		                        $param['know_sharing']          	= 	$KnowledgeSharing;

								$param['creation_date']          	= 	$date.' '.$time;
		                        $param['created_by']              	= 	$userId;

		                        $param['status']              		= 	'Y';
		                        $ulastInsertId                    	= 	$this->common_model->addData('feedback', $param);
		                        $TUparam['encrypt_id']            	= 	manojEncript($ulastInsertId);
		                        $TUwhere['feedback_id'] 			= 	$ulastInsertId;
		                        $this->common_model->editDataByMultipleCondition('feedback', $TUparam, $TUwhere);

		                        $result['feedbackId']				=	$TUparam['encrypt_id'];
		                        echo outPut(lang('SUCESS_STATUS'),lang('FEEDBACK_SENT'),$result);
		                    endif;
		                endif;
					else:
						echo outPut(lang('ERROR_STATUS'),lang('INVALID_USER_ID'),$result);
					endif;
				else:
					echo outPut(lang('ERROR_STATUS'),lang('INVALID_BRANCH_CODE'),$result);
				endif;
			endif;
		else:
			echo outPut(lang('ERROR_STATUS'),lang('INVALID_APIKEY'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : getfeedbacklist
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get feedback list
	 * * Date : 08 JANUARY 2019
	 * * **********************************************************************/
	public function getfeedbacklist()
	{	
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();
		$returnData 						= 	array();
		if(requestAuthenticate(APIKEY)):  
			if($this->input->post('branchCode') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('BRANCH_CODE_EMPTY'),$result);
			elseif($this->input->post('userType') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_TYPE_EMPTY'),$result);
			elseif($this->input->post('userId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_ID_EMPTY'),$result);
			elseif($this->input->post('boardId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('BOARD_ID_EMPTY'),$result);
			elseif($this->input->post('feedbackType') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('FEEDBACK_TYPE_EMPTY'),$result);
			else:  
				$branchCode					=	trim($this->input->post('branchCode'));	
				$userType					=	trim($this->input->post('userType'));	
				$userId						=	trim($this->input->post('userId'));	
				$boardId					=	trim($this->input->post('boardId'));

				$feedbackType				=	trim($this->input->post('feedbackType'));	

				$branchQuery				=	"SELECT admin_name, admin_franchise_id as franchiseId, 
												 admin_school_id as schoolId, encrypt_id as branchId
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					$userQuery				=	"SELECT usr.encrypt_id, CONCAT(usr.user_f_name,' ',usr.user_l_name) as userName
												 FROM ".getTablePrefix()."users as usr
												 WHERE usr.encrypt_id = '".$userId."' AND usr.user_type = '".$userType."'";
					$userData				=	$this->common_model->getDataByQuery('single',$userQuery);
					if($userData <> ""): 
						if($userType == 'Teacher'):
							if($feedbackType == 'sent'):
								$feedbackQuery	=	"SELECT fback.encrypt_id as feedbackId, '".$userData['userName']."' as feedbackFrom, 
													 CONCAT(stud.student_f_name,' ',stud.student_l_name) as feedbackTO, clss.class_name as feedbackClass,
													 sect.class_section_name as feedbackSection, '' as feedbackSubject, 
													 fback.message as feedbackMessage, '' as wayOfTeaching, 
													 '' as knowledgeAboutSubject,  '' as ClassCoOrdination,  
													 '' as KnowledgeSharing, fback.creation_date as messageDate
													 FROM ".getTablePrefix()."feedback as fback
													 LEFT JOIN ".getTablePrefix()."classes as clss ON fback.to_class_id=clss.encrypt_id
													 LEFT JOIN ".getTablePrefix()."class_section as sect ON fback.to_section_id=sect.encrypt_id
													 LEFT JOIN ".getTablePrefix()."students as stud ON fback.to_student_id=stud.student_qunique_id
													 LEFT JOIN ".getTablePrefix()."subject as subj ON fback.to_subject_id=subj.encrypt_id
													 WHERE fback.sender_type = 'Teacher' AND fback.sender_id = '".$userId."'
													 AND fback.franchise_id = '".$branchData['franchiseId']."' AND fback.school_id = '".$branchData['schoolId']."' 
												 	 AND fback.branch_id = '".$branchData['branchId']."' AND fback.board_id = '".$boardId."'
												 	 ORDER BY UNIX_TIMESTAMP(fback.creation_date) DESC";
								$feedbackData 	=	$this->common_model->getDataByQuery('multiple',$feedbackQuery);
		                        if($feedbackData):  
									$result['feedbackList']	=	$feedbackData;						
									echo outPut(lang('SUCESS_STATUS'),lang('SUCESS_MESSAGE'),$result);
								else:
									echo outPut(lang('ERROR_STATUS'),lang('ERROR_MESSAGE'),$result);
								endif;
							elseif($feedbackType == 'received'):
								$feedbackQuery	=	"SELECT fback.encrypt_id as feedbackId, CONCAT(stud.student_f_name,' ',stud.student_l_name) as feedbackFrom, 
													 '".$userData['userName']."' as feedbackTO, clss.class_name as feedbackClass,
													 sect.class_section_name as feedbackSection, subj.subject_name as feedbackSubject, 
													 '' as feedbackMessage, fback.way_of_tech as wayOfTeaching, 
													 fback.kan_abot_subject as knowledgeAboutSubject,  fback.class_co_ordinat as ClassCoOrdination,  
													 fback.know_sharing as KnowledgeSharing, fback.creation_date as messageDate
													 FROM ".getTablePrefix()."feedback as fback
													 LEFT JOIN ".getTablePrefix()."classes as clss ON fback.to_class_id=clss.encrypt_id
													 LEFT JOIN ".getTablePrefix()."class_section as sect ON fback.to_section_id=sect.encrypt_id
													 LEFT JOIN ".getTablePrefix()."students as stud ON fback.sender_id=stud.student_qunique_id
													 LEFT JOIN ".getTablePrefix()."subject as subj ON fback.to_subject_id=subj.encrypt_id
													 WHERE fback.sender_type = 'Parent' AND fback.to_teacher_id = '".$userId."'
													 AND fback.franchise_id = '".$branchData['franchiseId']."' AND fback.school_id = '".$branchData['schoolId']."' 
												 	 AND fback.branch_id = '".$branchData['branchId']."' AND fback.board_id = '".$boardId."'
												 	 ORDER BY UNIX_TIMESTAMP(fback.creation_date) DESC";
								$feedbackData 	=	$this->common_model->getDataByQuery('multiple',$feedbackQuery);
		                        if($feedbackData):  
									$result['feedbackList']	=	$feedbackData;						
									echo outPut(lang('SUCESS_STATUS'),lang('SUCESS_MESSAGE'),$result);
								else:
									echo outPut(lang('ERROR_STATUS'),lang('ERROR_MESSAGE'),$result);
								endif;
							endif;
						elseif($userType == 'Parent'):
							if($this->input->post('classId') == ''):
								echo outPut(lang('ERROR_STATUS'),lang('CLASS_ID_EMPTY'),$result);
							elseif($this->input->post('sectionId') == ''):
								echo outPut(lang('ERROR_STATUS'),lang('SECTION_ID_EMPTY'),$result);
							elseif($this->input->post('childId') == ''):
								echo outPut(lang('ERROR_STATUS'),lang('CHILD_ID_EMPTY'),$result);
							else:
								$classId							=	trim($this->input->post('classId'));
								$sectionId							=	trim($this->input->post('sectionId'));
								$childId							=	trim($this->input->post('childId'));

		                        if($feedbackType == 'sent'):
									$feedbackQuery	=	"SELECT fback.encrypt_id as feedbackId, CONCAT(stud.student_f_name,' ',stud.student_l_name) as feedbackFrom, 
														 CONCAT(usr.user_f_name,' ',usr.user_l_name) as feedbackTO, clss.class_name as feedbackClass,
														 sect.class_section_name as feedbackSection, subj.subject_name as feedbackSubject, 
														 '' as feedbackMessage, fback.way_of_tech as wayOfTeaching, 
														 fback.kan_abot_subject as knowledgeAboutSubject,  fback.class_co_ordinat as ClassCoOrdination,  
														 fback.know_sharing as KnowledgeSharing, fback.creation_date as messageDate
														 FROM ".getTablePrefix()."feedback as fback
														 LEFT JOIN ".getTablePrefix()."classes as clss ON fback.to_class_id=clss.encrypt_id
														 LEFT JOIN ".getTablePrefix()."class_section as sect ON fback.to_section_id=sect.encrypt_id
														 LEFT JOIN ".getTablePrefix()."students as stud ON fback.sender_id=stud.student_qunique_id
														 LEFT JOIN ".getTablePrefix()."users as usr ON fback.to_teacher_id=usr.encrypt_id
														 LEFT JOIN ".getTablePrefix()."subject as subj ON fback.to_subject_id=subj.encrypt_id
														 WHERE fback.sender_type = 'Parent' AND fback.sender_id = '".$childId."'
														 AND fback.sender_class_id = '".$classId."'  AND fback.sender_section_id = '".$sectionId."'
														 AND fback.franchise_id = '".$branchData['franchiseId']."' AND fback.school_id = '".$branchData['schoolId']."' 
													 	 AND fback.branch_id = '".$branchData['branchId']."' AND fback.board_id = '".$boardId."'
													 	 ORDER BY UNIX_TIMESTAMP(fback.creation_date) DESC";
									$feedbackData 	=	$this->common_model->getDataByQuery('multiple',$feedbackQuery);
			                        if($feedbackData):  
										$result['feedbackList']	=	$feedbackData;						
										echo outPut(lang('SUCESS_STATUS'),lang('SUCESS_MESSAGE'),$result);
									else:
										echo outPut(lang('ERROR_STATUS'),lang('ERROR_MESSAGE'),$result);
									endif;
								elseif($feedbackType == 'received'):
									$feedbackQuery	=	"SELECT fback.encrypt_id as feedbackId, CONCAT(usr.user_f_name,' ',usr.user_l_name) as feedbackFrom, 
														 CONCAT(stud.student_f_name,' ',stud.student_l_name) as feedbackTO, clss.class_name as feedbackClass,
														 sect.class_section_name as feedbackSection, '' as feedbackSubject, 
														 fback.message as feedbackMessage, '' as wayOfTeaching, 
														 '' as knowledgeAboutSubject,  '' as ClassCoOrdination,  
														 '' as KnowledgeSharing, fback.creation_date as messageDate
														 FROM ".getTablePrefix()."feedback as fback
														 LEFT JOIN ".getTablePrefix()."classes as clss ON fback.to_class_id=clss.encrypt_id
														 LEFT JOIN ".getTablePrefix()."class_section as sect ON fback.to_section_id=sect.encrypt_id
														 LEFT JOIN ".getTablePrefix()."students as stud ON fback.to_student_id=stud.student_qunique_id
														 LEFT JOIN ".getTablePrefix()."users as usr ON fback.sender_id=usr.encrypt_id
														 LEFT JOIN ".getTablePrefix()."subject as subj ON fback.to_subject_id=subj.encrypt_id
														 WHERE fback.sender_type = 'Teacher' AND fback.to_student_id = '".$childId."'
														 AND fback.to_class_id = '".$classId."'  AND fback.to_section_id = '".$sectionId."'
														 AND fback.franchise_id = '".$branchData['franchiseId']."' AND fback.school_id = '".$branchData['schoolId']."' 
													 	 AND fback.branch_id = '".$branchData['branchId']."' AND fback.board_id = '".$boardId."'
													 	 ORDER BY UNIX_TIMESTAMP(fback.creation_date) DESC";
									$feedbackData 	=	$this->common_model->getDataByQuery('multiple',$feedbackQuery);
			                        if($feedbackData):  
										$result['feedbackList']	=	$feedbackData;						
										echo outPut(lang('SUCESS_STATUS'),lang('SUCESS_MESSAGE'),$result);
									else:
										echo outPut(lang('ERROR_STATUS'),lang('ERROR_MESSAGE'),$result);
									endif;
								endif;
		                    endif;
		                endif;
					else:
						echo outPut(lang('ERROR_STATUS'),lang('INVALID_USER_ID'),$result);
					endif;
				else:
					echo outPut(lang('ERROR_STATUS'),lang('INVALID_BRANCH_CODE'),$result);
				endif;
			endif;
		else:
			echo outPut(lang('ERROR_STATUS'),lang('INVALID_APIKEY'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : getnoticeboardlist
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get notice board list
	 * * Date : 09 JANUARY 2019
	 * * **********************************************************************/
	public function getnoticeboardlist()
	{	
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();
		$returnData 						= 	array();
		if(requestAuthenticate(APIKEY)):  
			if($this->input->post('branchCode') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('BRANCH_CODE_EMPTY'),$result);
			elseif($this->input->post('userType') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_TYPE_EMPTY'),$result);
			elseif($this->input->post('userId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_ID_EMPTY'),$result);
			elseif($this->input->post('boardId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('BOARD_ID_EMPTY'),$result);
			else:  
				$branchCode					=	trim($this->input->post('branchCode'));	
				$userType					=	trim($this->input->post('userType'));	
				$userId						=	trim($this->input->post('userId'));	
				$boardId					=	trim($this->input->post('boardId'));

				$branchQuery				=	"SELECT admin_name, admin_franchise_id as franchiseId, 
												 admin_school_id as schoolId, encrypt_id as branchId
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					$userQuery				=	"SELECT usr.encrypt_id, CONCAT(usr.user_f_name,' ',usr.user_l_name) as userName
												 FROM ".getTablePrefix()."users as usr
												 WHERE usr.encrypt_id = '".$userId."' AND usr.user_type = '".$userType."'";
					$userData				=	$this->common_model->getDataByQuery('single',$userQuery);
					if($userData <> ""): 
						if($userType == 'Teacher'):
							$notBordQuery	=	"SELECT nboard.encrypt_id as noticeBoardId, nboard.message as noticeBoardMessage,
												  nboard.image as noticeBoardImage, nboard.creation_date as noticeBoardDate
												 FROM ".getTablePrefix()."notice_board as nboard
												 WHERE nboard.franchise_id = '".$branchData['franchiseId']."' AND nboard.school_id = '".$branchData['schoolId']."' 
											 	 AND nboard.branch_id = '".$branchData['branchId']."' AND nboard.board_id = '".$boardId."'
											 	 AND (nboard.visibility = 'All' OR nboard.visibility = 'Teacher')
											 	 ORDER BY UNIX_TIMESTAMP(nboard.creation_date) DESC";
						    //print $notBordQuery; die;
							$notBordData 	=	$this->common_model->getDataByQuery('multiple',$notBordQuery);
	                        if($notBordData):  
								$result['noticeBoardList']	=	$notBordData;						
								echo outPut(lang('SUCESS_STATUS'),lang('SUCESS_MESSAGE'),$result);
							else:
								echo outPut(lang('ERROR_STATUS'),lang('ERROR_MESSAGE'),$result);
							endif;
						elseif($userType == 'Parent'):
							if($this->input->post('classId') == ''):
								echo outPut(lang('ERROR_STATUS'),lang('CLASS_ID_EMPTY'),$result);
							elseif($this->input->post('sectionId') == ''):
								echo outPut(lang('ERROR_STATUS'),lang('SECTION_ID_EMPTY'),$result);
							else:
								$classId		=	trim($this->input->post('classId'));
								$sectionId		=	trim($this->input->post('sectionId'));

								$notBordQuery	=	"SELECT nboard.encrypt_id as noticeBoardId, nboard.message as noticeBoardMessage,
													  nboard.image as noticeBoardImage, nboard.creation_date as noticeBoardDate
													 FROM ".getTablePrefix()."notice_board as nboard
													 WHERE nboard.franchise_id = '".$branchData['franchiseId']."' AND nboard.school_id = '".$branchData['schoolId']."' 
												 	 AND nboard.branch_id = '".$branchData['branchId']."' AND nboard.board_id = '".$boardId."'
												 	 AND (nboard.class_id = 'All' OR nboard.class_id = '".$classId."')
												 	 AND (nboard.section_id = 'All' OR nboard.section_id = '".$sectionId."')
												 	 AND (nboard.visibility = 'All' OR nboard.visibility = 'Parent')
												 	 ORDER BY UNIX_TIMESTAMP(nboard.creation_date) DESC";
								$notBordData 	=	$this->common_model->getDataByQuery('multiple',$notBordQuery);
		                        if($notBordData):  
									$result['noticeBoardList']	=	$notBordData;						
									echo outPut(lang('SUCESS_STATUS'),lang('SUCESS_MESSAGE'),$result);
								else:
									echo outPut(lang('ERROR_STATUS'),lang('ERROR_MESSAGE'),$result);
								endif;
		                    endif;
		                endif;
					else:
						echo outPut(lang('ERROR_STATUS'),lang('INVALID_USER_ID'),$result);
					endif;
				else:
					echo outPut(lang('ERROR_STATUS'),lang('INVALID_BRANCH_CODE'),$result);
				endif;
			endif;
		else:
			echo outPut(lang('ERROR_STATUS'),lang('INVALID_APIKEY'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : getnotificationlist
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get notification list
	 * * Date : 09 JANUARY 2019
	 * * **********************************************************************/
	public function getnotificationlist()
	{			
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();
		$returnData 						= 	array();
		if(requestAuthenticate(APIKEY)):  
			if($this->input->post('branchCode') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('BRANCH_CODE_EMPTY'),$result);
			elseif($this->input->post('userType') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_TYPE_EMPTY'),$result);
			elseif($this->input->post('userId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('USER_ID_EMPTY'),$result);
			elseif($this->input->post('boardId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('BOARD_ID_EMPTY'),$result);
			else:  
				$branchCode					=	trim($this->input->post('branchCode'));	
				$userType					=	trim($this->input->post('userType'));	
				$userId						=	trim($this->input->post('userId'));	
				$boardId					=	trim($this->input->post('boardId'));

				$branchQuery				=	"SELECT admin_name, admin_franchise_id as franchiseId, 
												 admin_school_id as schoolId, encrypt_id as branchId
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					$userQuery				=	"SELECT usr.encrypt_id, CONCAT(usr.user_f_name,' ',usr.user_l_name) as userName
												 FROM ".getTablePrefix()."users as usr
												 WHERE usr.encrypt_id = '".$userId."' AND usr.user_type = '".$userType."'";
					$userData				=	$this->common_model->getDataByQuery('single',$userQuery);
					if($userData <> ""): 
						if($userType == 'Teacher'):
					
							$notisQuery	=	"SELECT noti.encrypt_id as notificationId, noti.message as notificationMessage,
												 noti.creation_date as notificationDate
												 FROM ".getTablePrefix()."notification as noti
												 WHERE noti.franchise_id = '".$branchData['franchiseId']."' AND noti.school_id = '".$branchData['schoolId']."' 
											 	 AND noti.branch_id = '".$branchData['branchId']."' AND noti.board_id = '".$boardId."'
											 	 ORDER BY UNIX_TIMESTAMP(noti.creation_date) DESC";											 
							$notisData 	=	$this->common_model->getDataByQuery('multiple',$notisQuery);
	                        if($notisData):  
								$result['notificationList']	=	$notisData;						
								echo outPut(lang('SUCESS_STATUS'),lang('SUCESS_MESSAGE'),$result);
							else:
								echo outPut(lang('ERROR_STATUS'),lang('ERROR_MESSAGE'),$result);
							endif;
						elseif($userType == 'Parent'):						
							$notisQuery	=	"SELECT noti.encrypt_id as notificationId, noti.message as notificationMessage,
											 noti.creation_date as notificationDate
											 FROM ".getTablePrefix()."notification as noti
											 WHERE noti.franchise_id = '".$branchData['franchiseId']."' AND noti.school_id = '".$branchData['schoolId']."' 
											 AND noti.branch_id = '".$branchData['branchId']."' AND noti.board_id = '".$boardId."'
											 ORDER BY UNIX_TIMESTAMP(noti.creation_date) DESC";
							$notisData 	=	$this->common_model->getDataByQuery('multiple',$notisQuery);
	                        if($notisData):  
								$result['notificationList']	=	$notisData;						
								echo outPut(lang('SUCESS_STATUS'),lang('SUCESS_MESSAGE'),$result);
							else:
								echo outPut(lang('ERROR_STATUS'),lang('ERROR_MESSAGE'),$result);
							endif;
		                endif;
					else:
						echo outPut(lang('ERROR_STATUS'),lang('INVALID_USER_ID'),$result);
					endif;
				else:
					echo outPut(lang('ERROR_STATUS'),lang('INVALID_BRANCH_CODE'),$result);
				endif;
			endif;
		else:
			echo outPut(lang('ERROR_STATUS'),lang('INVALID_APIKEY'),$result);
		endif;
	}
}