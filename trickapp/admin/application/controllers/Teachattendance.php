<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Teachattendance extends CI_Controller {

    public
            function __construct() {
        parent:: __construct();
        $this->load->helper(array('form', 'url', 'html', 'path', 'form', 'cookie'));
        $this->load->library(array('email', 'session', 'form_validation', 'pagination', 'parser', 'encrypt'));
        error_reporting(E_ALL ^ E_NOTICE);
        $this->load->library("layouts");
        $this->load->library('excel');
        $this->load->model(array('admin_model', 'common_model', 'emailtemplate_model', 'sms_model'));
        $this->load->helper('language');
        $this->lang->load('statictext', 'admin');
    }

    /*     * *********************************************************************
     * * Function name : classlist
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for classlist
     * * Date : 23 JANUARY 2018
     * * ********************************************************************* */
    public function index() {
        $this->admin_model->authCheck('admin', 'view_data');
        $this->admin_model->get_permission_type($data);
        $teacherid         = $this->input->get('teacher_id');
        $data['teacherid'] = $teacherid;
        ///all teachers name ////
        $teacherQuery      = "SELECT encrypt_id, user_f_name, user_m_name, user_l_name, user_mobile 
                              FROM sms_users WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
							  AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
							  AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'AND user_type='Teacher'  AND status='A' ";
        $data['TEACHERDATA'] = $this->common_model->get_data_by_query('multiple', $teacherQuery);
        $data['error']       = '';

        $data['start_date'] = $this->input->get('start_date');
        $data['end_date']   = $this->input->get('end_date');
        $data['date']       = $this->input->get('date');
   
        if ($teacherid != 'All') :
            //single techer query
            $singleTeacherQuery = "SELECT user_f_name ,user_m_name ,user_l_name ,user_mobile ,employee_id  FROM sms_users WHERE encrypt_id = '" . $teacherid . "'  AND franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
								   AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
								   AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'AND user_type='Teacher'  AND status='A' ";
            $teacherInfo = $this->common_model->get_data_by_query('single', $singleTeacherQuery);
           
            $startDate        = strtotime(DDMMYYtoYYMMDD($this->input->get('start_date')));
            $endDate          = (strtotime(DDMMYYtoYYMMDD($this->input->get('end_date'))) + 82800);
            //find joining date
            $joiningDateQuery = "SELECT user_joining_date   FROM `sms_user_detils` WHERE user_id = '" . $teacherid . "' ";

            $joining_date = $this->common_model->get_data_by_query('single', $joiningDateQuery);
            $joiningDate  = strtotime($joining_date['user_joining_date']);
            if($teacherInfo):
                if ($joiningDate < $endDate) :
                    //all present data
                    $alldataQuery    = "SELECT attnd.date ,  attnd.time ,t.user_f_name,t.user_l_name,t.user_m_name, t.employee_id , t.user_mobile 
                                        FROM `sms_staff_attendance` AS attnd
                                        LEFT JOIN `sms_users` AS t  ON t.encrypt_id = attnd.staff_id WHERE attnd.staff_id ='" . $teacherid . "'  
                                        AND UNIX_TIMESTAMP(attnd.date) >= '" . ($startDate) . "' AND UNIX_TIMESTAMP(attnd.date)  <= '" . ($endDate) . "'
                                        AND attnd.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
    									AND attnd.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
    									AND attnd.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'";
                    $allPresentAttnd = $this->common_model->get_data_by_query('multiple', $alldataQuery);

                    $allAttendance = array();
                    $workingDate   = array();
                    //joining date and start date validation
                    if ($startDate < $joiningDate):
                        $startDate = $joiningDate;
                        $this->session->set_flashdata('alert_warning', 'Joinging Date is "' . YYMMDDtoDDMMYY($joining_date['user_joining_date']) . '"');
                    endif;
                    //find the weekends//////
                    $workingDayQuery = "SELECT working_day_name FROM `sms_working_days` WHERE  school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
    												 AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'  and franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'   and board_id = (SELECT board_id   FROM sms_working_days  where franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
    												 AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
    												 AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' LIMIT 1 )";
                    $working_day = $this->common_model->get_data_by_query('multiple', $workingDayQuery);
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
                                    $dateFlag          = '1';
                                    $APA['attandance'] = 'Present';
                                    array_push($allAttendance, $APA);
                                endif;
                            endforeach;
                        endif;
                        if (!$dateFlag) :
                            $tmpArry                = array();
                            $tmpArry                = '';
                            $tmpArry['user_f_name'] = $teacherInfo['user_f_name'];
                            $tmpArry['user_m_name'] = $$teacherInfo['user_m_name'];
                            $tmpArry['user_l_name'] = $teacherInfo['user_l_name'];
                            $tmpArry['user_mobile'] = $teacherInfo['user_mobile'];
                            $tmpArry['employee_id'] = $teacherInfo['employee_id'];
                            $tmpArry['attandance']  = 'Absent';
                            $tmpArry['date']        = $WDATE;
                            array_push($allAttendance, $tmpArry);
                        endif;
                    endforeach;
                    if($allAttendance):
                        $data['ALLDATA'] = $allAttendance;
                    endif ;
                else:
                    $this->session->set_flashdata('alert_warning', lang('enddateerror'));
                endif;
            endif;
        else:
            $startDate       = strtotime(DDMMYYtoYYMMDD($this->input->get('date')));
            $endDate         = (strtotime(DDMMYYtoYYMMDD($this->input->get('date'))) + 82800);
            $alldataQuery    = "SELECT attnd.date , attnd.staff_id , attnd.time ,t.user_f_name,t.user_l_name,t.user_m_name, t.employee_id ,t.user_mobile 
                                FROM `sms_staff_attendance` AS attnd
                                LEFT JOIN `sms_users` AS t  ON t.encrypt_id = attnd.staff_id     LEFT JOIN sms_user_detils AS d  ON  d.user_id = t.encrypt_id WHERE  UNIX_TIMESTAMP(attnd.date) >= '" . ($startDate) . "' 
                                AND UNIX_TIMESTAMP(attnd.date)  <= '" . ($endDate) . "'
                                AND  UNIX_TIMESTAMP(d.user_joining_date)    <= '" . ($startDate) . "'  AND  attnd.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
								AND attnd.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
								AND attnd.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'";
            $allPresentAttnd = $this->common_model->get_data_by_query('multiple', $alldataQuery);

            $allteacherQuery = "SELECT t.user_f_name,t.user_l_name,t.user_m_name,t.user_mobile ,t.employee_id, t.encrypt_id FROM `sms_users` AS t
                                LEFT JOIN sms_user_detils AS d  ON  d.user_id = t.encrypt_id WHERE   UNIX_TIMESTAMP(d.user_joining_date)    <= '" . ($startDate) . "'  AND  t.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
								AND t.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
                                AND t.user_type='Teacher' AND t.status='A'
								AND t.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'";
            $allteacher      = $this->common_model->get_data_by_query('multiple', $allteacherQuery);
            $allTAttendance = array();
            foreach ($allteacher as $AT):
                $encryptFlag = '0';
                if ($allPresentAttnd):
                    foreach ($allPresentAttnd as $APA):
                        if ($APA['staff_id'] == $AT['encrypt_id']):
                            $encryptFlag       = '1';
                            $APA['attandance'] = 'Present';
                            array_push($allTAttendance, $APA);
                        endif;
                    endforeach;
                endif;
                if (!$encryptFlag) :
                    $tmpArry                = array();
                    $tmpArry                = '';
                    $tmpArry['user_f_name'] = $AT['user_f_name'];
                    $tmpArry['user_m_name'] = $AT['user_m_name'];
                    $tmpArry['user_l_name'] = $AT['user_l_name'];
                    $tmpArry['user_mobile'] = $AT['user_mobile'];
                    $tmpArry['employee_id'] = $AT['employee_id'];
                    $tmpArry['date']        = $this->input->get('date');
                    $tmpArry['attandance'] = 'Absent';
                    array_push($allTAttendance, $tmpArry);
                endif;
            endforeach;
            $data['ALLDATA'] = $allTAttendance;
        endif;
        //// download visitor report  execl
        if ($this->input->post('report')):
            if($teacherid):
                $this->downloadTeacherAttendance($data['ALLDATA']);
                //excel download end 
            else:
              $this->session->set_flashdata('alert_warning', 'Please  select Date and Teacher.');
           endif ; 
        endif;        
        if ($this->input->post('SaveTeacherExcelUpload')):
            $this->addTeacherAttendanceByExcel($data,$_FILES);
        endif;
        // end Upload excel

        $this->load->library('pagination');
        $config['base_url'] = $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index/';
        $this->session->set_userdata('teacherAttendanceListAdminData', currentFullUrl());
        $qStringdata        = explode('?', currentFullUrl());
        $config['suffix']   = $qStringdata[1] ? '?' . $qStringdata[1] : '';
        $data['forAction']  = $config['base_url'];

        $this->layouts->set_title('Manage teacher attendance details');
        $this->layouts->admin_view('teachattendance/index', array(), $data);
    } // END OF FUNCTION

    /* * *********************************************************************
     * * Function name : downloadTeacherAttendance
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for Download Teacher Attendance
     * * Date : 11 DECEMBER 2018
     * * ********************************************************************* */
    public function downloadTeacherAttendance($ALLDATA) {
        $tmpReportArry = array();
        $reportData    = array();
        foreach ($ALLDATA as $AD):
            $tmpReportArry                = '';
            $tmpReportArry['name']        = $AD['user_f_name'] . ' ' . $AD['user_m_name'] . ' ' . $AD['user_l_name'];
            $tmpReportArry['user_mobile'] = $AD['user_mobile'];
            $tmpReportArry['employee_id'] = $AD['employee_id'];
            $tmpReportArry['date']        = date('d-m-Y ', strtotime(stripslashes($AD['date'])));
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
        $this->excel->getActiveSheet()->getStyle('A1:F1')->getFill()->applyFromArray(array(
            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                'rgb' => 'faca33'
            )
        ));
        $this->excel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        ));
        $this->excel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setTextRotation(0);
        $this->excel->getActiveSheet()->setCellValue('A1', ' Name');
        $this->excel->getActiveSheet()->setCellValue('B1', 'Mobile No');
        $this->excel->getActiveSheet()->setCellValue('C1', 'Employee Id');
        $this->excel->getActiveSheet()->setCellValue('D1', 'Attendance Date');
        $this->excel->getActiveSheet()->setCellValue('E1', 'Time');
        $this->excel->getActiveSheet()->setCellValue('F1', 'Attendance');
        for ($col = ord('A'); $col <= ord('F'); $col++) {
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
                    $this->excel->getActiveSheet()->getStyle('A'.$ci.':F'.$ci)->getFill()->applyFromArray(array(
                        'type'       => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array(
                            'rgb' => 'ADFF2F'
                        )
                    ));
                endif;
                $this->excel->getActiveSheet()->getStyle('A'.$ci.':F'.$ci)->applyFromArray(array(
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
        $filename = 'Teacher_Attendance_Report.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
        return true;
    }

    /* * *********************************************************************
     * * Function name : addTeacherAttendanceByExcel
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for add Teacher Attendance By Excel
     * * Date : 11 DECEMBER 2018
     * * ********************************************************************* */
    public function addTeacherAttendanceByExcel($data,$FILES) {
        include APPPATH . 'third_party/PHPExcel/XLSReader.php';
        include APPPATH . 'third_party/PHPExcel/XLSXReader.php';
        $dir = 'S_' . manojDecript($this->session->userdata('SMS_ADMIN_SCHOOL_ID')) . '/B_' . manojDecript($this->session->userdata('SMS_ADMIN_BRANCH_ID'));

        $teacherData = $data['TEACHERDATA'];
        $pathinfo    = pathinfo($_FILES['teacherFile']['name']);
        $uploadedFileName = "'" . $pathinfo['filename'] . "'";
        $file_extension = isset($pathinfo['extension']) ? $pathinfo['extension'] : ''; 
        $file_extension = strtolower($file_extension);
        if ($file_extension == 'csv'):
            $dir      = $config['root_path'] . 'assets/teacherDocument/S_'  . manojDecript($this->session->userdata('SMS_ADMIN_SCHOOL_ID')) . '/B_' . manojDecript($this->session->userdata('SMS_ADMIN_BRANCH_ID')).'/';
            if (!file_exists($dir))
                mkdir($dir, 0777, true);
            $filename = basename(time() .'_'. str_replace(" ", "-", $_FILES['studentFile']['name']));
            $tmpname  = $_FILES['teacherFile']['tmp_name'];
            move_uploaded_file("$tmpname", $dir . $filename);
            if ($file_extension == 'csv') {  
                $row    = 0;
                if ($handle = fopen($dir . $filename, "r")):
                    while (($csvData = fgetcsv($handle, 10000)) !== FALSE):
                        $num = count($csvData);
                        $row++;
                        if ($row > 1):
                            $branddataarray = str_replace("'", "`", $csvData);
                            $name   =   $branddataarray[0];
                            $mobile =   $branddataarray[1];
                            $date   =   $branddataarray[2];
                            $month  =   $branddataarray[3];
                            $year   =   $branddataarray[4];
                            $time   =   $branddataarray[5];
                            $data1  =   array();
                            foreach ($teacherData as $TD):
                                if ($TD['user_mobile'] == $mobile):
                                    $data1['staff_id']   = $TD['encrypt_id'];
                                    $data1['franchise_id'] = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                                    $data1['school_id']    = addslashes($this->session->userdata('SMS_ADMIN_SCHOOL_ID'));
                                    $data1['branch_id']    = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                                    $data1['date']          = $year.'-'.$month.'-'.$date ;
                                    $data1['time']          = $time;
                                    $data1['creation_date'] = currentDateTime();
                                    $data1['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                                    $data1['session_year']  =   CURRENT_SESSION;
                                    $ulastInsertId          = $this->common_model->add_data('staff_attendance', $data1);
                                    $TUparam['encrypt_id']  = manojEncript($ulastInsertId);
                                    $TUwhere['staff_attendance_id'] = $ulastInsertId;
                                    $this->common_model->edit_data_by_multiple_cond('staff_attendance', $TUparam, $TUwhere);
                                endif;
                            endforeach;
                        endif;
                    endwhile;
                    $this->session->set_flashdata('alert_success', lang('addsuccess'));
                endif;
            }
        endif;
        return true;
    }
}