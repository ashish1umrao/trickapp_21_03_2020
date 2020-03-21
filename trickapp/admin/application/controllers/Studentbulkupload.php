<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Studentbulkupload extends \CI_Controller {

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

    public function index() {
		error_reporting(0);
        $this->admin_model->authCheck('admin', 'view_data');
        $this->admin_model->get_permission_type($data);
        $data['error']     = '';
        $classid           = $this->input->post('class_id');
        $data['classid']   = $classid;
        $sectionid         = $this->input->post('section_id');
        $data['sectionid'] = $sectionid;



        $subQuery          = "SELECT encrypt_id, class_name FROM sms_classes
									 	WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
										AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
										AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
										AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";
        $data['CLASSDATA'] = $this->common_model->get_data_by_query('multiple', $subQuery);
        include APPPATH . 'third_party/PHPExcel/XLSReader.php';
        include APPPATH . 'third_party/PHPExcel/XLSXReader.php';

        if ($this->input->post('SaveStudentExcelUpload')): 

           $errorFlag = '0'; 
            $pathinfo = pathinfo($_FILES['studentFile']['name']);

            $uploadedFileName = "'" . $pathinfo['filename'] . "'";
            $file_extension = isset($pathinfo['extension']) ? $pathinfo['extension'] : ''; // die;
            $file_extension = strtolower($file_extension);

            if ($file_extension == 'csv'):

                $dir = $config['root_path'] . 'assets/bulkupload/student/S_' . manojDecript($this->session->userdata('SMS_ADMIN_SCHOOL_ID')) . '/B_' . manojDecript($this->session->userdata('SMS_ADMIN_BRANCH_ID')) . '/';
           
                if (!file_exists($dir))
                    mkdir($dir, 0777, true);
                $filename = basename(time() . '_' . $this->session->userdata('SMS_ADMIN_ID') . '_student_' . str_replace(" ", "-", $_FILES['studentFile']['name']));
               
                $tmpname  = $_FILES['studentFile']['tmp_name'];
                move_uploaded_file("$tmpname", $dir . $filename);
                if ($file_extension == 'csv') {  
                    $row    = 0;
                    if ($handle = fopen($dir . $filename, "r")):
                        $ErrorFlagJit=0;
                        while (($csvData = fgetcsv($handle, 10000)) !== FALSE):
                            $num         = count($csvData);
                          
                            $data['num'] = $num;
                            $row++;
                            if ($row > 1):
                                $branddataarray = str_replace("'", "`", $csvData);
                               $data1    = array();
                               //echo "<pre>";  print_r($branddataarray); die;
                                $SBParam  = array();
                                $SCSParam = array();
                                if ($branddataarray[0] and $branddataarray[3] and $branddataarray[4]
                                        and $branddataarray[8] and $branddataarray[9] and $branddataarray[10]
                                        and $branddataarray[11] and $branddataarray[14]):
                                    if (strlen(trim($branddataarray[0])) >= 2):
                                        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $branddataarray[3])):
                                                   
                                        if ($branddataarray[10]):
                                                if (($branddataarray[4])<>""):
                                                    if (($branddataarray[8])<>""):
                                                        if (($branddataarray[9])<>""):
                                                            if (($branddataarray[11])<>""):
                                                                if (($branddataarray[14])<>""):
                                                                    $error = 'No';
                                                                else:
                                                                    $error = 'yes';
                                                                  $data['error_csv_' . $row] = 'Row:' . $row .lang('fathermobileno');
                                                                endif;
                                                            else:
                                                                $error = 'yes';
                                                              $data['error_csv_' . $row] = 'Row:' . $row .lang('fathernameError');
                                                            endif;
                                                        else:
                                                            $error = 'yes';
                                                          $data['error_csv_' . $row] = 'Row:' . $row .lang('registrationerror');
                                                        endif;
                                                    else:
                                                        $error = 'yes';
                                                      $data['error_csv_' . $row] = 'Row:' . $row .lang('rollno');
                                                    endif;
                                                else:
                                                    $error = 'yes';
                                                  $data['error_csv_' . $row] = 'Row:' . $row .lang('gendererror');
                                                endif;
                                            else:
                                                $error = 'yes';
                                              $data['error_csv_' . $row] = 'Row:' . $row .lang('csvAdmissionDateError');
                                            endif;
                                        else:
                                            $error                   = 'yes';
                                            $data['error_csv_' . $row] = 'Row:' . $row .lang('csvDobError');
                                        endif;
                                    else:
                                        $error = 'yes';
                                      $data['error_csv_' . $row] = 'Row:' . $row .lang('csvFirstNameError');
                                    endif;
                                else:
                                     $error                   = 'yes';
                                     $data['error_csv_' . $row] = 'Row:' . $row . lang('csvMendatoryError');
                                 endif;

                                 $RegistrationNO = $branddataarray[9];
                                 $UNIQUEREGISTRATIONNO	   = 	$this->common_model->getregistrationnoexistornot($RegistrationNO); 
                                // echo "<pre>"; print_r($UNIQUEREGISTRATIONNO); die;
                                    if($UNIQUEREGISTRATIONNO != ""):  //echo "AAA"; die;  
                                        $ErrorFlagJit = 1;
                                        $ErrMsg = "Row No ".$row." registration number is already exist!";                                    
                                        $this->session->set_flashdata('alert_error',$ErrMsg);
                                    else:
                                        if ($error == 'No'):
                                            //////////////	STUDENT SECTION 	///////////////////////                      
                                            $data1['student_f_name'] = addslashes(trim($branddataarray[0]));
                                            $data1['student_m_name'] = addslashes(trim($branddataarray[1]));
                                            $data1['student_l_name'] = addslashes(trim($branddataarray[2]));
                                            $data1['student_dob']    = YYMMDDtoDDMMYY(trim($branddataarray[3]));
                                            $data1['student_gender'] = trim($branddataarray[4]);
                                            $data1['student_religion']     = trim($branddataarray[5]);
                                            $data1['student_category']     = trim($branddataarray[6]);
                                            $data1['student_visible_mark'] = trim($branddataarray[7]);
                                            $data1['creation_date'] = currentDateTime();
                                            $data1['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                                            $data1['status']        = 'A';
                                            $data1['session_year']  = CURRENT_SESSION;
                                            $stulastInsertId = $this->common_model->add_data('students', $data1);
                                            $SPUparam['encrypt_id']         = manojEncript($stulastInsertId);
                                            $uniqueID = trim($branddataarray[9]);
                                            $SPUparam['student_qunique_id'] = studentUniqueId($data1['student_dob'], $data1['student_f_name']);
                                            $SPUwhere['student_id']         = $stulastInsertId;
                                            $this->common_model->edit_data_by_multiple_cond('students', $SPUparam, $SPUwhere);
                                            $studentUniqueId                = $SPUparam['student_qunique_id'];
                                            if ($studentUniqueId):
                                                //////////////	STUDENT SCHOOL BRANCH RELATION 	///////////////////////
                                            
                                                $SBParam['student_admission_date']  = YYMMDDtoDDMMYY(trim($branddataarray[10]));
                                                $SBParam['franchise_id']       = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                                                $SBParam['school_id']          = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                                                $SBParam['branch_id']          = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                                                $SBParam['board_id']           = $this->session->userdata('SMS_ADMIN_BOARD_ID');
                                                $SBParam['student_registration_no'] = trim($branddataarray[9]); 
                                                $SBParam['student_qunique_id'] = $studentUniqueId;
                                                $SBParam['creation_date']      = currentDateTime();
                                                $SBParam['created_by']         = $this->session->userdata('SMS_ADMIN_ID');
                                                $SBParam['session_year']       = CURRENT_SESSION;
                                                $SBParam['status']             = 'Y';
                                                //echo "<pre>"; print_r($SBParam); die;
                                                $stubrLastInsertId             = $this->common_model->add_data('student_branch', $SBParam);
                                                $SBUparam['encrypt_id']        = manojEncript($stubrLastInsertId);
                                                $SBUwhere['student_branch_id'] = $stubrLastInsertId;
                                                $this->common_model->edit_data_by_multiple_cond('student_branch', $SBUparam, $SBUwhere);

                                                //////////////	STUDENT CLASS SECTION RELATION 	///////////////////////
                                                $SCSParam['class_id']        = $classid;
                                                $SCSParam['section_id']      = $sectionid;
                                                $SCSParam['student_roll_no'] = trim($branddataarray[8]);

                                                $SCSParam['franchise_id']       = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                                                $SCSParam['school_id']          = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                                                $SCSParam['branch_id']          = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                                                $SCSParam['board_id']           = $this->session->userdata('SMS_ADMIN_BOARD_ID');
                                                $SCSParam['student_qunique_id'] = $studentUniqueId;
                                                $SCSParam['current_year']       = currentDateTime();
                                                $SCSParam['creation_date']      = currentDateTime();
                                                $SCSParam['created_by']         = $this->session->userdata('SMS_ADMIN_ID');
                                                $SCSParam['status']             = 'Y';
                                                $SCSParam['session_year']       = CURRENT_SESSION;
                                                $stuclsLastInsertId             = $this->common_model->add_data('student_class', $SCSParam);

                                                $SCSUparam['encrypt_id']       = manojEncript($stuclsLastInsertId);
                                                $SCSUwhere['student_class_id'] = $stuclsLastInsertId;
                                                $this->common_model->edit_data_by_multiple_cond('student_class', $SCSUparam, $SCSUwhere);
                                                    
                                                //add parent
                                                $params['user_f_name'] = trim($branddataarray[11]);
                                                $params['user_m_name'] = trim($branddataarray[12]);
                                                $params['user_l_name'] = trim($branddataarray[13]);
                                                $params['user_mobile'] = trim($branddataarray[14]);
                                                $params['user_phone']  = trim($branddataarray[15]);
                                                $params['user_email']  = trim($branddataarray[16]);
                                                //vaildation on email id
                                                $subQuery = "SELECT * FROM `sms_users` WHERE `user_email` = '" . $params['user_email'] . "' AND `user_type` = 'Parent' ";

                                                $parentemail = $this->common_model->get_data_by_query('single', $subQuery);
                                                if ($parentemail):
                                                    $Psmuparams['student_qunique_id'] = $studentUniqueId;
                                                    $Psmuparams['parent_type']        = 'Father';
                                                    $Psmuparams['parent_id']          = $parentemail['encrypt_id'];
                                                    $Psmuparams['creation_date']      = currentDateTime();
                                                    $Psmuparams['created_by']         = $this->session->userdata('SMS_ADMIN_ID');
                                                    $Psmuparams['status']             = 'Y';
                                                    $Psmuparams['session_year']       = CURRENT_SESSION;
                                                    $smuplastInsertId                 = $this->common_model->add_data('student_parent', $Psmuparams);
                                                    $PSmuparam['encrypt_id']          = manojEncript($smuplastInsertId);
                                                    $PSwhere['student_parent_id']     = $smuplastInsertId;
                                                    $PSmuparam['encrypt_id']          = manojEncript($smuplastInsertId);
                                                    $PSmuwhere['student_parent_id']   = $smuplastInsertId;

                                                    $this->common_model->edit_data_by_multiple_cond('student_parent', $PSmuparam, $PSmuwhere);
                                                    //add sibling
                                                    $siblingbranchsubQuery = "SELECT * FROM `sms_users` WHERE `user_email` = '" . $params['user_email'] . "' AND `user_type` = 'Parent' AND franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
                                                AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
                                                AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'";
                                                    $siblingbranch         = $this->common_model->get_data_by_query('single', $siblingbranchsubQuery);
                                                    if ($siblingbranch):
                                                        $sliblingRcheckQuery = "SELECT * FROM  sms_student_sibling  AS sb   WHERE  sb.sibling_id='" . $studentUniqueId . "' 
                                                AND sb.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
                                                AND sb.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
                                                AND sb.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
                                                
                                                AND sb.status = 'Y'
                                                ";
                                                    $sliblingRowcheck    = $this->common_model->get_data_by_query('single', $sliblingRcheckQuery);
                                                    if (!$sliblingRowcheck) :
                                                            $sliblingQuery = "SELECT sb.student_qunique_id FROM `sms_student_parent` AS sp  
                                                            LEFT JOIN `sms_student_branch` AS sb ON sb.student_qunique_id = sp.student_qunique_id 
                                                            LEFT JOIN `sms_students` AS s ON s.student_qunique_id = sp.student_qunique_id 
                                                            WHERE sp.parent_id = '" . $parentemail['encrypt_id'] . "'
                                                            AND sb.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
                                                            AND sb.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
                                                            AND sb.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
                                                            AND sb.status = 'Y'
                                                            AND sp.status = 'Y'
                                                            AND s.status = 'A'
                                                            ORDER BY sb.student_admission_date , sb.creation_date ASC  LIMIT 0,1 ";
                                                            $sliblingData  = $this->common_model->get_data_by_query('single', $sliblingQuery);
                                                            if ($sliblingData):
                                                                $sbParam['first_admission_student_id'] = $sliblingData['student_qunique_id'];
                                                                $sbParam['franchise_id']               = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                                                                $sbParam['school_id']                  = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                                                                $sbParam['branch_id']                  = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                                                                $sbParam['sibling_id']                 = $studentUniqueId;
                                                                $sbParam['creation_date']              = currentDateTime();
                                                                $sbParam['created_by']                 = $this->session->userdata('SMS_ADMIN_ID');
                                                                $sbParam['session_year']               = CURRENT_SESSION;
                                                                $sblastInsertId                        = $this->common_model->add_data('student_sibling', $sbParam);
                                                                $sbeParam['encrypt_id']                = manojEncript($sblastInsertId);
                                                                $sbwhere['student_sibling_id']         = $sblastInsertId;
                                                                $this->common_model->edit_data_by_multiple_cond('student_sibling', $sbeParam, $sbwhere);
                                                            endif;
                                                        endif;
                                                    endif;
                                                else:
                                                    $dparams['user_pan_card']    = trim($branddataarray[17]);
                                                    $dparams['user_adhaar_card'] = trim($branddataarray[18]);
                                                    $dparams['user_income']      = trim($branddataarray[19]);
                                                    $dparams['user_occupation']  = trim($branddataarray[20]);
                                                    $dparams['user_education']   = trim($branddataarray[21]);
                                                    $dparams['user_age']         = trim($branddataarray[22]);
                                                    $params['parent_type']   = 'Father';
                                                    $params['user_password'] = $this->admin_model->encript_password('123456');

                                                    $params['franchise_id']  = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                                                    $params['school_id']     = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                                                    $params['branch_id']     = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                                                    $params['user_type']     = 'Parent';
                                                    $params['creation_date'] = currentDateTime();
                                                    $params['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                                                    $params['status']        = 'A';
                                                    $params['session_year']  = CURRENT_SESSION;
                                                    $ulastInsertId = $this->common_model->add_data('users', $params);
                                                    $Uparam['encrypt_id'] = manojEncript($ulastInsertId);
                                                    $Uwhere['user_id']    = $ulastInsertId;
                                                    $this->common_model->edit_data_by_multiple_cond('users', $Uparam, $Uwhere);

                                                    $ParentId = $Uparam['encrypt_id'];
                                                    //add to user detail table////
                                                    $dparams['user_id']       = $ParentId;
                                                    $dparams['creation_date'] = currentDateTime();
                                                    $dparams['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                                                    $dparams['status']        = 'Y';
                                                    $dparams['session_year']  = CURRENT_SESSION;
                                                    $uDlastInsertId = $this->common_model->add_data('user_detils', $dparams);
                                                    $UDparam['encrypt_id']          = manojEncript($uDlastInsertId);
                                                    $UDwhere['user_detail_id']      = $uDlastInsertId;
                                                    $this->common_model->edit_data_by_multiple_cond('user_detils', $UDparam, $UDwhere);
                                                // add to student parent table  /////
                                                    $Psparams['parent_type']        = 'Father';
                                                    $Psparams['student_qunique_id'] = $studentUniqueId;
                                                    $Psparams['parent_id']          = $ParentId;
                                                    $Psparams['creation_date']      = currentDateTime();
                                                    $Psparams['created_by']         = $this->session->userdata('SMS_ADMIN_ID');
                                                    $Psparams['status']             = 'Y';
                                                    $Psparams['session_year']       = CURRENT_SESSION;
                                                    $splastInsertId                 = $this->common_model->add_data('student_parent', $Psparams);
                                                    $PSparam['encrypt_id']          = manojEncript($splastInsertId);
                                                    $PSwhere['student_parent_id']   = $splastInsertId;
                                                    $this->common_model->edit_data_by_multiple_cond('student_parent', $PSparam, $PSwhere);
                                                endif;
                                                //add Mother ditails
                                                $mparams['user_f_name']       = trim($branddataarray[23]);
                                                $mparams['user_m_name']       = trim($branddataarray[24]);
                                                $mparams['user_l_name']       = trim($branddataarray[25]);
                                                $mparams['user_mobile']       = trim($branddataarray[26]);
                                                $mparams['user_phone']        = trim($branddataarray[27]);
                                                $mparams['user_email']        = trim($branddataarray[28]);
                                                $mparams['parent_type']       = 'Mother';
                                                $dpmarams['user_pan_card']    = trim($branddataarray[29]);
                                                $dpmarams['user_adhaar_card'] = trim($branddataarray[30]);
                                                $dpmarams['user_income']      = trim($branddataarray[31]);
                                                $dpmarams['user_occupation']  = trim($branddataarray[32]);
                                                $dpmarams['user_education']   = trim($branddataarray[33]);
                                                $dpmarams['user_age']         = trim($branddataarray[34]);
                                                $mparams['user_password'] = $this->admin_model->encript_password('123456');
                                                ;
                                                $subQuery                 = "SELECT * FROM `sms_users` WHERE `user_email` = '" . $mparams['user_email'] . "' AND `user_type` = 'Parent' ";
                                                $parentemail = $this->common_model->get_data_by_query('single', $subQuery);
                                                if ($parentemail):
                                                    $Psmuparams['student_qunique_id'] = $studentUniqueId;
                                                    $Psmuparams['parent_type']        = 'Mother';
                                                    $Psmuparams['parent_id']          = $parentemail['encrypt_id'];
                                                    $Psmuparams['creation_date']      = currentDateTime();
                                                    $Psmuparams['created_by']         = $this->session->userdata('SMS_ADMIN_ID');
                                                    $Psmuparams['status']             = 'Y';
                                                    $Psmuparams['session_year']       = CURRENT_SESSION;
                                                    $smuplastInsertId                 = $this->common_model->add_data('student_parent', $Psmuparams);
                                                    $PSmuparam['encrypt_id']          = manojEncript($smuplastInsertId);
                                                    $mPSwhere['student_parent_id']    = $smuplastInsertId;
                                                    $this->common_model->edit_data_by_multiple_cond('student_parent', $PSmuparam, $mPSwhere);
                                                    //add sibling
                                                    $msiblingbranchsubQuery = "SELECT * FROM `sms_users` WHERE `user_email` = '" . $mparams['user_email'] . "' AND `user_type` = 'Parent' AND franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
                                                    AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
                                                    AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'";
                                                    $msiblingbranch         = $this->common_model->get_data_by_query('single', $msiblingbranchsubQuery);
                                                    if ($msiblingbranch):
                                                        $msliblingRcheckQuery = "SELECT * FROM  sms_student_sibling  AS sb   WHERE  sb.sibling_id='" . $studentUniqueId . "' 
                                                        AND sb.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
                                                        AND sb.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
                                                        AND sb.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
                                                                                                AND sb.status = 'Y'
                                                                                            ";
                                                        $msliblingRowcheck    = $this->common_model->get_data_by_query('single', $msliblingRcheckQuery);

                                                        if (!$msliblingRowcheck) :
                                                            $sliblingQuery = "SELECT sb.student_qunique_id FROM `sms_student_parent` AS sp  
                                                                    LEFT JOIN `sms_student_branch` AS sb ON sb.student_qunique_id = sp.student_qunique_id 
                                                                    LEFT JOIN `sms_students` AS s ON s.student_qunique_id = sp.student_qunique_id 
                                                                    WHERE sp.parent_id = '" . $parentemail['encrypt_id'] . "'
                                                                    AND sb.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
                                                                    AND sb.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
                                                                    AND sb.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
                                                                    AND sb.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
                                                                    AND sb.status = 'Y'
                                                                    AND sp.status = 'Y'
                                                                    AND s.status = 'A'
                                                                    ORDER BY sb.student_admission_date , sb.creation_date ASC  LIMIT 0,1 ";
                                                            $sliblingData  = $this->common_model->get_data_by_query('single', $sliblingQuery);
                                                            if ($sliblingData):
                                                                $msbParam['first_admission_student_id'] = $sliblingData['student_qunique_id'];
                                                                $msbParam['franchise_id']               = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                                                                $msbParam['school_id']                  = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                                                                $msbParam['branch_id']                  = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                                                                $msbParam['sibling_id']                 = $studentUniqueId;
                                                                $msbParam['creation_date']              = currentDateTime();
                                                                $msbParam['created_by']                 = $this->session->userdata('SMS_ADMIN_ID');
                                                                $msbParam['session_year']               = CURRENT_SESSION;
                                                                $msblastInsertId                        = $this->common_model->add_data('student_sibling', $msbParam);
                                                                $msbeParam['encrypt_id']                = manojEncript($msblastInsertId);
                                                                $msbwhere['student_sibling_id']         = $msblastInsertId;
                                                                $this->common_model->edit_data_by_multiple_cond('student_sibling', $msbeParam, $msbwhere);
                                                            endif;
                                                        endif;
                                                    endif;

                                                else:
                                                    // add to user table /////
                                                    $mparams['franchise_id']  = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                                                    $mparams['school_id']     = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                                                    $mparams['branch_id']     = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                                                    $mparams['user_type']     = 'Parent';
                                                    $mparams['creation_date'] = currentDateTime();
                                                    $mparams['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                                                    $mparams['status']        = 'A';
                                                    $mparams['session_year']  = CURRENT_SESSION;
                                                    $ulastInsertmId           = $this->common_model->add_data('users', $mparams);

                                                    $Umparam['encrypt_id'] = manojEncript($ulastInsertmId);
                                                    $Umwhere['user_id']    = $ulastInsertmId;
                                                    $this->common_model->edit_data_by_multiple_cond('users', $Umparam, $Umwhere);

                                                    $ParentmId = $Umparam['encrypt_id'];

                                                    //add to user detail table////

                                                    $dpmarams['user_id']       = $ParentmId;
                                                    $dpmarams['creation_date'] = currentDateTime();
                                                    $dpmarams['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                                                    $dpmarams['status']        = 'Y';

                                                    $dpmarams['session_year'] = CURRENT_SESSION;
                                                    $uDlastmInsertId          = $this->common_model->add_data('user_detils', $dpmarams);

                                                    $UDmparam['encrypt_id']          = manojEncript($uDlastmInsertId);
                                                    $UDmwhere['user_detail_id']      = $uDlastmInsertId;
                                                    $this->common_model->edit_data_by_multiple_cond('user_detils', $UDmparam, $UDmwhere);
                                                    // add to student parent table  /////
                                                    $mPsparams['parent_type']        = 'Mother';
                                                    $mPsparams['student_qunique_id'] = $studentUniqueId;
                                                    $mPsparams['parent_id']          = $ParentmId;
                                                    $mPsparams['creation_date']      = currentDateTime();
                                                    $mPsparams['created_by']         = $this->session->userdata('SMS_ADMIN_ID');
                                                    $mPsparams['status']             = 'Y';
                                                    $mPsparams['session_year']       = CURRENT_SESSION;
                                                    $msplastInsertId                 = $this->common_model->add_data('student_parent', $mPsparams);
                                                    $mPSparam['encrypt_id']          = manojEncript($msplastInsertId);
                                                    $mPSwhere['student_parent_id']   = $msplastInsertId;
                                                    $this->common_model->edit_data_by_multiple_cond('student_parent', $mPSparam, $mPSwhere);
                                                endif;
                                                 //Add Student Address
                                                    
                                                    //////////////	STUDENT CLASS SECTION RELATION 	///////////////////////
                                                    $SADDParam['student_c_state']    = trim($branddataarray[35]);
                                                    $SADDParam['student_c_city']     = trim($branddataarray[36]);
                                                    $SADDParam['student_c_locality'] = trim($branddataarray[37]);
                                                    $SADDParam['student_c_address']  = trim($branddataarray[38]);
                                                    $SADDParam['student_c_zipcode']  = trim($branddataarray[39]);
                                                    $SADDParam['same_address']       = trim($branddataarray[40]);
                                                    $SADDParam['student_p_state']    = trim($branddataarray[41]);
                                                    $SADDParam['student_p_city']     = trim($branddataarray[42]);
                                                    $SADDParam['student_p_locality'] = trim($branddataarray[43]);
                                                    $SADDParam['student_p_address']  = trim($branddataarray[44]);
                                                    $SADDParam['student_p_zipcode']  = trim($branddataarray[45]);
                                                    $SADDParam['o_address_type']     = trim($branddataarray[46]);
                                                    $SADDParam['student_o_state']    = trim($branddataarray[47]);
                                                    $SADDParam['student_o_city']     = trim($branddataarray[48]);
                                                    $SADDParam['student_o_locality'] = trim($branddataarray[49]);
                                                    $SADDParam['student_o_address']  = trim($branddataarray[50]);
                                                    $SADDParam['student_o_zipcode']  = trim($branddataarray[51]);

                                                $SADDParam['franchise_id']       = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                                                $SADDParam['school_id']          = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                                                $SADDParam['branch_id']          = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                                                $SADDParam['board_id']           = $this->session->userdata('SMS_ADMIN_BOARD_ID');
                                                $SADDParam['student_qunique_id'] = $studentUniqueId;
                                                $SADDParam['creation_date']      = currentDateTime();
                                                $SADDParam['created_by']         = $this->session->userdata('SMS_ADMIN_ID');
                                                $SADDParam['status']             = 'Y';
                                                $SADDParam['session_year']       = CURRENT_SESSION;
                                            //echo "<pre>"; print_r($SADDParam); die;
                                                $stuaddressLastInsertId             = $this->common_model->add_data('student_address', $SADDParam);
                                                $SADDParam['encrypt_id']       = manojEncript($stuaddressLastInsertId);
                                                $SCSTwhere['student_address_id'] = $stuaddressLastInsertId;
                                                $this->common_model->edit_data_by_multiple_cond('student_address', $SADDParam, $SCSTwhere);
                                            endif;
                                        else:
                                            $errorFlag = '1'; 
                                        endif;
                        endif;
                    endif;
                        endwhile;
                        if ($errorFlag == '1'):
                              $data['csv_error'] = '1';
                      
                            $this->session->set_flashdata('alert_warning', lang('uploaderror'));
                        else:
                               $data['csv_error'] = '0';
                            $this->session->set_flashdata('alert_success', lang('addsuccess'));
                         
                        endif;
                endif;
                }
              
            endif;
        endif;
        // end Upload image
 

        if ($this->input->post('uploadimage')):
            $data['error_image'] = array();
       
            $count               = count($_FILES['uploadfile']['name']);

            $idQuery = "SELECT student_qunique_id FROM `sms_student_class` WHERE  franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
										AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
										AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'AND section_id = '" . $this->input->post('section_id') . "'AND class_id = '" . $this->input->post('class_id') . "' ";
            $idData  = $this->common_model->get_data_by_query('multiple', $idQuery);
           
            for ($i = 0; $i < $count; $i++) :
                $file_name = $_FILES['uploadfile']['name'][$i];

                $withoutExtName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file_name);
                //check for correct image name
if($idData):
    foreach ($idData as $ID):             
if($ID['student_qunique_id'] == $withoutExtName ):
    $studentExist = '1';
    break;
endif;
   endforeach;          
endif;
                $file_size    = $_FILES['uploadfile']['size'][$i];
                $sizeError    = 'No';
                //image size should be less then 50kb
               if ($file_size > '50000'):
                    $sizeError = 'Yes';
                endif;

                if ($file_name and $studentExist and $sizeError == 'No'):
                    $tmp_name = $_FILES['uploadfile']['tmp_name'][$i];

                    $imagefolder = 'S_' . manojDecript($this->session->userdata('SMS_ADMIN_SCHOOL_ID')) . '/B_' . manojDecript($this->session->userdata('SMS_ADMIN_BRANCH_ID'));
                    $ext         = pathinfo($file_name);
                    $newfilename = $withoutExtName . '.' . $ext['extension'];
                    $this->load->library("upload_crop_img");
                    //add student image to studentimage folder
                    $return_file_name               = $this->upload_crop_img->_upload_image($file_name, $tmp_name, 'studentImage', $newfilename, $imagefolder);
                    $SParam['student_image']        = $return_file_name;
                    $SParam['update_date']          = currentDateTime();
                    $SParam['updated_by']           = $this->session->userdata('SMS_ADMIN_ID');
                    $SPUwhere['student_qunique_id'] = $withoutExtName;
                    $this->common_model->edit_data_by_multiple_cond('students', $SParam, $SPUwhere);
                    $this->session->set_flashdata('alert_success', lang('addsuccess'));
                else:
                    $this->session->set_flashdata('alert_warning', lang('uploaderror'));
                    if (!$studentExist):
                        array_push($data['error_image'], 'Unable to Upload image  ' . $file_name . ' . ' . lang('uniqueiderror'));
                    elseif ($sizeError == 'Yes'):
                        array_push($data['error_image'], 'Unable to Upload image  ' . $file_name . ' . ' . lang('imagesizeerror'));

                    endif;
                endif;
            endfor;
        endif;
        $this->layouts->set_title('Bulk Upload');
        $this->layouts->admin_view('studentbulkupload/index', array(), $data);
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
