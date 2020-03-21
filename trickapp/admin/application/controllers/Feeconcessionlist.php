<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Feeconcessionlist extends CI_Controller {

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
     * * Function name : feeheadlist
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for Fee head list
     * * Date : 23 JANUARY 2018
     * * ********************************************************************* */

    public
            function index() {
        

        $this->admin_model->authCheck('admin', 'view_data');
        $this->admin_model->get_permission_type($data);
        $data['error'] = '';
        $catQuery      = "SELECT *    FROM `sms_user_category` ";

        $data['CATDATA'] = $this->common_model->get_data_by_query('multiple', $catQuery);


        $haedQuery        = "SELECT encrypt_id,fee_head_name ,fee_concession_type FROM sms_fee_head 
										 WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
										 AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
										 AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
										 AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
	 AND session_year = '" . CURRENT_SESSION . "' AND status = 'Y'										 
";
        $data['HEADDATA'] = $this->common_model->get_data_by_query('multiple', $haedQuery);


        $data['CONDATA'] = $this->admin_model->get_fee_concession_by_head_category();
       
        if ($this->input->post('SaveChanges')):

 
            if ($data['HEADDATA']):

                foreach ($data['HEADDATA'] as $HEADDATA):
                    if ($data['CATDATA']):
                        foreach ($data['CATDATA'] as $CATDATA):


                            if (count($data['CONDATA']) > 0):

                                if ($data['CONDATA'][$HEADDATA['encrypt_id'] . '_' . $CATDATA['user_category_name']]):
                                    $param['franchise_id']         = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                                    $param['fee_head_id']          = $HEADDATA['encrypt_id'];
                                    $param['fee_head_name']        = $HEADDATA['fee_head_name'];
                                    $param['fee_concession_type']  = $HEADDATA['fee_concession_type'];
                                    $param['fee_category_name']    = $CATDATA['user_category_name'];
                                    $param['school_id']            = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                                    $param['branch_id']            = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                                    $param['board_id']             = $this->session->userdata('SMS_ADMIN_BOARD_ID');
                                    $param['creation_date']        = currentDateTime();
                                    $param['created_by']           = $this->session->userdata('SMS_ADMIN_ID');
                                    $param['session_year']         = CURRENT_SESSION;
                                    $param['status']               = 'Y';
                                                                 $param['fee_concession_value'] = $this->input->post('fee_concession_value_' . $HEADDATA['encrypt_id'] . '_' . $CATDATA['user_category_name']) > 0 ? $this->input->post('fee_concession_value_' . $HEADDATA['encrypt_id'] . '_' . $CATDATA['user_category_name']) : 0.00;

                                    $whecon['fee_head_id']       = $HEADDATA['encrypt_id'];
                                    $whecon['fee_category_name'] = $CATDATA['user_category_name'];

                                    $this->common_model->edit_data_by_multiple_cond('fee_concession', $param, $whecon);


                                else:
                                    $param['franchise_id']         = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                                    $param['fee_head_id']          = $HEADDATA['encrypt_id'];
                                    $param['fee_head_name']        = $HEADDATA['fee_head_name'];
                                    $param['fee_concession_type']  = $HEADDATA['fee_concession_type'];
                                    $param['fee_category_name']    = $CATDATA['user_category_name'];
                                    $param['school_id']            = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                                    $param['branch_id']            = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                                    $param['board_id']             = $this->session->userdata('SMS_ADMIN_BOARD_ID');
                                    $param['creation_date']        = currentDateTime();
                                    $param['created_by']           = $this->session->userdata('SMS_ADMIN_ID');
                                    $param['session_year']         = CURRENT_SESSION;
                                    $param['status']               = 'Y';
                                                                 $param['fee_concession_value'] = $this->input->post('fee_concession_value_' . $HEADDATA['encrypt_id'] . '_' . $CATDATA['user_category_name']) > 0 ? $this->input->post('fee_concession_value_' . $HEADDATA['encrypt_id'] . '_' . $CATDATA['user_category_name']) : 0.00;

                                    $lastInsertId                = $this->common_model->add_data('fee_concession', $param);
                                    $Uparam['encrypt_id']        = manojEncript($lastInsertId);
                                    $Uwhere['fee_concession_id'] = $astInsertId;
                                    $this->common_model->edit_data_by_multiple_cond('fee_concession', $Uparam, $Uwhere);

                                endif;



                            else :
                                $param['franchise_id']         = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                                $param['fee_head_id']          = $HEADDATA['encrypt_id'];
                                $param['fee_head_name']        = $HEADDATA['fee_head_name'];
                                $param['fee_concession_type']  = $HEADDATA['fee_concession_type'];
                                $param['fee_category_name']    = $CATDATA['user_category_name'];
                                $param['school_id']            = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                                $param['branch_id']            = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                                $param['board_id']             = $this->session->userdata('SMS_ADMIN_BOARD_ID');
                                $param['creation_date']        = currentDateTime();
                                $param['created_by']           = $this->session->userdata('SMS_ADMIN_ID');
                                $param['session_year']         = CURRENT_SESSION;
                                $param['status']               = 'Y';
                                $param['fee_concession_value'] = $this->input->post('fee_concession_value_' . $HEADDATA['encrypt_id'] . '_' . $CATDATA['user_category_name']) > 0 ? $this->input->post('fee_concession_value_' . $HEADDATA['encrypt_id'] . '_' . $CATDATA['user_category_name']) : 0.00;
                               
                                $lastInsertId                = $this->common_model->add_data('fee_concession', $param);
                                $Uparam['encrypt_id']        = manojEncript($lastInsertId);
                                $Uwhere['fee_concession_id'] = $lastInsertId;
                                $this->common_model->edit_data_by_multiple_cond('fee_concession', $Uparam, $Uwhere);
                            endif;
                        endforeach;
                    endif;

                endforeach;


            endif;


            redirect(correctLink('feeconcessionListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));

        endif;


        $whereCon['like']    = "";
        $data['searchValue'] = '';


        $whereCon['where'] = "con.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
								 AND con.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
								 AND con.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
								 AND con.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";
        $shortField        = 'con.fee_head_name DESC';

        $this->load->library('pagination');
        $config['base_url']   = $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index';
        $this->session->set_userdata('feeconcessionListAdminData', currentFullUrl());
        $qStringdata          = explode('?', currentFullUrl());
        $config['suffix']     = $qStringdata[1] ? '?' . $qStringdata[1] : '';
        $tblName              = 'fee_concession as con';
        $con                  = '';
        $config['total_rows'] = $this->admin_model->SelectFeeConcessionListData('count', $tblName, $whereCon, $shortField, '0', '0');
        $config['per_page']   = $config['total_rows'];
        $data['perpage']      = $this->input->get('showLength');

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

        $data['ALLDATA'] = $this->admin_model->SelectFeeConcessionListData('data', $tblName, $whereCon, $shortField, $config['per_page'], $page);


        $this->layouts->set_title('Manage fee concession');
        $this->layouts->admin_view('feeconcessionlist/index', array(), $data);
    }

// END OF FUNCTION
}
