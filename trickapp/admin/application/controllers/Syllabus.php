<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Syllabus extends CI_Controller {

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
     * * Function name : Sylabuslist
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for Teacherlist
     * * Date : 01 FEBRUARY 2018
	 * * Updated By : Ashish UMrao
     * * Updated Date : 12 JUNE 2019
     * * ********************************************************************* */
    public function index() {
        $this->admin_model->authCheck('admin', 'view_data');
        $this->admin_model->get_permission_type($data);
        $data['error']     = '';
        $data['classid']   = $this->input->get('class_id');
       // $data['sectionid'] = $this->input->get('section_id');

        $subQuery          = "SELECT encrypt_id, class_name FROM sms_classes
						 	WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
							AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
							AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
							AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";
        //echo $subQuery; die;
        $data['CLASSDATA'] = $this->common_model->get_data_by_query('multiple', $subQuery);
        if ($this->input->get('searchValue')):
            $sValue              = $this->input->get('searchValue');
			$whereCon['like']    = "(cls.class_name LIKE '%".$sValue."%' OR sub.subject_name LIKE '%".$sValue."%')"; 
            $data['searchValue'] = $sValue;
        else:
            $whereCon['like']    = "";
            $data['searchValue'] = '';
        endif;
        if ($data['classid']):
            $whereCon['where'] = "  csyllabus.class_id = '" . $data['classid'] . "' AND csyllabus.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
								 AND csyllabus.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
								 AND csyllabus.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
								 AND csyllabus.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";
        else:
            $whereCon['where'] = "   csyllabus.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
								 AND csyllabus.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
								 AND csyllabus.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
								 AND csyllabus.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";

        endif;
        $shortField = 'csyllabus.class_syllabus_id ASC';

        $this->load->library('pagination');
        $config['base_url']   = $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index';
        $this->session->set_userdata('syllabusListAdminData', currentFullUrl());
        $qStringdata          = explode('?', currentFullUrl());
        $config['suffix']     = $qStringdata[1] ? '?' . $qStringdata[1] : '';
        $tblName              = 'class_syllabus as csyllabus';
        $con                  = '';
        $config['total_rows'] = $this->admin_model->SelectStudentsyallbusListData('count', $tblName, $whereCon, $shortField, '0', '0');

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
        $data['ALLDATA'] = $this->admin_model->SelectStudentsyallbusListData('data', $tblName, $whereCon, $shortField, $config['per_page'], $page);
        $this->layouts->set_title('Manage Syllabus details');
        $this->layouts->admin_view('syllabus/index', array(), $data);
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
			//echo $subQuery; die;
			$data['CLASSDATA']      = $this->common_model->get_data_by_query('multiple', $subQuery);
        if ($editid): 
            $this->admin_model->authCheck('admin', 'edit_data');
            $stuQuery       = 		"SELECT csyllabus.* FROM sms_class_syllabus as csyllabus
									LEFT JOIN sms_subject as sub ON 'csyllabus.subject_id=sub.encrypt_id'
									LEFT JOIN sms_classes as cls ON 'csyllabus.class_id=cls.encrypt_id'
									WHERE csyllabus.encrypt_id = '" . $editid . "'";
			$data['EDITDATA'] = $this->common_model->get_data_by_query('single', $stuQuery);
        else:
            $this->admin_model->authCheck('admin', 'add_data');
        endif;

        if ($this->input->post('SaveChanges')):  //echo "<pre>"; print_r($_POST); die; 
            $error = 'NO';
            $this->form_validation->set_rules('class_id', 'Class name', 'trim');
            $this->form_validation->set_rules('subject_id', 'Subject name', 'trim');
            $this->form_validation->set_rules('syllabus', 'Message name', 'trim|required');
            if ($this->form_validation->run() && $error == 'NO'):    
                //////////////	STUDENT CLASS SECTION RELATION 	///////////////////////
                if($data['EDITDATA'] == ''):
                    $SParam['class_id']        = addslashes($this->input->post('class_id'));
                    $SParam['subject_id']      = addslashes($this->input->post('subject_id'));
                endif;
                $SParam['syllabus']         = addslashes($this->input->post('syllabus'));                
           
                if ($this->input->post('CurrentDataID') == ''):
                    //////////////	STUDENT SECTION 	///////////////////////
                    $SParam['creation_date'] = currentDateTime();
                    $SParam['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                    $SParam['status']        = 'A';
                    $SParam['session_year']	 =	CURRENT_SESSION;
                   //
                    $stulastInsertId         = $this->common_model->add_data('sms_class_syllabus', $SParam);
                   //print  $stulastInsertId ; die;
                    $SCSUparam['encrypt_id']       = manojEncript($stulastInsertId);
                    $SCSUparam['franchise_id']       = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                    $SCSUparam['school_id']          = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                    $SCSUparam['branch_id']          = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                    $SCSUparam['board_id']           = $this->session->userdata('SMS_ADMIN_BOARD_ID');
                    //print "<pre>"; print_r($SCSUparam); die;
					$SCSUwhere['class_syllabus_id'] = $stulastInsertId;
                    $this->common_model->edit_data_by_multiple_cond('sms_class_syllabus', $SCSUparam, $SCSUwhere);
                    $this->session->set_flashdata('alert_success', lang('addsuccess'));
                else:
                    $studentUniqueId = $this->input->post('CurrentDataID');

                    //////////////	STUDENT SCHOOL BRANCH RELATION 	///////////////////////
                    $SParam['update_date']          = currentDateTime();
                    $SParam['updated_by']           = $this->session->userdata('SMS_ADMIN_ID');
                    $SPUwhere['class_syllabus_id'] = $studentUniqueId;
                    $this->common_model->edit_data_by_multiple_cond('sms_class_syllabus', $SParam, $SPUwhere);

                    //////////////	STUDENT SCHOOL BRANCH RELATION 	///////////////////////
                    $SBParam['update_date']         = currentDateTime();
                    $SBParam['updated_by']          = $this->session->userdata('SMS_ADMIN_ID');
                    $SBUwhere['franchise_id']       = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                    $SBUwhere['school_id']          = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                    $SBUwhere['branch_id']          = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                    $SBUwhere['board_id']           = $this->session->userdata('SMS_ADMIN_BOARD_ID');
                    $SBUwhere['class_syllabus_id'] = $studentUniqueId;
                    $this->common_model->edit_data_by_multiple_cond('sms_class_syllabus', $SBParam, $SBUwhere);

                    //////////////	STUDENT CLASS SECTION RELATION 	///////////////////////
                    $SCSParam['creation_date'] = currentDateTime();
                    $SCSParam['created_by']    = $this->session->userdata('SMS_ADMIN_ID');

                    $SCSUwhere['franchise_id']       = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                    $SCSUwhere['school_id']          = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                    $SCSUwhere['branch_id']          = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                    $SCSUwhere['board_id']           = $this->session->userdata('SMS_ADMIN_BOARD_ID');
                    $SCSUwhere['class_syllabus_id'] = $studentUniqueId;
                    $this->common_model->edit_data_by_multiple_cond('sms_class_syllabus', $SCSParam, $SCSUwhere);

                    $this->session->set_flashdata('alert_success', lang('updatesuccess'));
                endif;
                //redirect($this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/addeditdata/' . $studentUniqueId);
            redirect(correctLink('syllabusListAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));

			endif;
        endif;

        $this->layouts->set_title('Edit student details');
        $this->layouts->admin_view('syllabus/addeditdata', array(), $data);
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
        $where['encrypt_id'] = $changeStatusId;
		//print_r($where['encrypt_id']); die;
        $this->common_model->edit_data_by_multiple_cond('sms_class_syllabus', $param, $where);

        if ($statusType == "A"):
            $this->session->set_flashdata('alert_success', lang('statussuccess'));
        elseif ($statusType == "I"):
            $this->session->set_flashdata('alert_success', lang('statussuccess'));
        elseif ($statusType == "B"):
            $this->session->set_flashdata('alert_success', lang('statussuccess'));
        elseif ($statusType == "D"):
            $this->session->set_flashdata('alert_success', lang('deletesuccess'));
        endif;

        redirect(correctLink('syllabusListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
    } // END OF FUNCTION	

    /* * *********************************************************************
     * * Function name : get_view_data
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for get view data.
     * * Date : 01 FEBRUARY 2018
     * ********************************************************************** */

    public function getSubjectdata(){ 
        $class_id = $this->input->post('class_id');
        //$section_id = $this->input->post('section_id');
        
        $subQuery         = "SELECT sub.subject_id,sub.encrypt_id,sub.subject_name 
                            FROM sms_class_subject sc 
							LEFT JOIN sms_subject sub on sc.subject_id=sub.encrypt_id 
                            WHERE sc.franchise_id='" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
                            AND sc.school_id='" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
                            AND sc.branch_id='" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
                            AND sc.board_id='" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "' 
                            AND sc.class_id='".$class_id."'";
		//echo $subQuery; die;
		$data['SUBJECTDATA'] = $this->common_model->get_data_by_query('multiple', $subQuery);
		foreach($data['SUBJECTDATA'] as $row){      
            echo "<option value='".$row['encrypt_id']."'>".$row['subject_name']."</option>";
        }
}		
}