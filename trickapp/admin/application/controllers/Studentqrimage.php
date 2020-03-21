<?php
//echo 'Current PHP version: ' . phpversion(); die;
defined('BASEPATH') OR exit('No direct script access allowed');

class Studentqrimage extends \CI_Controller {

    public
            function __construct() {
        parent:: __construct();
        $this->load->helper(array('form', 'url', 'html', 'path', 'form', 'cookie'));
        $this->load->library(array('email', 'session', 'form_validation', 'pagination', 'parser', 'encrypt'));
        error_reporting(E_ALL ^ E_NOTICE);
        $this->load->library("layouts");
        $this->load->model(array('admin_model', 'common_model', 'emailtemplate_model', 'sms_model'));
        $this->load->helper('language');
        $this->lang->load('statictext', 'admin');
         $this->load->library('ciqrcode');
    }

    /*     * *********************************************************************
     * * Function name : Teacherlist
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for Teacherlist
     * * Date : 01 FEBRUARY 2018
     * * ********************************************************************* */

    public
            function index() {
        $this->admin_model->authCheck('admin', 'view_data');
        $this->admin_model->get_permission_type($data);
        $data['error'] = '';

        $subQuery = "SELECT encrypt_id, class_name FROM sms_classes
									 	WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
										AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
										AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
										AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";
        $data['CLASSDATA'] = $this->common_model->get_data_by_query('multiple', $subQuery);

        $classId           = $this->input->post('class_id');
        $sectionId         = $this->input->post('section_id');
        $data['classid']   = $classId;
        $data['sectionid'] = $sectionId;


        $qrQuery = "SELECT qr.qr_pic , stud.student_f_name , stud.student_l_name , stud.student_m_name ,branch.student_registration_no FROM `sms_student_qrcode` AS qr 
                    LEFT JOIN `sms_student_class` AS class ON class.student_qunique_id = qr.student_qunique_id 
                    LEFT JOIN `sms_classes` AS c ON c.encrypt_id = class.class_id LEFT JOIN `sms_class_section` AS sec ON sec.encrypt_id = class.section_id 
                    LEFT JOIN `sms_students` AS stud ON stud.student_qunique_id = qr.student_qunique_id 
                    LEFT JOIN `sms_student_branch` AS branch ON stud.student_qunique_id = branch.student_qunique_id 
									 	WHERE class.class_id = '" . $classId . "' 
										AND class.section_id = '" . $sectionId . "' 
										";


        $qrData               = $this->common_model->get_data_by_query('multiple', $qrQuery);
        
        $data['QRDATA']       = $qrData;
       
        $secQuery             = "SELECT sms_class_section.class_section_short_name FROM sms_class_section WHERE encrypt_id = '" . $sectionId . "' ;									";
        $section_name         = $this->common_model->get_data_by_query('single', $secQuery);
        $classQuery           = "SELECT sms_classes.class_short_name FROM `sms_classes`   WHERE encrypt_id = '" . $classId . "' ;									";
        $class_name           = $this->common_model->get_data_by_query('single', $classQuery);
        $data['CLASS_NAME']   = $class_name;
        $data['SECTION_NAME'] = $section_name;

        if ($this->input->post('DownloadQR')):
            $this->load->library('Mpdf');
            
            $classSection         = $class_name['class_short_name'] . '_' . $section_name['class_section_short_name'];
            $data['classSection'] = $classSection;
            $this->layouts->set_title('Display Student QRCode');
            $this->load->view('studentqrimage/printinpdf', $data);
            $this->download_pdf('QR_code_class_' . $classSection . '.pdf');

        //$this->download_pdf($classSection.".pdf");
        endif;



        if ($this->input->post('SaveChanges')):


            //all student details


            $addQuery    = "SELECT  stud.student_f_name ,stud.student_m_name ,stud.student_l_name  ,stud.student_qunique_id,b.student_registration_no FROM `sms_student_class` AS class
LEFT JOIN `sms_students` AS stud ON stud.student_qunique_id = class.student_qunique_id 
LEFT JOIN sms_student_branch AS b  on b.student_qunique_id = stud.student_qunique_id
										WHERE class.class_id = '" . $classId . "' 
										
										AND class.section_id = '" . $sectionId . "' 
										";
            $studentData = $this->common_model->get_data_by_query('multiple', $addQuery);
            

            //all student with qr code alredy 

            $stdQrQuery = "SELECT qr.student_qunique_id 
            FROM `sms_student_qrcode` AS qr 
            LEFT JOIN `sms_student_class` AS class ON class.student_qunique_id = qr.student_qunique_id 
			WHERE class.class_id = '" . $classId . "' 
			AND class.section_id = '" . $sectionId . "'";
            $studentqrData = $this->common_model->get_data_by_query('multiple', $stdQrQuery);
            //echo "<pre>"; print_r($studentqrData); die;
            //father data
            $fQuery = "SELECT  usr.user_f_name ,usr.user_m_name ,usr.user_l_name  ,per.student_qunique_id FROM `sms_student_class` AS class
                        LEFT JOIN `sms_student_parent` AS per ON per.student_qunique_id = class.student_qunique_id
                        LEFT JOIN `sms_users` AS usr ON per.parent_id = usr.encrypt_id
                        WHERE per.parent_type = 'Father' AND   class.class_id = '" . $classId . "' 
										
										AND class.section_id = '" . $sectionId . "' 
										";

            $fatherData = $this->common_model->get_data_by_query('multiple', $fQuery);

            //mother data
            $mQuery     = "SELECT  usr.user_f_name ,usr.user_m_name ,usr.user_l_name  ,per.student_qunique_id FROM `sms_student_class` AS class
LEFT JOIN `sms_student_parent` AS per ON per.student_qunique_id = class.student_qunique_id
LEFT JOIN `sms_users` AS usr ON per.parent_id = usr.encrypt_id
WHERE per.parent_type = 'Mother' AND   class.class_id = '" . $classId . "' 
										
										AND class.section_id = '" . $sectionId . "' 
										";
            $motherData = $this->common_model->get_data_by_query('multiple', $mQuery);

            $PNG_TEMP_DIR = './assets/studentimages/S_' . \manojDecript($this->session->userdata('SMS_ADMIN_SCHOOL_ID')) . '/B_' . \manojDecript($this->session->userdata('SMS_ADMIN_BRANCH_ID')) . DIRECTORY_SEPARATOR . 'qrcode' . DIRECTORY_SEPARATOR;
            $PNG_WEB_DIR  = 'qrcode/';
            if (!file_exists($PNG_TEMP_DIR))
                mkdir($PNG_TEMP_DIR, 0777, true);
              $classSec = $class_name['class_short_name'] . '-' . $section_name['class_section_short_name'];
            
                        $qrdata['level'] = 'M';
                        $qrdata['size']  = 6;
                         $Param['qr_level'] = 'M';
                        $Param['qr_size']  = 6;
            if ($studentData) :
                foreach ($studentData as $STDALL) :
                    $fathName = '';
                    $mothName = '';
                        $Param['qr_level'] = 'M';
                        $Param['qr_size']  = 6;
                    $rollNo   = $STDALL['student_roll_no'];
                    $studName = $STDALL['student_f_name'] . ' ' . $STDALL['student_m_name']->mname . ' ' . $STDALL['student_l_name'];

                    if ($fatherData):
                        foreach ($fatherData as $FATHALL) :
                            if ($FATHALL['student_qunique_id'] == $STDALL['student_qunique_id']) :
                                $fathName = $FATHALL['user_f_name'] . ' ' . $FATHALL['user_m_name'] . ' ' . $FATHALL['user_l_name'];
                            endif;
                        endforeach;
                    endif;

                    if ($motherData):
                        foreach ($motherData as $MOTHALL) :
                            if ($MOTHALL['student_qunique_id'] == $STDALL['student_qunique_id']) :
                                $mothName = $MOTHALL['user_f_name'] . ' ' . $MOTHALL['user_m_name'] . ' ' . $MOTHALL['user_l_name'];
                            endif;
                        endforeach;
                    endif;

                    $qrdata['data'] = 'Registration No: ' .$STDALL['student_registration_no']  . '     Roll No: ' . $rollNo . '             Student Name: ' . $studName . '        Father Name: ' . $fathName . '      Mother Name: ' . $mothName . '       Class-Section: ' . $classSec;
                
                    $qrdata['savename'] = $STDALL['student_qunique_id'] . '.png';
                    $Param['qr_pic']    = $PNG_TEMP_DIR . $qrdata['savename'];

                    ///genrate qr code

                    if (isset($qrdata['data'])) {
                        if (trim($qrdata['data']) == '')
                            die('data cannot be empty! <a href="?">back</a>');

                       
                        
                        $this->ciqrcode->generate($qrdata, $PNG_TEMP_DIR);

                        ///add and edit to student qrcode table

                                $edit = 0 ; 
                             if($studentqrData):
                                
                    foreach ($studentqrData AS $EXISTQR) :
                                 
                      if(  $EXISTQR['student_qunique_id']  == $STDALL['student_qunique_id'] ) :
                          $edit = 1 ;
                     $editQuniqueId = $STDALL['student_qunique_id'] ;
                      
                      endif;
                    
                    endforeach;
                endif;
                        
                 
              
                        if (!$edit):
                          
                             $Param['qr_level'] = 'M';
                        $Param['qr_size']  = 6;
                            $Param['student_qunique_id'] = $STDALL['student_qunique_id'];

                            $Param['creation_date'] = \currentDateTime();
                            $Param['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                            $Param['status']        = 'Y';
                             $Param['session_year']=CURRENT_SESSION;
                            $stuaddlastInsertId     = $this->common_model->add_data('student_qrcode', $Param);

                            $Sparam['encrypt_id']        = \manojEncript($stuaddlastInsertId);
                            $Swhere['student_qrcode_id'] = $stuaddlastInsertId;
                            $this->common_model->edit_data_by_multiple_cond('student_qrcode', $Sparam, $Swhere);

                     
                        else:
                            
                     
                           $Params['qr_level'] = 'M';
                        $Params['qr_size']  = 6;
                        
                      
                            $Params['update_date'] = \currentDateTime();
                            $Params['updated_by']  = $this->session->userdata('SMS_ADMIN_ID');
                            $Where['student_qunique_id']  =  $editQuniqueId ;
                       
                            
                            $this->common_model->edit_data_by_multiple_cond('student_qrcode', $Params, $Where);
            
                        endif;
                    }

                endforeach;
   
            endif;
              $this->session->set_flashdata('alert_success', \lang('updatesuccess'));
        endif;

        $this->layouts->set_title('Download Student QR Code');
        $this->layouts->admin_view('studentqrimage/index', array(), $data);
    }

// END OF FUNCTION


    /*     * *********************************************************************
     * * Function name: download_pdf
     * * Developed By: Manoj Kumar
     * * Purpose: This function used for download pdf
     * * Date: 01 FEBRUARY 2018
     * ********************************************************************** */

    public
            function download_pdf($file = '') {
        header('Content-Description: File Transfer');
        // We'll be outputting a PDF
        header('Content-type: application/pdf');
        // It will be called downloaded.pdf
        header('Content-Disposition: attachment; filename="' . $file . '"');
        // The PDF source is in original.pdf
        readfile($this->config->item("root_path") . "assets/downloadpdf/" . $file);

        @unlink($this->config->item("root_path") . "assets/downloadpdf/" . $file);
        exit;
    }

// END OF FUNCTION	
// END OF FUNCTION	
}
