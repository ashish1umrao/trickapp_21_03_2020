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
        $subQuery          = "SELECT encrypt_id, class_name FROM sms_classes
						 	  WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
							  AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
							  AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
							  AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";
        $data['CLASSDATA'] = $this->common_model->get_data_by_query('multiple', $subQuery);
        $workingDayQuery   = "SELECT working_day_name FROM `sms_working_days` WHERE  school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
							  AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' and franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' and board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";
        $working_day = $this->common_model->get_data_by_query('multiple', $workingDayQuery);
        if($working_day):  
            if ($studentid): 
                if ($studentid != 'All') : 
                    //single query
                    $singleStudentQuery = "SELECT stud.student_f_name ,stud.student_m_name ,stud.student_l_name,
											sb.student_registration_no,att.attedence_status   
                                           FROM  sms_students as stud 
                                           left join sms_student_branch as sb on sb.student_qunique_id=stud.student_qunique_id 
										   INNER JOIN sms_student_attendance as att on stud.student_qunique_id=att.student_qunique_id		
										  WHERE sb.student_qunique_id = '" . $studentid . "'  
                                           AND sb.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
										   AND sb.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'                                                 
                                           AND sb.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
										   AND sb.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' ";
                    $studentInfo = $this->common_model->get_data_by_query('single', $singleStudentQuery);
                    if ($studentInfo):
                        $startDate          = strtotime(DDMMYYtoYYMMDD($this->input->get('start_date')));
                        $endDate            = (strtotime(DDMMYYtoYYMMDD($this->input->get('end_date'))) + 82800);
                        //find joining date
                        $admissionDateQuery = "SELECT student_admission_date   FROM `sms_student_branch` WHERE student_qunique_id = '" . $studentid . "' ";

                        $admission_date = $this->common_model->get_data_by_query('single', $admissionDateQuery);
                        $admissionDate  = strtotime($admission_date['student_admission_date']);

                        if ($admissionDate < $endDate) :
                            //all present data
                            $alldataQuery    = "SELECT attnd.date ,  attnd.time  
                                                FROM `sms_student_attendance` AS attnd 
                                                WHERE attnd.student_qunique_id ='" . $studentid . "'  
                                                AND UNIX_TIMESTAMP(attnd.date) >= '" . ($startDate) . "' 
                                                AND UNIX_TIMESTAMP(attnd.date)  <= '" . ($endDate) . "'";
                            $allPresentAttnd = $this->common_model->get_data_by_query('multiple', $alldataQuery);

                            $allAttendance = array();
                            $workingDate   = array();
                            //joining date and start date validation
                            if ($startDate < $admissionDate):
                                $startDate = $admissionDate;
                                $this->session->set_flashdata('alert_warning', 'Admission Date is "' . YYMMDDtoDDMMYY($admission_date['student_admission_date']) . '"');
                            endif;
                            //find the weekends//////
                            $numDays = abs($startDate - $endDate) / 60 / 60 / 24;
                            for ($i = 0; $i < $numDays; $i++) {
                                if ($working_day):
                                    foreach ($working_day as $WD):
                                        if ($WD['working_day_name'] == date('l', strtotime("+{$i} day", $startDate))):
                                            ;
                                            array_push($workingDate, date('Y-m-d', strtotime("+{$i} day", $startDate)));
                                        endif;
                                    endforeach;
                                endif;
                            }
                            foreach ($workingDate as $WDATE):
                                $tmpArry['student_f_name'] = $studentInfo['student_f_name'];
								$tmpArry['student_m_name'] = $studentInfo['student_m_name'];
								$tmpArry['student_l_name'] = $studentInfo['student_l_name'];
								$tmpArry['student_registration_no'] = $studentInfo['student_registration_no'];
								$tmpArry['attandance']      = $studentInfo['attedence_status'];
								$tmpArry['date'] = $this->input->get('date');
								array_push($allAttendance, $tmpArry);
                            endforeach;
                            $data['ALLDATA'] = $allAttendance;
                        else:
                            $this->session->set_flashdata('alert_warning', lang('enddateerror'));
                        endif;
                    endif;
                else:
                    $startDate       = strtotime(DDMMYYtoYYMMDD($this->input->get('date')));
                    $endDate         = (strtotime(DDMMYYtoYYMMDD($this->input->get('date'))) + 82800);
                    $alldataQuery    = "SELECT attnd.date  , attnd.time , stud.student_f_name ,stud.student_m_name ,
										stud.student_l_name ,sb.student_registration_no ,stud.student_qunique_id,att.attedence_status  
                                        FROM `sms_student_attendance` AS attnd 
                                        left join sms_students as stud on stud.student_qunique_id = attnd.student_qunique_id
                                        LEFT JOIN sms_student_branch AS sb  ON  sb.student_qunique_id = attnd.student_qunique_id   
                                        LEFT JOIN sms_student_class as sc on  sc.student_qunique_id = attnd.student_qunique_id    
										INNER JOIN sms_student_attendance as att on stud.student_qunique_id=att.student_qunique_id
									   WHERE  UNIX_TIMESTAMP(attnd.date) >= '" . ($startDate) . "' AND UNIX_TIMESTAMP(attnd.date)  <= '" . ($endDate) . "' AND sc.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
										AND sc.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
                                        AND sc.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
                                        AND sc.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
                                        AND sc.class_id = '" . $classid . "'
                                        AND sc.section_id = '" . $sectionid . "'
										AND  UNIX_TIMESTAMP(sb.student_admission_date)  <= '" . ($startDate) . "' ";
                    $allPresentAttnd = $this->common_model->get_data_by_query('multiple', $alldataQuery);

                    $allstudentQuery = "SELECT stud.student_f_name ,stud.student_m_name ,stud.student_l_name ,
										sb.student_registration_no ,stud.student_qunique_id,att.attedence_status
                                        FROM `sms_students` AS stud
                                        LEFT JOIN sms_student_branch AS sb  ON  sb.student_qunique_id = stud.student_qunique_id   
                                        LEFT JOIN sms_student_class as sc on  sc.student_qunique_id = stud.student_qunique_id 
                                        INNER JOIN sms_student_attendance as att on stud.student_qunique_id=att.student_qunique_id
										WHERE UNIX_TIMESTAMP(sb.student_admission_date)    <= '" . ($startDate) . "' AND sc.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
										AND sc.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
                                        AND sc.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
                                        AND sc.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
                                        AND sc.class_id = '" . $classid . "' AND stud.status = 'A' AND sc.section_id = '" . $sectionid . "' ";
					//print $allstudentQuery; die;
				   $allstudent      = $this->common_model->get_data_by_query('multiple', $allstudentQuery);
                    $allTAttendance = array();
                    foreach ($allstudent as $AT):
							$tmpArry['student_f_name'] = $AT['student_f_name'];
                            $tmpArry['student_m_name'] = $AT['student_m_name'];
                            $tmpArry['student_l_name'] = $AT['student_l_name'];
                            $tmpArry['student_registration_no'] = $AT['student_registration_no'];
							$tmpArry['attandance']      = $AT['attedence_status'];
                            $tmpArry['date'] = $this->input->get('date');
                            array_push($allTAttendance, $tmpArry);
                    endforeach;
                    $data['ALLDATA'] = $allTAttendance;
                endif;
                //// download student attendance in execl
                if ($this->input->post('report')):
                   $this->downloadStudentAttendance($data['ALLDATA']);
                //excel download end 
                endif;
            endif;
            // Excel Bulk Upload Student data
            if ($this->input->post('SaveStudentExcelUpload')):
                $this->addStudentAttendanceByExcel($_FILES);
            // end Upload excel
            endif;
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
     * * Function name : addStudentAttendanceByExcel
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for add Student Attendance By Excel
     * * Date : 10 DECEMBER 2018
     * * ********************************************************************* */
    public function addStudentAttendanceByExcel($FILES) {
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
                            $branddataarray = str_replace("'", "`", $csvData);
                            //$branchName   =   $branddataarray[0];
                            $name           = $branddataarray[0];
                            $registrationNo = $branddataarray[1];
                            $date           = $branddataarray[2];
                            $month          = $branddataarray[3];
                            $year           = $branddataarray[4];
                            $time = $branddataarray[5];
                            $data1 = array();
                            foreach ($studentData as $SD):
                                if ($SD['student_registration_no'] == $registrationNo):
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
	
	/* * *********************************************************************
     * * Function name : classlist
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for classlist
     * * Date : 23 JANUARY 2018
     * * ********************************************************************* */
    public function addStudentAttendance() {  //echo "<pre>"; print_r($this->input->get()); die;
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
        $subQuery          = "SELECT encrypt_id, class_name FROM sms_classes
						 	  WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
							  AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
							  AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
							  AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";
		    //print $subQuery; die;
        $data['CLASSDATA'] = $this->common_model->get_data_by_query('multiple', $subQuery);
        $workingDayQuery   = "SELECT working_day_name FROM `sms_working_days` WHERE  school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
							  AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' and franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' and board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";
        $working_day = $this->common_model->get_data_by_query('multiple', $workingDayQuery);
       
        //
        
        if($working_day):  
            if ($studentid): 
                if ($studentid != 'All') : 
                    //single query
                    $singleStudentQuery = "SELECT stud.student_f_name ,stud.student_m_name ,stud.student_l_name ,sb.student_registration_no   
                                           FROM  sms_students as stud 
                                           left join sms_student_branch as sb on sb.student_qunique_id=stud.student_qunique_id 
                                           WHERE sb.student_qunique_id = '" . $studentid . "'  
                                           AND sb.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
										   AND sb.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'                                                 
                                           AND sb.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
										   AND sb.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' ";
                    $studentInfo = $this->common_model->get_data_by_query('single', $singleStudentQuery);
                    //echo "<pre>"; print_r ($studentInfo); die;
                    if ($studentInfo):
                        $startDate          = strtotime(DDMMYYtoYYMMDD($this->input->get('start_date')));
                        $endDate            = (strtotime(DDMMYYtoYYMMDD($this->input->get('end_date'))) + 82800);
                        //find joining date
                        $admissionDateQuery = "SELECT student_admission_date   FROM `sms_student_branch` WHERE student_qunique_id = '" . $studentid . "' ";

                        $admission_date = $this->common_model->get_data_by_query('single', $admissionDateQuery);
                        $admissionDate  = strtotime($admission_date['student_admission_date']);

                        if ($admissionDate < $endDate) :
                            //all present data
                            $alldataQuery    = "SELECT attnd.date ,  attnd.time  
                                                FROM `sms_student_attendance` AS attnd 
                                                WHERE attnd.student_qunique_id ='" . $studentid . "'  
                                                AND UNIX_TIMESTAMP(attnd.date) >= '" . ($startDate) . "' 
                                                AND UNIX_TIMESTAMP(attnd.date)  <= '" . ($endDate) . "'";
                            $allPresentAttnd = $this->common_model->get_data_by_query('multiple', $alldataQuery);

                            $allAttendance = array();
                            $workingDate   = array();
                            //joining date and start date validation
                            if ($startDate < $admissionDate):
                                $startDate = $admissionDate;
                                $this->session->set_flashdata('alert_warning', 'Admission Date is "' . YYMMDDtoDDMMYY($admission_date['student_admission_date']) . '"');
                            endif;
                            //find the weekends//////
                            $numDays = abs($startDate - $endDate) / 60 / 60 / 24;
                            for ($i = 0; $i < $numDays; $i++) {
                                if ($working_day):
                                    foreach ($working_day as $WD):
                                        if ($WD['working_day_name'] == date('l', strtotime("+{$i} day", $startDate))):
                                            ;
                                            array_push($workingDate, date('Y-m-d', strtotime("+{$i} day", $startDate)));
                                        endif;
                                    endforeach;
                                endif;
                            }
                            foreach ($workingDate as $WDATE):
                                $dateFlag = '0';
                                if ($allPresentAttnd):
                                    foreach ($allPresentAttnd as $APA):
                                        if (in_array($WDATE . ' 00:00:00', $APA)):
                                            $dateFlag              = '1';
                                            $APA['student_f_name'] = $studentInfo['student_f_name'];
                                            $APA['student_m_name'] = $$studentInfo['student_m_name'];
                                            $APA['student_l_name'] = $studentInfo['student_l_name'];

                                            $APA['student_registration_no'] = $studentInfo['student_registration_no'];
                                            $APA['attandance']              = 'Present';
                                            array_push($allAttendance, $APA);
                                        endif;
                                    endforeach;
                                endif;
                                if (!$dateFlag) :
                                    $tmpArry                   = array();
                                    $tmpArry                   = '';
                                    $tmpArry['student_f_name'] = $studentInfo['student_f_name'];
                                    $tmpArry['student_m_name'] = $$studentInfo['student_m_name'];
                                    $tmpArry['student_l_name'] = $studentInfo['student_l_name'];

                                    $tmpArry['student_registration_no'] = $studentInfo['student_registration_no'];
                                    $tmpArry['attandance']              = 'Absent';
                                    $tmpArry['date']                    = $WDATE;
                                    array_push($allAttendance, $tmpArry);
                                endif;
                            endforeach;
                            $data['ALLDATA'] = $allAttendance;
                        else:
                            $this->session->set_flashdata('alert_warning', lang('enddateerror'));
                        endif;
                    endif;
                else:
                    $startDate       = strtotime(DDMMYYtoYYMMDD($this->input->get('date')));
                    $endDate         = (strtotime(DDMMYYtoYYMMDD($this->input->get('date'))) + 82800);
                    $alldataQuery    = "SELECT attnd.date  , attnd.time , stud.student_f_name ,stud.student_m_name ,stud.student_l_name ,sb.student_registration_no ,stud.student_qunique_id  
                                        FROM `sms_student_attendance` AS attnd 
                                        left join sms_students as stud on stud.student_qunique_id = attnd.student_qunique_id
                                        LEFT JOIN sms_student_branch AS sb  ON  sb.student_qunique_id = attnd.student_qunique_id   
                                        LEFT JOIN sms_student_class as sc on  sc.student_qunique_id = attnd.student_qunique_id    
                                        WHERE  UNIX_TIMESTAMP(attnd.date) >= '" . ($startDate) . "' AND UNIX_TIMESTAMP(attnd.date)  <= '" . ($endDate) . "' AND sc.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
										AND sc.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
                                        AND sc.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
                                        AND sc.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
                                        AND sc.class_id = '" . $classid . "'
                                        AND sc.section_id = '" . $sectionid . "'
										AND  UNIX_TIMESTAMP(sb.student_admission_date)  <= '" . ($startDate) . "' ";
                    $allPresentAttnd = $this->common_model->get_data_by_query('multiple', $alldataQuery);

                    $allstudentQuery = "SELECT stud.student_f_name ,stud.student_m_name ,stud.student_l_name ,sb.student_registration_no ,stud.student_qunique_id 
                                        FROM `sms_students` AS stud
                                        LEFT JOIN sms_student_branch AS sb  ON  sb.student_qunique_id = stud.student_qunique_id   
                                        LEFT JOIN sms_student_class as sc on  sc.student_qunique_id = stud.student_qunique_id 
                                        WHERE UNIX_TIMESTAMP(sb.student_admission_date)    <= '" . ($startDate) . "' AND sc.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
										AND sc.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
                                        AND sc.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
                                        AND sc.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
                                        AND sc.class_id = '" . $classid . "' AND stud.status = 'A' AND sc.section_id = '" . $sectionid . "' ";
                    //print  $allstudentQuery; die;
					$allstudent      = $this->common_model->get_data_by_query('multiple', $allstudentQuery);
                    $allTAttendance = array();
                    foreach ($allstudent as $AT):
                        $encryptFlag = '0';
                        if ($allPresentAttnd):
                            foreach ($allPresentAttnd as $APA):
                                if ($APA['student_qunique_id'] == $AT['student_qunique_id']):
                                    $encryptFlag       = '1';
                                    $APA['attandance'] = 'Present';
                                    array_push($allTAttendance, $APA);
                                endif;
                            endforeach;
                        endif;
                        if (!$encryptFlag) :
                            $tmpArry = array();
                            $tmpArry = '';
                            $tmpArry['student_f_name'] = $AT['student_f_name'];
                            $tmpArry['student_m_name'] = $AT['student_m_name'];
                            $tmpArry['student_l_name'] = $AT['student_l_name'];
                            $tmpArry['student_registration_no'] = $AT['student_registration_no'];
                            $tmpArry['attandance']              = 'Absent';
                            $tmpArry['date'] = $this->input->get('date');
                            array_push($allTAttendance, $tmpArry);
                        endif;
                    endforeach;
                    $data['ALLDATA'] = $allTAttendance;
                endif;
                //// download student attendance in execl
                if ($this->input->post('report')):
                   $this->downloadStudentAttendance($data['ALLDATA']);
                //excel download end 
                endif;
            endif;
            // Excel Bulk Upload Student data
            if ($this->input->post('SaveStudentExcelUpload')):
                $this->addStudentAttendanceByExcel($_FILES);
            // end Upload excel
            endif;
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
        $this->layouts->admin_view('studattendance/addStudentAttendance', array(), $data);
    }  // END OF FUNCTION
}   