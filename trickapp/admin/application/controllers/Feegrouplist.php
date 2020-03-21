<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Feegrouplist extends CI_Controller {

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
     * * Function name : feegrouplist
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for Fee head list
     * * Date : 23 JANUARY 2018
     * * ********************************************************************* */

    public
            function index() {

        $this->admin_model->authCheck('admin', 'view_data');
        $this->admin_model->get_permission_type($data);
        $data['error'] = '';

        if ($this->input->get('searchValue')):
            $sValue              = $this->input->get('searchValue');
            $whereCon['like']    = "(fgroup.fee_group_name LIKE '%" . $sValue . "%')";
            $data['searchValue'] = $sValue;
        else:
            $whereCon['like']    = "";
            $data['searchValue'] = '';
        endif;

        $whereCon['where'] = "fgroup.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
								 AND fgroup.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
								 AND fgroup.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
								 AND fgroup.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";
        $shortField        = 'fgroup.fee_group_name ';

        $this->load->library('pagination');
        $config['base_url']   = $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index';
        $this->session->set_userdata('feegroupListAdminData', currentFullUrl());
        $qStringdata          = explode('?', currentFullUrl());
        $config['suffix']     = $qStringdata[1] ? '?' . $qStringdata[1] : '';
        $tblName              = 'fee_group as fgroup';
        $con                  = '';
        $config['total_rows'] = $this->admin_model->SelectFeeGroupListData('count', $tblName, $whereCon, $shortField, '0', '0');

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

        $data['ALLDATA'] = $this->admin_model->SelectFeeGroupListData('data', $tblName, $whereCon, $shortField, $config['per_page'], $page);

        $classQuery = "SELECT session_month_name,session_month_short_name FROM sms_session_month 
										 WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
										 AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
										 AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
										 AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
										 AND session_year = '" . CURRENT_SESSION . "' AND status = 'Y'";

        $this->layouts->set_title('Manage fee group');
        $this->layouts->admin_view('feegrouplist/index', array(), $data);
    }

// END OF FUNCTION

    /*     * *********************************************************************
     * * Function name : addeditdata
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for add edit data
     * * Date : 23 JANUARY 2018
     * * ********************************************************************* */

    public
            function addeditdata($editid) {
        $data['error'] = '';

  


        $haedQuery        = "SELECT encrypt_id,fee_head_name FROM sms_fee_head 
										 WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
										 AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
										 AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
										 AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
	 AND session_year = '" . CURRENT_SESSION . "' AND status = 'Y'										 
";
        $data['HEADDATA'] = $this->common_model->get_data_by_query('multiple', $haedQuery);

        if ($editid):
            $this->admin_model->authCheck('admin', 'edit_data');
            $data['EDITDATA']    = $this->common_model->get_data_by_encryptId('fee_group', $editid);
           
      

        endif;

      if($data['EDITDATA']):
           $activeQuery         = "SELECT * FROM sms_fee_group_class_relation    WHERE   fee_group_id = '" . $editid . "' AND session_year = '" . CURRENT_SESSION . "' ";
            $allActiveClass      = $this->common_model->get_data_by_query('multiple', $activeQuery);
            $data['ACTIVECLASS'] = array();
            if ($allActiveClass):
                foreach ($allActiveClass as $info):
                    array_push($data['ACTIVECLASS'], $info['class_id']);
                endforeach;
            endif;
            $data['FEEDATA'] = $this->admin_model->get_fee_rate_by_group($editid);
            $classQuery      = "SELECT c.encrypt_id,c.class_name FROM sms_classes AS c 
 
LEFT JOIN  `sms_fee_group_class_relation` AS  fc  ON fc.class_id = c.encrypt_id   WHERE fc.class_id IS NULL
 
										 AND c.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
										 AND c.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
										 AND c.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
										 AND c.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
	                                                                         AND c.session_year = '" . CURRENT_SESSION . "' AND c.status = 'Y'
                     UNION ALL
 
                                                                                  SELECT c.encrypt_id,c.class_name FROM sms_classes AS c 
 
                                                                                  LEFT JOIN  `sms_fee_group_class_relation` AS  fc  ON fc.class_id = c.encrypt_id   WHERE fc.fee_group_id = '" . $data['EDITDATA']['encrypt_id'] . "'
                                                                                      
";
           
            else:
            $this->admin_model->authCheck('admin', 'add_data');
            $classQuery = "SELECT c.encrypt_id,c.class_name FROM sms_classes AS c 
 
LEFT JOIN  `sms_fee_group_class_relation` AS  fc  ON fc.class_id = c.encrypt_id   WHERE fc.class_id IS NULL
 
										 AND c.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
										 AND c.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
										 AND c.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
										 AND c.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
	 AND c.session_year = '" . CURRENT_SESSION . "' AND c.status = 'Y'
             
";
          
          
      endif;

        $data['CLASSDATA'] = $this->common_model->get_data_by_query('multiple', $classQuery);


        if ($this->input->post('SaveChanges')):
            $error = 'NO';
            $this->form_validation->set_rules('fee_group_name', 'Group Name', 'trim|required');
            $this->form_validation->set_rules('class_id[]', 'Class', 'trim|required');





            if ($this->form_validation->run() && $error == 'NO'):

                $param['fee_group_name'] = addslashes($this->input->post('fee_group_name'));

                if ($this->input->post('CurrentDataID') == ''):
                    //add Fee group
                    $param['franchise_id']  = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                    $param['school_id']     = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                    $param['branch_id']     = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                    $param['board_id']      = $this->session->userdata('SMS_ADMIN_BOARD_ID');
                    $param['creation_date'] = currentDateTime();
                    $param['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                    $param['session_year']  = CURRENT_SESSION;
                    $param['status']        = 'Y';
                    $alastInsertId          = $this->common_model->add_data('fee_group', $param);

                    $Uparam['encrypt_id']   = manojEncript($alastInsertId);
                    $Uwhere['fee_group_id'] = $alastInsertId;
                    $this->common_model->edit_data_by_multiple_cond('fee_group', $Uparam, $Uwhere);
                    $this->session->set_flashdata('alert_success', lang('addsuccess'));
                    $group_id               = manojEncript($alastInsertId);
                else:
                    $TDPROGId             = $this->input->post('CurrentDataID');
                    $param['update_date'] = currentDateTime();
                    $param['updated_by']  = $this->session->userdata('SMS_ADMIN_ID');
                    $this->common_model->edit_data('fee_group', $param, $TDPROGId);
                    $this->session->set_flashdata('alert_success', lang('updatesuccess'));
                    $group_id             = $TDPROGId;
                endif;
                if ($this->input->post('class_id')):
                    $this->common_model->delete_particular_data('fee_group_class_relation', 'fee_group_id', $group_id);
                    $class_id = $this->input->post('class_id');
                    for ($i = 0; $i < count($class_id); $i++):
                        $cparam['fee_group_id'] = $group_id;
                        $cparam['class_id']     = $class_id[$i];
                        $cparam['session_year'] = CURRENT_SESSION;

                        $calastInsertId = $this->common_model->add_data('fee_group_class_relation', $cparam);

                        $cUparam['encrypt_id']         = manojEncript($calastInsertId);
                        $cUwhere['fee_group_class_id'] = $calastInsertId;
                        $this->common_model->edit_data_by_multiple_cond('fee_group_class_relation', $cUparam, $cUwhere);
                    endfor;
                endif;

                if ($data['HEADDATA']):

                    foreach ($data['HEADDATA'] as $HEADDATA):

                        if (count($data['FEEDATA']) > 0):

                            if ($data['FEEDATA'][$HEADDATA['encrypt_id']]):

                                $fparam['fee_amount'] = $this->input->post('fee_amount_' . $HEADDATA['encrypt_id']) > 0 ? $this->input->post('fee_amount_' . $HEADDATA['encrypt_id']) : 0.00;

                                $fparam['status'] = $this->input->post('active_' . $HEADDATA['encrypt_id']) ? $this->input->post('active_' . $HEADDATA['encrypt_id']) : 'N';

                                $this->common_model->edit_data('fee_group_feehead_relation', $fparam, $HEADDATA['encrypt_id']);


                            else:

                                $fparam['fee_group_id'] = $group_id;
                                $fparam['fee_head_id']  = $HEADDATA['encrypt_id'];
                                $fparam['fee_amount']   = $this->input->post('fee_amount_' . $HEADDATA['encrypt_id']) > 0 ? $this->input->post('fee_amount_' . $HEADDATA['encrypt_id']) : 0.00;
                                $fparam['session_year'] = CURRENT_SESSION;


                                $fparam['status']                = $this->input->post('active_' . $HEADDATA['encrypt_id']) ? $this->input->post('active_' . $HEADDATA['encrypt_id']) : 'N';
                                $falastInsertId                  = $this->common_model->add_data('fee_group_feehead_relation', $fparam);
                                $fUparam['encrypt_id']           = manojEncript($falastInsertId);
                                $fUwhere['fee_group_feehead_id'] = $falastInsertId;
                                $this->common_model->edit_data_by_multiple_cond('fee_group_feehead_relation', $fUparam, $fUwhere);

                            endif;



                        else :
                            $fparam['fee_group_id'] = $group_id;
                            $fparam['fee_head_id']  = $HEADDATA['encrypt_id'];
                            $fparam['fee_amount']   = $this->input->post('fee_amount_' . $HEADDATA['encrypt_id']) > 0 ? $this->input->post('fee_amount_' . $HEADDATA['encrypt_id']) : 0.00;
                            $fparam['session_year'] = CURRENT_SESSION;


                            $fparam['status']                = $this->input->post('active_' . $HEADDATA['encrypt_id']) ? $this->input->post('active_' . $HEADDATA['encrypt_id']) : 'N';
                            $falastInsertId                  = $this->common_model->add_data('fee_group_feehead_relation', $fparam);
                            $fUparam['encrypt_id']           = manojEncript($falastInsertId);
                            $fUwhere['fee_group_feehead_id'] = $falastInsertId;
                            $this->common_model->edit_data_by_multiple_cond('fee_group_feehead_relation', $fUparam, $fUwhere);

                        endif;

                    endforeach;


                endif;


                redirect(correctLink('feegroupListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
            endif;
        endif;

        $this->layouts->set_title('Edit fee group');
        $this->layouts->admin_view('feegrouplist/addeditdata', array(), $data);
    }

// END OF FUNCTION	

    /*     * *********************************************************************
     * * Function name : changestatus
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for change status
     * * Date : 23 JANUARY 2018
     * ********************************************************************** */

    function changestatus($changeStatusId = '', $statusType = '') {
        $this->admin_model->authCheck('admin', 'edit_data');

        $param['status'] = $statusType;
        $this->common_model->edit_data('fee_group', $param, $changeStatusId);

        $this->session->set_flashdata('alert_success', lang('statussuccess'));

        redirect(correctLink('feegroupListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
    }

}
