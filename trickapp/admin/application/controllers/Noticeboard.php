<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Noticeboard extends CI_Controller {

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

        if ($this->input->get('searchValue')):
            $sValue              = $this->input->get('searchValue');
            $whereCon['like']    = "( snoboard.visibility LIKE '%" . $sValue . "%')";
            $data['searchValue'] = $sValue;
        else:
            $whereCon['like']    = "";
            $data['searchValue'] = '';
        endif;
        
        $shortField = 'snoboard.notice_board_id ASC';

        $this->load->library('pagination');
        $config['base_url']   = $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index';
        $this->session->set_userdata('noticeboardListAdminData', currentFullUrl());
        $qStringdata          = explode('?', currentFullUrl());
        $config['suffix']     = $qStringdata[1] ? '?' . $qStringdata[1] : '';
        $tblName              = 'sms_notice_board as snoboard';
        $con                  = '';
        $config['total_rows'] = $this->admin_model->SelectnoticeboardListData('count', $tblName, $whereCon, $shortField, '0', '0');

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

        $data['ALLDATA'] = $this->admin_model->SelectnoticeboardListData('data', $tblName, $whereCon, $shortField, $config['per_page'], $page);
       // echo ""; print_r($data['ALLDATA']); die;
        $this->layouts->set_title('Manage notice Board');
        $this->layouts->admin_view('noticeboard/index', array(), $data);
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

        if ($this->input->post('SaveChanges')): //echo '<pre>'; print_r($_POST); die;   
            $error = 'NO';
            $this->form_validation->set_rules('class_id', 'Class name', 'trim|required');
            $this->form_validation->set_rules('section_id', 'Section name', 'trim|required');
            $this->form_validation->set_rules('visibility', 'Visibility name', 'trim|required');
            $this->form_validation->set_rules('notice_title', 'Notice Title', 'trim|required');
            $this->form_validation->set_rules('message', 'Message name', 'trim|required');
           

            if ($this->form_validation->run() && $error == 'NO'):

                //////////////	STUDENT CLASS SECTION RELATION 	///////////////////////
                $SParam['class_id']        = addslashes($this->input->post('class_id'));
                $SParam['section_id']      = addslashes($this->input->post('section_id'));
                $SParam['visibility']      = addslashes($this->input->post('visibility'));
                $SParam['notice_title']    = addslashes($this->input->post('notice_title'));       
                $SParam['message']         = addslashes($this->input->post('message'));                
                $SParam['image']           = addslashes($this->input->post('uploadimage0'));
           
                if ($this->input->post('CurrentDataID') == ''):
                    //////////////	STUDENT SECTION 	///////////////////////
                    $SParam['creation_date'] = currentDateTime();
                    $SParam['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                    $SParam['status']        = 'Y';
                    $SParam['session_year']	 =	CURRENT_SESSION;
                //   print "<pre>"; print_r($SParam); die;
                    $stulastInsertId         = $this->common_model->add_data('sms_notice_board', $SParam);
                   // print  $stulastInsertId ; die;
                    $SCSUparam['encrypt_id']       = manojEncript($stuclsLastInsertId);
                    $SCSUparam['franchise_id']       = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                    $SCSUparam['school_id']          = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                    $SCSUparam['branch_id']          = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                    $SCSUparam['board_id']           = $this->session->userdata('SMS_ADMIN_BOARD_ID');
                    $SCSUwhere['notice_board_id'] = $stulastInsertId;

                        $this->common_model->edit_data_by_multiple_cond('sms_notice_board', $SCSUparam, $SCSUwhere);
                    $this->session->set_flashdata('alert_success', lang('addsuccess'));
                else:
                    $studentUniqueId = $this->input->post('CurrentDataID');

                    //////////////	STUDENT SCHOOL BRANCH RELATION 	///////////////////////
                    $SParam['update_date']          = currentDateTime();
                    $SParam['updated_by']           = $this->session->userdata('SMS_ADMIN_ID');
                    $SPUwhere['student_qunique_id'] = $studentUniqueId;
                    $this->common_model->edit_data_by_multiple_cond('sms_notice_board', $SParam, $SPUwhere);
                    $this->session->set_flashdata('alert_success', lang('updatesuccess'));
                endif;
                //redirect($this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/addeditdata/' . $studentUniqueId);
                redirect(correctLink('noticeboardListAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));

            endif;
        endif;

        $this->layouts->set_title('Edit noticeboard details');
        $this->layouts->admin_view('noticeboard/addeditdata', array(), $data);
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

        redirect(correctLink('noticeboardListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
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
           // print $imagefolder; die;
            $ext         = pathinfo($file_name);
            $newfilename = time() . '.' . $ext['extension'];
            $this->load->library("upload_crop_img");
            $this->load->library('user_agent');
            // add parent image in teacherImage foder
            if (strpos($this->agent->referrer(), 'addeditparents')):
                $return_file_name = $this->upload_crop_img->_upload_image($file_name, $tmp_name, 'NoticeBoardImage', $newfilename, $imagefolder);
            else:
                //add student image to studentimage folder
                $return_file_name = $this->upload_crop_img->_upload_image($file_name, $tmp_name, 'NoticeBoardImage', $newfilename, $imagefolder);
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
}