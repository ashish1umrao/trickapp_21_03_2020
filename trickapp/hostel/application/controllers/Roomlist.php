<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Roomlist extends CI_Controller {

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

    /*     * *********************************************************************
     * * Function name : roomlist
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for roomlist
     * * Date : 16 JANUARY 2018
     * * ********************************************************************* */

    public function index() {
        $this->admin_model->authCheck('admin', 'view_data');
        $this->admin_model->get_permission_type($data);
        $data['error'] = '';

        if ($this->input->get('searchValue')):
            $sValue              = $this->input->get('searchValue'); 
            $whereCon['like']    = "(r.room_name LIKE '%" . $sValue . "%' OR r.location LIKE '%" . $sValue . "%' OR rt.room_type_name LIKE '%" . $sValue . "%'
			                                      )";
            $data['searchValue'] = $sValue;
        else:
            $whereCon['like']    = "";
            $data['searchValue'] = '';
        endif;

        $whereCon['where'] = "r.franchise_id = '" . $this->session->userdata('SMS_HOSTEL_ADMIN_FRANCHISE_ID') . "'
												 AND r.school_id = '" . $this->session->userdata('SMS_HOSTEL_ADMIN_SCHOOL_ID') . "' 
												 AND r.branch_id = '" . $this->session->userdata('SMS_HOSTEL_ADMIN_BRANCH_ID') . "'
                                                                                                      AND r.hostel_id = '" . $this->session->userdata('SMS_HOSTEL_ADMIN_HOSTEL_ID') . "'
												 ";
        $shortField        = 'r.room_name ASC';

        $this->load->library('pagination');
        $config['base_url']   = $this->session->userdata('SMS_HOSTEL_ADMIN_PATH') . $this->router->fetch_class() . '/index/';
        $this->session->set_userdata('roomListAdminData', currentFullUrl());
        $qStringdata          = explode('?', currentFullUrl());
     
        $config['suffix']     = $qStringdata[1] ? '?' . $qStringdata[1] : ''; 
      
        $tblName              = 'hostel_room as r';
        $con                  = '';
        $config['total_rows'] = $this->admin_model->SelectroomListData('count', $tblName, $whereCon, $shortField, '0', '0');
      
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

        $config['uri_segment'] = 5;
        $this->pagination->initialize($config);

        if ($this->uri->segment(5)):
            $page = $this->uri->segment(5);
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
        
        
        
        
        
  

        $data['ALLDATA'] = $this->admin_model->SelectroomListData('data', $tblName, $whereCon, $shortField, $config['per_page'], $page);
     
        $this->layouts->set_title('Manage book details');
        $this->layouts->admin_view('roomlist/index', array(), $data);
    }

// END OF FUNCTION

    /*     * *********************************************************************
     * * Function name : addeditdata
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for add edit data
     * * Date : 16 JANUARY 2018
     * * ********************************************************************* */

    public function addeditdata($editid = '') {
        $data['error'] = '';

        
                
              $subQuery          = "SELECT type.*  FROM `sms_hostel_room_type`  AS type   where
type.franchise_id = '" . $this->session->userdata('SMS_HOSTEL_ADMIN_FRANCHISE_ID') . "'
												 AND type.school_id = '" . $this->session->userdata('SMS_HOSTEL_ADMIN_SCHOOL_ID') . "' 
												 AND type.branch_id = '" . $this->session->userdata('SMS_HOSTEL_ADMIN_BRANCH_ID') . "'
                                                                                                      AND type.hostel_id = '" . $this->session->userdata('SMS_HOSTEL_ADMIN_HOSTEL_ID') . "'
                                                                                                     AND type.status='Y'
												 ";
        $data['ROOMTYPEDATA'] = $this->common_model->get_data_by_query('multiple', $subQuery);
        
        
        

        if ($editid):
            $this->admin_model->authCheck('admin', 'edit_data');
            $data['EDITDATA'] = $this->common_model->get_data_by_encryptId('hostel_room', $editid);
        else:
            $this->admin_model->authCheck('admin', 'add_data');
        endif;

        if ($this->input->post('SaveChanges')):


            $error = 'NO';
            $this->form_validation->set_rules('room_name', 'Room No.', 'trim');
            $this->form_validation->set_rules('location', 'Room  Location', 'trim');
       
            $this->form_validation->set_rules('room_type_id', 'book type', 'trim|required');
               $this->form_validation->set_rules('description', ' Room Description', 'trim');
         



            if ($this->form_validation->run() && $error == 'NO'):

                $param['room_name']   = addslashes($this->input->post('room_name'));
                $param['location'] = addslashes($this->input->post('location'));
             
                  $param['room_type_id']  = addslashes($this->input->post('room_type_id'));
                  

                $param['description'] = addslashes($this->input->post('description'));
               

                if ($this->input->post('CurrentDataID') == ''):
                    $param['franchise_id'] = $this->session->userdata('SMS_HOSTEL_ADMIN_FRANCHISE_ID');
                    $param['school_id']    = $this->session->userdata('SMS_HOSTEL_ADMIN_SCHOOL_ID');
                    $param['branch_id']    = $this->session->userdata('SMS_HOSTEL_ADMIN_BRANCH_ID');
                     $param['hostel_id']    = $this->session->userdata('SMS_HOSTEL_ADMIN_HOSTEL_ID');

                    $param['creation_date'] = currentDateTime();
                    $param['created_by']    = $this->session->userdata('SMS_HOSTEL_ADMIN_ID');
                    $param['status']        = 'Y';
                       $param['session_year']			=	CURRENT_SESSION;
                    $alastInsertId          = $this->common_model->add_data('hostel_room', $param);
                    $Uparam['encrypt_id']   = manojEncript($alastInsertId);
                    $Uwhere['room_id']      = $alastInsertId;
                    $bookaddId              = $Uparam['encrypt_id'];
                    $this->common_model->edit_data_by_multiple_cond('hostel_room', $Uparam, $Uwhere);
                    $this->session->set_flashdata('alert_success', lang('addsuccess'));
                else:
                  
                    $param['update_date'] = currentDateTime();
                    $param['updated_by']  = $this->session->userdata('SMS_HOSTEL_ADMIN_ID');
                    $this->common_model->edit_data('hostel_room', $param, $this->input->post('CurrentDataID'));
                    
                    $this->session->set_flashdata('alert_success', lang('updatesuccess'));
                endif;




                redirect(correctLink('roomListAdminData', $this->session->userdata('SMS_HOSTEL_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
            endif;
        endif;

        $this->layouts->set_title('Edit room details');
        $this->layouts->admin_view('roomlist/addeditdata', array(), $data);
    }
 /*     * *********************************************************************
     * * Function name : addeditdata
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for add edit data
     * * Date : 16 JANUARY 2018
     * * ********************************************************************* */

    public function allocate($editid = '') {
        $data['error'] = '';

        
                
             
     
        

        if ($editid):
            $this->admin_model->authCheck('admin', 'edit_data');
            $data['EDITDATA'] = $this->common_model->get_data_by_encryptId('hostel_room', $editid);
           
        else:
            $this->admin_model->authCheck('admin', 'add_data');
        endif;
 
     
        if ($this->input->post('SaveChanges')):
        

            $error = 'NO';
      

            $error = 'NO';
          $this->form_validation->set_rules('hosteler_type', 'Hosteler Type', 'trim|required');
            $this->form_validation->set_rules('hosteler_id', 'Hosteler', 'trim|required');
                 
            $this->form_validation->set_rules('startdate', 'Start Date', 'trim|required');
               $this->form_validation->set_rules('enddate', ' End Date', 'trim');
          $this->form_validation->set_rules('searchtext', 'Hosteler', 'trim|required');



            if ($this->form_validation->run() && $error == 'NO'):
                $param['hosteler_type']   = addslashes($this->input->post('hosteler_type'));
                $param['hosteler_id'] = addslashes($this->input->post('hosteler_id'));
             
                  $param['room_id']  =  $data['EDITDATA']['encrypt_id'] ;
                  

              $param['startdate']		= 	DDMMYYtoYYMMDD($this->input->post('startdate'));
        $param['enddate']		= 	DDMMYYtoYYMMDD($this->input->post('enddate'));
               
                 

                    $param['franchise_id'] = $this->session->userdata('SMS_HOSTEL_ADMIN_FRANCHISE_ID');
                    $param['school_id']    = $this->session->userdata('SMS_HOSTEL_ADMIN_SCHOOL_ID');
                    $param['branch_id']    = $this->session->userdata('SMS_HOSTEL_ADMIN_BRANCH_ID');
                     $param['hostel_id']    = $this->session->userdata('SMS_HOSTEL_ADMIN_HOSTEL_ID');

                    $param['creation_date'] = currentDateTime();
                    $param['created_by']    = $this->session->userdata('SMS_HOSTEL_ADMIN_ID');
                    $param['status']        = 'Y';
                       $param['session_year']			=	CURRENT_SESSION;
                    $alastInsertId          = $this->common_model->add_data('hostel_room_allocate', $param);
                    $Uparam['encrypt_id']   = manojEncript($alastInsertId);
                    $Uwhere['room_allocate_id']      = $alastInsertId;
                    $bookaddId              = $Uparam['encrypt_id'];
                    $this->common_model->edit_data_by_multiple_cond('hostel_room_allocate', $Uparam, $Uwhere);
                    $this->session->set_flashdata('alert_success', lang('addsuccess'));
             


                redirect(correctLink('roomListAdminData', $this->session->userdata('SMS_HOSTEL_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
            endif;
        endif;

        $this->layouts->set_title('Room allocation details');
        $this->layouts->admin_view('roomlist/allocate', array(), $data);
    }

// END OF FUNCTION	

    /*     * *********************************************************************
     * * Function name : changestatus
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for change status
     * * Date : 16 JANUARY 2018
     * ********************************************************************** */

    function changestatus($changeStatusId = '', $statusType = '') {
        $this->admin_model->authCheck('admin', 'edit_data');

        $param['status'] = $statusType;
        $this->common_model->edit_data('hostel_room', $param, $changeStatusId);

        $this->session->set_flashdata('alert_success', lang('statussuccess'));

        redirect(correctLink('roomListAdminData', $this->session->userdata('SMS_HOSTEL_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
    }

   
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
         
    }

   
    
    
    /***********************************************************************
	** Function name : get_view_data
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get view data.
	** Date : 15 JANUARY 2018
	************************************************************************/
	function get_view_data()
	{           
		$html					=	'';
		if($this->input->post('viewid')):
			$viewId				=	$this->input->post('viewid'); 
			 
             $subQuery          = "SELECT hosteler_id , hosteler_type FROM `sms_hostel_room_allocate` WHERE  room_id =  '" . $viewId . "' AND STATUS = 'Y'";
      $hosteler_data = $this->common_model->get_data_by_query('multiple', $subQuery);
        
        
           $viewData =$this->admin_model->SelectroomviewData($viewId );
           
        $Details= $this->admin_model->StudentDetails($editid);
        
        
      
                              		$html			.=	'<table class="table border-none">
								  <tbody>
								    <tr>
									 
									</tr>
									<tr>
                                                                         <td align="left" width="20%"><strong>Room No.</strong></td>
									  <td align="left" width="30%">'.stripslashes($viewData['room_name']).'</td>
                                                                               <td align="left" width="20%"><strong>Room Type</strong></td>
									  <td align="left" width="30%">'.stripslashes($viewData['room_type_name']).' person</td>
									 
									 
									</tr>
									
									<tr>
                                                                              <td align="left" width="20%"><strong>Room Capacity</strong></td>
									  <td align="left" width="30%">'.stripslashes($viewData['room_capacity']).' person</td>
								 <td align="left" width="20%"><strong>Bed Avabiility</strong></td>
									  <td align="left" width="30%">'.stripslashes($viewData['bed_avabiility']).'</td>
									 
									</tr>
									';
                                        if($hosteler_data):
                                            $i = 1 ;
                                        foreach($hosteler_data as $hd):
                                         
                                            if($hd['hosteler_type'] == 'Student'):
                                                      $Studentdata =  $this->admin_model->StudentDetails($hd['hosteler_id']);
                                                                            $html			.=           ' 
                                                                                <tr><td colspan="4">
										  <table class="table border-none">
											<thead>
											<tr>
											  <th align="left" colspan="4"><strong>Hosteler  '.$id.'</strong></th>
											</tr>
											</thead>
											<tbody>
												<tr>
												  <td align="left" width="20%"><strong>Student Name</strong></td>
												  <td align="left" width="30%">'.$Studentdata['student_f_name'].' '.$Studentdata['student_m_name'].' '.$Studentdata['student_l_name'].'
												  <td align="left" width="20%"><strong>Father Name</strong></td>
												  <td align="left" width="30%">'.$Studentdata['user_f_name'].' '.$Studentdata['user_m_name'].' '.$Studentdata['user_l_name'].'</td>
												</tr>
												<tr>
												  <td align="left" width="20%"><strong>Class</strong></td>
												  <td align="left" width="30%">'.stripslashes($Studentdata['class_name']).'</td>
												  <td align="left" width="20%"><strong>Section</strong></td>
												  <td align="left" width="30%">'.stripslashes(v['class_section_name']).'</td>
												</tr>
												<tr>
												  <td align="left" width="20%"><strong>Profile picture</strong></td>
												  <td align="left" width="30%"><img src="'.stripslashes($Studentdata['student_image']).'" width="100" border="0" alt="" /></td>
												  <td align="left" width="20%">&nbsp;</td>
									  			  <td align="left" width="30%">&nbsp;</td>
												</tr>
											</tbody>
											</table>
										</td> 
                                                                                </tr>';
                                                
                                           
                                       else:
                                          $Staffdata =  $this->admin_model->staffDetails($hd['hosteler_id']);
                                       
                                                 $html			.=           '    <tr><td colspan="4">
										  <table class="table border-none">
											<thead>
											<tr>
											  <th align="left" colspan="4"><strong>Hosteler  '.$id.'</strong></th>
											</tr>
											</thead>
											<tbody>
												<tr>
												  <td align="left" width="20%"><strong>'.$Staffdata['user_type'].' Name</strong></td>
												  <td align="left" width="30%">'.$Staffdata['user_f_name'].' '.$Staffdata['user_m_name'].' '.$Staffdata['user_l_name'].'</td>
												  <td align="left" width="20%"><strong>Email</strong></td>
												  <td align="left" width="30%">'.stripslashes($Staffdata['user_email']).'</td>
												</tr>
												<tr>
												  <td align="left" width="20%"><strong>Mobile</strong></td>
												  <td align="left" width="30%">'.stripslashes($Staffdata['user_phone']).'</td>
												  <td align="left" width="20%"><strong>Employee Id</strong></td>
												  <td align="left" width="30%">'.stripslashes($Staffdata['employee_id']).'</td>
												</tr>
												<tr>
												  <td align="left" width="20%"><strong>Profile picture</strong></td>
												  <td align="left" width="30%"><img src="'.stripslashes($Staffdata['user_pic']).'" width="100" border="0" alt="" /></td>
												  <td align="left" width="20%">&nbsp;</td>
									  			  <td align="left" width="30%">&nbsp;</td>
												</tr>
											</tbody>
											</table>
										</td> </tr> ';
                                                
                                            endif; $i++ ;
                                        endforeach; endif;
                                            
				$html			.=	'</tbody>
								</table>';
			endif;
		echo $html; die;
	}
    
   function search_ajax() {
        $html = '';
        if ($this->input->post('string') && $this->input->post('type') ):
            $searchtext = $this->input->post('string');
            $type       = $this->input->post('type');
            if($type == 'Student'):
            $gdata      = $this->admin_model->get_all_student_data($searchtext, $type);

            if ($gdata <> ""):
                $html .= '<ul>';
                foreach ($gdata as $ginfo):
                    $html .= '<li><p data-id="' . $ginfo->encrypt_id . '">' . stripcslashes($ginfo->student_f_name . ' ' . $ginfo->student_m_name . ' ' . $ginfo->student_l_name . '  ( Unique Id -' . $ginfo->student_qunique_id . ')'. '  ( Reg No.-' . $ginfo->student_registration_no . ')') . '</p></li>';

                endforeach;
                $html .= '</ul>';

            else:
                $html = 'ERROR';
            endif;
            else:
                   $gdata      = $this->admin_model->get_all_staff_data($searchtext, $type);

            if ($gdata <> ""):
                $html .= '<ul>';
                foreach ($gdata as $ginfo):
                    $html .= '<li><p data-id="' . $ginfo->encrypt_id . '">' . stripcslashes($ginfo->user_f_name . ' ' . $ginfo->user_m_name . ' ' . $ginfo->user_l_name . '  (Emp Id -' . $ginfo->employee_id . '  ' . $ginfo->user_type . ')') . '</p></li>';

                endforeach;
                $html .= '</ul>';

            else:
                $html = 'ERROR';
            endif;
                
            endif;
        endif;
        echo $html;
        die;
    }
   

}
