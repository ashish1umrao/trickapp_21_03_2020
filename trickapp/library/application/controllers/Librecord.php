<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class librecord extends CI_Controller {

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
     * * Function name : issuebook
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for issuebook
     * * Date : 16 JANUARY 2018
     * * ********************************************************************* */

    public
            function index() {
        $this->admin_model->authCheck('admin', 'view_data');
        $this->admin_model->get_permission_type($data);
        $data['error'] = '';
        $data['start_date'] = $this->input->get('start_date');
         $data['end_date'] = $this->input->get('end_date');
         if($data['start_date'] and $data['start_date']):
          $startDate        = strtotime(DDMMYYtoYYMMDD($this->input->get('start_date')));
            $endDate          = (strtotime(DDMMYYtoYYMMDD($this->input->get('end_date'))) + 82800);
            else:
            $startDate        = strtotime(date('Y-m-d'));
            $endDate          = (strtotime(date('Y-m-d')) + 82800); 
            endif;
         $data['status'] = $this->input->get('status');
         if($data['status'] == 'All'):
             $status = '';
             else:
               $status = $this->input->get('status');
         endif;
        
         
        if ($this->input->get('searchValue')):
            $sValue              = $this->input->get('searchValue');
            $whereCon['like']    = '';
            $data['searchValue'] = $sValue;
        else:
            $whereCon['like']    = "";
            $data['searchValue'] = '';
        endif;
        
        
          if ($this->input->get('showLength') == 'All'):
            $config['per_page'] = $config['total_rows'];
            $data['perpage']    = $this->input->get('showLength');
            $data['status']     = $this->input->get('status');
        elseif ($this->input->get('showLength')):
            $config['per_page'] = $this->input->get('showLength');
            $data['perpage']    = $this->input->get('showLength');
             $data['status']     = $this->input->get('status');
        else:
            $config['per_page'] = SHOW_NO_OF_DATA;
            $data['perpage']    = SHOW_NO_OF_DATA;
             $data['status']     = 'All';
        endif;
       if($status == '') :
        $whereConUpdateDate['where'] = " UNIX_TIMESTAMP(bk.update_date) >= '" . ($startDate) . "' AND UNIX_TIMESTAMP(bk.update_date)  <= '" . ($endDate) . "' 
												 ";
          $whereConCreateDate['where'] = " UNIX_TIMESTAMP(bk.creation_date) >= '" . ($startDate) . "' AND UNIX_TIMESTAMP(bk.creation_date)  <= '" . ($endDate) . "'
												 
												 ";
       else:
           
           
       endif;
        $shortField        = 'bk.issue_return_id ASC';

        $this->load->library('pagination');
        $config['base_url']   = $this->session->userdata('SMS_LIBRARY_ADMIN_PATH') . $this->router->fetch_class() . '/index/';
        $this->session->set_userdata('issueBookAdminData', currentFullUrl());
        $qStringdata          = explode('?', currentFullUrl());
        $config['suffix']     = $qStringdata[1] ? '?' . $qStringdata[1] : '';
        $tblName              = 'lib_issue_return_book as bk';
        $con                  = '';
        $config['total_rows'] = $this->admin_model->SelectLibRecordListData('count', $tblName, $whereConUpdateDate,$whereConCreateDate, $shortField, '0', '0');

      

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

        $data['ALLDATA'] = $this->admin_model->SelectLibRecordListData('data', $tblName, $whereCon, $shortField, $config['per_page'], $page);

        $this->layouts->set_title('Library record');
        $this->layouts->admin_view('librecord/index', array(), $data);
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
            $data['EDITDATA'] = $this->common_model->get_data_by_encryptId('lib_issue_return_book', $editid);
        else:
            $this->admin_model->authCheck('admin', 'add_data');
        endif;

        if ($this->input->post('SaveChanges')):


            $error = 'NO';
            $this->form_validation->set_rules('book_name', 'book name', 'trim');
            $this->form_validation->set_rules('book_author', 'book author', 'trim|required');
            $this->form_validation->set_rules('book_price', 'book price', 'trim|required');
            if ($this->input->post('CurrentDataID') == ''):
                $this->form_validation->set_rules('add_quantity', 'quantity', 'trim|required');
            endif;

            if ($this->input->post('CurrentDataID') == ''):
                $product_quantity = addslashes($this->input->post('add_quantity'));
            else:
                $product_quantity = $data['EDITDATA']['book_quantity'] + addslashes($this->input->post('add_quantity'));
            endif;

            if ($this->form_validation->run() && $error == 'NO'):

                $param['book_name']        = addslashes($this->input->post('book_name'));
                $param['book_author']      = addslashes($this->input->post('book_author'));
                $param['book_price']       = addslashes($this->input->post('book_price'));
                $param['book_quantity']    = $product_quantity;
                $param['book_description'] = addslashes($this->input->post('book_description'));


                if ($this->input->post('CurrentDataID') == ''):
                    $param['franchise_id'] = $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID');
                    $param['school_id']    = $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID');
                    $param['branch_id']    = $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID');

                    $param['creation_date'] = currentDateTime();
                    $param['created_by']    = $this->session->userdata('SMS_LIBRARY_ADMIN_ID');
                    $param['status']        = 'Y';
                     $param['session_year']			=	CURRENT_SESSION;
                    $alastInsertId          = $this->common_model->add_data('lib_issue_return_book', $param);
                    $Uparam['encrypt_id']   = manojEncript($alastInsertId);
                    $Uwhere['book_id']      = $alastInsertId;
                    $bookaddId              = $Uparam['encrypt_id'];
                    $this->common_model->edit_data_by_multiple_cond('lib_issue_return_book', $Uparam, $Uwhere);
                    $this->session->set_flashdata('alert_success', lang('addsuccess'));
                else:
                    $bookId               = $this->input->post('CurrentDataID');
                    $param['update_date'] = currentDateTime();
                    $param['updated_by']  = $this->session->userdata('SMS_LIBRARY_ADMIN_ID');
                    $this->common_model->edit_data('lib_issue_return_book', $param, $bookId);
                    $bookaddId            = $bookId;
                    $this->session->set_flashdata('alert_success', lang('updatesuccess'));
                endif;
                /// add books quantity in sms_lib_issue_return_book_add table
                $params['franchise_id']  = $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID');
                $params['school_id']     = $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID');
                $params['branch_id']     = $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID');
                $params['book_id']       = $bookaddId;
                $params['creation_date'] = currentDateTime();
                $params['created_by']    = $this->session->userdata('SMS_LIBRARY_ADMIN_ID');
                $params['status']        = 'Y';
                $alastInseraddtId        = $this->common_model->add_data('lib_issue_return_book_add', $params);
                $Uparams['encrypt_id']   = manojEncript($alastInseraddtId);
                $Uwheres['books_add_id'] = $alastInseraddtId;

                $this->common_model->edit_data_by_multiple_cond('lib_issue_return_book_add', $Uparams, $Uwheres);



                redirect(correctLink('issueBookAdminData', $this->session->userdata('SMS_LIBRARY_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
            endif;
        endif;

        $this->layouts->set_title('Edit book details');
        $this->layouts->admin_view('issuebook/addeditdata', array(), $data);
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
        $this->common_model->edit_data('lib_issue_return_book', $param, $changeStatusId);

        $this->session->set_flashdata('alert_success', lang('statussuccess'));

        redirect(correctLink('issueBookAdminData', $this->session->userdata('SMS_LIBRARY_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
    }

}
