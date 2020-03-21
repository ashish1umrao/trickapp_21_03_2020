<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Studentlist extends CI_Controller {

    public function __construct() {
        parent:: __construct();
        $this->load->helper(array('form', 'url', 'html', 'path', 'form', 'cookie'));
        $this->load->library(array('email', 'session', 'form_validation', 'pagination', 'parser', 'encrypt'));
        error_reporting(E_ALL ^ E_NOTICE);
        $this->load->library("layouts");
         $this->load->library('excel');
        $this->load->model(array('admin_model', 'common_model', 'emailtemplate_model', 'sms_model'));
        $this->load->helper('language');
        $this->lang->load('statictext', 'admin');
        $this->load->library('ciqrcode');
    }

    /* * *********************************************************************
     * * Function name : Teacherlist
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for Teacherlist
     * * Date : 01 FEBRUARY 2018
     * * ********************************************************************* */
    public function index() {
        $this->admin_model->authCheck('admin', 'view_data');
        $this->admin_model->get_permission_type($data);
        $data['error']     = '';
        $data['classid']   = $this->input->get('class_id');
        $data['sectionid'] = $this->input->get('section_id');

        $subQuery          = "SELECT encrypt_id, class_name FROM sms_classes
						 	WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
							AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
							AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
							AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";
        $data['CLASSDATA'] = $this->common_model->get_data_by_query('multiple', $subQuery);
        if ($this->input->get('searchValue')):
            $sValue              = $this->input->get('searchValue');
            $whereCon['like']    = "( stu.student_qunique_id LIKE '%" . $sValue . "%' OR  stubr.student_registration_no LIKE '%" . $sValue . "%' 
                   					  OR stubr.student_admission_date LIKE '%" . $sValue . "%' 
									  OR stubr.student_relieving_date LIKE '%" . $sValue . "%' 
									  OR stu.student_f_name LIKE '%" . $sValue . "%' 
									  OR stu.student_m_name LIKE '%" . $sValue . "%' 
									  OR stu.student_l_name LIKE '%" . $sValue . "%' 
									  OR stu.student_dob LIKE '%" . $sValue . "%' 
									  OR stu.student_gender LIKE '%" . $sValue . "%' 
									  OR stu.student_gender_short LIKE '%" . $sValue . "%' 
									  OR stu.student_religion LIKE '%" . $sValue . "%' 
									  OR stu.student_category LIKE '%" . $sValue . "%' 
									  OR stu.student_visible_mark LIKE '%" . $sValue . "%' 
									  OR cls.class_name LIKE '%" . $sValue . "%' 
									  OR clsec.class_section_name LIKE '%" . $sValue . "%' 
									  OR stucls.student_roll_no LIKE '%" . $sValue . "%' 
									  OR stuheal.student_blood_group LIKE '%" . $sValue . "%' 
									  OR stuheal.student_height LIKE '%" . $sValue . "%' 
									  OR stuheal.student_weight LIKE '%" . $sValue . "%'
									  OR stuheal.student_allergy_from LIKE '%" . $sValue . "%' 
									  OR stuheal.student_l_medical_checkup LIKE '%" . $sValue . "%' 
									  OR stuheal.student_special_notes LIKE '%" . $sValue . "%')";
            $data['searchValue'] = $sValue;
        else:
            $whereCon['like']    = "";
            $data['searchValue'] = '';
        endif;
        if ($data['classid'] and $data['sectionid']):
            $whereCon['where'] = "  stucls.class_id = '" . $data['classid'] . "' AND stucls.section_id = '" . $data['sectionid'] . "' AND stubr.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
								 AND stubr.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
								 AND stubr.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
								 AND stubr.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";
        elseif ($data['classid']):
            $whereCon['where'] = "  stucls.class_id = '" . $data['classid'] . "'  AND stubr.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
								 AND stubr.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
								 AND stubr.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
								 AND stubr.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";

        else:
            $whereCon['where'] = "   stubr.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
								 AND stubr.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
								 AND stubr.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
								 AND stubr.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";

        endif;
        $shortField = 'stubr.student_qunique_id';

        $this->load->library('pagination');
        $config['base_url']   = $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index';
        $this->session->set_userdata('studentListAdminData', currentFullUrl());
        $qStringdata          = explode('?', currentFullUrl());
        $config['suffix']     = $qStringdata[1] ? '?' . $qStringdata[1] : '';
        $tblName              = 'student_branch as stubr';
        $con                  = '';
        $config['total_rows'] = $this->admin_model->SelectStudentListData('count', $tblName, $whereCon, $shortField, '0', '0');

        if ($this->input->get('showLength') == 'All'):
            $config['per_page'] = $config['total_rows'];
            $data['perpage']    = $this->input->get('showLength');
        elseif ($this->input->get('showLength')):
            $config['per_page'] = $this->input->get('showLength');
            $data['perpage']    = $this->input->get('showLength');
        else:
            $config['per_page'] = SHOW_NO_OF_DATA;
            $data['perpage']    = SHOW_NO_OF_DATA;
        endif;

        $config['uri_segment'] = getUrlSegment();
        $this->pagination->initialize($config);

        if ($this->uri->segment(getUrlSegment())):
            $page = $this->uri->segment(getUrlSegment());
        else:
            $page = 0;
        endif;

        $data['forAction'] = $config['base_url'];
        if ($config['total_rows']):
            $first               = ($page) + 1;
            $data['first']       = $first;
            $last                = (($page) + $data['perpage']) > $config['total_rows'] ? $config['total_rows'] : (($page) + $data['perpage']);
            $data['noOfContent'] = 'Showing ' . $first . '-' . $last . ' of ' . $config['total_rows'] . ' items';
        else:
            $data['first']       = 1;
            $data['noOfContent'] = '';
        endif;

        $data['ALLDATA'] = $this->admin_model->SelectStudentListData('data', $tblName, $whereCon, $shortField, $config['per_page'], $page);
       //echo "<pre>";  print_r($data['ALLDATA']); die;
        //download  reprt report 
        if ($this->input->post('report')):
            $AllData = $this->admin_model->SelectStudentExcelreportData('data', $tblName, $whereCon, $shortField);
            $tmpReportArry = array();
            $reportData    = array();
            if($AllData):
                foreach ($AllData as $AD):
                    $tmpReportArry                            = '';
                    $tmpReportArry['name']                    = $AD['student_f_name'] . ' ' . $AD['student_m_name'] . ' ' . $AD['student_l_name'];
                    $tmpReportArry['student_registration_no'] = $AD['student_registration_no'];
                    $tmpReportArry['student_qunique_id']      = $AD['student_qunique_id'];
                    $tmpReportArry['student_gender']          = $AD['student_gender'];
                    $tmpReportArry['student_religion']        = $AD['student_religion'];
                    $tmpReportArry['student_category']        = $AD['student_category'];
                    $tmpReportArry['class_name']              = $AD['class_name'];
                    $tmpReportArry['class_section_name']      = $AD['class_section_name'];
                    $tmpReportArry['status']                  = $AD['status'];
                    array_push($reportData, $tmpReportArry);
                endforeach;
            endif;
            //excel download start
            $this->excel->setActiveSheetIndex(0);
            $this->excel->getActiveSheet()->setTitle('Students');

            $this->excel->getActiveSheet()->getStyle('A1:I1')->getFill()->applyFromArray(array(
                'type'       => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array(
                    'rgb' => 'faca33'
                )
            ));
            $this->excel->getActiveSheet()->getStyle('A1:I1')->applyFromArray(array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            ));
            $this->excel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setTextRotation(90);
            $this->excel->getActiveSheet()->setCellValue('A1', ' Name');
            $this->excel->getActiveSheet()->setCellValue('B1', 'Registration No');
            $this->excel->getActiveSheet()->setCellValue('C1', 'Unique Id');
            $this->excel->getActiveSheet()->setCellValue('D1', 'Gender');
            $this->excel->getActiveSheet()->setCellValue('E1', 'Religion');
            $this->excel->getActiveSheet()->setCellValue('F1', 'Category');
            $this->excel->getActiveSheet()->setCellValue('G1', 'Class');
            $this->excel->getActiveSheet()->setCellValue('H1', 'Section');
            $this->excel->getActiveSheet()->setCellValue('I1', 'Status');
            for ($col = ord('A'); $col <= ord('I'); $col++) {
                $this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
                $this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }
            $exceldata = array();
            if ($reportData <> ""):
                foreach ($reportData as $row) {
                    $exceldata[] = $row;
                }
            endif;
            //Fill data 
            $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A2');
            $filename = 'Student_Report.xls';
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            $objWriter->save('php://output');
            //excel download end 
        endif;

        $this->layouts->set_title('Manage student details');
        $this->layouts->admin_view('studentlist/index', array(), $data);
    } // END OF FUNCTION

    /* * *********************************************************************
     * * Function name : addeditdata
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for add edit data
     * * Date : 01 FEBRUARY 2018
     * * ********************************************************************* */
    public function addeditdata($editid = '') {
        $data['error']          = '';
        $data['studentId']      = $editid;
        $data['studentDetails'] = $this->admin_model->StudentDetails($editid);
        $subQuery               = "SELECT encrypt_id, class_name FROM sms_classes
								 	WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
									AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
									AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
									AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";
        $data['CLASSDATA']      = $this->common_model->get_data_by_query('multiple', $subQuery);
        $genQuery               = "SELECT user_gender_name, user_gender_short_name FROM sms_user_gender WHERE status = 'Y'";
        $data['GENDERDATA']     = $this->common_model->get_data_by_query('multiple', $genQuery);
        $RERIQuery              = "SELECT user_religion_name FROM sms_user_religion WHERE status = 'Y'";
        $data['RERIGIONDATA']   = $this->common_model->get_data_by_query('multiple', $RERIQuery);
        $CATEQuery              = "SELECT user_category_name FROM sms_user_category WHERE status = 'Y'";
        $data['CATEGORYDATA']   = $this->common_model->get_data_by_query('multiple', $CATEQuery);

        if ($editid):
            $this->admin_model->authCheck('admin', 'edit_data');
            $stuQuery         = "SELECT * FROM sms_students WHERE student_qunique_id = '" . $editid . "'";
            $data['EDITDATA'] = $this->common_model->get_data_by_query('single', $stuQuery);

            $stubrQuery   = "SELECT student_registration_no,student_admission_date,student_relieving_date
			                             FROM sms_student_branch WHERE student_qunique_id = '" . $editid . "'";
            $stubrDetails = $this->common_model->get_data_by_query('single', $stubrQuery);
            if ($stubrDetails <> ""):
                $data['EDITDATA'] = array_merge($data['EDITDATA'], $stubrDetails);
            endif;

            $ustuclsQuery  = "SELECT class_id,section_id,student_roll_no 
			                             FROM sms_student_class WHERE student_qunique_id = '" . $editid . "'";
            $stuclsDetails = $this->common_model->get_data_by_query('single', $ustuclsQuery);
            if ($stuclsDetails <> ""):
                $data['EDITDATA'] = array_merge($data['EDITDATA'], $stuclsDetails);
            endif;
        else:
            $this->admin_model->authCheck('admin', 'add_data');
        endif;

        if ($this->input->post('SaveChanges')):
            $error = 'NO';
            $this->form_validation->set_rules('class_id', 'Class name', 'trim|required');
            $this->form_validation->set_rules('section_id', 'Section name', 'trim|required');
            $this->form_validation->set_rules('student_roll_no', 'Roll no', 'trim|required');
            $this->form_validation->set_rules('student_registration_no', 'Registration no', 'trim|required');
            $this->form_validation->set_rules('student_admission_date', 'Admission date', 'trim|required');
            $this->form_validation->set_rules('student_relieving_date', 'Relieving date', 'trim');
            $this->form_validation->set_rules('student_f_name', 'First name', 'trim|required|min_length[2]|max_length[50]');
            $this->form_validation->set_rules('student_m_name', 'Middle name', 'trim');
            $this->form_validation->set_rules('student_l_name', 'Last name', 'trim');
            $this->form_validation->set_rules('student_dob', 'Date of birth', 'trim|required');
            $this->form_validation->set_rules('student_gender', 'Gender', 'trim');
            $this->form_validation->set_rules('student_religion', 'Religion', 'trim');
            $this->form_validation->set_rules('student_category', 'Category', 'trim');
            $this->form_validation->set_rules('student_visible_mark', 'Visible mark', 'trim');
            $this->form_validation->set_rules('uploadimage0', 'Profile picture', 'trim');

            if ($this->form_validation->run() && $error == 'NO'):
                //echo '<pre>'; print_r($_POST); die;
                //////////////	STUDENT SCHOOL BRANCH RELATION 	///////////////////////
                $SBParam['student_registration_no'] = addslashes($this->input->post('student_registration_no'));
                $SBParam['student_admission_date']  = DDMMYYtoYYMMDD($this->input->post('student_admission_date'));
                $SBParam['student_relieving_date']  = DDMMYYtoYYMMDD($this->input->post('student_relieving_date'));

                //////////////	STUDENT CLASS SECTION RELATION 	///////////////////////
                $SCSParam['class_id']        = addslashes($this->input->post('class_id'));
                $SCSParam['section_id']      = addslashes($this->input->post('section_id'));
                $SCSParam['student_roll_no'] = addslashes($this->input->post('student_roll_no'));

                //////////////	STUDENT SECTION 	///////////////////////
                $SParam['student_f_name']       = addslashes($this->input->post('student_f_name'));
                $SParam['student_m_name']       = addslashes($this->input->post('student_m_name'));
                $SParam['student_l_name']       = addslashes($this->input->post('student_l_name'));
                $SParam['student_dob']          = DDMMYYtoYYMMDD($this->input->post('student_dob'));
                $student_gender                 = $this->input->post('student_gender') ? explode('___', $this->input->post('student_gender')) : array();
                $SParam['student_gender']       = $student_gender[0] ? $student_gender[0] : '';
                $SParam['student_gender_short'] = $student_gender[1] ? $student_gender[1] : '';
                $SParam['student_religion']     = addslashes($this->input->post('student_religion'));
                $SParam['student_category']     = addslashes($this->input->post('student_category'));
                $SParam['student_visible_mark'] = addslashes($this->input->post('student_visible_mark'));
                $SParam['student_image']        = addslashes($this->input->post('uploadimage0'));

                if ($this->input->post('CurrentDataID') == ''):
                    //////////////	STUDENT SECTION 	///////////////////////
                    $SParam['creation_date'] = currentDateTime();
                    $SParam['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                    $SParam['status']        = 'A';
                    $SParam['session_year']	 =	CURRENT_SESSION;
                    $stulastInsertId         = $this->common_model->add_data('students', $SParam);

                    $SPUparam['encrypt_id']         = manojEncript($stulastInsertId);
                    $SPUparam['student_qunique_id'] = studentUniqueId($SParam['student_dob'], $SParam['student_f_name']);
                    $SPUwhere['student_id']         = $stulastInsertId;
                    $this->common_model->edit_data_by_multiple_cond('students', $SPUparam, $SPUwhere);

                    $studentUniqueId = $SPUparam['student_qunique_id'];
                    if ($studentUniqueId):
                        //////////////	STUDENT SCHOOL BRANCH RELATION 	///////////////////////
                        $SBParam['franchise_id']       = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                        $SBParam['school_id']          = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                        $SBParam['branch_id']          = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                        $SBParam['board_id']           = $this->session->userdata('SMS_ADMIN_BOARD_ID');
                        $SBParam['student_qunique_id'] = $studentUniqueId;
                        $SBParam['creation_date']      = currentDateTime();
                        $SBParam['created_by']         = $this->session->userdata('SMS_ADMIN_ID');
                         $SBParam['session_year']			=	CURRENT_SESSION;
                        $SBParam['status']             = 'Y';
                        $stubrLastInsertId             = $this->common_model->add_data('student_branch', $SBParam);

                        $SBUparam['encrypt_id']        = manojEncript($stubrLastInsertId);
                        $SBUwhere['student_branch_id'] = $stubrLastInsertId;
                        $this->common_model->edit_data_by_multiple_cond('student_branch', $SBUparam, $SBUwhere);

                        //////////////	STUDENT CLASS SECTION RELATION 	///////////////////////
                        $SCSParam['franchise_id']       = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                        $SCSParam['school_id']          = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                        $SCSParam['branch_id']          = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                        $SCSParam['board_id']           = $this->session->userdata('SMS_ADMIN_BOARD_ID');
                        $SCSParam['student_qunique_id'] = $studentUniqueId;
                        $SCSParam['current_year']       = currentDateTime();
                        $SCSParam['creation_date']      = currentDateTime();
                        $SCSParam['created_by']         = $this->session->userdata('SMS_ADMIN_ID');
                        $SCSParam['status']             = 'Y';
                           $SCSParam['session_year']			=	CURRENT_SESSION;
                        $stuclsLastInsertId             = $this->common_model->add_data('student_class', $SCSParam);

                        $SCSUparam['encrypt_id']       = manojEncript($stuclsLastInsertId);
                        $SCSUwhere['student_class_id'] = $stuclsLastInsertId;
                        $this->common_model->edit_data_by_multiple_cond('student_class', $SCSUparam, $SCSUwhere);
                    endif;

                    $this->session->set_flashdata('alert_success', lang('addsuccess'));
                else:
                    $studentUniqueId = $this->input->post('CurrentDataID');

                    //////////////	STUDENT SCHOOL BRANCH RELATION 	///////////////////////
                    $SParam['update_date']          = currentDateTime();
                    $SParam['updated_by']           = $this->session->userdata('SMS_ADMIN_ID');
                    $SPUwhere['student_qunique_id'] = $studentUniqueId;
                    $this->common_model->edit_data_by_multiple_cond('students', $SParam, $SPUwhere);

                    //////////////	STUDENT SCHOOL BRANCH RELATION 	///////////////////////
                    $SBParam['update_date']         = currentDateTime();
                    $SBParam['updated_by']          = $this->session->userdata('SMS_ADMIN_ID');
                    $SBUwhere['franchise_id']       = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                    $SBUwhere['school_id']          = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                    $SBUwhere['branch_id']          = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                    $SBUwhere['board_id']           = $this->session->userdata('SMS_ADMIN_BOARD_ID');
                    $SBUwhere['student_qunique_id'] = $studentUniqueId;
                    $this->common_model->edit_data_by_multiple_cond('student_branch', $SBParam, $SBUwhere);

                    //////////////	STUDENT CLASS SECTION RELATION 	///////////////////////
                    $SCSParam['creation_date'] = currentDateTime();
                    $SCSParam['created_by']    = $this->session->userdata('SMS_ADMIN_ID');

                    $SCSUwhere['franchise_id']       = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                    $SCSUwhere['school_id']          = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                    $SCSUwhere['branch_id']          = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                    $SCSUwhere['board_id']           = $this->session->userdata('SMS_ADMIN_BOARD_ID');
                    $SCSUwhere['student_qunique_id'] = $studentUniqueId;
                    $this->common_model->edit_data_by_multiple_cond('student_class', $SCSParam, $SCSUwhere);

                    $this->session->set_flashdata('alert_success', lang('updatesuccess'));
                endif;
                redirect($this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/addeditdata/' . $studentUniqueId);
            endif;
        endif;

        $this->layouts->set_title('Edit student details');
        $this->layouts->admin_view('studentlist/addeditdata', array(), $data);
    } // END OF FUNCTION	

    /* * *********************************************************************
     * * Function name : changestatus
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for change status
     * * Date : 01 FEBRUARY 2018
     * ********************************************************************** */
    function changestatus($changeStatusId = '', $statusType = '') {
        $this->admin_model->authCheck('admin', 'edit_data');

        $param['status']             = $statusType;
        $where['student_qunique_id'] = $changeStatusId;
        $this->common_model->edit_data_by_multiple_cond('students', $param, $where);

        if ($statusType == "A"):
            $this->session->set_flashdata('alert_success', lang('statussuccess'));
        elseif ($statusType == "I"):
            $this->session->set_flashdata('alert_success', lang('statussuccess'));
        elseif ($statusType == "B"):
            $this->session->set_flashdata('alert_success', lang('statussuccess'));
        elseif ($statusType == "D"):
            $this->session->set_flashdata('alert_success', lang('deletesuccess'));
        endif;

        redirect(correctLink('studentListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
    } // END OF FUNCTION	

    /* * *********************************************************************
     * * Function name : get_view_data
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for get view data.
     * * Date : 01 FEBRUARY 2018
     * ********************************************************************** */
    function get_view_data() {
        $html = '';
        if ($this->input->post('viewid')):
            $viewId   = $this->input->post('viewid');
            $stuQuery = "SELECT * FROM sms_students WHERE student_qunique_id = '" . $viewId . "'";
            $viewData = $this->common_model->get_data_by_query('single', $stuQuery);
            if ($viewData <> ""):

                $stubrQuery   = "SELECT student_registration_no,student_admission_date,student_relieving_date
                             	FROM sms_student_branch WHERE student_qunique_id = '" . $viewId . "'";
                $stubrDetails = $this->common_model->get_data_by_query('single', $stubrQuery);
                if ($stubrDetails <> ""):
                    $viewData = array_merge($viewData, $stubrDetails);
                endif;

                $ustuclsQuery  = "SELECT cls.class_name, clsec.class_section_name, stucls.student_roll_no
	                             FROM sms_student_class as stucls
	                             LEFT JOIN sms_classes as cls  ON stucls.class_id=cls.encrypt_id 
	                             LEFT JOIN sms_class_section as clsec  ON stucls.section_id=clsec.encrypt_id 
	                             WHERE stucls.student_qunique_id = '" . $viewId . "'";
                $stuclsDetails = $this->common_model->get_data_by_query('single', $ustuclsQuery);
                if ($stuclsDetails <> ""):
                    $viewData = array_merge($viewData, $stuclsDetails);
                endif;

                $html .= '<table class="table border-none">
								  <tbody>
								    <tr>
									  <td align="right" colspan="4">
									    <a href="' . base_url() . $this->router->fetch_class() . '/print_in_pdf/' . $viewId . '" id="downloadlink"><img src="' . \base_url() . 'assets/images/print-icone.png" title="Print Teacher details" alt="Print Teacher details" width="30" /></a>
									  </td>
									</tr>
									<tr>
									  <td align="left" width="20%"><strong>Class name</strong></td>
									  <td align="left" width="30%">' . stripslashes($viewData['class_name']) . '</td>
									  <td align="left" width="20%"><strong>Section name</strong></td>
									  <td align="left" width="30%">' . stripslashes($viewData['class_section_name']) . '</td>
									</tr>
									<tr>
									  <td align="left" width="20%"><strong>Roll no</strong></td>
									  <td align="left" width="30%">' . stripslashes($viewData['student_roll_no']) . '</td>
									  <td align="left" width="20%"><strong>Registration no</strong></td>
									  <td align="left" width="30%">' . stripslashes($viewData['student_registration_no']) . '</td>
									</tr>
									<tr>
									  <td align="left" width="20%"><strong>Admission date</strong></td>
									  <td align="left" width="30%">' . YYMMDDtoDDMMYY($viewData['student_admission_date']) . '</td>
									  <td align="left" width="20%"><strong>Relieving date</strong></td>
									  <td align="left" width="30%">' . YYMMDDtoDDMMYY($viewData['student_relieving_date']) . '</td>
									</tr>
									<tr>
									  <td align="left" width="20%"><strong>First name</strong></td>
									  <td align="left" width="30%">' . stripslashes($viewData['student_f_name']) . '</td>
									  <td align="left" width="20%"><strong>Middle name</strong></td>
									  <td align="left" width="30%">' . stripslashes($viewData['student_m_name']) . '</td>
									</tr>
									<tr>
									  <td align="left" width="20%"><strong>Last name</strong></td>
									  <td align="left" width="30%">' . stripslashes($viewData['student_l_name']) . '</td>
									  <td align="left" width="20%"><strong>Date of birth</strong></td>
									  <td align="left" width="30%">' . YYMMDDtoDDMMYY($viewData['student_dob']) . '</td>
									</tr>
                                    <tr>
									  <td align="left" width="20%"><strong>Unique id</strong></td>
									  <td align="left" width="30%">' . stripslashes($viewId) . '</td>
									</tr>
									<tr>
										<td colspan="4">
										  <table class="table border-none">
											<thead>
											<tr>
											  <th align="left" colspan="4"><strong>Other Details</strong></th>
											</tr>
											</thead>
											<tbody>
												<tr>
												  <td align="left" width="20%"><strong>Gender</strong></td>
												  <td align="left" width="30%">' . stripslashes($viewData['student_gender']) . '</td>
												  <td align="left" width="20%"><strong>Religion</strong></td>
												  <td align="left" width="30%">' . stripslashes($viewData['student_religion']) . '</td>
												</tr>
												<tr>
												  <td align="left" width="20%"><strong>Category</strong></td>
												  <td align="left" width="30%">' . stripslashes($viewData['student_category']) . '</td>
												  <td align="left" width="20%"><strong>Visible mark (if any)</strong></td>
												  <td align="left" width="30%">' . stripslashes($viewData['student_visible_mark']) . '</td>
												</tr>
												<tr>
												  <td align="left" width="20%"><strong>Profile picture</strong></td>
												  <td align="left" width="30%"><img src="' . stripslashes($viewData['student_image']) . '" width="100" border="0" alt="" /></td>
												  <td align="left" width="20%">&nbsp;</td>
									  			  <td align="left" width="30%">&nbsp;</td>
												</tr>
											</tbody>
											</table>
										</td>
									</tr>
									<tr>
									  <td align="left" width="20%"><strong>Status</strong></td>
									  <td align="left" width="30%">' . showStatus($viewData['status']) . '</td>
									  <td align="left" width="20%">&nbsp;</td>
									  <td align="left" width="30%">&nbsp;</td>
									</tr>';
                $html .= '</tbody>
								</table>';
            endif;
        endif;
        echo $html;
        die;
    } // END OF FUNCTION	

    /* * *********************************************************************
     * * Function name : uplode_image
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used uplode image
     * * Date : 01 FEBRUARY 2018
     * ********************************************************************** */
    function uplode_image() {
        $file_name = $_FILES['uploadfile']['name'];
        if ($file_name):
            $tmp_name = $_FILES['uploadfile']['tmp_name'];

            $imagefolder = 'S_' . manojDecript($this->session->userdata('SMS_ADMIN_SCHOOL_ID')) . '/B_' . manojDecript($this->session->userdata('SMS_ADMIN_BRANCH_ID'));
            $ext         = pathinfo($file_name);
            $newfilename = time() . '.' . $ext['extension'];
            $this->load->library("upload_crop_img");
            $this->load->library('user_agent');
            // add parent image in teacherImage foder
            if (strpos($this->agent->referrer(), 'addeditparents')):
                $return_file_name = $this->upload_crop_img->_upload_image($file_name, $tmp_name, 'teacherImage', $newfilename, $imagefolder);
            else:
                //add student image to studentimage folder
                $return_file_name = $this->upload_crop_img->_upload_image($file_name, $tmp_name, 'studentImage', $newfilename, $imagefolder);
            endif;
            echo $return_file_name;
            die;
        else:
            echo 'UPLODEERROR';
            die;
        endif;
    } // END OF FUNCTION	

    /* * *********************************************************************
     * * Function name : DeleteImage
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for delete image by ajax.
     * * Date : 01 FEBRUARY 2018
     * ********************************************************************** */
    function DeleteImage() {
        $imagename = $this->input->post('imagename');
        if ($imagename):
            $this->load->library("upload_crop_img");
            $return = $this->upload_crop_img->_delete_image($imagename);
        endif;
        echo '1';
        die;
    } // END OF FUNCTION	

    /* * *********************************************************************
     * * Function name: print_in_pdf
     * * Developed By: Manoj Kumar
     * * Purpose: This function used for print in pdf
     * * Date: 01 FEBRUARY 2018
     * ********************************************************************** */
    public function print_in_pdf($printpdfId = '') {
        $this->load->library('Mpdf');
        if ($printpdfId == ''): redirect(correctLink('studentListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
        endif;
        $data['printpdfId'] = $printpdfId;
        $viewId             = $printpdfId;

        $stuQuery = "SELECT * FROM sms_students WHERE student_qunique_id = '" . $viewId . "'";
        $viewData = $this->common_model->get_data_by_query('single', $stuQuery);
        if ($viewData <> ""):
            $stubrQuery   = "SELECT student_registration_no,student_admission_date,student_relieving_date
                         	FROM sms_student_branch WHERE student_qunique_id = '" . $viewId . "'";
            $stubrDetails = $this->common_model->get_data_by_query('single', $stubrQuery);
            if ($stubrDetails <> ""):
                $viewData = array_merge($viewData, $stubrDetails);
            endif;

            $ustuclsQuery  = "SELECT cls.class_name, clsec.class_section_name, stucls.student_roll_no
                             FROM sms_student_class as stucls
                             LEFT JOIN sms_classes as cls  ON stucls.class_id=cls.encrypt_id 
                             LEFT JOIN sms_class_section as clsec  ON stucls.section_id=clsec.encrypt_id 
                             WHERE stucls.student_qunique_id = '" . $viewId . "'";
            $stuclsDetails = $this->common_model->get_data_by_query('single', $ustuclsQuery);
            if ($stuclsDetails <> ""):
                $viewData = array_merge($viewData, $stuclsDetails);
            endif;
        endif;
        $data['viewData'] = $viewData;

        $this->load->view('studentlist/printinpdf', $data);
        $this->download_pdf('student_details_' . $data['printpdfId'] . '.pdf');
    } // END OF FUNCTION		

    /* * *********************************************************************
     * * Function name: download_pdf
     * * Developed By: Manoj Kumar
     * * Purpose: This function used for download pdf
     * * Date: 01 FEBRUARY 2018
     * ********************************************************************** */
    public function download_pdf($file = '') {
        header('Content-Description: File Transfer');
        // We'll be outputting a PDF
        header('Content-type: application/pdf');
        // It will be called downloaded.pdf
        header('Content-Disposition: attachment; filename="' . $file . '"');
        // The PDF source is in original.pdf
        readfile($this->config->item("root_path") . "assets/downloadpdf/" . $file);

        @unlink($this->config->item("root_path") . "assets/downloadpdf/" . $file);
        exit;
    } // END OF FUNCTION		

    /* * *********************************************************************
     * * Function name : addeditparents
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for add edit parents data
     * * Date : 01 FEBRUARY 2018
     * * ********************************************************************* */
    public function addeditparents($studentId = '') {
        $data['error']          = '';
        $data['studentId']      = $studentId;
        $data['studentDetails'] = $this->admin_model->StudentDetails($studentId);
        if (!$studentId):
            redirect(correctLink('studentListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
        endif;

        $subQuery  = "SELECT stupar.*  ,par.user_f_name ,par.user_m_name,par.user_f_name,par.user_l_name ,par.user_email ,  par.user_email ,
                    par.user_mobile ,par.user_phone,par.user_password ,det.user_pic , det.user_income ,det.user_occupation,det.user_income,det.user_education ,  det.user_age,
					det.user_pan_card ,det.user_adhaar_card			
                    FROM sms_student_parent as stupar
					LEFT JOIN sms_users as par ON stupar.parent_id=par.encrypt_id
                    LEFT JOIN sms_user_detils as  det ON stupar.parent_id=det.user_id
				 	WHERE stupar.student_qunique_id = '" . $data['studentId'] . "' 
					AND stupar.parent_type = 'Father'";
        $mSubQuery = "SELECT stupar.*  ,par.user_f_name ,par.user_m_name,par.user_f_name,par.user_l_name ,par.user_email ,  par.user_email ,
                    par.user_mobile ,par.user_password,par.user_phone ,det.user_pic , det.user_income ,det.user_occupation,det.user_income,det.user_education ,  det.user_age,
					det.user_pan_card ,det.user_adhaar_card				
                    FROM sms_student_parent as stupar
					LEFT JOIN sms_users as par ON stupar.parent_id=par.encrypt_id
                    LEFT JOIN sms_user_detils as  det ON stupar.parent_id=det.user_id
				 	WHERE stupar.student_qunique_id = '" . $data['studentId'] . "' 
					AND stupar.parent_type = 'Mother'";
        if ($studentId):
            $data['FEDITDATA'] = $this->common_model->get_data_by_query('single', $subQuery);
            $data['MEDITDATA'] = $this->common_model->get_data_by_query('single', $mSubQuery);
        endif;
        if ($data['studentId']):
            $this->admin_model->authCheck('admin', 'edit_data');
            $stuQuery         = "SELECT * FROM sms_students WHERE student_qunique_id = '" . $data['studentId'] . "'";
            $data['EDITDATA'] = $this->common_model->get_data_by_query('single', $stuQuery);
        else:
            $this->admin_model->authCheck('admin', 'add_data');
        endif;
        if ($this->input->post('SaveChanges')):
            $error = 'NO';
            $this->form_validation->set_rules('f_fname', 'lang:Father First Name', 'trim|required');
            $this->form_validation->set_rules('f_mname', 'lang:Middle Name', 'trim');
            $this->form_validation->set_rules('f_lname', 'lang:Last Name', 'trim');
            $this->form_validation->set_rules('f_occupation', 'lang:Phone', 'trim');
            $this->form_validation->set_rules('f_income', 'lang:Income', 'trim');
            $this->form_validation->set_rules('f_education', 'lang:Occupation', 'trim');
            $this->form_validation->set_rules('f_age', 'lang:Age', 'trim');
            $this->form_validation->set_rules('f_email', 'lang:E-Mail', 'trim|required|valid_email|is_unique[users.user_email]');
            $this->form_validation->set_rules('f_mobile', 'lang:Education', 'trim|required');
            $this->form_validation->set_rules('f_phone', 'lang:Phone', 'trim');
            $this->form_validation->set_rules('f_adhaar_card', 'lang:Adhaar No', 'trim|required');
            $this->form_validation->set_rules('f_pan_card', 'lang:Pan No', 'trim');
            if ($this->input->post('CurrentDataID')):
                if ($this->input->post('f_password')):
                    $this->form_validation->set_rules('f_password', 'lang:Password', 'trim|required|min_length[6]|max_length[25]');
                    $this->form_validation->set_rules('f_conf_password', 'lang:Confirm Password', 'trim|required|min_length[6]|matches[f_password]');
                endif;
            else:
                $this->form_validation->set_rules('f_password', 'lang:Password', 'trim|required|min_length[6]|max_length[25]');
                $this->form_validation->set_rules('f_conf_password', 'lang:Confirm Password', 'trim|required|min_length[6]|matches[f_password]');
            endif;

            if ($this->form_validation->run()):
                $params['user_f_name'] = addslashes($this->input->post('f_fname'));
                $params['user_m_name'] = addslashes($this->input->post('f_mname'));
                $params['user_l_name'] = addslashes($this->input->post('f_lname'));
                $params['user_mobile'] = addslashes($this->input->post('f_mobile'));
                $params['user_phone']  = addslashes($this->input->post('f_phone'));
                $params['user_email']  = addslashes($this->input->post('f_email'));

                $dparams['user_pan_card']    = addslashes($this->input->post('f_pan_card'));
                $dparams['user_adhaar_card'] = addslashes($this->input->post('f_adhaar_card'));
                $dparams['user_income']      = addslashes($this->input->post('f_income'));
                $dparams['user_occupation']  = addslashes($this->input->post('f_occupation'));
                $dparams['user_education']   = addslashes($this->input->post('f_education'));
                $dparams['user_age']         = addslashes($this->input->post('f_age'));
                $dparams['user_pic']         = addslashes($this->input->post('uploadimage0'));
                $params['parent_type']       = 'Father';
                if ($this->input->post('f_password') != 'ManojVajpayee'):
                    $curpassword             = html_escape(addslashes($this->input->post('f_password')));
                    $params['user_password'] = $this->admin_model->encript_password($curpassword);
                endif;
                $params['parent_type'] = 'Father';
                // add to user table /////
                if ($this->input->post('CurrentDataID') == ''):
                    $params['franchise_id']  = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                    $params['school_id']     = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                    $params['branch_id']     = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                    $params['user_type']     = 'Parent';
                    $params['creation_date'] = currentDateTime();
                    $params['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                    $params['status']        = 'A';
                    $params['session_year']=	CURRENT_SESSION;
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
                    $dparams['session_year']  =	CURRENT_SESSION;
                    $uDlastInsertId = $this->common_model->add_data('user_detils', $dparams);

                    $UDparam['encrypt_id']          = manojEncript($uDlastInsertId);
                    $UDwhere['user_detail_id']      = $uDlastInsertId;
                    $this->common_model->edit_data_by_multiple_cond('user_detils', $UDparam, $UDwhere);
                    // add to student parent table  /////
                    $Psparams['parent_type']        = 'Father';
                    $Psparams['student_qunique_id'] = $data['studentId'];
                    $Psparams['parent_id']          = $ParentId;
                    $Psparams['creation_date']      = currentDateTime();
                    $Psparams['created_by']         = $this->session->userdata('SMS_ADMIN_ID');
                    $Psparams['status']             = 'Y';
                    $Psparams['session_year']		=	CURRENT_SESSION;
                    $splastInsertId                 = $this->common_model->add_data('student_parent', $Psparams);
                    $PSparam['encrypt_id']          = manojEncript($splastInsertId);
                    $PSwhere['student_parent_id']   = $splastInsertId;

                    $this->common_model->edit_data_by_multiple_cond('student_parent', $PSparam, $PSwhere);
                    $this->session->set_flashdata('alert_success', lang('addsuccess'));
                else:
                    $ParentId = $this->input->post('CurrentDataID');
                    //update to users table //
                    $where['encrypt_id']   = $ParentId;
                    $params['update_date'] = currentDateTime();
                    $params['updated_by']  = $this->session->userdata('SMS_ADMIN_ID');
                    $this->common_model->edit_data_by_multiple_cond('users', $params, $where);
                    //update to users detail table //
                    $dwhere['user_id']      = $ParentId;
                    $dparams['update_date'] = currentDateTime();
                    $dparams['updated_by']  = $this->session->userdata('SMS_ADMIN_ID');
                    $this->common_model->edit_data_by_multiple_cond('user_detils', $dparams, $dwhere);
                    ///  ///
                    $relationdata           = $this->admin_model->check_student_and_parenty_relation($data['studentId'],$ParentId);
                    if ($relationdata == ''):
                        $Psparams['student_qunique_id'] = $data['studentId'];
                        $Psparams['parent_type']        = 'Father';
                        $Psparams['parent_id']          = $ParentId;
                        $Psparams['creation_date']      = currentDateTime();
                        $Psparams['created_by']         = $this->session->userdata('SMS_ADMIN_ID');
                        $Psparams['status']             = 'Y';
                        $Psparams['session_year']       =	CURRENT_SESSION;
                        $splastInsertId                 = $this->common_model->add_data('student_parent', $Psparams);
                        $PSparam['encrypt_id']          = manojEncript($splastInsertId);
                        $PSwhere['student_parent_id']   = $splastInsertId;

                        $this->common_model->edit_data_by_multiple_cond('student_parent', $PSparam, $PSwhere);
                    endif;
                    /// add sibling data
                    if(null !==$this->input->post('searchtext')): 
                        $sliblingRcheckQuery         = "SELECT * FROM  sms_student_sibling  AS sb   WHERE  sb.sibling_id='".$data['studentId']."' 
            									 	AND sb.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
            										AND sb.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
            										AND sb.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
                                                    AND sb.status = 'Y'";
                        $sliblingRowcheck = $this->common_model->get_data_by_query('single', $sliblingRcheckQuery);   
                        if(!$sliblingRowcheck)    :
                           $sliblingQuery         = "SELECT sb.student_qunique_id FROM `sms_student_parent` AS sp  
                                                    LEFT JOIN `sms_student_branch` AS sb ON sb.student_qunique_id = sp.student_qunique_id 
                                                    LEFT JOIN `sms_students` AS s ON s.student_qunique_id = sp.student_qunique_id 
                                                    WHERE sp.parent_id = '".$ParentId."'
            									 	AND sb.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
            										AND sb.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
            										AND sb.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
                                                    AND sb.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
                                                    AND sb.status = 'Y'
                                                    AND sp.status = 'Y'
                                                    AND s.status = 'A'
									                ORDER BY sb.student_admission_date , sb.creation_date ASC  LIMIT 0,1 ";
                            $sliblingData = $this->common_model->get_data_by_query('single', $sliblingQuery);
                            if($sliblingData):
                                    $sbParam['first_admission_student_id']  = $sliblingData['student_qunique_id'] ;
                                    $sbParam['franchise_id']  = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                                    $sbParam['school_id']     = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                                    $sbParam['branch_id']     = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                                    $sbParam['sibling_id']     = $data['studentId'] ;
                                    $sbParam['creation_date'] = currentDateTime();
                                    $sbParam['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                                    $sbParam['session_year']			=	CURRENT_SESSION;
                                    $sblastInsertId                 = $this->common_model->add_data('student_sibling', $sbParam);
                                    $sbeParam['encrypt_id']          = manojEncript($sblastInsertId);
                                    $sbwhere['student_sibling_id']   = $sblastInsertId;          
                                    $this->common_model->edit_data_by_multiple_cond('student_sibling', $sbeParam, $sbwhere);   
                            endif;
                        endif;
                    endif;
                    $this->session->set_flashdata('alert_success', lang('updatesuccess'));
                endif;
                /*
                  $studentid = $this->input->post('StudentID');

                  $relationdata = $this->admindata_model->check_student_and_parenty_relation($studentid, $gurdianid);
                  if ($relationdata == ''):
                  $sprparams['student_id'] = $studentid;
                  $sprparams['parent_id']  = $gurdianid;
                  $sprparams['status']     = 'Y';
                  $this->admindata_model->add_student_parent_relation_data($sprparams);
                  endif;
                 */
                redirect($this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/addeditparents/' . $data['studentId']);
            endif;
        endif;

        if ($this->input->post('SaveChanges1')):
            $error = 'NO';
            $this->form_validation->set_rules('m_fname', 'lang:First Name', 'trim|required');
            $this->form_validation->set_rules('m_mname', 'lang:Middle Name', 'trim');
            $this->form_validation->set_rules('m_lname', 'lang:Last Name', 'trim');
            $this->form_validation->set_rules('m_occupation', 'lang:Phone', 'trim');
            $this->form_validation->set_rules('m_income', 'lang:Income', 'trim');
            $this->form_validation->set_rules('m_education', 'lang:Occupation', 'trim');
            $this->form_validation->set_rules('m_age', 'lang:Age', 'trim');
            $this->form_validation->set_rules('m_email', 'lang:E-Mail', 'trim|required|valid_email|is_unique[users.user_email]');
            $this->form_validation->set_rules('m_mobile', 'lang:Education', 'trim|required');
            $this->form_validation->set_rules('m_phone', 'lang:Phone', 'trim');
            $this->form_validation->set_rules('m_adhaar_card', 'lang:Adhaar No', 'trim|required');
            $this->form_validation->set_rules('m_pan_card', 'lang:Pan No', 'trim');

            if ($this->input->post('CurrentDataID')):
                if ($this->input->post('m_password')):
                    $this->form_validation->set_rules('m_password', 'lang:Password', 'trim|required|min_length[6]|max_length[25]');
                    $this->form_validation->set_rules('m_conf_password', 'lang:Confirm Password', 'trim|required|min_length[6]|matches[m_password]');
                endif;
            else:
                $this->form_validation->set_rules('m_password', 'lang:Password', 'trim|required|min_length[6]|max_length[25]');
                $this->form_validation->set_rules('m_conf_password', 'lang:Confirm Password', 'trim|required|min_length[6]|matches[m_password]');
            endif;

            if ($this->form_validation->run()):
                $params['user_f_name']       = addslashes($this->input->post('m_fname'));
                $params['user_m_name']       = addslashes($this->input->post('m_mname'));
                $params['user_l_name']       = addslashes($this->input->post('m_lname'));
                $params['user_mobile']       = addslashes($this->input->post('m_mobile'));
                $params['user_phone']        = addslashes($this->input->post('m_phone'));
                $params['user_email']        = addslashes($this->input->post('m_email'));
                $params['parent_type']       = 'Mother';
                $dparams['user_pan_card']    = addslashes($this->input->post('m_pan_card'));
                $dparams['user_adhaar_card'] = addslashes($this->input->post('m_adhaar_card'));
                $dparams['user_income']      = addslashes($this->input->post('m_income'));
                $dparams['user_occupation']  = addslashes($this->input->post('m_occupation'));
                $dparams['user_education']   = addslashes($this->input->post('m_education'));
                $dparams['user_age']         = addslashes($this->input->post('m_age'));
                $dparams['user_pic']         = addslashes($this->input->post('uploadimage1'));
                if ($this->input->post('m_password') != 'ManojVajpayee'):
                    $curpassword = html_escape(addslashes($this->input->post('m_password')));

                    $params['user_password'] = $this->admin_model->encript_password($curpassword);
                endif;
                // add to user table /////
                if ($this->input->post('CurrentDataID') == ''):
                    $params['franchise_id']  = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                    $params['school_id']     = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                    $params['branch_id']     = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                    $params['user_type']     = 'Parent';
                    $params['creation_date'] = currentDateTime();
                    $params['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                    $params['status']        = 'A';
                    $params['session_year']			=	CURRENT_SESSION;
                    $ulastInsertId           = $this->common_model->add_data('users', $params);

                    $Uparam['encrypt_id'] = manojEncript($ulastInsertId);
                    $Uwhere['user_id']    = $ulastInsertId;
                    $this->common_model->edit_data_by_multiple_cond('users', $Uparam, $Uwhere);
                    $ParentId = $Uparam['encrypt_id'];
                    //add to user detail table////
                    $dparams['user_id']       = $ParentId;
                    $dparams['creation_date'] = currentDateTime();
                    $dparams['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                    $dparams['status']        = 'Y';
                    $dparams['session_year']			=	CURRENT_SESSION;
                    $uDlastInsertId = $this->common_model->add_data('user_detils', $dparams);

                    $UDparam['encrypt_id']          = manojEncript($uDlastInsertId);
                    $UDwhere['user_detail_id']      = $uDlastInsertId;
                    $this->common_model->edit_data_by_multiple_cond('user_detils', $UDparam, $UDwhere);
                    // add to student parent table  /////
                    $Psparams['parent_type']        = 'Mother';
                    $Psparams['student_qunique_id'] = $data['studentId'];
                    $Psparams['parent_id']          = $ParentId;
                    $Psparams['creation_date']      = currentDateTime();
                    $Psparams['created_by']         = $this->session->userdata('SMS_ADMIN_ID');
                    $Psparams['status']             = 'Y';
                    $Psparams['session_year']			=	CURRENT_SESSION;
                    $splastInsertId                 = $this->common_model->add_data('student_parent', $Psparams);
                    $PSparam['encrypt_id']          = manojEncript($splastInsertId);
                    $PSwhere['student_parent_id']   = $splastInsertId;

                    $this->common_model->edit_data_by_multiple_cond('student_parent', $PSparam, $PSwhere);
                    $this->session->set_flashdata('alert_success', lang('addsuccess'));
                else:
                    $ParentId = $this->input->post('CurrentDataID');
                    //update to users table //
                    $where['encrypt_id']    = $ParentId;
                    $params['update_date']  = currentDateTime();
                    $params['updated_by']   = $this->session->userdata('SMS_ADMIN_ID');
                    $this->common_model->edit_data_by_multiple_cond('users', $params, $where);
                    //update to users detail table //
                    $dwhere['user_id']      = $ParentId;
                    $dparams['update_date'] = currentDateTime();
                    $dparams['updated_by']  = $this->session->userdata('SMS_ADMIN_ID');
                    $this->common_model->edit_data_by_multiple_cond('user_detils', $dparams, $dwhere);
                    ///  ///
                    $relationdata = $this->admin_model->check_student_and_parenty_relation($data['studentId'], $ParentId);
                    if ($relationdata == ''):
                        $Psparams['student_qunique_id'] = $data['studentId'];
                        $Psparams['parent_id']          = $ParentId;
                        $Psparams['parent_type']        = 'Mother';
                        $Psparams['creation_date']      = currentDateTime();
                        $Psparams['created_by']         = $this->session->userdata('SMS_ADMIN_ID');
                        $Psparams['status']             = 'Y';
                          $Psparams['session_year']			=	CURRENT_SESSION;
                        $splastInsertId                 = $this->common_model->add_data('student_parent', $Psparams);
                        $PSparam['encrypt_id']          = manojEncript($splastInsertId);
                        $PSwhere['student_parent_id']   = $splastInsertId;

                        $this->common_model->edit_data_by_multiple_cond('student_parent', $PSparam, $PSwhere);
                    endif;
                    /// add sibling data
                    if(null !==$this->input->post('searchtext')):
                              
                        $sliblingRcheckQuery         = "SELECT * FROM  sms_student_sibling  AS sb   WHERE  sb.sibling_id='".$data['studentId']."' 
                									 	AND sb.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
                										AND sb.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
                										AND sb.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
                                                        AND sb.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
                                                        AND sb.status = 'Y'";
                        $sliblingRowcheck = $this->common_model->get_data_by_query('single', $sliblingRcheckQuery);
                        if(!$sliblingRowcheck):
                            $sliblingQuery         = "SELECT sb.student_qunique_id FROM `sms_student_parent` AS sp  
                                                    LEFT JOIN `sms_student_branch` AS sb ON sb.student_qunique_id = sp.student_qunique_id 
                                                    LEFT JOIN `sms_students` AS s ON s.student_qunique_id = sp.student_qunique_id 
                                                    WHERE sp.parent_id = '".$ParentId."'
            									 	AND sb.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
            										AND sb.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
            										AND sb.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
                                                    AND sb.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
                                                    AND sb.status = 'Y'
                                                    AND sp.status = 'Y'
                                                    AND s.status = 'A'
										            ORDER BY sb.student_admission_date , sb.creation_date ASC  LIMIT 0,1 ";
                            $sliblingData = $this->common_model->get_data_by_query('single', $sliblingQuery);
                            if($sliblingData):
                                $sbParam['first_admission_student_id']  = $sliblingData['student_qunique_id'] ;
                                $sbParam['franchise_id']  = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                                $sbParam['school_id']     = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                                $sbParam['branch_id']     = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                                $sbParam['sibling_id']     = $data['studentId'] ;
                                $sbParam['creation_date'] = currentDateTime();
                                $sbParam['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                                $sbParam['session_year']			=	CURRENT_SESSION;
                                $sblastInsertId                 = $this->common_model->add_data('student_sibling', $sbParam);
                                $sbeParam['encrypt_id']          = manojEncript($sblastInsertId);
                                $sbwhere['student_sibling_id']   = $sblastInsertId;          
                                $this->common_model->edit_data_by_multiple_cond('student_sibling', $sbeParam, $sbwhere);   
                            endif;
                        endif;
                    endif;
                    $this->session->set_flashdata('alert_success', lang('updatesuccess'));
                endif;
                /*
                  $studentid = $this->input->post('StudentID');

                  $relationdata = $this->admindata_model->check_student_and_parenty_relation($studentid, $gurdianid);
                  if ($relationdata == ''):
                  $sprparams['student_id'] = $studentid;
                  $sprparams['parent_id']  = $gurdianid;
                  $sprparams['status']     = 'Y';
                  $this->admindata_model->add_student_parent_relation_data($sprparams);
                  endif;
                 */
                redirect($this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/addeditparents/' . $data['studentId']);
            endif;
        endif;

        $this->layouts->set_title('Add/edit student parents details');
        $this->layouts->admin_view('studentlist/addeditparents', array(), $data);
    } // END OF FUNCTION	

    /* * *********************************************************************
     * * Function name : addeditaddress
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for add edit address data
     * * Date : 01 FEBRUARY 2018
     * * ********************************************************************* */
    public function addeditaddress($studentId = '') {
        $data['error']          = '';
        $data['studentId']      = $studentId;
        $data['studentDetails'] = $this->admin_model->StudentDetails($studentId);
        if (!$studentId):
            redirect(correctLink('studentListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
        endif;
        $StateQuery        = "SELECT state FROM sms_country WHERE county = 'India' GROUP BY state";
        $data['STATEDATA'] = $this->common_model->get_data_by_query('multiple', $StateQuery);

        $addQuery         = "SELECT *
							FROM sms_student_address 
						 	WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
							AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
							AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
							AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
							AND student_qunique_id = '" . $data['studentId'] . "'";
        $data['EDITDATA'] = $this->common_model->get_data_by_query('single', $addQuery);
        if ($data['EDITDATA'] <> ""):
            $this->admin_model->authCheck('admin', 'edit_data');
        else:
            $this->admin_model->authCheck('admin', 'add_data');
        endif;
        if ($this->input->post('SaveChanges')):
            $error = 'NO';
            $this->form_validation->set_rules('student_c_state', 'State', 'trim|required');
            $this->form_validation->set_rules('student_c_city', 'City', 'trim|required');
            $this->form_validation->set_rules('student_c_locality', 'Locality', 'trim|required');
            $this->form_validation->set_rules('student_c_address', 'Address', 'trim|required');
            $this->form_validation->set_rules('student_c_zipcode', 'Zip code', 'trim|required');
            $this->form_validation->set_rules('same_address', 'Same address', 'trim');
            $this->form_validation->set_rules('student_p_state', 'State', 'trim|required');
            $this->form_validation->set_rules('student_p_city', 'City', 'trim|required');
            $this->form_validation->set_rules('student_p_locality', 'Locality', 'trim|required');
            $this->form_validation->set_rules('student_p_address', 'Address', 'trim|required');
            $this->form_validation->set_rules('student_p_zipcode', 'Zip code', 'trim|required');
            $this->form_validation->set_rules('o_address_type', 'Parent\'s Type', 'trim|required');
            $this->form_validation->set_rules('student_o_state', 'State', 'trim|required');
            $this->form_validation->set_rules('student_o_city', 'City', 'trim|required');
            $this->form_validation->set_rules('student_o_locality', 'Locality', 'trim|required');
            $this->form_validation->set_rules('student_o_address', 'Address', 'trim|required');
            $this->form_validation->set_rules('student_o_zipcode', 'Zip code', 'trim|required');

            if ($this->form_validation->run() && $error == 'NO'):
                $Param['student_c_state']    = addslashes($this->input->post('student_c_state'));
                $Param['student_c_city']     = addslashes($this->input->post('student_c_city'));
                $Param['student_c_locality'] = addslashes($this->input->post('student_c_locality'));
                $Param['student_c_address']  = addslashes($this->input->post('student_c_address'));
                $Param['student_c_zipcode']  = addslashes($this->input->post('student_c_zipcode'));
                $Param['same_address']       = $this->input->post('same_address') ? 'Y' : 'N';
                $Param['student_p_state']    = addslashes($this->input->post('student_p_state'));
                $Param['student_p_city']     = addslashes($this->input->post('student_p_city'));
                $Param['student_p_locality'] = addslashes($this->input->post('student_p_locality'));
                $Param['student_p_address']  = addslashes($this->input->post('student_p_address'));
                $Param['student_p_zipcode']  = addslashes($this->input->post('student_p_zipcode'));
                $Param['o_address_type']     = addslashes($this->input->post('o_address_type'));
                $Param['student_o_state']    = addslashes($this->input->post('student_o_state'));
                $Param['student_o_city']     = addslashes($this->input->post('student_o_city'));
                $Param['student_o_locality'] = addslashes($this->input->post('student_o_locality'));
                $Param['student_o_address']  = addslashes($this->input->post('student_o_address'));
                $Param['student_o_zipcode']  = addslashes($this->input->post('student_o_zipcode'));

                if ($this->input->post('CurrentDataID') == ''):
                    $Param['franchise_id']       = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                    $Param['school_id']          = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                    $Param['branch_id']          = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                    $Param['board_id']           = $this->session->userdata('SMS_ADMIN_BOARD_ID');
                    $Param['student_qunique_id'] = $data['studentId'];

                    $Param['creation_date'] = currentDateTime();
                    $Param['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                    $Param['status']        = 'Y';
                      $Param['session_year']			=	CURRENT_SESSION;
                    $stuaddlastInsertId     = $this->common_model->add_data('student_address', $Param);

                    $Sparam['encrypt_id']         = manojEncript($stuaddlastInsertId);
                    $Swhere['student_address_id'] = $stuaddlastInsertId;
                    $this->common_model->edit_data_by_multiple_cond('student_address', $Sparam, $Swhere);

                    $this->session->set_flashdata('alert_success', lang('addsuccess'));
                else:
                    $stuaddlastInsertId = $this->input->post('CurrentDataID');

                    $Param['update_date'] = currentDateTime();
                    $Param['updated_by']  = $this->session->userdata('SMS_ADMIN_ID');
                    $Where['encrypt_id']  = $stuaddlastInsertId;
                    $this->common_model->edit_data_by_multiple_cond('student_address', $Param, $Where);

                    $this->session->set_flashdata('alert_success', lang('updatesuccess'));
                endif;
                redirect($this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/addeditaddress/' . $data['studentId']);
            endif;
        endif;

        $this->layouts->set_title('Add/edit student address details');
        $this->layouts->admin_view('studentlist/addeditaddress', array(), $data);
    }

    /* * *********************************************************************
     * * Function name : addedithealth
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for add edit health data
     * * Date : 01 FEBRUARY 2018
     * * ********************************************************************* */
    public function addedithealth($studentId = '') {
        $data['error']          = '';
        $data['studentId']      = $studentId;
        $data['studentDetails'] = $this->admin_model->StudentDetails($studentId);
        if (!$studentId):
            redirect(correctLink('studentListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
        endif;
        $addQuery         = "SELECT *
							FROM sms_student_health 
							WHERE 
							 student_qunique_id = '" . $data['studentId'] . "'";
        $data['EDITDATA'] = $this->common_model->get_data_by_query('single', $addQuery);
        if ($data['EDITDATA'] <> ""):
            $this->admin_model->authCheck('admin', 'edit_data');
        else:
            $this->admin_model->authCheck('admin', 'add_data');
        endif;

        if ($this->input->post('SaveChanges')):
            $error = 'NO';
            $this->form_validation->set_rules('student_blood_group', 'Blood Group', 'trim|required');
            if ($this->form_validation->run() && $error == 'NO'):
                $Param['student_blood_group']       = addslashes($this->input->post('student_blood_group'));
                $Param['student_height']            = addslashes($this->input->post('student_height'));
                $Param['student_weight']            = addslashes($this->input->post('student_weight'));
                $Param['student_allergy_from']      = addslashes($this->input->post('student_allergy_from'));
                $Param['student_l_medical_checkup'] = addslashes($this->input->post('student_l_medical_checkup'));
                $Param['student_special_notes']     = addslashes($this->input->post('student_special_notes'));

                if ($this->input->post('CurrentDataID') == ''):

                    $Param['student_qunique_id'] = $data['studentId'];
                    $Param['creation_date'] = currentDateTime();
                    $Param['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                    $Param['status']        = 'Y';
                    $Param['session_year']	=	CURRENT_SESSION;
                    $stuaddlastInsertId     = $this->common_model->add_data('student_health', $Param);
                    $Sparam['encrypt_id']        = manojEncript($stuaddlastInsertId);
                    $Swhere['student_health_id'] = $stuaddlastInsertId;
                    $this->common_model->edit_data_by_multiple_cond('student_health', $Sparam, $Swhere);

                    $this->session->set_flashdata('alert_success', lang('addsuccess'));
                else:
                    $stuaddlastInsertId = $this->input->post('CurrentDataID');

                    $Param['update_date'] = currentDateTime();
                    $Param['updated_by']  = $this->session->userdata('SMS_ADMIN_ID');
                    $Where['encrypt_id']  = $stuaddlastInsertId;
                    $this->common_model->edit_data_by_multiple_cond('student_health', $Param, $Where);

                    $this->session->set_flashdata('alert_success', lang('updatesuccess'));
                endif;
                redirect($this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/addedithealth/' . $data['studentId']);
            endif;
        endif;

        $this->layouts->set_title('Add/edit student health details');
        $this->layouts->admin_view('studentlist/addedithealth', array(), $data);
    }

    /* * *********************************************************************
     * * Function name : addedittransport
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for add edit transport data
     * * Date : 28 MARCH 2018
     * * ********************************************************************* */
    public function addedittransport($studentId = '') {
        $data['error']          = '';
        $data['studentId']      = $studentId;
        $data['studentDetails'] = $this->admin_model->StudentDetails($studentId);
        $data['routeAssignId']  = $this->uri->segment(getUrlSegment() + 1);
        if (!$studentId):
            redirect(correctLink('studentListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
        endif;

        $data['changeUrl'] = $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/addedittransport/' . $data['studentId'] . '/';

        $this->admin_model->authCheck('admin', 'add_data');
        $RVQuery = "SELECT routas.encrypt_id,
				 rout.route_name, vehity.vehicle_type_name
		 		 FROM sms_route_assign as routas
				 LEFT JOIN sms_route as rout ON routas.route_id=rout.encrypt_id
				 LEFT JOIN sms_vehicle as vehi ON routas.vehicle_id=vehi.encrypt_id 
				 LEFT JOIN sms_vehicle_types as vehity ON vehi.vehicle_type=vehity.encrypt_id 
				 WHERE rout.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
				 AND rout.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
				 AND rout.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'";
        $data['TRANPORTDATA'] = $this->common_model->get_data_by_query('multiple', $RVQuery);
        $EditQuery = "SELECT trans_assign_user_id, encrypt_id, student_qunique_id, route_assign_id, assign_type, assign_date
			 		 FROM sms_trans_assign_user 
					 WHERE student_qunique_id = '" . $data['studentId'] . "' AND assign_type = 'Active'";
        $data['EDITDATA'] = $this->common_model->get_data_by_query('single', $EditQuery);
        if ($data['EDITDATA'] <> "" && $data['routeAssignId'] == ''):
            redirect($this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/addedittransport/' . $data['studentId'] . '/' . $data['EDITDATA']['route_assign_id']);
        endif;
        if($data['routeAssignId']):
            $assignQuery        = "SELECT routa.route_assign_id,routa.encrypt_id,routa.route_id,
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
								 WHERE routa.encrypt_id = '" . $data['routeAssignId'] . "' ORDER BY routa.vehicle_id ASC";
            $data['ASSIGNDATA'] = $this->common_model->get_data_by_query('single', $assignQuery);
            if ($data['ASSIGNDATA'] <> ""):
                $data['ROUTEDATA'] = $this->common_model->get_data_by_encryptId('route', $data['ASSIGNDATA']['route_id']);
                $assignQuery        = "SELECT stop_name,stop_latitude,stop_longitude,pickup_time,drop_time,fee
											 FROM sms_route_detail WHERE route_id = '" . $data['ASSIGNDATA']['route_id'] . "' ORDER BY route_detail_id ASC";
                $data['ROUTEDDATA'] = $this->common_model->get_data_by_query('multiple', $assignQuery);
            endif;
        endif;
        $TAssQuery = "SELECT student_qunique_id
					 FROM sms_trans_assign_user 
					 WHERE assign_type = 'Active'";
        $data['TASSIGNDATA'] = $this->common_model->get_ids_in_array('student_qunique_id', $TAssQuery);
        if ($this->input->post('SaveChanges')):
            $error = 'NO';
            $this->form_validation->set_rules('route_assign_id', 'Transport', 'trim|required');
            if ($this->form_validation->run() && $error == 'NO'):
                $Param['route_assign_id'] = addslashes($this->input->post('route_assign_id'));
                if ($this->input->post('CurrentDataID') == ''):
                    $Param['student_qunique_id'] = $data['studentId'];
                    $Param['assign_type']        = 'Active';
                    $Param['assign_date']        = currentDateTime();
                    $Param['creation_date'] = currentDateTime();
                    $Param['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                    $Param['status']        = 'Y';
                    $Param['session_year']	=	CURRENT_SESSION;
                    $lastInsertId           = $this->common_model->add_data('trans_assign_user', $Param);

                    $Sparam['encrypt_id']           = manojEncript($lastInsertId);
                    $Swhere['trans_assign_user_id'] = $lastInsertId;
                    $this->common_model->edit_data_by_multiple_cond('trans_assign_user', $Sparam, $Swhere);

                    $this->session->set_flashdata('alert_success', lang('addsuccess'));
                else:
                    $assignTRansID = $this->input->post('CurrentDataID');

                    $Param['update_date'] = currentDateTime();
                    $Param['updated_by']  = $this->session->userdata('SMS_ADMIN_ID');
                    $Where['encrypt_id']  = $assignTRansID;
                    $this->common_model->edit_data_by_multiple_cond('trans_assign_user', $Param, $Where);

                    $this->session->set_flashdata('alert_success', lang('updatesuccess'));
                endif;

                redirect($this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/addedittransport/' . $data['studentId'] . '/' . $data['routeAssignId']);
            endif;
        endif;

        $this->layouts->set_title('Add/edit student transport details');
        $this->layouts->admin_view('studentlist/addedittransport', array(), $data);
    }

    /* * *********************************************************************
     * * Function name : same_parent_data_ajax
     * * Developed By : Jitendra Chaudhari
     * * Purpose  : This function used for same_parent_data_ajax
     * * Date : 21 Nov 2016
     * ********************************************************************** */
    function same_parent_data_ajax($parentId = '') {
        if ($this->input->post('curgid') && $this->input->post('type')):
            $curgid      = $this->input->post('curgid');
            $type        = $this->input->post('type');
            $paerntQuery = "SELECT u.encrypt_id ,  u.user_f_name ,u.user_m_name ,u.user_l_name ,u.user_email , "
                            . " u.user_password ,u.user_mobile ,u.user_type ,u.parent_type ,u.user_phone ,d.user_occupation"
                            . ", d.user_income,d.user_pic ,d.user_education , d.user_age ,d.user_adhaar_card ,d.user_pan_card  FROM sms_users as u  left join  sms_user_detils as d "
                            . "on d.user_id = u.encrypt_id  "
                            . "WHERE u.encrypt_id = '" . $curgid . "' AND    u.parent_type = '" . $type . "' ";
            $gdata = $this->common_model->get_data_by_query('single', $paerntQuery);
            if ($gdata <> ""):
                header('Content-Type: application/json');
                echo json_encode($gdata);
                die;
            endif;
        endif;
    }

    function search_ajax() {
        $html = '';
        if ($this->input->post('string') && $this->input->post('type')):
            $searchtext = $this->input->post('string');
            $type       = $this->input->post('type');
            $gdata      = $this->admin_model->get_all_parent_data($searchtext, $type);
            if ($gdata <> ""):
                $html .= '<ul>';
                foreach ($gdata as $ginfo):
                    $html .= '<li><p data-id="' . $ginfo->encrypt_id . '">' . stripcslashes($ginfo->user_f_name . ' ' . $ginfo->user_m_name . ' ' . $ginfo->user_l_name . '  (' . $ginfo->user_email . ')') . '</p></li>';
                endforeach;
                $html .= '</ul>';
            else:
                $html = 'ERROR';
            endif;
        endif;
        echo $html;
        die;
    }

    /* * *********************************************************************
     * * Function name : addeditaddress
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for add edit address data
     * * Date : 01 FEBRUARY 2018
     * * ********************************************************************* */
    public function addeditqrcode($studentId = '') {
        $data['error']     = '';
        $data['studentId'] = $studentId;
        if (!$studentId):
            redirect(correctLink('studentListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
        endif;
        $data['studentDetails'] = $this->admin_model->StudentDetails($studentId);
        $addQuery               = "SELECT *
    								FROM sms_student_qrcode
    							 	WHERE 
    								 student_qunique_id = '" . $data['studentId'] . "'";
        $data['EDITDATA']       = $this->common_model->get_data_by_query('single', $addQuery);
        //student data
        $studentQuery           = "SELECT sdt.student_f_name , sdt.student_m_name ,sdt.student_l_name ,c.class_name , s.class_section_name , b.student_registration_no  ,cls.student_roll_no
								FROM sms_students AS sdt 
								LEFT JOIN sms_student_class AS cls 
								ON sdt.student_qunique_id = cls.student_qunique_id 
								LEFT JOIN sms_classes AS c ON cls.class_id = c.encrypt_id 
								LEFT JOIN sms_class_section AS s ON cls.section_id = s.encrypt_id 
								LEFT JOIN sms_student_branch AS b 
								ON sdt.student_qunique_id = b.student_qunique_id  
								where
								sdt.student_qunique_id = '" . $data['studentId'] . "'";
        $studentData = $this->common_model->get_data_by_query('single', $studentQuery);
        //father data
        $fQuery = "SELECT par.user_f_name ,par.user_m_name,par.user_f_name,par.user_l_name 	FROM sms_student_parent as stupar
					LEFT JOIN sms_users as par ON stupar.parent_id=par.encrypt_id
                    LEFT JOIN sms_user_detils as  det ON stupar.parent_id=det.user_id
				 	WHERE stupar.student_qunique_id = '" . $data['studentId'] . "' 
					AND stupar.parent_type = 'Father'";
        $fatherData = $this->common_model->get_data_by_query('single', $fQuery);
        //mother data
        $mQuery     = "SELECT par.user_f_name ,par.user_m_name,par.user_f_name,par.user_l_name 			FROM sms_student_parent as stupar
					LEFT JOIN sms_users as par ON stupar.parent_id=par.encrypt_id
                    LEFT JOIN sms_user_detils as  det ON stupar.parent_id=det.user_id
				 	WHERE stupar.student_qunique_id = '" . $data['studentId'] . "' 
					AND stupar.parent_type = 'Mother'";
        $motherData = $this->common_model->get_data_by_query('single', $mQuery);

        if ($data['EDITDATA'] <> ""):
            $this->admin_model->authCheck('admin', 'edit_data');
        else:
            $this->admin_model->authCheck('admin', 'add_data');
        endif;

        if ($this->input->post('SaveChanges')):
            $error = 'NO';
            $this->form_validation->set_rules('qr_level', 'lang:QR Level', 'trim');
            $this->form_validation->set_rules('qr_size', 'lang:QR Size', 'trim');

            if ($this->form_validation->run() && $error == 'NO'):
                $Param['qr_level'] = addslashes($this->input->post('qr_level'));
                $Param['qr_size']  = addslashes($this->input->post('qr_size'));

                $PNG_TEMP_DIR = './assets/studentimages/S_' . manojDecript($this->session->userdata('SMS_ADMIN_SCHOOL_ID')) . '/B_' . \manojDecript($this->session->userdata('SMS_ADMIN_BRANCH_ID')) . DIRECTORY_SEPARATOR . 'qrcode' . DIRECTORY_SEPARATOR;
                $PNG_WEB_DIR  = 'qrcode/';
                if (!file_exists($PNG_TEMP_DIR))
                    mkdir($PNG_TEMP_DIR, 0777, true);

                //$enroll = $data['Student']->registration_no;
                //$studName = $data['Student']->fname.' '.$data['Student']->mname.' '.$data['Student']->lname;

                $regNo    = $studentData['student_registration_no'];
                $rollNo   = $studentData['student_roll_no'];
                $studName = $studentData['student_f_name'] . ' ' . $studentData['student_m_name']->mname . ' ' . $studentData['student_l_name'];

                $classSec = $studentData['class_name'] . '-' . $studentData['class_section_name'];
                $fathName = $fatherData['user_f_name'] . ' ' . $fatherData['user_m_name'] . ' ' . $fatherData['user_l_name'];
                $mothName = $motherData['user_f_name'] . ' ' . $motherData['user_m_name'] . ' ' . $motherData['user_l_name'];

                if ($fathName == ''):
                    $qrdata['data'] = 'Registration No: ' . $regNo . '    Roll No: ' . $rollNo . '             Student Name: ' . $studName . '   Mother Name: ' . $mothName . '       Class-Section: ' . $classSec;
                elseif ($mothName == ''):
                    $qrdata['data'] = 'Registration No: ' . $regNo . '     Roll No: ' . $rollNo . '             Student Name: ' . $studName . '        Father Name: ' . $fathName . '     Class-Section: ' . $classSec;
                else:
                    $qrdata['data'] = 'Registration No: ' . $regNo . '     Roll No: ' . $rollNo . '             Student Name: ' . $studName . '        Father Name: ' . $fathName . '      Mother Name: ' . $mothName . '       Class-Section: ' . $classSec;
                endif;
                $qrdata['level'] = $Param['qr_level'];
                $qrdata['size']  = $Param['qr_size'];
                if (($Param['qr_level'] == '') || ($Param['qr_size'] == '')):
                    $qrdata['level'] = 'M';
                    $qrdata['size']  = 6;
                endif;
                $qrdata['savename'] = $studentId . '.png';
                $Param['qr_pic']    = $PNG_TEMP_DIR . $qrdata['savename'];

                ///genrate qr code
                if (isset($qrdata['data'])) {
                    if (trim($qrdata['data']) == '')
                        die('data cannot be empty! <a href="?">back</a>');
                    $this->ciqrcode->generate($qrdata, $PNG_TEMP_DIR);
                    ///add and edit to student qrcode table
                    if ($this->input->post('CurrentDataID') == ''):

                        $Param['student_qunique_id'] = $data['studentId'];

                        $Param['creation_date'] = currentDateTime();
                        $Param['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                        $Param['status']        = 'Y';
                         $Param['session_year']			=	CURRENT_SESSION;
                        $stuaddlastInsertId     = $this->common_model->add_data('student_qrcode', $Param);

                        $Sparam['encrypt_id']        = manojEncript($stuaddlastInsertId);
                        $Swhere['student_qrcode_id'] = $stuaddlastInsertId;
                        $this->common_model->edit_data_by_multiple_cond('student_qrcode', $Sparam, $Swhere);

                        $this->session->set_flashdata('alert_success', lang('addsuccess'));
                    else:
                        $stuaddlastInsertId = $this->input->post('CurrentDataID');

                        $Param['update_date'] = currentDateTime();
                        $Param['updated_by']  = $this->session->userdata('SMS_ADMIN_ID');
                        $Where['encrypt_id']  = $stuaddlastInsertId;
                        $this->common_model->edit_data_by_multiple_cond('student_qrcode', $Param, $Where);

                        $this->session->set_flashdata('alert_success', lang('updatesuccess'));
                    endif;
                }
                redirect($this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/addeditqrcode/' . $data['studentId']);
            endif;
        endif;

        $this->layouts->set_title('Add/edit student QR Code');
        $this->layouts->admin_view('studentlist/addeditqrcode', array(), $data);
    }

    /* * *********************************************************************
     * * Function name : addeditdoc
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for add edit document data data
     * * Date : 01 FEBRUARY 2018
     * * ********************************************************************* */
    public function addeditdoc($studentId = '') {
        $data['error']          = '';
        $data['studentId']      = $studentId;
        $data['studentDetails'] = $this->admin_model->StudentDetails($studentId);
        if (!$studentId):
            redirect(correctLink('studentListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
        endif;
        $docQuery         = "SELECT * FROM `sms_student_doc_type` ";
        $data['DOCDATA']  = $this->common_model->get_data_by_query('multiple', $docQuery);
        $docSelectedQuery = "SELECT * FROM `sms_student_doc_type` where encrypt_id = '" . $this->input->post('doc_type_id') . "' ";
        $DOCSELECTDATA    = $this->common_model->get_data_by_query('single', $docSelectedQuery);
        $stuQuery         = "SELECT * FROM sms_students WHERE student_qunique_id = '" . $studentId . "'";
        $STUDATA          = $this->common_model->get_data_by_query('single', $stuQuery);
        $addQuery         = "SELECT *
							FROM sms_student_document 
						 	WHERE 
							 student_qunique_id = '" . $data['studentId'] . "'";
        $data['EDITDATA'] = $this->common_model->get_data_by_query('multiple', $addQuery);
        $alldataQuery     = "SELECT doc.doc_type , stu.* FROM sms_student_document AS stu  
                            LEFT  JOIN  `sms_student_doc_type` AS doc  ON doc.encrypt_id =  stu.doc_type_id  WHERE student_qunique_id = '" . $studentId . "'";

        $data['ALLDATA'] = $this->common_model->get_data_by_query('multiple', $alldataQuery);
        if ($data['EDITDATA'] <> ""):
            $this->admin_model->authCheck('admin', 'edit_data');
        else:
            $this->admin_model->authCheck('admin', 'add_data');
        endif;

        if ($this->input->post('SaveChanges')):
            $error = 'NO';
            $this->form_validation->set_rules('doc_type_id', 'Doc Type', 'trim|required');
            //upload  document   

            $file_name = $_FILES['uploadfile']['name'];

            if ($file_name):
                $tmp_name = $_FILES['uploadfile']['tmp_name'];

                $imagefolder = 'S_' . manojDecript($this->session->userdata('SMS_ADMIN_SCHOOL_ID')) . '/B_' . manojDecript($this->session->userdata('SMS_ADMIN_BRANCH_ID'));
                $ext         = pathinfo($file_name);
                $newfilename = $studentId . '_' . str_replace(' ', '_', $DOCSELECTDATA['doc_type']) . '_' . time() . '.' . $ext['extension'];

                $this->load->library("upload_crop_img");
                //add student image to studentimage folder
                $return_file_name = $this->upload_crop_img->_upload_image($file_name, $tmp_name, 'studentDocument', $newfilename, $imagefolder);
            endif;

            //add edit to database

            if ($this->form_validation->run() && $error == 'NO'):

                $Param['document']    = $return_file_name;
                $Param['doc_type_id'] = addslashes($this->input->post('doc_type_id'));

                //add or delete condition
                $edit = '';
                if($data['ALLDATA']):
                    foreach ($data['ALLDATA'] as $INFO):
                        if ($Param['doc_type_id'] == $INFO['doc_type_id']) :
                            $edit = 'YES';
                        endif;
                    endforeach;
                endif;

                if (($this->input->post('CurrentDataID') == '') and ( $edit == '')):

                    $Param['student_qunique_id'] = $data['studentId'];

                    $Param['creation_date'] = currentDateTime();
                    $Param['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                    $Param['status']        = 'Y';
                     $Param['session_year']			=	CURRENT_SESSION;
                    $stuaddlastInsertId     = $this->common_model->add_data('student_document', $Param);

                    $Sparam['encrypt_id']          = manojEncript($stuaddlastInsertId);
                    $Swhere['student_document_id'] = $stuaddlastInsertId;
                    $this->common_model->edit_data_by_multiple_cond('student_document', $Sparam, $Swhere);

                    $this->session->set_flashdata('alert_success', lang('addsuccess'));
                else:

                    $stuaddlastInsertId = $this->input->post('CurrentDataID');

                    $Param['update_date']        = currentDateTime();
                    $Param['updated_by']         = $this->session->userdata('SMS_ADMIN_ID');
                    $Where['doc_type_id']        = $Param['doc_type_id'];
                    $Where['student_qunique_id'] = $studentId;

                    $this->common_model->edit_data_by_multiple_cond('student_document', $Param, $Where);

                    $this->session->set_flashdata('alert_success', lang('updatesuccess'));
                endif;
                redirect($this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/addeditdoc/' . $data['studentId']);
            endif;
        endif;

        $this->layouts->set_title('Add/edit student documents details');
        $this->layouts->admin_view('studentlist/addeditdoc', array(), $data);
    } // END OF FUNCTION	
}