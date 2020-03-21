<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Studattendance extends CI_Controller {

    public  function __construct() {
        parent:: __construct();
        $this->load->helper(array('form', 'url', 'html', 'path', 'form', 'cookie'));
        $this->load->library(array('email', 'session', 'form_validation', 'pagination', 'parser', 'encrypt'));
        error_reporting(E_ALL ^ E_NOTICE);
		error_reporting(0);
        $this->load->library("layouts");
        $this->load->library('excel');
        $this->load->model(array('admin_model', 'common_model', 'emailtemplate_model', 'sms_model'));
        $this->load->helper('language');
        $this->lang->load('statictext', 'admin');
    }

    /* * *********************************************************************
     * * Function name : classlist
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for classlist
     * * Date : 23 JANUARY 2018
     * * ********************************************************************* */
    public function index() {

        //echo "<pre>"; print_r($this->input->get()); die;
        $this->admin_model->authCheck('admin', 'view_data');
        $this->admin_model->get_permission_type($data);
        $classid           = $this->input->get('class_id');
        $data['classid']   = $classid;
        $sectionid         = $this->input->get('section_id');
        $data['sectionid'] = $sectionid;
        $studentid         = $this->input->get('student_id');
        $data['studentid'] = $studentid;
        ///all students name ////

        $data['error'] = '';
        $data['start_date'] = $this->input->get('start_date');
        $data['end_date']   = $this->input->get('end_date');
        $data['date']       = $this->input->get('date');
        $data['contentMsg'] = 'No data available in table';
        //echo "<pre>"; print_r($data); die;

        $startDate          = addTimeInDate(DDMMYYtoYYMMDD($this->input->get('start_date')));
        $endDate            = addTimeInDate((DDMMYYtoYYMMDD($this->input->get('end_date'))));
        //echo $startDate.'---'.$endDate; die;

        $subQuery          = "SELECT encrypt_id, class_name FROM sms_classes
						 	  WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
							  AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
							  AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
                              AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
                              AND status= 'Y'";
       
        $data['CLASSDATA'] = $this->common_model->get_data_by_query('multiple', $subQuery);
        
        $workingDayQuery   = "SELECT working_day_name FROM `sms_working_days` WHERE  school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
							  AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' and franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' and board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";
        $working_day = $this->common_model->get_data_by_query('multiple', $workingDayQuery);
        if($working_day):  
            if ($studentid): 
                if ($studentid != 'All') : 
                    //single query
                    $singleStudentQuery = "SELECT stud.student_f_name ,stud.student_m_name ,stud.student_l_name,
											sb.student_registration_no,att.attedence_status as attandance,att.date,att.time   
                                           FROM  sms_students as stud 
                                           left join sms_student_branch as sb on sb.student_qunique_id=stud.student_qunique_id 
										   INNER JOIN sms_student_attendance as att on stud.student_qunique_id=att.student_qunique_id		
										  WHERE sb.student_qunique_id = '" . $studentid . "'  
                                           AND att.date >= '" . $startDate. "'
										   AND att.date <= '" .$endDate."' ";
                    //echo "<pre>"; print_r($singleStudentQuery); die;
                    $allTAttendance = $this->common_model->get_data_by_query('multiple', $singleStudentQuery);
                    $data['ALLDATA'] = $allTAttendance;
                    //echo "<pre>"; print_r($data['ALLDATA']); die;
                    // if ($studentInfo):
                    //     $startDate          = strtotime(DDMMYYtoYYMMDD($this->input->get('start_date')));
                    //     $endDate            = (strtotime(DDMMYYtoYYMMDD($this->input->get('end_date'))) + 82800);
                    //     //find joining date
                    //     $admissionDateQuery = "SELECT student_admission_date   FROM `sms_student_branch` WHERE student_qunique_id = '" . $studentid . "' ";

                    //     $admission_date = $this->common_model->get_data_by_query('single', $admissionDateQuery);
                    //     $admissionDate  = strtotime($admission_date['student_admission_date']);

                        
                    // endif;
                else:
                    $Date       = addTimeInDate(DDMMYYtoYYMMDD($this->input->get('date')));

                    $startDate          = addTimeInDate(DDMMYYtoYYMMDD($this->input->get('start_date')));
                    $endDate            = addTimeInDate((DDMMYYtoYYMMDD($this->input->get('end_date'))));
                    $alldataQuery    = "SELECT attnd.date  , attnd.time , stud.student_f_name ,stud.student_m_name ,
                                        stud.student_l_name ,sb.student_registration_no ,stud.student_qunique_id,attnd.attedence_status as attandance  
                                        FROM `sms_student_attendance` AS attnd  
                                        LEFT JOIN sms_students as stud on stud.student_qunique_id = attnd.student_qunique_id
                                        LEFT JOIN sms_student_branch AS sb  ON  sb.student_qunique_id = attnd.student_qunique_id   
                                        LEFT JOIN sms_student_class as sc on  sc.student_qunique_id = attnd.student_qunique_id    
                                        WHERE attnd.date like '%$Date%'
                                        AND sc.class_id = '" . $classid . "'
                                        AND sc.section_id = '" . $sectionid . "'";
                    $allPresentAttnd = $this->common_model->get_data_by_query('multiple', $alldataQuery);
                    //echo "<pre>"; print_r($allPresentAttnd); die;
                    $data['ALLDATA'] = $allPresentAttnd;
                endif;
                //// download student attendance in execl
                if ($this->input->post('report')):
                   $this->downloadStudentAttendance($data['ALLDATA']);
                //excel download end 
                endif;
            endif;
            // Excel Bulk Upload Student data
            // if ($this->input->post('SaveStudentExcelUpload')):
            //     $this->addStudentAttendanceByExcel($_FILES);
            // // end Upload excel
            // endif;
        else:
            $this->session->set_flashdata('alert_warning', 'Please define working week days first');
        endif;

        $this->load->library('pagination');
        $config['base_url'] = $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index/';
        $this->session->set_userdata('studentAttendanceListAdminData', currentFullUrl());
        $qStringdata        = explode('?', currentFullUrl());
        $config['suffix']   = $qStringdata[1] ? '?' . $qStringdata[1] : '';
        $data['forAction']  = $config['base_url'];
        
        //echo '<pre>'; print_r($data); die;
        $this->layouts->set_title('Manage student attendance details');
        $this->layouts->admin_view('studattendance/index', array(), $data);
    }  // END OF FUNCTION

     /* * *********************************************************************
     * * Function name : downloadStudentAttendance
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for Download Student Attendance
     * * Date : 10 DECEMBER 2018
     * * ********************************************************************* */
    public function downloadStudentAttendance($ALLDATA) {
        $tmpReportArry = array();
        $reportData    = array();
        foreach ($ALLDATA as $AD):
            $tmpReportArry                            = '';
            $tmpReportArry['name']                    = $AD['student_f_name'] . ' ' . $AD['student_m_name'] . ' ' . $AD['student_l_name'];
            $tmpReportArry['student_registration_no'] = $AD['student_registration_no'];
            $tmpReportArry['date'] = date('d-m-Y ', strtotime(stripslashes($AD['date'])));
            if ($AD['time']):
                $tmpReportArry['time'] = date("g:i ", strtotime($AD['time']));
            else:
                $tmpReportArry['time'] = '';
            endif;
            $tmpReportArry['attandance'] = $AD['attandance'];
            array_push($reportData, $tmpReportArry);
        endforeach;
        //excel download start
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Attendance');
        $this->excel->getActiveSheet()->getStyle('A1:E1')->getFill()->applyFromArray(array(
            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                'rgb' => 'faca33'
            )
        ));
        $this->excel->getActiveSheet()->getStyle('A1:E1')->applyFromArray(array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        ));
        $this->excel->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setTextRotation(0);
        $this->excel->getActiveSheet()->setCellValue('A1', ' Name');
        $this->excel->getActiveSheet()->setCellValue('B1', 'Registration No');
        $this->excel->getActiveSheet()->setCellValue('C1', 'Attendance Date');
        $this->excel->getActiveSheet()->setCellValue('D1', 'Time');
        $this->excel->getActiveSheet()->setCellValue('E1', 'Attendance');
        for ($col = ord('A'); $col <= ord('E'); $col++) {
            $this->excel->getActiveSheet()->getColumnDimension(chr($col))->setWidth('25');
            $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
            $this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
            $this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
        $exceldata = array();
        if ($reportData <> ""):
            $ci=2;
            foreach ($reportData as $row) {
                $exceldata[] = $row;
                $this->excel->getActiveSheet()->getRowDimension($ci)->setRowHeight(15);
                if($row['attandance'] == 'Present'):
                    $this->excel->getActiveSheet()->getStyle('A'.$ci.':E'.$ci)->getFill()->applyFromArray(array(
                        'type'       => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array(
                            'rgb' => 'ADFF2F'
                        )
                    ));
                endif;
                $this->excel->getActiveSheet()->getStyle('A'.$ci.':E'.$ci)->applyFromArray(array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                ));
                $ci++;
            }
        endif;
        //Fill data 
        $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A2');
        $filename = 'Student_Attendance_Report.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
        return true;
    }

	/* * *********************************************************************
     * * Function name : classlist
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for classlist
     * * Date : 23 JANUARY 2018
     * * ********************************************************************* */
    public function addStudentAttendance() {  //echo "<pre>"; print_r($this->input->get()); die;
        //print "<pre>"; print_r($_POST); die;
        $data['error'] = '';
        $this->admin_model->authCheck('admin', 'view_data');
        $this->admin_model->get_permission_type($data);
        $classid           = $this->input->get('class_id');
        $data['classid']   = $classid;
        $sectionid         = $this->input->get('section_id');
        $data['sectionid'] = $sectionid;
        $teacherid         = $this->input->get('teacher_id');
        $data['teacherid'] = $teacherid;

        $subQuery          = "SELECT encrypt_id, class_name FROM sms_classes
						 	  WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
							  AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
							  AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
                              AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
                              AND status = 'Y'";
		    //print $subQuery; die;
        $data['CLASSDATA'] = $this->common_model->get_data_by_query('multiple', $subQuery);
        // $workingDayQuery   = "SELECT working_day_name FROM `sms_working_days` WHERE  school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
		// 					  AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' and franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' and board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";
        // $working_day = $this->common_model->get_data_by_query('multiple', $workingDayQuery);
       
        /////////////////////////////////Pagination All rows Data????????????????????????

        if($this->input->get('searchValue')):
			$sValue							=	$this->input->get('searchValue');
			$whereCon['like']		 		= 	"(stdc.id LIKE '%".$sValue."%' 
												 )";
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchValue'] 			= 	'';
		endif;
		
		$whereCon['where']		 			= 	"stdc.franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' 
												 AND stdc.school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
												 AND stdc.branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."'";		
		$shortField 						= 	'UNIX_TIMESTAMP(stdc.creation_date) DESC';
        $tblName 							= 	'student_class as stdc';
		
        //echo "<pre>"; print_r($data); die;
        $data['ALLAttEDANCE']		= 	$this->admin_model->get_all_attedance_student_data('data',$tblName,$whereCon,$shortField,$config['per_page'],$page,$classid,$sectionid,$date,$teacherid);

        /*************************
                                   Student upload data Submit attendance
                                                                         ****************************/
                
             if($this->input->post('SaveChangesattendancedata')): 
			$error					=	'NO';  
			$this->form_validation->set_rules('studentID[]', 'Student ID', 'trim');
			if($this->form_validation->run() && $error == 'NO'): 
                $date = (DDMMYYtoYYMMDD($this->input->get('start_date')));
                $time = date("H:i:s");
                
                $STUDENTDATA				=	$this->input->post('studentID');
                	if($STUDENTDATA <> ""):
                    foreach($STUDENTDATA as $studentinfo): 

                        $studentattendQuery1 = "SELECT st.student_attendance_id, st.student_qunique_id 
                         FROM `sms_student_attendance` AS st 
                         WHERE  st.class_id = '".$_POST['class_id_'.$studentinfo]."' 
                         AND st.section_id = '".$_POST['section_id_'.$studentinfo]."'
                         AND st.teacher_id = '".$_POST['teacherID_'.$studentinfo]."'
                         AND st.student_qunique_id = '".$studentinfo."'
                         AND st.date = '".$date."'";
                        $studentattendData1  = $this->common_model->get_data_by_query('single', $studentattendQuery1);
                        if($studentattendData1== ""): 
                        $param['student_qunique_id']   =  $studentinfo;
                        $param['date']						=	($date);
                        $param['time']						=	$time;
                        $param['class_id']             =  $_POST['class_id_'.$studentinfo]; 
                        $param['section_id']           =  $_POST['section_id_'.$studentinfo];
                        $param['teacher_id']           =  $_POST['teacherID_'.$studentinfo]; 
                        $param['attedence_status']     =  $_POST['leaverequest_'.$studentinfo];
                        $alastInsertId				   =  $this->common_model->add_data('student_attendance',$param);
                        $this->session->set_flashdata('alert_success',lang('addsuccess'));
                     else:
                        $SParam['attedence_status']     =  $_POST['leaverequest_'.$studentinfo];
                        $SParam['update_date']          = currentDateTime();
                        $SBUwhere['date']						=	($date);
                        $SBUwhere['student_qunique_id']						=	($studentinfo);
                        $this->common_model->edit_data_by_multiple_cond('student_attendance', $SParam, $SBUwhere);
                        $this->session->set_flashdata('alert_success', lang('updatesuccess')); 
                      //$this->session->set_flashdata('alert_error',lang('studattendrror'));
                     endif;
                    endforeach; 
                endif;
				redirect(correctLink('studentAttendanceListAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
			endif;
		endif;

        $this->layouts->set_title('Manage student attendance details');
        $this->layouts->admin_view('studattendance/addStudentAttendance', array(), $data);
    }  // END OF FUNCTION


 /* * *********************************************************************
     * * Function name : bulkStudentAttendance
     * * Developed By : Ashish UMrao
     * * Purpose  : This function used for bulkStudentAttendance
     * * Date : 29 FEB 2020
     * * ********************************************************************* */
    public function bulkStudentAttendance() {

        $this->admin_model->authCheck('admin', 'view_data');
        $this->admin_model->get_permission_type($data);
        ///all students name ////

        $subQuery          = "SELECT encrypt_id, class_name FROM sms_classes
						 	  WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
							  AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
							  AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
                              AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
                              AND status= 'Y'";
       
        $data['CLASSDATA'] = $this->common_model->get_data_by_query('multiple', $subQuery);
        if($this->input->post('SaveStudentExcelUpload')): //echo "<pre>"; print_r($_FILES); die;
			$error					=	'NO';
			$this->form_validation->set_rules('class_id', 'Class name', 'trim|required');
            $this->form_validation->set_rules('section_id', 'Section name', 'trim|required');
            $this->form_validation->set_rules('teacher_id', 'Teacher name', 'trim|required');
            
            if ($this->input->post('SaveStudentExcelUpload')):
                $this->addStudentAttendanceByExcel($_FILES);
            // end Upload excel
            endif;
			
		endif;
        $this->layouts->set_title('Manage student attendance details');
        $this->layouts->admin_view('studattendance/bulkStudentAttendance', array(), $data);
}
        
         function addStudentAttendanceByExcel($FILES) {   //echo "<pre>"; print_r($FILES['studentFile']); die;
        include APPPATH . 'third_party/PHPExcel/XLSReader.php';
        include APPPATH . 'third_party/PHPExcel/XLSXReader.php';
        $dir = 'S_' . manojDecript($this->session->userdata('SMS_ADMIN_SCHOOL_ID')) . '/B_' . manojDecript($this->session->userdata('SMS_ADMIN_BRANCH_ID'));

        $studentQuery = "SELECT sb.student_registration_no , sc.student_qunique_id 
                         FROM `sms_student_class` AS sc 
                         LEFT JOIN sms_student_branch AS sb ON sb.student_qunique_id = sc.student_qunique_id 
                         WHERE  sc.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
                         AND sc.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
                         AND sc.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
                         AND sc.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'";
        $studentData  = $this->common_model->get_data_by_query('multiple', $studentQuery);
        $pathinfo = pathinfo($FILES['studentFile']['name']);
        $uploadedFileName = "'" . $pathinfo['filename'] . "'";
        //$fp = file($pathinfo['filename']);
        //print_r($pathinfo); die;
        $file_extension = isset($pathinfo['extension']) ? $pathinfo['extension'] : ''; // die;
        $file_extension = strtolower($file_extension);
        if ($file_extension == 'csv'):
            $dir      = $config['root_path'] . 'assets/studentDocument/S_' . manojDecript($this->session->userdata('SMS_ADMIN_SCHOOL_ID')) . '/B_' . manojDecript($this->session->userdata('SMS_ADMIN_BRANCH_ID')) . '/';
            if (!file_exists($dir))
                mkdir($dir, 0777, true);
            $filename = basename(time() .'_'. str_replace(" ", "-", $FILES['studentFile']['name']));
            $tmpname  = $FILES['studentFile']['tmp_name'];
            move_uploaded_file("$tmpname", $dir . $filename);
            if ($file_extension == 'csv'):  //echo "true"; die;
                $row    = 0;
                if ($handle = fopen($dir . $filename, "r")):
                   
                    while (($csvData = fgetcsv($handle, 10000)) !== FALSE):
                        $num = count($csvData);
                        $row++;
                        if ($row > 1):
                            $branddataarray = ($csvData);
                            $name           = $branddataarray[0];
                            $registrationNo = $branddataarray[1];
                            $date           = $branddataarray[2];
                            $month          = $branddataarray[3];
                            $year           = $branddataarray[4];
                            $time           = $branddataarray[5];
                            $status         = $branddataarray[6]; 
                            $datetime  = ($year.'-0'.$month.'-'.$date);
                            $data1 = array();                           
                                foreach ($studentData as $SD):  //echo "<pre>"; print_r($studentData); die;
                                    $studentattendQuery = "SELECT st.student_attendance_id, st.student_qunique_id 
                                        FROM `sms_student_attendance` AS st
                                        LEFT JOIN sms_student_branch as sb on st.student_qunique_id = sb.student_qunique_id 
                                        WHERE  st.class_id = '".$this->input->post('class_id')."' 
                                        AND st.section_id = '".$this->input->post('section_id')."'
                                        AND st.teacher_id = '".$this->input->post('teacher_id')."'
                                        AND sb.student_registration_no = '".$registrationNo."'
                                        AND st.date = '$datetime'";                                   
                                    $studentattendData  = $this->common_model->get_data_by_query('multiple', $studentattendQuery);
                                    //echo "<pre>"; print_r($studentattendData); die;
                                    if($studentattendData == ''): 
                                    if ($SD['student_registration_no'] == $registrationNo): 
                                        $data1['class_id']		    =	$this->input->post('class_id');
                                        $data1['section_id']		=	$this->input->post('section_id');
                                        $data1['teacher_id']		=	$this->input->post('teacher_id');	
                                        $data1['attedence_status']        = $status;
                                        $data1['student_qunique_id']   = $SD['student_qunique_id'];
                                        $data1['date']                    = $year . '-' . $month . '-' . $date;
                                        $data1['time']                    = $time;
                                        $data1['creation_date']           = currentDateTime();
                                        $data1['created_by']              = $this->session->userdata('SMS_ADMIN_ID');
                                        $data1['session_year']            =   CURRENT_SESSION;
                                        $ulastInsertId                    = $this->common_model->add_data('student_attendance', $data1);
                                        $TUparam['encrypt_id']            = manojEncript($ulastInsertId);
                                        $TUwhere['student_attendance_id'] = $ulastInsertId;
                                        $this->common_model->edit_data_by_multiple_cond('student_attendance', $TUparam, $TUwhere);
                                        //$this->session->set_flashdata('alert_success', lang('addsuccess'));
                                    // else: 
                                    //     $this->session->set_flashdata('alert_error',lang('studattendrror'));
                                    endif; 
                                endif;
                                  
                            endforeach; 
                            
                        endif;
                    endwhile;
                    $this->session->set_flashdata('alert_success', lang('addsuccess'));
                endif;
            endif;
        endif;
        return true;
    }
        

}   