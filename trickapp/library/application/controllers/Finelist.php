<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class finelist extends CI_Controller {

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
    }

    /*     * *********************************************************************
     * * Function name : finelist
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for finelist
     * * Date : 16 JANUARY 2018
     * * ********************************************************************* */

    public
            function index() {
        $this->admin_model->authCheck('admin', 'view_data');
        $this->admin_model->get_permission_type($data);
        $data['error'] = '';

        if ($this->input->get('searchValue')):
            $sValue              = $this->input->get('searchValue');
            $whereCon['like']    = "(fine_rule LIKE '%" . $sValue . "%' 
			                                      )";
            $data['searchValue'] = $sValue;
        else:
            $whereCon['like']    = "";
            $data['searchValue'] = '';
        endif;

        $whereCon['where'] = "f.franchise_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID') . "'
												 AND f.school_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID') . "' 
												 AND f.branch_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID') . "'
												 ";
        $shortField        = 'f.fine_id ASC';

        $this->load->library('pagination');
        $config['base_url']   = $this->session->userdata('SMS_LIBRARY_ADMIN_PATH') . $this->router->fetch_class() . '/index/';
        $this->session->set_userdata('fineListAdminData', currentFullUrl());
        $qStringdata          = explode('?', currentFullUrl());
        $config['suffix']     = $qStringdata[1] ? '?' . $qStringdata[1] : '';
        $tblName              = 'lib_fine as f';
        $con                  = '';
        $config['total_rows'] = $this->admin_model->SelectfineListData('count', $tblName, $whereCon, $shortField, '0', '0');

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

        $config['uri_segment'] = 4;
        $this->pagination->initialize($config);

        if ($this->uri->segment(4)):
            $page = $this->uri->segment(4);
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

        $data['ALLDATA'] = $this->admin_model->SelectfineListData('data', $tblName, $whereCon, $shortField, $config['per_page'], $page);

        $this->layouts->set_title('Manage fine details');
        $this->layouts->admin_view('finelist/index', array(), $data);
    }

// END OF FUNCTION

    /*     * *********************************************************************
     * * Function name : addeditdata
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for add edit data
     * * Date : 16 JANUARY 2018
     * * ********************************************************************* */

    public
            function addeditdata($editid = '') {
        $data['error'] = '';


       
     

        if ($editid):
            $this->admin_model->authCheck('admin', 'edit_data');
            $data['EDITDATA'] = $this->common_model->get_data_by_encryptId('lib_fine', $editid);
        else:
            $this->admin_model->authCheck('admin', 'add_data');
        endif;
 $subQuery             = "SELECT * FROM `sms_lib_fine_rule`  WHERE STATUS='Y'";
       $data['FINERULEDATA'] = $this->common_model->get_data_by_query('multiple', $subQuery);
        if ($this->input->post('SaveChanges')):


            $error = 'NO';
        if( $data['EDITDATA']['fine_rule_id'] !=  $this->input->post('fine_rule_id')) :
        
            $this->form_validation->set_rules('fine_rule_id', 'fine rule', 'trim|required|is_unique[lib_fine.f_fine_rule_id]');
            endif ;
            $this->form_validation->set_rules('fine_per_day', 'fine per day', 'trim|required');


            if ($this->form_validation->run() && $error == 'NO'):

                $param['fine_rule_id'] = addslashes($this->input->post('fine_rule_id'));
                $param['fine_per_day'] = addslashes($this->input->post('fine_per_day'));



                if ($this->input->post('CurrentDataID') == ''):
                    $param['franchise_id'] = $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID');
                    $param['school_id']    = $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID');
                    $param['branch_id']    = $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID');

                    $param['creation_date'] = currentDateTime();
                    $param['created_by']    = $this->session->userdata('SMS_LIBRARY_ADMIN_ID');
                    $param['status']        = 'Y';
                        $param['session_year']			=	CURRENT_SESSION;
                    $alastInsertId          = $this->common_model->add_data('lib_fine', $param);
                    $Uparam['encrypt_id']   = manojEncript($alastInsertId);
                    $Uwhere['fine_id']      = $alastInsertId;
                    $fineaddId              = $Uparam['encrypt_id'];
                    $this->common_model->edit_data_by_multiple_cond('lib_fine', $Uparam, $Uwhere);
                    $this->session->set_flashdata('alert_success', lang('addsuccess'));
                else:
                    $fineId               = $this->input->post('CurrentDataID');
                    $param['update_date'] = currentDateTime();
                    $param['updated_by']  = $this->session->userdata('SMS_LIBRARY_ADMIN_ID');
                    $this->common_model->edit_data('lib_fine', $param, $fineId);
                    $fineaddId            = $fineId;
                    $this->session->set_flashdata('alert_success', lang('updatesuccess'));
                endif;


                redirect(correctLink('fineListAdminData', $this->session->userdata('SMS_LIBRARY_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
            endif;
        endif;

        $this->layouts->set_title('Edit fine details');
        $this->layouts->admin_view('finelist/addeditdata', array(), $data);
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
        $this->common_model->edit_data('lib_fine', $param, $changeStatusId);

        $this->session->set_flashdata('alert_success', lang('statussuccess'));

        redirect(correctLink('fineListAdminData', $this->session->userdata('SMS_LIBRARY_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
    }

}
