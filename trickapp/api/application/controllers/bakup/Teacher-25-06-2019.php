<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends CI_Controller {
	
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
	 * * Function name : getclasslist
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get class list
	 * * Date : 12 DECEMBER 2018
	 * * **********************************************************************/
	public function getclasslist()
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

				$branchQuery				=	"SELECT admin_name
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					$teacherQuery				=	"SELECT encrypt_id
													 FROM ".getTablePrefix()."users 
													 WHERE encrypt_id = '".$userId."' AND user_type = '".$userType."'";
					$teacherData				=	$this->common_model->getDataByQuery('single',$teacherQuery);
					if($teacherData <> ""):  
						$classQuery				=	"SELECT clas.encrypt_id as classId, clas.class_name as className 
											 		 FROM ".getTablePrefix()."period_subject_teacher AS pst
											 		 LEFT JOIN ".getTablePrefix()."classes AS clas ON pst.class_id=clas.encrypt_id
											 		 WHERE pst.teacher_id = '".$userId."' AND pst.board_id = '".$boardId."' 
											 		 AND pst.session_year = '".CURRENT_SESSION."' GROUP BY pst.class_id";
						//print $classQuery; die;
						$classData				=	$this->common_model->getDataByQuery('multiple',$classQuery);
						if($classData <> ""):  
							$result['classList']=	$classData;						
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
	 * * Function name : getsectionlist
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get section list
	 * * Date : 12 DECEMBER 2018
	 * * **********************************************************************/
	public function getsectionlist()
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
			else:	
				$branchCode					=	trim($this->input->post('branchCode'));	
				$userType					=	trim($this->input->post('userType'));	
				$userId						=	trim($this->input->post('userId'));
				$classId					=	trim($this->input->post('classId'));
				$boardId					=	trim($this->input->post('boardId'));	

				$branchQuery				=	"SELECT admin_name
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					$teacherQuery				=	"SELECT encrypt_id
													 FROM ".getTablePrefix()."users 
													 WHERE encrypt_id = '".$userId."' AND user_type = '".$userType."'";
					$teacherData				=	$this->common_model->getDataByQuery('single',$teacherQuery);
					if($teacherData <> ""):  
						$sectionQuery			=	"SELECT clsec.encrypt_id as sectionId, clsec.class_section_name as sectionName 
											 		 FROM ".getTablePrefix()."period_subject_teacher AS pst
											 		 LEFT JOIN ".getTablePrefix()."class_section AS clsec ON pst.section_id=clsec.encrypt_id
											 		 WHERE pst.teacher_id = '".$userId."' AND pst.board_id = '".$boardId."' 
											 		 AND pst.class_id = '".$classId."' AND pst.session_year = '".CURRENT_SESSION."'
											 		 GROUP BY pst.section_id";
						//print $sectionQuery; die;							 
						$sectionData			=	$this->common_model->getDataByQuery('multiple',$sectionQuery);
						if($sectionData <> ""):  
							$result['sectionList']=	$sectionData;						
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
	 * * Function name : getsubjectlist
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get subject list
	 * * Date : 12 DECEMBER 2018
	 * * **********************************************************************/
	public function getsubjectlist()
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
				$classId					=	trim($this->input->post('classId'));
				$sectionId					=	trim($this->input->post('sectionId'));	
				$boardId					=	trim($this->input->post('boardId'));	

				$branchQuery				=	"SELECT admin_name
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					$teacherQuery				=	"SELECT encrypt_id
													 FROM ".getTablePrefix()."users 
													 WHERE encrypt_id = '".$userId."' AND user_type = '".$userType."'";
					$teacherData				=	$this->common_model->getDataByQuery('single',$teacherQuery);
					if($teacherData <> ""):  
						if($classId !='' && $sectionId !=''):
							$subjectQuery			=	"SELECT subj.encrypt_id as subjectId, subj.subject_name as subjectName 
												 		 FROM ".getTablePrefix()."period_subject_teacher AS pst
												 		 LEFT JOIN ".getTablePrefix()."subject AS subj ON pst.subject_id=subj.encrypt_id
												 		 WHERE pst.teacher_id = '".$userId."' AND pst.board_id = '".$boardId."' 
												 		 AND pst.class_id = '".$classId."' AND pst.section_id = '".$sectionId."' 
												 		 AND pst.session_year = '".CURRENT_SESSION."' GROUP BY pst.subject_id";
							$subjectData			=	$this->common_model->getDataByQuery('multiple',$subjectQuery);
							if($subjectData <> ""):  
								$result['subjectList']=	$subjectData;						
								echo outPut(lang('SUCESS_STATUS'),lang('SUCESS_MESSAGE'),$result);
							else:
								echo outPut(lang('ERROR_STATUS'),lang('ERROR_MESSAGE'),$result);
							endif;
						else:
							$subjectQuery			=	"SELECT subj.encrypt_id as subjectId, subj.subject_name as subjectName 
												 		 FROM ".getTablePrefix()."teacher_subject AS ts
												 		 LEFT JOIN ".getTablePrefix()."subject AS subj ON ts.subject_id=subj.encrypt_id
												 		 WHERE ts.teacher_id = '".$userId."' AND ts.session_year = '".CURRENT_SESSION."'
												 		 GROUP BY ts.subject_id";
							$subjectData			=	$this->common_model->getDataByQuery('multiple',$subjectQuery);
							if($subjectData <> ""):  
								$result['subjectList']=	$subjectData;						
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

	/* * *********************************************************************
	 * * Function name : getsyllabus
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get syllabus
	 * * Date : 12 DECEMBER 2018
	 * * **********************************************************************/
	public function getsyllabus()
	{	
		//print CURRENT_SESSION; die;
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
			/*elseif($this->input->post('sectionId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('SECTION_ID_EMPTY'),$result);*/
			elseif($this->input->post('subjectId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('SUBJECT_ID_EMPTY'),$result);
			else:	
				$branchCode					=	trim($this->input->post('branchCode'));	
				$userType					=	trim($this->input->post('userType'));	
				$userId						=	trim($this->input->post('userId'));
				$boardId					=	trim($this->input->post('boardId'));
				$classId					=	trim($this->input->post('classId'));
				$sectionId					=	trim($this->input->post('sectionId'));
				$subjectId					=	trim($this->input->post('subjectId'));	

				$branchQuery				=	"SELECT admin_name
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					$teacherQuery				=	"SELECT encrypt_id
													 FROM ".getTablePrefix()."users 
													 WHERE encrypt_id = '".$userId."' AND user_type = '".$userType."'";
					$teacherData				=	$this->common_model->getDataByQuery('single',$teacherQuery);
					if($teacherData <> ""):  
						$syllabusQuery			=	"SELECT clsyl.syllabus
											 		 FROM ".getTablePrefix()."class_syllabus AS clsyl
											 		 WHERE clsyl.board_id = '".$boardId."' AND clsyl.class_id = '".$classId."' 
											 		 AND clsyl.subject_id = '".$subjectId."' AND clsyl.session_year = '".CURRENT_SESSION."'";
						//print $syllabusQuery; die; 
						$syllabusData			=	$this->common_model->getDataByQuery('single',$syllabusQuery);
						if($syllabusData <> ""):  
							$result['syllabusData']=	$syllabusData;						
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
	 * * Function name : getstudentlist
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get student list
	 * * Date : 12 DECEMBER 2018
	 * * **********************************************************************/
	public function getstudentlist()
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
			else:	
				$branchCode					=	trim($this->input->post('branchCode'));	
				$userType					=	trim($this->input->post('userType'));	
				$userId						=	trim($this->input->post('userId'));
				$boardId					=	trim($this->input->post('boardId'));
				$classId					=	trim($this->input->post('classId'));
				$sectionId					=	trim($this->input->post('sectionId'));

				$branchQuery				=	"SELECT admin_name
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					$teacherQuery				=	"SELECT encrypt_id
													 FROM ".getTablePrefix()."users 
													 WHERE encrypt_id = '".$userId."' AND user_type = '".$userType."'";
					$teacherData				=	$this->common_model->getDataByQuery('single',$teacherQuery);
					if($teacherData <> ""):

						$studentQuery			=	"SELECT stud.student_qunique_id as studentId, CONCAT(stud.student_f_name,' ',stud.student_l_name) as studentName,
													 stud.student_image  as studentImage
											 		 FROM ".getTablePrefix()."student_class AS stucls
											 		 LEFT JOIN ".getTablePrefix()."students AS stud ON stucls.student_qunique_id=stud.student_qunique_id
											 		 WHERE stucls.board_id = '".$boardId."' AND stucls.class_id = '".$classId."' 
											 		 AND stucls.section_id = '".$sectionId."' AND stucls.session_year = '".CURRENT_SESSION."'";
						$studentData			=	$this->common_model->getDataByQuery('multiple',$studentQuery);
						if($studentData <> ""):  
							$result['studentList']=	$studentData;						
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
	 * * Function name : getownstudentlist
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get own student list
	 * * Date : 13 DECEMBER 2018
	 * * **********************************************************************/
	public function getownstudentlist()
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

				$branchQuery				=	"SELECT admin_name
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					$teacherQuery				=	"SELECT encrypt_id
													 FROM ".getTablePrefix()."users 
													 WHERE encrypt_id = '".$userId."' AND user_type = '".$userType."'";
					$teacherData				=	$this->common_model->getDataByQuery('single',$teacherQuery);
					if($teacherData <> ""):
						$clstuQuery				=	"SELECT clsec.class_id as classId, clsec.encrypt_id  as sectionId
											 		 FROM ".getTablePrefix()."class_section AS clsec
											 		 WHERE clsec.class_teacher_id = '".$userId."' AND clsec.board_id = '".$boardId."' AND clsec.session_year = '".CURRENT_SESSION."'";
						$clstuData				=	$this->common_model->getDataByQuery('single',$clstuQuery);
						if($clstuData <> ""):
							$studentQuery			=	"SELECT stud.student_qunique_id as studentId, CONCAT(stud.student_f_name,' ',stud.student_l_name) as studentName,
														 stud.student_image  as studentImage
												 		 FROM ".getTablePrefix()."student_class AS stucls
												 		 LEFT JOIN ".getTablePrefix()."students AS stud ON stucls.student_qunique_id=stud.student_qunique_id
												 		 WHERE stucls.board_id = '".$boardId."' AND stucls.class_id = '".$clstuData['classId']."' 
												 		 AND stucls.section_id = '".$clstuData['sectionId']."' AND stucls.session_year = '".CURRENT_SESSION."'";
							$studentData			=	$this->common_model->getDataByQuery('multiple',$studentQuery);
							if($studentData <> ""):  
								$result['classId']		=	$clstuData['classId'];	
								$result['sectionId']	=	$clstuData['sectionId'];
								$result['studentList']	=	$studentData;					
								echo outPut(lang('SUCESS_STATUS'),lang('SUCESS_MESSAGE'),$result);
							else:
								echo outPut(lang('ERROR_STATUS'),lang('ERROR_MESSAGE'),$result);
							endif;
						else:
							echo outPut(lang('ERROR_STATUS'),lang('NO_CLASS_ALLOUT'),$result);
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
	 * * Function name : setstudentattendance
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for set student attendance
	 * * Date : 13 DECEMBER 2018
	 * * **********************************************************************/
	public function setstudentattendance()
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
			elseif($this->input->post('attendanceData') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('ATTENDANCE_DATA_EMPTY'),$result);	
			else:	
				$branchCode					=	trim($this->input->post('branchCode'));	
				$userType					=	trim($this->input->post('userType'));	
				$userId						=	trim($this->input->post('userId'));
				$boardId					=	trim($this->input->post('boardId'));
				$classId					=	trim($this->input->post('classId'));
				$sectionId					=	trim($this->input->post('sectionId'));
				$date						=	trim($this->input->post('date'));
				$time						=	trim($this->input->post('time'));
				$attendanceData				=	trim($this->input->post('attendanceData'));

				$branchQuery				=	"SELECT admin_name
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					$teacherQuery				=	"SELECT encrypt_id
													 FROM ".getTablePrefix()."users 
													 WHERE encrypt_id = '".$userId."' AND user_type = '".$userType."'";
					$teacherData				=	$this->common_model->getDataByQuery('single',$teacherQuery);
					if($teacherData <> ""):
						$clstuQuery				=	"SELECT clsec.class_id as classId, clsec.encrypt_id  as sectionId
											 		 FROM ".getTablePrefix()."class_section AS clsec
											 		 WHERE clsec.class_teacher_id = '".$userId."' AND clsec.board_id = '".$boardId."' AND clsec.session_year = '".CURRENT_SESSION."'";
						$clstuData				=	$this->common_model->getDataByQuery('single',$clstuQuery);
						if($clstuData <> ""):  
							$attendQuery			=	"SELECT student_qunique_id
												 		 FROM ".getTablePrefix()."student_attendance
												 		 WHERE teacher_id = '".$userId."' 
												 		 AND date = '".addTimeInDate($date)."' AND session_year = '".CURRENT_SESSION."'";
							$attendData				=	$this->common_model->getDataByQuery('single',$attendQuery);
							if($attendData <> ""):
								echo outPut(lang('ERROR_STATUS'),lang('ALREADY_ATTENDANCE'),$result);
							else:
								$allattendanceData	=	json_decode($attendanceData);
				                $attendanceArray	=	array(); 
				                foreach($allattendanceData as $allattendanceValue): 
									foreach($allattendanceValue as $studentId=>$attendance): 
										array_push($attendanceArray,array('studentId'=>$studentId,'attendance'=>$attendance));
									endforeach;
								endforeach;
								//echo '<pre>'; print_r($allattendanceData); print_r($attendanceArray); die;
								foreach($attendanceArray as $attendanceValue):
									if($attendanceValue['attendance'] == 'Present'):
										$param['teacher_id']   				= 	$userId;
										$param['student_qunique_id']   		= 	$attendanceValue['studentId'];
		                                $param['date']                    	= 	addTimeInDate($date);
		                                $param['time']                   	= 	$time;
		                                $param['creation_date']          	= 	currentDateTime();
		                                $param['created_by']              	= 	$userId;
		                                $param['session_year']            	=   CURRENT_SESSION;
		                                $ulastInsertId                    	= 	$this->common_model->addData('student_attendance', $param);
		                                $TUparam['encrypt_id']            	= 	manojEncript($ulastInsertId);
		                                $TUwhere['student_attendance_id'] 	= 	$ulastInsertId;
		                                $this->common_model->editDataByMultipleCondition('student_attendance', $TUparam, $TUwhere);
		                            endif;
								endforeach;
								echo outPut(lang('SUCESS_STATUS'),lang('ATTENDANCE_SUCCESSFULLY'),$result);
							endif;
						else:
							echo outPut(lang('ERROR_STATUS'),lang('NO_CLASS_ALLOUT'),$result);
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
	 * * Function name : getstudentattendance
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get student attendance
	 * * Date : 13 DECEMBER 2018
	 * * **********************************************************************/
	public function getstudentattendance()
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
			elseif($this->input->post('attendanceType') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('ATTENDANCE_TYPE_EMPTY'),$result);	
			else:	
				$branchCode					=	trim($this->input->post('branchCode'));	
				$userType					=	trim($this->input->post('userType'));	
				$userId						=	trim($this->input->post('userId'));
				$boardId					=	trim($this->input->post('boardId'));
				$classId					=	trim($this->input->post('classId'));
				$sectionId					=	trim($this->input->post('sectionId'));
				$date						=	trim($this->input->post('date'));
				$attendanceType				=	trim($this->input->post('attendanceType'));

				$branchQuery				=	"SELECT admin_name
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";												 
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					$teacherQuery				=	"SELECT encrypt_id
													 FROM ".getTablePrefix()."users 
													 WHERE encrypt_id = '".$userId."' AND user_type = '".$userType."'";				
					$teacherData				=	$this->common_model->getDataByQuery('single',$teacherQuery);
					if($teacherData <> ""):
						$attendQuery			=	"SELECT stuatt.student_qunique_id as studentId
													 FROM ".getTablePrefix()."student_class as stucl 
													 LEFT JOIN ".getTablePrefix()."student_attendance as stuatt ON stucl.student_qunique_id=stuatt.student_qunique_id
													 WHERE stucl.board_id = '".$boardId."' AND stucl.class_id = '".$classId."' AND stucl.section_id = '".$sectionId."' 
													 AND stuatt.date = '".addTimeInDate($date)."' AND stuatt.session_year = '".CURRENT_SESSION."'";
						
						$attendData				=	$this->common_model->getFieldInArray('studentId',$attendQuery);					
						$studentQuery			=	"SELECT stud.student_qunique_id as studentId, CONCAT(stud.student_f_name,' ',stud.student_l_name) as studentName,
													 stud.student_image  as studentImage
											 		 FROM ".getTablePrefix()."student_class AS stucls
											 		 LEFT JOIN ".getTablePrefix()."students AS stud ON stucls.student_qunique_id=stud.student_qunique_id
											 		 WHERE stucls.board_id = '".$boardId."' AND stucls.class_id = '".$classId."' 
											 		 AND stucls.section_id = '".$sectionId."' AND stucls.session_year = '".CURRENT_SESSION."'";
						//print "<pre>"; print_r($studentQuery); die;									
						$studentData			=	$this->common_model->getDataByQuery('multiple',$studentQuery);
						if($studentData <> ""):  
							$stuAttrArray		=	array();
							$i=0;
							foreach($studentData as $studentValue):
								if($attendanceType == 'All'):
									$stuAttrArray[$i]['studentId']		=	$studentValue['studentId'];	
									$stuAttrArray[$i]['studentName']	=	$studentValue['studentName'];
									$stuAttrArray[$i]['studentImage']	=	$studentValue['studentImage'];
									$stuAttrArray[$i]['status']			=	in_array($studentValue['studentId'],$attendData)?'Present':'Absent';
									$i++;
								elseif($attendanceType == 'Present'):
									if(in_array($studentValue['studentId'],$attendData)):
										$stuAttrArray[$i]['studentId']		=	$studentValue['studentId'];	
										$stuAttrArray[$i]['studentName']	=	$studentValue['studentName'];
										$stuAttrArray[$i]['studentImage']	=	$studentValue['studentImage'];
										$stuAttrArray[$i]['status']			=	'Present';
										$i++;
									endif;
								elseif($attendanceType == 'Absent'):
									if(!in_array($studentValue['studentId'],$attendData)):
										$stuAttrArray[$i]['studentId']		=	$studentValue['studentId'];	
										$stuAttrArray[$i]['studentName']	=	$studentValue['studentName'];
										$stuAttrArray[$i]['studentImage']	=	$studentValue['studentImage'];
										$stuAttrArray[$i]['status']			=	'Absent';
										$i++;
									endif;
								endif;
							endforeach;
							$result['classId']			=	$classId;	
							$result['sectionId']		=	$sectionId;
							$result['attendanceData']	=	$stuAttrArray;	
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
	 * * Function name : setownattendance
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for set own attendance
	 * * Date : 12 JANUARY 2019
	 * * **********************************************************************/
	public function setownattendance()
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
			elseif($this->input->post('attendanceData') == ''):	// Present  Absent 
				echo outPut(lang('ERROR_STATUS'),lang('ATTENDANCE_DATA_EMPTY'),$result);	
			elseif($this->input->post('date') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('DATE_EMPTY'),$result);
			elseif($this->input->post('time') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('TIME_EMPTY'),$result);
			else:	
				$branchCode					=	trim($this->input->post('branchCode'));	
				$userType					=	trim($this->input->post('userType'));	
				$userId						=	trim($this->input->post('userId'));
				$boardId					=	trim($this->input->post('boardId'));
				$attendanceData				=	trim($this->input->post('attendanceData'));
				$date						=	trim($this->input->post('date'));
				$time						=	trim($this->input->post('time'));

				$branchQuery				=	"SELECT admin_name, admin_franchise_id as franchiseId, 
												 admin_school_id as schoolId, encrypt_id as branchId
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					$teacherQuery				=	"SELECT encrypt_id
													 FROM ".getTablePrefix()."users 
													 WHERE encrypt_id = '".$userId."' AND user_type = '".$userType."'";
					$teacherData				=	$this->common_model->getDataByQuery('single',$teacherQuery);
					if($teacherData <> ""):
						$attendQuery			=	"SELECT teacher_id
											 		 FROM ".getTablePrefix()."teacher_attendance
											 		 WHERE teacher_id = '".$userId."' 
											 		 AND date = '".addTimeInDate($date)."' AND session_year = '".CURRENT_SESSION."'";
						$attendData				=	$this->common_model->getDataByQuery('single',$attendQuery);
						if($attendData <> ""):
							echo outPut(lang('ERROR_STATUS'),lang('ALREADY_ATTENDANCE'),$result);
						else:
							if($attendanceData == 'Present'):
								$param['franchise_id']   			= 	$branchData['franchiseId'];
								$param['school_id']   				= 	$branchData['schoolId'];
								$param['branch_id']   				= 	$branchData['branchId'];
								$param['teacher_id']   				= 	$userId;

                                $param['date']                    	= 	addTimeInDate($date);
                                $param['time']                   	= 	$time;

                                $param['creation_date']          	= 	currentDateTime();
                                $param['created_by']              	= 	$userId;
                                $param['session_year']            	=   CURRENT_SESSION;
                                $param['attendance']            	=   'Y';
                                $ulastInsertId                    	= 	$this->common_model->addData('teacher_attendance', $param);
                                $TUparam['encrypt_id']            	= 	manojEncript($ulastInsertId);
                                $TUwhere['teacher_attendance_id'] 	= 	$ulastInsertId;
                                $this->common_model->editDataByMultipleCondition('teacher_attendance', $TUparam, $TUwhere);
                            endif;
							echo outPut(lang('SUCESS_STATUS'),lang('ATTENDANCE_SUCCESSFULLY'),$result);
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
	 * * Function name : getownattendance
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get own attendance
	 * * Date : 12 JANUARY 2019
	 * * **********************************************************************/
	public function getownattendance()
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
			elseif($this->input->post('fromDate') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('FROM_DATE_EMPTY'),$result);
			elseif($this->input->post('toDate') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('TO_DATE_EMPTY'),$result);  
			else:	
				$branchCode					=	trim($this->input->post('branchCode'));	
				$userType					=	trim($this->input->post('userType'));	
				$userId						=	trim($this->input->post('userId'));
				$boardId					=	trim($this->input->post('boardId'));
				$fromDate					=	trim($this->input->post('fromDate'));
				$toDate						=	trim($this->input->post('toDate'));

				$branchQuery				=	"SELECT admin_name, admin_franchise_id as franchiseId, 
												 admin_school_id as schoolId, encrypt_id as branchId
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					$teacherQuery				=	"SELECT encrypt_id
													 FROM ".getTablePrefix()."users 
													 WHERE encrypt_id = '".$userId."' AND user_type = '".$userType."'";
					$teacherData				=	$this->common_model->getDataByQuery('single',$teacherQuery);
					if($teacherData <> ""):
						$dateRange 				=	dateRangeBetweenTwoDate($fromDate,$toDate);
						$attendQuery			=	"SELECT atten.date as attendanceDate, atten.time as attendanceTime
											 		 FROM ".getTablePrefix()."teacher_attendance AS atten
											 		 WHERE atten.franchise_id = '".$branchData['franchiseId']."' AND atten.school_id = '".$branchData['schoolId']."' 
											 		 AND atten.branch_id = '".$branchData['branchId']."' AND atten.teacher_id = '".$userId."'";
						$attendData				=	$this->common_model->getDataByQuery('multiple',$attendQuery);
						if($attendData <> ""): 
							foreach($attendData as $attendInfo):
								if(in_array(removeTimeFromDate($attendInfo['attendanceDate']),$dateRange)):
									$arrayIndex =	array_search(removeTimeFromDate($attendInfo['attendanceDate']),$dateRange);
									$dateRange[$arrayIndex] 	=	array("attendanceType"=>'Present',"attendanceDate"=>removeTimeFromDate($attendInfo['attendanceDate']),"attendanceTime"=>$attendInfo['attendanceTime']);
								endif;
							endforeach;
						endif;
						foreach($dateRange as $dateKey=>$dateRInfo):
							if(!isset($dateRInfo['attendanceType'])):
								$dateRange[$dateKey] 	=	array("attendanceType"=>'Absent',"attendanceDate"=>$dateRInfo,"attendanceTime"=>'00:00:00');
							endif;
						endforeach;
						$result['attendanceData']	=	$dateRange;	
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
	 * * Function name : getTimetable
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get Timetable
	 * * Date : 13 DECEMBER 2018
	 * * **********************************************************************/
	public function getTimetable()
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

				$branchQuery				=	"SELECT admin_name
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					$teacherQuery				=	"SELECT encrypt_id
													 FROM ".getTablePrefix()."users 
													 WHERE encrypt_id = '".$userId."' AND user_type = '".$userType."'";
					$teacherData				=	$this->common_model->getDataByQuery('single',$teacherQuery);
					if($teacherData <> ""):

						
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
	 * * Function name : sendmessagetoparent
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for send message to parent
	 * * Date : 08 JANUARY 2019
	 * * **********************************************************************/
	public function sendmessagetoparent()
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
			elseif($this->input->post('studentData') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('STUDENT_DATA_EMPTY'),$result);
			elseif($this->input->post('message') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('MESSAGE_EMPTY'),$result);	
			else:	
				$branchCode					=	trim($this->input->post('branchCode'));	
				$userType					=	trim($this->input->post('userType'));	
				$userId						=	trim($this->input->post('userId'));
				$boardId					=	trim($this->input->post('boardId'));
				$classId					=	trim($this->input->post('classId'));
				$sectionId					=	trim($this->input->post('sectionId'));
				$date						=	trim($this->input->post('date'));
				$time						=	trim($this->input->post('time'));
				$studentData				=	trim($this->input->post('studentData'));
				$message					=	trim($this->input->post('message'));

				$branchQuery				=	"SELECT admin_name, admin_franchise_id as franchiseId, 
												 admin_school_id as schoolId, encrypt_id as branchId
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					$teacherQuery				=	"SELECT encrypt_id
													 FROM ".getTablePrefix()."users 
													 WHERE encrypt_id = '".$userId."' AND user_type = '".$userType."'";
					$teacherData				=	$this->common_model->getDataByQuery('single',$teacherQuery);
					if($teacherData <> ""):
						$allstudentData		=	json_decode($studentData);
		                if($allstudentData): 
			                foreach($allstudentData as $allstudentValue): 
								$param['franchise_id']   			= 	$branchData['franchiseId'];
								$param['school_id']   				= 	$branchData['schoolId'];
                                $param['branch_id']                 = 	$branchData['branchId'];
                                $param['board_id']                  = 	$boardId;
                                $param['message_content']           = 	addslashes($message);
                                $param['message_to_type']           = 	'TeacherParent';
                                $param['message_from_id']           = 	$userId;
                                $param['message_to_id']             = 	$allstudentValue;
                                $param['creation_date']          	= 	$date.' '.$time;
                                $param['created_by']              	= 	$userId;
                                $param['status']            		=   'Y';
                                $ulastInsertId                    	= 	$this->common_model->addData('sms_message',$param);
                                $TUparam['encrypt_id']            	= 	manojEncript($ulastInsertId);
                                $TUwhere['message_id'] 				= 	$ulastInsertId;
                                $this->common_model->editDataByMultipleCondition('sms_message',$TUparam,$TUwhere);
							endforeach;
						endif;
						echo outPut(lang('SUCESS_STATUS'),lang('MESSAGE_SENT'),$result);
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
	 * * Function name : getmessagelist
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get message list
	 * * Date : 08 JANUARY 2019
	 * * **********************************************************************/
	public function getmessagelist()
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
			elseif($this->input->post('messageType') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('MESSAGE_TYPE_EMPTY'),$result);
			else:  
				$branchCode					=	trim($this->input->post('branchCode'));	
				$userType					=	trim($this->input->post('userType'));	
				$userId						=	trim($this->input->post('userId'));	
				$boardId					=	trim($this->input->post('boardId'));	
				$messageType				=	trim($this->input->post('messageType'));	//  sent received  

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
						$messageData 		=	array();
						if($messageType == 'sent'):
							$toMessQuery	=	"SELECT mess.encrypt_id as messageId, mess.message_content as messageContent, '".$userData['userName']."' as messageFrom, 
											     CONCAT(stu.student_f_name,' ',stu.student_l_name) as messageTo, mess.creation_date as messageDate
												 FROM ".getTablePrefix()."message as mess
												 LEFT JOIN ".getTablePrefix()."students as stu ON mess.message_to_id=stu.student_qunique_id
												 WHERE mess.message_to_type = 'TeacherParent' AND mess.message_from_id = '".$userId."'
												 AND mess.franchise_id = '".$branchData['franchiseId']."' AND mess.school_id = '".$branchData['schoolId']."' 
											 	 AND mess.branch_id = '".$branchData['branchId']."' AND mess.board_id = '".$boardId."'
											 	 ORDER BY UNIX_TIMESTAMP(mess.creation_date) DESC";
							$messageData 	=	$this->common_model->getDataByQuery('multiple',$toMessQuery);
						elseif($messageType == 'received'):
							$fromMes1Query	=	"SELECT mess.encrypt_id as messageId, mess.message_content as messageContent, 'Admin' as messageFrom, 
											     '".$userData['userName']."' as messageTo, mess.creation_date as messageDate
												 FROM ".getTablePrefix()."message as mess
												 WHERE (mess.message_to_type = 'All' OR mess.message_to_type = 'Teacher')
												 AND mess.franchise_id = '".$branchData['franchiseId']."' AND mess.school_id = '".$branchData['schoolId']."' 
											 	 AND mess.branch_id = '".$branchData['branchId']."' AND mess.board_id = '".$boardId."'
											 	 ORDER BY UNIX_TIMESTAMP(mess.creation_date) DESC";
							$fromMes1Data	=	$this->common_model->getDataByQuery('multiple',$fromMes1Query);
							$fromMes2Query	=	"SELECT mess.encrypt_id as messageId, mess.message_content as messageContent, CONCAT(stu.student_f_name,' ',stu.student_l_name) as messageFrom, 
											     '".$userData['userName']."' as messageTo, mess.creation_date as messageDate
												 FROM ".getTablePrefix()."message as mess
												 LEFT JOIN ".getTablePrefix()."students as stu ON mess.message_from_id=stu.student_qunique_id
												 WHERE mess.message_to_type = 'ParentTeacher' AND mess.message_to_id = '".$userId."'
												 AND mess.franchise_id = '".$branchData['franchiseId']."' AND mess.school_id = '".$branchData['schoolId']."' 
											 	 AND mess.branch_id = '".$branchData['branchId']."' AND mess.board_id = '".$boardId."'
											 	 ORDER BY UNIX_TIMESTAMP(mess.creation_date) DESC";
							$fromMes2Data 	=	$this->common_model->getDataByQuery('multiple',$fromMes2Query);
							if($fromMes1Data <> "" && $fromMes2Data <> ""):
								$fromMessageData 	=	array_merge($fromMes1Data,$fromMes2Data);
								$messageData 		=	array_orderby($fromMessageData, 'messageDate', SORT_DESC, 'messageId', SORT_ASC);
							elseif($fromMes1Data <> ""):
								$messageData 		=	$fromMes1Data;
							elseif($fromMes2Data <> ""):
								$messageData 		=	$fromMes2Data;
							endif;
						endif;
						if($messageData):  
							$result['messageList']=	$messageData;						
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
	 * * Function name : sethomework
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for set homework
	 * * Date : 14 JANUARY 2019
	 * * **********************************************************************/
	public function sethomework()
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
			elseif($this->input->post('subjectId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('SUBJECT_ID_EMPTY'),$result);
			elseif($this->input->post('title') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('HOMEWORK_TITLE_EMPTY'),$result);
			elseif($this->input->post('fromDate') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('FROM_DATE_EMPTY'),$result);
			elseif($this->input->post('toDate') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('TO_DATE_EMPTY'),$result);
			elseif($_FILES['homeWorkFile']['name'] == '' && $this->input->post('content') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('HOMEWORK_CONTENT_EMPTY'),$result);
			else:	
				$branchCode					=	trim($this->input->post('branchCode'));	
				$userType					=	trim($this->input->post('userType'));	
				$userId						=	trim($this->input->post('userId'));
				$boardId					=	trim($this->input->post('boardId'));
				
				$classId					=	trim($this->input->post('classId'));
				$sectionId					=	trim($this->input->post('sectionId'));
				$subjectId					=	trim($this->input->post('subjectId'));
				$title						=	trim($this->input->post('title'));
				$fromDate					=	trim($this->input->post('fromDate'));
				$toDate						=	trim($this->input->post('toDate'));

				$content					=	trim($this->input->post('content'));

				$branchQuery				=	"SELECT admin_name, admin_franchise_id as franchiseId, 
												 admin_school_id as schoolId, encrypt_id as branchId
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					$teacherQuery				=	"SELECT encrypt_id
													 FROM ".getTablePrefix()."users 
													 WHERE encrypt_id = '".$userId."' AND user_type = '".$userType."'";
					$teacherData				=	$this->common_model->getDataByQuery('single',$teacherQuery);
					if($teacherData <> ""):

						if($_FILES['homeWorkFile']['name']):
							$ufileName				= 	$_FILES['homeWorkFile']['name'];
							$utmpName				= 	$_FILES['homeWorkFile']['tmp_name'];
							$ufileExt         		= 	pathinfo($ufileName);
							$unewFileName 			= 	time().'.'.$ufileExt['extension'];
							$this->load->library("upload_crop_img");
							 $param['home_work_file']     	= 	$this->upload_crop_img->_upload_image_from_app($ufileName,$utmpName,$unewFileName,'homeWorkFile','');						
						endif;

						$param['franchise_id']   			= 	$branchData['franchiseId'];
						$param['school_id']   				= 	$branchData['schoolId'];
                        $param['branch_id']                 = 	$branchData['branchId'];
                        $param['board_id']                  = 	$boardId;

                        $param['class_id']                  = 	$classId;
                        $param['section_id']                = 	$sectionId;
                        $param['subject_id']                = 	$subjectId;

                        $param['home_work_title']           = 	addslashes($title);
                        $param['home_work_content']         = 	$content?addslashes($content):'';
                        $param['home_work_from_date']       = 	$fromDate;
                        $param['home_work_to_date']         = 	$toDate;

                        $param['creation_date']          	= 	currentDateTime();
                        $param['created_by']              	= 	$userId;
                        $param['session_year']            	=   CURRENT_SESSION;
                        $param['status']            		=   'Y';
                        $ulastInsertId                    	= 	$this->common_model->addData('home_work',$param);
                        $TUparam['encrypt_id']            	= 	manojEncript($ulastInsertId);
                        $TUwhere['home_work_id'] 			= 	$ulastInsertId;
                        $this->common_model->editDataByMultipleCondition('home_work',$TUparam,$TUwhere);
						
						$result['homeWorkId']				=	$TUparam['encrypt_id'];
		                echo outPut(lang('SUCESS_STATUS'),lang('HOMEWORK_SENT'),$result);
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
	 * * Function name : gethomeworklist
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get homework list
	 * * Date : 14 JANUARY 2019
	 * * **********************************************************************/
	public function gethomeworklist()
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
			elseif($this->input->post('subjectId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('SUBJECT_ID_EMPTY'),$result);
			else:  
				$branchCode					=	trim($this->input->post('branchCode'));	
				$userType					=	trim($this->input->post('userType'));	
				$userId						=	trim($this->input->post('userId'));	
				$boardId					=	trim($this->input->post('boardId'));

				$classId					=	trim($this->input->post('classId'));	
				$sectionId					=	trim($this->input->post('sectionId'));	
				$subjectId					=	trim($this->input->post('subjectId'));	
				$title						=	trim($this->input->post('title'));	
				$fromDate					=	trim($this->input->post('fromDate'));	
				$toDate						=	trim($this->input->post('toDate'));	

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
						$homewkQuery	=	"SELECT clss.encrypt_id as classId, clss.class_name as className,
											 sect.encrypt_id as sectionId, sect.class_section_name as sectionName,
											 subj.encrypt_id as subjectId, subj.subject_name as subjectName, 
											 hwork.encrypt_id as homeWorkId, hwork.home_work_title as homeWorkTitle,
											 hwork.home_work_content as homeWorkContent, hwork.home_work_file as homeWorkFile,
											 hwork.home_work_from_date as homeWorkFromDate, hwork.home_work_to_date as homeWorkToDate
											 FROM ".getTablePrefix()."home_work as hwork
											 LEFT JOIN ".getTablePrefix()."classes as clss ON hwork.class_id=clss.encrypt_id
											 LEFT JOIN ".getTablePrefix()."class_section as sect ON hwork.section_id=sect.encrypt_id
											 LEFT JOIN ".getTablePrefix()."subject as subj ON hwork.subject_id=subj.encrypt_id
											 WHERE hwork.franchise_id = '".$branchData['franchiseId']."' AND hwork.school_id = '".$branchData['schoolId']."' 
										 	 AND hwork.branch_id = '".$branchData['branchId']."' AND hwork.board_id = '".$boardId."' 
										 	 AND hwork.class_id = '".$classId."'  AND hwork.section_id = '".$sectionId."' 
										 	 AND hwork.subject_id = '".$subjectId."'"; 
						if($title):
							$homewkQuery.=	" AND hwork.home_work_title LIKE '%".$title."%'";
						endif;
						if($fromDate):
							$fromDateTime=	(strtotime($fromDate)-3600);
							$homewkQuery.=	" AND UNIX_TIMESTAMP(hwork.home_work_from_date) >= '".$fromDateTime."'";
						endif;
						if($toDate):
							$toDateTime=	(strtotime($toDate)+3600);
							$homewkQuery.=	" AND UNIX_TIMESTAMP(hwork.home_work_to_date) <= '".$toDateTime."'";
						endif;
						$homewkQuery	.=	" ORDER BY UNIX_TIMESTAMP(hwork.home_work_from_date) DESC";
						$homewkData 	=	$this->common_model->getDataByQuery('multiple',$homewkQuery);
                        if($homewkData):  
							$result['homeWorkList']	=	$homewkData;						
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
	 * * Function name : setassignment
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for set assignment
	 * * Date : 14 JANUARY 2019
	 * * **********************************************************************/
	public function setassignment()
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
			elseif($this->input->post('subjectId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('SUBJECT_ID_EMPTY'),$result);
			elseif($this->input->post('title') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('HOMEWORK_TITLE_EMPTY'),$result);
			elseif($this->input->post('fromDate') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('FROM_DATE_EMPTY'),$result);
			elseif($this->input->post('toDate') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('TO_DATE_EMPTY'),$result);
			elseif($_FILES['assignmentFile']['name'] == '' && $this->input->post('content') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('ASSIGNMENT_CONTENT_EMPTY'),$result);
			else:	
				$branchCode					=	trim($this->input->post('branchCode'));	
				$userType					=	trim($this->input->post('userType'));	
				$userId						=	trim($this->input->post('userId'));
				$boardId					=	trim($this->input->post('boardId'));
				
				$classId					=	trim($this->input->post('classId'));
				$sectionId					=	trim($this->input->post('sectionId'));
				$subjectId					=	trim($this->input->post('subjectId'));
				$title						=	trim($this->input->post('title'));
				$fromDate					=	trim($this->input->post('fromDate'));
				$toDate						=	trim($this->input->post('toDate'));

				$content					=	trim($this->input->post('content'));

				$branchQuery				=	"SELECT admin_name, admin_franchise_id as franchiseId, 
												 admin_school_id as schoolId, encrypt_id as branchId
												 FROM ".getTablePrefix()."admin 
												 WHERE branch_code = '".$branchCode."' AND admin_type = 'Branch'
												 AND admin_level = '3' AND status ='A'";
				$branchData					=	$this->common_model->getDataByQuery('single',$branchQuery);
				if($branchData <> ""):  
					$teacherQuery				=	"SELECT encrypt_id
													 FROM ".getTablePrefix()."users 
													 WHERE encrypt_id = '".$userId."' AND user_type = '".$userType."'";
					$teacherData				=	$this->common_model->getDataByQuery('single',$teacherQuery);
					if($teacherData <> ""):

						if($_FILES['assignmentFile']['name']):
							$ufileName				= 	$_FILES['assignmentFile']['name'];
							$utmpName				= 	$_FILES['assignmentFile']['tmp_name'];
							$ufileExt         		= 	pathinfo($ufileName);
							$unewFileName 			= 	time().'.'.$ufileExt['extension'];
							$this->load->library("upload_crop_img");
							 $param['assignments_file']    	= 	$this->upload_crop_img->_upload_image_from_app($ufileName,$utmpName,$unewFileName,'assignmentFile','');						
						endif;

						$param['franchise_id']   			= 	$branchData['franchiseId'];
						$param['school_id']   				= 	$branchData['schoolId'];
                        $param['branch_id']                 = 	$branchData['branchId'];
                        $param['board_id']                  = 	$boardId;

                        $param['class_id']                  = 	$classId;
                        $param['section_id']                = 	$sectionId;
                        $param['subject_id']                = 	$subjectId;

                        $param['assignments_title']         = 	addslashes($title);
                        $param['assignments_content']       = 	$content?addslashes($content):'';
                        $param['assignments_from_date']     = 	$fromDate;
                        $param['assignments_to_date']       = 	$toDate;

                        $param['creation_date']          	= 	currentDateTime();
                        $param['created_by']              	= 	$userId;
                        $param['session_year']            	=   CURRENT_SESSION;
                        $param['status']            		=   'Y';
                        $ulastInsertId                    	= 	$this->common_model->addData('assignments',$param);
                        $TUparam['encrypt_id']            	= 	manojEncript($ulastInsertId);
                        $TUwhere['assignments_id'] 			= 	$ulastInsertId;
                        $this->common_model->editDataByMultipleCondition('assignments',$TUparam,$TUwhere);
						
						$result['assignmentId']				=	$TUparam['encrypt_id'];
		                echo outPut(lang('SUCESS_STATUS'),lang('ASSIGNMENT_SENT'),$result);
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
	 * * Function name : getassignmentlist
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get assignment list
	 * * Date : 14 JANUARY 2019
	 * * **********************************************************************/
	public function getassignmentlist()
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
			elseif($this->input->post('subjectId') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('SUBJECT_ID_EMPTY'),$result);
			else:  
				$branchCode					=	trim($this->input->post('branchCode'));	
				$userType					=	trim($this->input->post('userType'));	
				$userId						=	trim($this->input->post('userId'));	
				$boardId					=	trim($this->input->post('boardId'));

				$classId					=	trim($this->input->post('classId'));	
				$sectionId					=	trim($this->input->post('sectionId'));	
				$subjectId					=	trim($this->input->post('subjectId'));	
				$title						=	trim($this->input->post('title'));	
				$fromDate					=	trim($this->input->post('fromDate'));	
				$toDate						=	trim($this->input->post('toDate'));	

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
						$assignQuery	=	"SELECT clss.encrypt_id as classId, clss.class_name as className,
											 sect.encrypt_id as sectionId, sect.class_section_name as sectionName,
											 subj.encrypt_id as subjectId, subj.subject_name as subjectName, 
											 assign.encrypt_id as assignmentId, assign.assignments_title as assignmentTitle,
											 assign.assignments_content as assignmentContent, assign.assignments_file as assignmentFile,
											 assign.assignments_from_date as assignmentFromDate, assign.assignments_to_date as assignmentToDate
											 FROM ".getTablePrefix()."assignments as assign
											 LEFT JOIN ".getTablePrefix()."classes as clss ON assign.class_id=clss.encrypt_id
											 LEFT JOIN ".getTablePrefix()."class_section as sect ON assign.section_id=sect.encrypt_id
											 LEFT JOIN ".getTablePrefix()."subject as subj ON assign.subject_id=subj.encrypt_id
											 WHERE assign.franchise_id = '".$branchData['franchiseId']."' AND assign.school_id = '".$branchData['schoolId']."' 
										 	 AND assign.branch_id = '".$branchData['branchId']."' AND assign.board_id = '".$boardId."' 
										 	 AND assign.class_id = '".$classId."'  AND assign.section_id = '".$sectionId."' 
										 	 AND assign.subject_id = '".$subjectId."'"; 
						if($title):
							$assignQuery.=	" AND assign.assignments_title LIKE '%".$title."%'";
						endif;
						if($fromDate):
							$fromDateTime=	(strtotime($fromDate)-3600);
							$assignQuery.=	" AND UNIX_TIMESTAMP(assign.assignments_from_date) >= '".$fromDateTime."'";
						endif;
						if($toDate):
							$toDateTime=	(strtotime($toDate)+3600);
							$assignQuery.=	" AND UNIX_TIMESTAMP(assign.assignments_to_date) <= '".$toDateTime."'";
						endif;
						$assignQuery	.=	" ORDER BY UNIX_TIMESTAMP(assign.assignments_from_date) DESC";
						$assignData 	=	$this->common_model->getDataByQuery('multiple',$assignQuery);
                        if($assignData):  
							$result['assignmentList']	=	$assignData;						
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
	
		public function getTimeTableTeacher(){
		
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();
		$returnData 						= 	array();
		if(requestAuthenticate(APIKEY)): 
			//print_r($_POST); die;
			if($this->input->post('school_id') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('SCHOOL_ID_EMPTY'),$result);
			elseif($this->input->post('branch_id') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('BRANCH_ID_EMPTY'),$result);
			elseif($this->input->post('board_id') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('BOARD_ID_EMPTY'),$result);
			elseif($this->input->post('teacher_id') == ''):
				echo outPut(lang('ERROR_STATUS'),lang('TEACHER_ID_EMPTY'),$result);
			else:  
							
				$school_id					=	trim($this->input->post('school_id'));	
				$branch_id					=	trim($this->input->post('branch_id'));	
				$board_id					=	trim($this->input->post('board_id'));	
				$teacher_id					=	trim($this->input->post('teacher_id'));	
				//$day_id = "TUFOT0o0NktVTUFS";	

				$subQuery   =	"SELECT *
							FROM sms_working_days 
							WHERE school_id='" . $school_id . "' AND branch_id='" . $branch_id . "' 
							AND board_id='" . $board_id . "'	
							ORDER BY working_day_id";

				//print $subQuery; die;										
				$dayData	=	$this->common_model->getDataByQuery('multiple',$subQuery);	
				//print "<pre>"; print_r($dayData); die;
				$array1 = array();
				$MondayArr = array();
				$TuesdayArr = array();
				$WednesdayArr = array();
				$ThursdayArr = array();
				$FridayArr = array();
				$SaturdayArr = array();
				foreach ($dayData as $infoDay) {
				$day_id = $infoDay['encrypt_id']; 	
				$subQuery   =	"SELECT t1.period_subject_teacher_id as id,t2.class_name,
						t3.class_section_name,t4.subject_name,t5.class_period_name,day.working_day_name
							FROM sms_period_subject_teacher t1 
							LEFT JOIN sms_classes t2 on t1.class_id=t2.encrypt_id
							LEFT JOIN sms_class_section t3 on t1.section_id=t3.encrypt_id
							LEFT JOIN sms_subject t4 on t1.subject_id=t4.encrypt_id
							LEFT JOIN sms_class_period t5 on t1.period_id=t5.encrypt_id
							LEFT JOIN sms_working_days day on t1.working_day_id=day.encrypt_id
							WHERE  
							t1.working_day_id='".$day_id."' 
							AND t1.teacher_id='".$teacher_id."'  
							AND t1.school_id='" . $school_id . "' 
							AND t1.branch_id='" . $branch_id . "' 
							AND t1.board_id='" . $board_id . "'	
							ORDER BY t2.class_name";

				//print $subQuery; die;										
				$branchData					=	$this->common_model->getDataByQuery('multiple',$subQuery);

				foreach ($branchData as $variantlistdetail) {
					if(!empty($infoDay['working_day_name'])){
					if($infoDay['working_day_name']=='Monday'){
					 $Monday   =   
					 	array('class_name'=>$variantlistdetail['class_name'],
                              'class_section_name'=>$variantlistdetail['class_section_name'],
                               'subject_name'=>$variantlistdetail['subject_name'],
                               'class_period_name'=>$variantlistdetail['class_period_name']);
					array_push($MondayArr, $Monday); 	
					}
					if($infoDay['working_day_name']=='Tuesday'){
					 $Tuesday   =   
					 	array('class_name'=>$variantlistdetail['class_name'],
                              'class_section_name'=>$variantlistdetail['class_section_name'],
                               'subject_name'=>$variantlistdetail['subject_name'],
                               'class_period_name'=>$variantlistdetail['class_period_name']);
					array_push($TuesdayArr, $Tuesday); 	
					}
					if($infoDay['working_day_name']=='Wednesday'){
					 $Wednesday   =   
					 	array('class_name'=>$variantlistdetail['class_name'],
                              'class_section_name'=>$variantlistdetail['class_section_name'],
                               'subject_name'=>$variantlistdetail['subject_name'],
                               'class_period_name'=>$variantlistdetail['class_period_name']);
					array_push($WednesdayArr, $Wednesday); 	
					}
					if($infoDay['working_day_name']=='Thursday'){
					 $Thursday   =   
					 	array('class_name'=>$variantlistdetail['class_name'],
                              'class_section_name'=>$variantlistdetail['class_section_name'],
                               'subject_name'=>$variantlistdetail['subject_name'],
                               'class_period_name'=>$variantlistdetail['class_period_name']);
					array_push($ThursdayArr, $Thursday); 	
					}
					if($infoDay['working_day_name']=='Friday'){
					 $Friday   =   
					 	array('class_name'=>$variantlistdetail['class_name'],
                              'class_section_name'=>$variantlistdetail['class_section_name'],
                               'subject_name'=>$variantlistdetail['subject_name'],
                               'class_period_name'=>$variantlistdetail['class_period_name']);
					array_push($FridayArr, $Friday); 	
					}
					if($infoDay['working_day_name']=='Saturday'){
					 $Saturday   =   
					 	array('class_name'=>$variantlistdetail['class_name'],
                              'class_section_name'=>$variantlistdetail['class_section_name'],
                               'subject_name'=>$variantlistdetail['subject_name'],
                               'class_period_name'=>$variantlistdetail['class_period_name']);
					array_push($SaturdayArr, $Saturday); 	
					}
					
				}

				}

			}
				
				if($branchData <> ""): 
					$result['TeacherTimeTableList']=	array('Monday'=> $MondayArr,
															'Tuesday'=> $TuesdayArr,
															'Wednesday'=> $WednesdayArr,
															'Thursday'=> $ThursdayArr,
															'Friday'=> $FridayArr,
															'Saturday'=> $SaturdayArr);	
					echo outPut(lang('SUCESS_STATUS'),lang('SUCESS_MESSAGE'),$result);					
				else:
					echo outPut(lang('ERROR_STATUS'),lang('INVALID_BRANCH_CODE'),$result);
				endif;
			endif;
		else:
			echo outPut(lang('ERROR_STATUS'),lang('INVALID_APIKEY'),$result);
		endif;
		
	}
}