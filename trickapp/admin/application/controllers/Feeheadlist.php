<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Feeheadlist extends CI_Controller {

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
     * * Function name : feeheadlist
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
            $whereCon['like']    = "(fhead.fee_head_name LIKE '%" . $sValue . "%' OR ffreq.fee_frequency_name LIKE '%" . $sValue . "%')";
            $data['searchValue'] = $sValue;
        else:
            $whereCon['like']    = "";
            $data['searchValue'] = '';
        endif;

        $whereCon['where'] = "fhead.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
								 AND fhead.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
								 AND fhead.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
								 AND fhead.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";
        $shortField        = 'fhead.fee_head_name DESC';

        $this->load->library('pagination');
        $config['base_url']   = $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index';
        $this->session->set_userdata('feeheadListAdminData', currentFullUrl());
        $qStringdata          = explode('?', currentFullUrl());
        $config['suffix']     = $qStringdata[1] ? '?' . $qStringdata[1] : '';
        $tblName              = 'fee_head as fhead';
        $con                  = '';
        $config['total_rows'] = $this->admin_model->SelectFeeHeadListData('count', $tblName, $whereCon, $shortField, '0', '0');

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

        $data['ALLDATA'] = $this->admin_model->SelectFeeHeadListData('data', $tblName, $whereCon, $shortField, $config['per_page'], $page);
		//echo "<pre>"; print_r($data['ALLDATA']); die;
		$classQuery        		= "SELECT session_month_name,session_month_short_name FROM sms_session_month 
										 WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
										 AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
										 AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
										 AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
										 AND session_year = '".CURRENT_SESSION."' AND status = 'Y'";
              
        $data['STARTMONTHDATA'] = $this->common_model->get_data_by_query('single', $classQuery);
		if($data['STARTMONTHDATA'] <> ""):
			$data['STARTMONTH']		=	$data['STARTMONTHDATA']['session_month_name'];
                       
		else:
			$data['STARTMONTH']		=	'April';
		endif;

        $this->layouts->set_title('Manage fee head');
        $this->layouts->admin_view('feeheadlist/index', array(), $data);
    } // END OF FUNCTION

    /* * *********************************************************************
     * * Function name : addeditdata
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for add edit data
     * * Date : 23 JANUARY 2018
     * * ********************************************************************* */
    public function addeditdata($editid = '') {

        $data['error'] = '';

        $frequencyQuery        = "SELECT encrypt_id,fee_frequency_name FROM sms_fee_frequency ORDER BY fee_frequency_name";
        $data['FREQUENCYDATA'] = $this->common_model->get_data_by_query('multiple', $frequencyQuery);
		
		$classQuery        		= "SELECT session_month_name,session_month_short_name FROM sms_session_month 
										 WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
										 AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
										 AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
										 AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
										 AND session_year = '".CURRENT_SESSION."' AND status = 'Y'";
        $data['STARTMONTHDATA'] = $this->common_model->get_data_by_query('single', $classQuery);
		if($data['STARTMONTHDATA'] <> ""):
			$data['STARTMONTH']		=	$data['STARTMONTHDATA']['session_month_name'];
		else:
			$data['STARTMONTH']		=	'April';
		endif;
		
        if ($editid):
            
            $this->admin_model->authCheck('admin', 'edit_data');
            $data['FEEHEADINGLIST']   =  $this->admin_model->get_all_fee_heading_list();
            //print_r($data['FEEHEADINGLIST']); die;
            $data['EDITDATA'] = $this->common_model->get_data_by_encryptId('fee_head', $editid);
        else:
            $this->admin_model->authCheck('admin', 'add_data');
        endif;
          //$data['feeheadinglist']   =  $this->admin_model->get_all_fee_heading_list();
            //echo "<pre>"; print_r($feeheadinglist); die;
    
        if ($this->input->post('SaveChanges')): //echo "<pre>"; print_r($_POST); die;
            $error = 'NO';
            $this->form_validation->set_rules('fee_head_name', 'Fees heading', 'trim|required');
            $this->form_validation->set_rules('fee_frequency_id', 'Frequency', 'trim|required');
             $this->form_validation->set_rules('fee_end_date', 'End date', 'trim|required');
            $this->form_validation->set_rules('fee_start_date', 'start date', 'trim|required');
            $this->form_validation->set_rules('fee_due_date', 'Due date', 'trim|required');
            $this->form_validation->set_rules('fee_refundable', 'Refundable', 'trim|required');
            $this->form_validation->set_rules('fee_concession_type', 'Concession type', 'trim|required');
          
             $month	=	date("m", strtotime($data['STARTMONTH']));
            // echo $month; die;
             $x = 1 ;
    for ($i = ($month-1); $i < (12+($month-1)); ++$i):
        if($x ==1):
             $param['month_'.$x.'st_name'] = date("F", strtotime("January +$i months")) ;
             $param['month_'.$x.'st_short_name'] = date("M", strtotime("January +$i months")) ;
             if($this->input->post('month_id_'.$i)   == 'True'):
                  $param['month_'.$x.'st_fee'] = 'True' ;
              else:
                 
                    $param['month_'.$x.'st_fee'] = 'False' ;
             endif;
          
      elseif($x ==2):
             $param['month_'.$x.'nd_name'] = date("F", strtotime("January +$i months")) ;
             $param['month_'.$x.'nd_short_name'] = date("M", strtotime("January +$i months")) ;
             if($this->input->post('month_id_'.$i)   == 'True'):
                  $param['month_'.$x.'nd_fee'] = 'True' ;
              else:
                 
                    $param['month_'.$x.'nd_fee'] = 'False' ;
             endif;
         elseif($x ==3):
               $param['month_'.$x.'rd_name'] = date("F", strtotime("January +$i months")) ;
             $param['month_'.$x.'rd_short_name'] = date("M", strtotime("January +$i months")) ;
             if($this->input->post('month_id_'.$i)   == 'True'):
                  $param['month_'.$x.'rd_fee'] = 'True' ;
              else:
                 
                    $param['month_'.$x.'rd_fee'] = 'False' ;
             endif;
             else:
                 $param['month_'.$x.'th_name'] = date("F", strtotime("January +$i months")) ;
             $param['month_'.$x.'th_short_name'] = date("M", strtotime("January +$i months")) ;
             if($this->input->post('month_id_'.$i)   == 'True'):
                  $param['month_'.$x.'th_fee'] = 'True' ;
              else:
                 
                    $param['month_'.$x.'th_fee'] = 'False' ;
             endif; 
                 
                 
      endif;
       $x++;
    endfor;
          
            
            if ($this->form_validation->run() && $error == 'NO'):

                $param['fee_head_name']                 = addslashes($this->input->post('fee_head_name'));
                $param['fee_frequency_id']       = addslashes($this->input->post('fee_frequency_id'));
                $param['fee_start_date'] = addslashes($this->input->post('fee_start_date'));
                $param['fee_end_date']         = addslashes($this->input->post('fee_end_date'));
                 $param['fee_due_date']         = addslashes($this->input->post('fee_due_date'));
                  $param['fee_refundable']         = addslashes($this->input->post('fee_refundable'));
                   $param['fee_concession_type']         = addslashes($this->input->post('fee_concession_type'));
                  // echo "<pre>"; print_r($param); die;

                if ($this->input->post('CurrentDataID') == ''):
                    $param['franchise_id']  = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                    $param['school_id']     = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                    $param['branch_id']     = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                    $param['board_id']      = $this->session->userdata('SMS_ADMIN_BOARD_ID');
                    $param['creation_date'] = currentDateTime();
                    $param['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                     $param['session_year']			=	CURRENT_SESSION;
                    $param['status']        = 'Y';
                    $alastInsertId          = $this->common_model->add_data('fee_head', $param);

                    $Uparam['encrypt_id']       = manojEncript($alastInsertId);
                    $Uwhere['fee_head_id'] = $alastInsertId;
                    $this->common_model->edit_data_by_multiple_cond('fee_head', $Uparam, $Uwhere);
                    $this->session->set_flashdata('alert_success', lang('addsuccess'));
                else:
                    $TDPROGId             = $this->input->post('CurrentDataID');
                    $param['update_date'] = currentDateTime();
                    $param['updated_by']  = $this->session->userdata('SMS_ADMIN_ID');
                    $this->common_model->edit_data('fee_head', $param, $TDPROGId);
                    $this->session->set_flashdata('alert_success', lang('updatesuccess'));
                endif;

                redirect(correctLink('feeheadListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
            endif;
        endif;

        $this->layouts->set_title('Edit fee head');
        $this->layouts->admin_view('feeheadlist/addeditdata', array(), $data);
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
        $this->common_model->edit_data('fee_head', $param, $changeStatusId);

        $this->session->set_flashdata('alert_success', lang('statussuccess'));

        redirect(correctLink('feeheadListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
    }

}
