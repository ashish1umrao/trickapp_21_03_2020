<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sectionlist extends CI_Controller {

    public function __construct() {
        parent:: __construct();
        $this->load->helper(array('form', 'url', 'html', 'path', 'form', 'cookie'));
        $this->load->library(array('email', 'session', 'form_validation', 'pagination', 'parser', 'encrypt'));
        error_reporting(E_ALL ^ E_NOTICE);
        $this->load->library("layouts");
        $this->load->model(array('admin_model', 'common_model', 'emailtemplate_model', 'sms_model'));
        $this->load->helper('language');
        $this->lang->load('statictext', 'admin');
    }

    /* * *********************************************************************
     * * Function name : sectionlist
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for sectionlist
     * * Date : 23 JANUARY 2018
     * * ********************************************************************* */
    public function index() {
        $this->admin_model->authCheck('admin', 'view_data');
        $this->admin_model->get_permission_type($data);
        $data['error'] = '';

        if ($this->input->get('searchValue')):
            $sValue              = $this->input->get('searchValue');
            $whereCon['like']    = "(clasec.class_section_name LIKE '%" . $sValue . "%' OR clasec.class_section_short_name LIKE '%" . $sValue . "%' 
			                         OR cls.class_name LIKE '%" . $sValue . "%' OR cls.class_short_name LIKE '%" . $sValue . "%'
									 OR tec.user_f_name LIKE '%" . $sValue . "%' OR tec.user_m_name LIKE '%" . $sValue . "%' OR tec.user_l_name LIKE '%" . $sValue . "%')";
            $data['searchValue'] = $sValue;
        else:
            $whereCon['like']    = "";
            $data['searchValue'] = '';
        endif;

        $whereCon['where'] = "clasec.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
							  AND clasec.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
							  AND clasec.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
							  AND clasec.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";
        $shortField        = 'clasec.class_section_id DESC';

        $this->load->library('pagination');
        $config['base_url']   = $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index';
        $this->session->set_userdata('sectionListAdminData', currentFullUrl());
        $qStringdata          = explode('?', currentFullUrl());
        $config['suffix']     = $qStringdata[1] ? '?' . $qStringdata[1] : '';
        $tblName              = 'class_section as clasec';
        $con                  = '';
        $config['total_rows'] = $this->admin_model->SelectSectionListData('count', $tblName, $whereCon, $shortField, '0', '0');

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

        $data['ALLDATA'] = $this->admin_model->SelectSectionListData('data', $tblName, $whereCon, $shortField, $config['per_page'], $page);

        $this->layouts->set_title('Manage section details');
        $this->layouts->admin_view('sectionlist/index', array(), $data);
    } // END OF FUNCTION

    /* * *********************************************************************
     * * Function name : addeditdata
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for add edit data
     * * Date : 23 JANUARY 2018
     * * ********************************************************************* */
    public function addeditdata($editid = '') {
        $data['error'] = '';

        $classQuery        = "SELECT encrypt_id,class_name FROM sms_classes 
							  WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
							  AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
							  AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
							  AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
							  AND status = 'Y'";
        $data['CLASSDATA'] = $this->common_model->get_data_by_query('multiple', $classQuery);

        $teacherQuery        = "SELECT encrypt_id,user_f_name,user_m_name,user_l_name,user_email FROM sms_users 
								WHERE user_type = 'Teacher' 
								AND franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
								AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
								AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
								AND status = 'A'";
        $data['TEACHERDATA'] = $this->common_model->get_data_by_query('multiple', $teacherQuery);
        if ($editid):
            $this->admin_model->authCheck('admin', 'edit_data');
            $data['EDITDATA'] = $this->common_model->get_data_by_encryptId('class_section', $editid);
        else:
            $this->admin_model->authCheck('admin', 'add_data');
        endif;

        if ($this->input->post('SaveChanges')):
            $error = 'NO';
            $this->form_validation->set_rules('class_id', 'Class name', 'trim|required');
            $this->form_validation->set_rules('class_section_name', 'Section name', 'trim|required');
            $this->form_validation->set_rules('class_section_short_name', 'Section short name', 'trim|required');
            $this->form_validation->set_rules('class_teacher_id', 'Class teacher', 'trim|required');

            if ($this->form_validation->run() && $error == 'NO'):

                $param['class_id']                 = addslashes($this->input->post('class_id'));
                $param['class_section_name']       = addslashes($this->input->post('class_section_name'));
                $param['class_section_short_name'] = addslashes($this->input->post('class_section_short_name'));
                $param['class_teacher_id']         = addslashes($this->input->post('class_teacher_id'));

                if ($this->input->post('CurrentDataID') == ''):
                    $param['franchise_id']  = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                    $param['school_id']     = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                    $param['branch_id']     = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                    $param['board_id']      = $this->session->userdata('SMS_ADMIN_BOARD_ID');
                    $param['creation_date'] = currentDateTime();
                    $param['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                     $param['session_year']	=	CURRENT_SESSION;
                    $param['status']        = 'Y';
                    $alastInsertId          = $this->common_model->add_data('class_section', $param);

                    $Uparam['encrypt_id']       = manojEncript($alastInsertId);
                    $Uwhere['class_section_id'] = $alastInsertId;
                    $this->common_model->edit_data_by_multiple_cond('class_section', $Uparam, $Uwhere);
                    $this->session->set_flashdata('alert_success', lang('addsuccess'));
                else:
                    $TDPROGId             = $this->input->post('CurrentDataID');
                    $param['update_date'] = currentDateTime();
                    $param['updated_by']  = $this->session->userdata('SMS_ADMIN_ID');
                    $this->common_model->edit_data('class_section', $param, $TDPROGId);
                    $this->session->set_flashdata('alert_success', lang('updatesuccess'));
                endif;

                redirect(correctLink('sectionListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
            endif;
        endif;

        $this->layouts->set_title('Edit section details');
        $this->layouts->admin_view('sectionlist/addeditdata', array(), $data);
    } // END OF FUNCTION	

    /* * *********************************************************************
     * * Function name : changestatus
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for change status
     * * Date : 23 JANUARY 2018
     * ********************************************************************** */
    function changestatus($changeStatusId = '', $statusType = '') {
        $this->admin_model->authCheck('admin', 'edit_data');

        $param['status'] = $statusType;
        $this->common_model->edit_data('class_section', $param, $changeStatusId);

        $this->session->set_flashdata('alert_success', lang('statussuccess'));

        redirect(correctLink('sectionListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
    }

    /* * *********************************************************************
     * * Function name : manageperiod
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for manage period data
     * * Date : 22  Nov 2016
     * ********************************************************************** */
    public function manageperiod($sectionid = '') {
        $this->admin_model->authCheck('admin', 'view_data');
        $this->admin_model->get_permission_type($data);
        $data['error'] = '';

        $secQuery            = "SELECT sc.encrypt_id,sc.class_section_name,sc.class_id ,  sms_classes.class_name ,school.admin_name AS school_name ,school.encrypt_id AS school_id  ,     branch.admin_name AS branch_name  ,branch.encrypt_id AS branch_id  ,  sms_branch_boards.branch_board_name ,  sms_branch_boards.encrypt_id AS  board_id,franchise.encrypt_id  AS franchise_id
                                FROM sms_class_section  AS sc 
                                LEFT JOIN `sms_classes` ON sms_classes.encrypt_id =  sc.class_id
                                LEFT JOIN `sms_admin` AS school  ON  school.encrypt_id = sc.school_id
                                LEFT JOIN `sms_admin` AS branch  ON  branch.encrypt_id = sc.branch_id
                                LEFT JOIN `sms_admin` AS franchise  ON  franchise.encrypt_id = sc.branch_id
                                LEFT JOIN `sms_branch_boards`   ON  sms_branch_boards.encrypt_id = sc.board_id
								WHERE  sc.encrypt_id =  '" . $sectionid . "' 
                                AND sc.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
								AND sc.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
								AND sc.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
								AND sc.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
                                                                                 										 AND sc.status = 'Y'";
        $data['sectiondata'] = $this->common_model->get_data_by_query('single', $secQuery);
        
        if(!$data['sectiondata']):
           $this->session->set_flashdata('alert_warning', 'Please select Section first to see timetable');
            redirect(correctLink('sectionListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
        endif;

        $perQuery           = "SELECT * FROM`sms_class_period` WHERE  sms_class_period.class_id =  '" . $data['sectiondata']['class_id'] . "' ";
        $data['perioddata'] = $this->common_model->get_data_by_query('multiple', $perQuery);

        $dayQuery             = "SELECT working_day_name,working_day_short_name,encrypt_id FROM sms_working_days
								WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
								AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
								AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
								AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
								AND status = 'Y'";
        $data['workdaysdata'] = $this->common_model->get_data_by_query('multiple', $dayQuery);

        $assignPeriodQuery = "SELECT pst.* , sms_subject.subject_name , sms_users.user_f_name ,sms_users.user_m_name ,sms_users.user_l_name   FROM sms_period_subject_teacher AS pst
                            LEFT JOIN `sms_subject` ON  sms_subject.encrypt_id = pst.subject_id
                            LEFT JOIN `sms_users` ON  sms_users.encrypt_id = pst.teacher_id
                            WHERE sms_users.user_type  = 'Teacher' 
			                AND pst.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
				            AND pst.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
                            AND pst.class_id = '" . $data['sectiondata']['class_id'] . "'
                            AND pst.section_id = '" . $sectionid . "'
	                        AND pst.status = 'Y'
                            AND sms_subject.status = 'Y'
                            AND sms_users.status = 'A'";
        $allAssigndata     = $this->common_model->get_data_by_query('multiple', $assignPeriodQuery);
    
        if ($allAssigndata) :
            foreach ($allAssigndata as $info):
                $assign                                                             = array('teacher_id' => $info['teacher_id'], 'subject' => $info['subject_name'], 'teacher' => $info['user_f_name'] );
                $assigndata[$info['period_id'] . '_____' . $info['working_day_id']] = $assign;
            endforeach;
        endif;
        $data['Assiperioddata'] = $assigndata;
        
        $this->layouts->set_title('Manage Time Table');
        $this->layouts->admin_view('sectionlist/manageperiod', array(), $data);
    }

    /* * *********************************************************************
     * * Function name : get_period_assign_data
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for get period assign data
     * * Date : 22  Nov 2016
     * ********************************************************************** */
    public function get_period_assign_data() {
        if ($this->input->post('classid') && $this->input->post('sectionid') && $this->input->post('periodid') && $this->input->post('workdayid')):
            $classid   = $this->input->post('classid');
            $sectionid = $this->input->post('sectionid');
            $periodid  = $this->input->post('periodid');
            $workdayid = $this->input->post('workdayid');

            $subjectQuery = "SELECT subject_name , encrypt_id FROM sms_subject
							WHERE branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
							AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
							AND status = 'Y'";
            $data['subjectdata'] = $this->common_model->get_data_by_query('multiple', $subjectQuery);

            $teacherQuery = "SELECT user_f_name, user_l_name,user_m_name , encrypt_id FROM `sms_users`
							WHERE user_type ='Teacher'
							AND  branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
							AND status = 'A'";

            $data['teacherdata'] = $this->common_model->get_data_by_query('multiple', $teacherQuery);
            $periodQuery         = "SELECT subject_id, teacher_id, encrypt_id FROM `sms_period_subject_teacher`
									WHERE branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
                                    AND  class_id = '" . $classid . "'
                                    AND  section_id = '" . $sectionid . "'
                                    AND  period_id = '" . $periodid . "'
                                    AND  working_day_id = '" . $workdayid . "'
									AND status = 'Y'";
            $data['perioddata'] = $this->common_model->get_data_by_query('single', $periodQuery);

            $data['classid']   = $classid;
            $data['sectionid'] = $sectionid;
            $data['periodid']  = $periodid;
            $data['workdayid'] = $workdayid;
            $this->load->view('sectionlist/get_assign_data', $data);
        endif;
    }

    /* * *********************************************************************
     * * Function name : add_period_assign_data
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for add period assign data
     * * Date : 22  Nov 2016
     * ********************************************************************** */
    public function add_period_assign_data() {
        if ($this->input->post('class_id') && $this->input->post('section_id') && $this->input->post('period_id') && $this->input->post('workday_id') && $this->input->post('subject_id') && $this->input->post('workday_id')):
            $SESSIONQuery = "SELECT encrypt_id FROM `sms_session_month`
							WHERE branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
                            AND  board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
                            AND status = 'Y'";
            $sessiondata = $this->common_model->get_data_by_query('single', $SESSIONQuery);

            $param['franchise_id']   = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
            $param['school_id']      = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
            $param['branch_id']      = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
            $param['board_id']       = $this->session->userdata('SMS_ADMIN_BOARD_ID');
            $param['class_id']       = $this->input->post('class_id');
            $param['section_id']     = $this->input->post('section_id');
            $param['period_id']      = $this->input->post('period_id');
            $param['working_day_id'] = $this->input->post('workday_id');
            $param['subject_id']     = $this->input->post('subject_id');
            $param['session_id']     = $sessiondata['encrypt_id'];
            $param['current_year']   = date('Y');
            $param['teacher_id']     = $this->input->post('teacher_id');
            $param['status'] = 'Y';

            if ($this->input->post('CurrentDataID') == ''):
                $param['creation_date'] = currentDateTime();
                $param['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                 $param['session_year']			=	CURRENT_SESSION;
                $alastInsertId = $this->common_model->add_data('sms_period_subject_teacher', $param);

                $Uparam['encrypt_id'] = manojEncript($alastInsertId);
                $Uwhere['period_subject_teacher_id'] = $alastInsertId;
                $this->common_model->edit_data_by_multiple_cond('sms_period_subject_teacher', $Uparam, $Uwhere);
                $this->session->set_flashdata('alert_success', lang('addsuccess'));
            else:
                $pstId					=	$this->input->post('CurrentDataID');
				$param['update_date']		=	currentDateTime();
				$param['updated_by']		=	$this->session->userdata('SMS_ADMIN_ID');
				$this->common_model->edit_data('sms_period_subject_teacher',$param,$pstId);
				$this->session->set_flashdata('alert_success',lang('updatesuccess'));
            endif;
            echo 'SUCCESS';
            die;
        else:
            $this->session->set_flashdata('alert_error', 'Syatem generate issue. Please try later.');
            echo 'ERROR';
            die;
        endif;
    }

    /* * *********************************************************************
     * * Function name : get_teacher_by_subject
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for get teacher by subject.
     * * Date : 25  Nov 2016
     * ********************************************************************** */
    function get_teacher_by_subject() {
        $html = '<option value="">Select Teacher</option>';
        if ($this->input->post('subjectid')):
            $branchid  = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
            $subjectid = $this->input->post('subjectid');
            $teacherid = $this->input->post('teacherid');

            $teacherQuery = "SELECT tech.user_f_name, tech.user_l_name,tech.user_m_name , tech.encrypt_id FROM `sms_teacher_subject`
                            LEFT JOIN `sms_users` AS tech ON tech.encrypt_id = sms_teacher_subject.teacher_id 
							WHERE tech.user_type ='Teacher'
							AND tech.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
							AND sms_teacher_subject.subject_id = '" . $subjectid . "'
                            AND sms_teacher_subject.status = 'Y'
							AND tech.status = 'A'";
            $techdata = $this->common_model->get_data_by_query('multiple', $teacherQuery);
            if ($techdata <> ""):
                foreach ($techdata as $techinfo):
                    if ($teacherid == $techinfo['encrypt_id']): $select = 'selected="selected"';
                    else: $select = '';
                    endif;
                    $html .= '<option value="' . $techinfo['encrypt_id'] . '" ' . $select . '>' . stripslashes($techinfo['user_f_name'] . ' ' . $techinfo['user_m_name'] . ' ' . $techinfo['user_l_name']) . '</option>';
                endforeach;
            endif;
        endif;
        echo $html;
        die;
    }
/***********************************************************************
	** Function name : displaytimetablepdf
	** Developed By : Jitendra Chaudhari
	** Purpose  : This function used to display Timetable PDF
	** Date : 25 NOV 2017
	************************************************************************/
	public function displaytimetablepdf($sectionid='') 
	{
		$this->load->library('Mpdf');
		$this->admin_model->authCheck('admin', 'view_data');
		$data['error'] 				= 	'';
        $secQuery            = "SELECT sc.encrypt_id,sc.class_section_name,sc.class_id ,  sms_classes.class_name ,school.admin_name AS school_name ,school.encrypt_id AS school_id  ,     branch.admin_name AS branch_name  ,branch.encrypt_id AS branch_id  ,  sms_branch_boards.branch_board_name ,  sms_branch_boards.encrypt_id AS  board_id,franchise.encrypt_id  AS franchise_id
                                FROM sms_class_section  AS sc 
                                LEFT JOIN `sms_classes` ON sms_classes.encrypt_id =  sc.class_id
                                LEFT JOIN `sms_admin` AS school  ON  school.encrypt_id = sc.school_id
                                LEFT JOIN `sms_admin` AS branch  ON  branch.encrypt_id = sc.branch_id
                                LEFT JOIN `sms_admin` AS franchise  ON  franchise.encrypt_id = sc.branch_id
                                LEFT JOIN `sms_branch_boards`   ON  sms_branch_boards.encrypt_id = sc.board_id
								WHERE  sc.encrypt_id =  '" . $sectionid . "' 
                                AND sc.status = 'Y'";
        $data['sectiondata'] = $this->common_model->get_data_by_query('single', $secQuery);

        $perQuery           = "SELECT * FROM`sms_class_period` WHERE  sms_class_period.class_id =  '" . $data['sectiondata']['class_id'] . "' ";
        $data['perioddata'] = $this->common_model->get_data_by_query('multiple', $perQuery);

        $dayQuery             = "SELECT working_day_name,working_day_short_name,encrypt_id FROM sms_working_days
								WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
								AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
								AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
								AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
								AND status = 'Y'";
        $data['workdaysdata'] = $this->common_model->get_data_by_query('multiple', $dayQuery);

        $assignPeriodQuery = "SELECT pst.* , sms_subject.subject_name , sms_users.user_f_name ,sms_users.user_m_name ,sms_users.user_l_name   FROM sms_period_subject_teacher AS pst
                            LEFT JOIN `sms_subject` ON  sms_subject.encrypt_id = pst.subject_id
                            LEFT JOIN `sms_users` ON  sms_users.encrypt_id = pst.teacher_id
                            WHERE sms_users.user_type  = 'Teacher' 
			                AND pst.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
				            AND pst.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
                            AND pst.class_id = '" . $data['sectiondata']['class_id'] . "'
                            AND pst.section_id = '" . $sectionid . "'
	                        AND pst.status = 'Y'
                            AND sms_subject.status = 'Y'
                            AND sms_users.status = 'A'";    
        $allAssigndata     = $this->common_model->get_data_by_query('multiple', $assignPeriodQuery);
        if ($allAssigndata) :
            foreach ($allAssigndata as $info):
                $assign                                                             = array('teacher_id' => $info['teacher_id'], 'subject' => $info['subject_name'], 'teacher' => $info['user_f_name'] );
                $assigndata[$info['period_id'] . '_____' . $info['working_day_id']] = $assign;
            endforeach;
        endif;
        $data['Assiperioddata'] = $assigndata;
		$classSection = $data['sectiondata']['class_name'].'_'.$data['sectiondata']['class_section_name'];
                     
		$this->layouts->set_title('Display Class Time Table');
		$this->layouts->admin_view('sectionlist/displaytimetablepdf',array(),$data);
		$this->DownlodeTTpdf($classSection.".pdf");
	}

	/***********************************************************************
	** Function name: DownlodeTTpdf
	** Developed By: jitendra Chaudhari
	** Purpose: This function used to download Time Table as pdf
	** Date: 25 NOV 2017
	************************************************************************/
	public function DownlodeTTpdf($file='')
	{
		header('Content-Description: File Transfer');
		// We'll be outputting a PDF
		header('Content-type: application/pdf');
		// It will be called downloaded.pdf
		header('Content-Disposition: attachment; filename="'.$file.'"');
		// The PDF source is in original.pdf
		readfile($this->config->item("root_path")."assets/downloadpdf/".$file);
		
		@unlink($this->config->item("root_path")."assets/downloadpdf/".$file);
		exit;
	}
}
