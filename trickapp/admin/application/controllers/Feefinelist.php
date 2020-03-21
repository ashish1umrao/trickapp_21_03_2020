<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Feefinelist extends CI_Controller {

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
     * * Function name : feefinelist
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for Fee head list
     * * Date : 23 JANUARY 2018
     * * ********************************************************************* */
    public function index() {
		
        $this->admin_model->authCheck('admin', 'view_data');
        $this->admin_model->get_permission_type($data);
        $data['error'] = '';

        if ($this->input->get('searchValue')):
            $sValue              = $this->input->get('searchValue');
            $whereCon['like']    = "(fhead.fee_head_name LIKE '%" . $sValue . "%' OR ffine.fee_fine_type LIKE '%" . $sValue . "%')";
            $data['searchValue'] = $sValue;
        else:
            $whereCon['like']    = "";
            $data['searchValue'] = '';
        endif;

        $whereCon['where'] = "ffine.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
								 AND ffine.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
								 AND ffine.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
								 AND ffine.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'  AND fhead.status = 'Y'  ";
                                                                 
                                                                   
        $shortField        = 'fhead.fee_head_name DESC';

        $this->load->library('pagination');
        $config['base_url']   = $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index';
        $this->session->set_userdata('feefineListAdminData', currentFullUrl());
        $qStringdata          = explode('?', currentFullUrl());
        $config['suffix']     = $qStringdata[1] ? '?' . $qStringdata[1] : '';
        $tblName              = 'fee_fine as ffine';
        $con                  = '';
        $config['total_rows'] = $this->admin_model->SelectFeeFineListData('count', $tblName, $whereCon, $shortField, '0', '0');

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

        $data['ALLDATA'] = $this->admin_model->SelectFeeFineListData('data', $tblName, $whereCon, $shortField, $config['per_page'], $page);
		
              
        

        $this->layouts->set_title('Manage fee fine');
        $this->layouts->admin_view('feefinelist/index', array(), $data);
    } // END OF FUNCTION

    /* * *********************************************************************
     * * Function name : addeditdata
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for add edit data
     * * Date : 23 JANUARY 2018
     * * ********************************************************************* */
    public function addeditdata($editid = '') {
     
        $data['error'] = '';
	
        if ($editid):
      
            $this->admin_model->authCheck('admin', 'edit_data');
            $data['EDITDATA'] = $this->common_model->get_data_by_encryptId('fee_fine', $editid);
            $data['FEEHEADINGLIST']   =  $this->admin_model->get_all_fee_heading_list();
            //echo "<pre>"; print_r($data['FEEHEADINGLIST']); die;
            endif;
            if($data['EDITDATA']):
            $haedQuery        = "SELECT f.encrypt_id,f.fee_head_name  FROM sms_fee_head AS f LEFT JOIN  `sms_fee_fine` AS fn  ON fn.fee_head_id = f.encrypt_id
WHERE fn.fee_head_id IS NULL AND   f.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
										 AND f.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
										 AND f.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
										 AND f.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
	 AND f.session_year = '" . CURRENT_SESSION . "' AND f.status = 'Y' UNION ALL
SELECT  f.encrypt_id,f.fee_head_name FROM sms_fee_head AS f  WHERE f.encrypt_id = '".$data['EDITDATA']['fee_head_id']."'										 
";
        else:
             $haedQuery        = "SELECT f.encrypt_id,f.fee_head_name ,fn.fee_head_id FROM sms_fee_head AS f LEFT JOIN  `sms_fee_fine` AS fn  ON fn.fee_head_id = f.encrypt_id
WHERE fn.fee_head_id IS NULL AND   f.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
										 AND f.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
										 AND f.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
										 AND f.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
	 AND f.session_year = '" . CURRENT_SESSION . "' AND f.status = 'Y' 										 
";

           
            $this->admin_model->authCheck('admin', 'add_data');
        endif;
          
    
    
 $data['HEADDATA'] = $this->common_model->get_data_by_query('multiple', $haedQuery);

        if ($this->input->post('SaveChanges')):
            $error = 'NO';
          $this->form_validation->set_rules('fee_head_id', 'Fees heading', 'trim|required');
         $this->form_validation->set_rules('fee_fine_type', 'Fine Type', 'trim|required');
         if($this->input->post('fee_fine_type') == 'Percentage'):
            $this->form_validation->set_rules('fee_fine_value', 'Fine percentage', 'trim|required|less_than[100]');
         else:
             $this->form_validation->set_rules('fee_fine_value', 'Fine Value', 'trim|required');  
         endif;
         $this->form_validation->set_rules('fee_fine_every_days', 'Fine every Day', 'trim|required');
           $this->form_validation->set_rules('fee_fine_upto_days', 'Fine upto Day', 'trim|required');
            
           
            
            
         if ($this->form_validation->run() && $error == 'NO'):

                $param['fee_head_id']                 = addslashes($this->input->post('fee_head_id'));
                $param['fee_fine_type']       = addslashes($this->input->post('fee_fine_type'));
                $param['fee_fine_value'] = addslashes($this->input->post('fee_fine_value'));
                $param['fee_fine_every_days']         = addslashes($this->input->post('fee_fine_every_days'));
                 $param['fee_fine_upto_days']         = addslashes($this->input->post('fee_fine_upto_days'));
                if ($this->input->post('CurrentDataID') == ''):
                    $param['franchise_id']  = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                    $param['school_id']     = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                    $param['branch_id']     = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                    $param['board_id']      = $this->session->userdata('SMS_ADMIN_BOARD_ID');
                    $param['creation_date'] = currentDateTime();
                    $param['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                     $param['session_year']			=	CURRENT_SESSION;
                    $param['status']        = 'Y';
                    $alastInsertId          = $this->common_model->add_data('fee_fine', $param);

                    $Uparam['encrypt_id']       = manojEncript($alastInsertId);
                    $Uwhere['fee_fine_id'] = $alastInsertId;
                    $this->common_model->edit_data_by_multiple_cond('fee_fine', $Uparam, $Uwhere);
                    $this->session->set_flashdata('alert_success', lang('addsuccess'));
                else:
                    $TDPROGId             = $this->input->post('CurrentDataID');
                    $param['update_date'] = currentDateTime();
                    $param['updated_by']  = $this->session->userdata('SMS_ADMIN_ID');
                    $this->common_model->edit_data('fee_fine', $param, $TDPROGId);
                    $this->session->set_flashdata('alert_success', lang('updatesuccess'));
                endif;

                redirect(correctLink('feefineListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
            endif;
        endif;

        $this->layouts->set_title('Edit fee fine');
        $this->layouts->admin_view('feefinelist/addeditdata', array(), $data);
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
        $this->common_model->edit_data('fee_fine', $param, $changeStatusId);

        $this->session->set_flashdata('alert_success', lang('statussuccess'));

        redirect(correctLink('feefineListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
    }

}
