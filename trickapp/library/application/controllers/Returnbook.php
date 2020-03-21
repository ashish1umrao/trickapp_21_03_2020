<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Returnbook extends CI_Controller {

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
    
        $data['error']     = '';
        $data['reader_id'] = $this->input->get('reader_id');
        $currentDate       = date_create(date('Y-m-d'));


        if ($this->input->get('reader_id')):

            $error = 'NO';



            //valid student   

            $subQuery = "SELECT br.student_qunique_id ,stud.student_f_name ,stud.student_m_name ,stud.student_l_name  ,CONCAT(stud.student_f_name,' ',stud.student_m_name,' ',stud.student_l_name) AS NAME FROM `sms_student_branch` AS br  
                                                                        LEFT JOIN `sms_students` AS stud ON br.student_qunique_id = stud.student_qunique_id 
                                                                                 WHERE br.student_registration_no = '" . $this->input->get('reader_id') . "' 
										AND br.school_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID') . "' 
										AND br.branch_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID') . "' 
                                                                                    AND stud.status = 'A'
										";

            $studData = $this->common_model->get_data_by_query('single', $subQuery);
          
             //valid staff   
             $subQuery					=	"SELECT encrypt_id ,user_type ,user_f_name ,user_m_name ,user_l_name  ,CONCAT(user_f_name,' ',user_m_name,' ',user_l_name) AS NAME FROM `sms_users` 
                                                                                 WHERE sms_users.employee_id = '".$this->input->get('reader_id')."' 
                                                                                     AND sms_users.user_type != 'Parent' 
										AND sms_users.school_id = '".$this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID')."' 
										AND sms_users.branch_id = '".$this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID')."' 
                                                                                    AND sms_users.status = 'A'
										";
             
             
             
		$staffData		=	$this->common_model->get_data_by_query('single',$subQuery);  
            
               
                
            
            
            
            
            
            $fQuery     = "SELECT fine_per_day   from sms_lib_fine
                                                                                 WHERE 
										 school_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID') . "' 
										AND branch_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID') . "' 
                                                                                    AND status = 'Y'
										";

            $fineData = $this->common_model->get_data_by_query('multiple', $fQuery);


            if(($studData['student_qunique_id'] == '') and ($staffData['encrypt_id'] == '')):

                $error               = 'YES';
                $data['readerError'] = ' Sorry! No User found';

            endif;
            if($studData):
            $data['readerData'] = $studData;
            else:
                  $data['readerData'] = $staffData;
            endif;
          
            
            
            if ($error == 'NO'):









                $whereCon['where'] = " Rb.reader_id= '" . addslashes($this->input->get('reader_id')) . "' and Rb.status='issued' AND Rb.franchise_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID') . "'
												 AND Rb.school_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID') . "' 
												 AND Rb.branch_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID') . "'
												 ";
                $shortField        = 'Rb.issue_return_id ASC';

                $this->load->library('pagination');
                $config['base_url']   = $this->session->userdata('SMS_LIBRARY_ADMIN_PATH') . $this->router->fetch_class() . '/index/';
                $this->session->set_userdata('returnBookListData', currentFullUrl());
                $qStringdata          = explode('?', currentFullUrl());
                $config['suffix']     = $qStringdata[1] ? '?' . $qStringdata[1] : '';
                $tblName              = 'lib_issue_return_book as Rb';
                $con                  = '';
                $config['total_rows'] = $this->admin_model->SelectReturnbookListData('count', $tblName, $whereCon, $shortField, '0', '0');


                $config['per_page'] = SHOW_NO_OF_DATA;
                $data['perpage']    = SHOW_NO_OF_DATA;


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


                //    $data['ALLDATA'] = $this->admin_model->SelectReturnbookListData('data', $tblName, $whereCon, $shortField, $config['per_page'], $page);
                $allData = $this->admin_model->SelectReturnbookListData('data', $tblName, $whereCon, $shortField, $config['per_page'], $page);

                if ($allData):
                    
                    $aDataFine = array();
                $data['total_fine'] = '' ;
                    foreach ($allData as $a):

                        $returnDate = date_create($a['return_date']);
                        $diff       = date_diff($currentDate, $returnDate);
                    
                        if($diff->format("%R") == '-'):
                        $fDay       = $diff->format("%a");
                        else:
                           $fDay = '0'; 
                        endif;
                       
                        $before_1_week = $fineData['0']['fine_per_day'];
                        $after_1_week  = $fineData['1']['fine_per_day'];
                        $after_1_month = $fineData['2']['fine_per_day'];
                      
//calculate fine
                      
                        if ($fDay == '0'):
                            $fine = '0';
                        elseif ($fDay <= '7'):
                            $fine = $fDay * $before_1_week;
                        
                        elseif (($fDay > '7') and ( $fDay <= '30')):
 
                            $fine = 7 * $before_1_week;
                            
                                $fine = $fine + ($fDay - 7) * $after_1_week;
                           
                        elseif ($fDay > '30'):

                            $fine = 7 * $before_1_week + 23*$after_1_week ;
                            $fine = ( $fDay - 30 )*$after_1_month  + $fine ; 
                        endif;
                   $a['fine'] = $fine;
                  
                  $data['total_fine'] = $fine +  $data['total_fine']   ;
                        array_push($aDataFine, $a);
                    endforeach;
                    $data['ALLDATA'] = $aDataFine;

                endif;




            endif;
        endif;

        $this->layouts->set_title('Return book');
        $this->layouts->admin_view('returnbook/index', array(), $data);
    }

    /*     * *********************************************************************
     * * Function name : changestatus
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for change status
     * * Date : 16 JANUARY 2018
     * ********************************************************************** */

    function changestatus($changeStatusId = '', $statusType = '' , $bookId = '', $barcodeId = '' ,$fine = '') {
       
        $this->admin_model->authCheck('admin', 'edit_data');
        $subQuery = " SELECT reader_id FROM`sms_lib_issue_return_book` WHERE encrypt_id='" . $changeStatusId . "' ";

        $readerData      = $this->common_model->get_data_by_query('single', $subQuery);
        $reader_id       = $readerData['reader_id'];
        $param['status'] = $statusType;
        $param['update_date'] = currentDateTime();
       $param['updated_by']  = $this->session->userdata('SMS_LIBRARY_ADMIN_ID');
      if($statusType =='return'):
         $param['actual_return_date']  = currentDateTime();
       endif;
        $param['fine'] =  $fine;
        
        if($fine):
              $param['fine_paid'] = 'Y';
        endif;
        $this->common_model->edit_data('lib_issue_return_book', $param, $changeStatusId);
        $bQuery = " SELECT * FROM `sms_lib_books` WHERE encrypt_id='" . $bookId . "' ";

        $bData      = $this->common_model->get_data_by_query('single', $bQuery);
        
        
        
    
   
                    $params['book_issued'] = $bData['book_issued'] - 1;
                      $Bparams['book_status'] = 'library';
                    if($statusType=='lost'):
                          $params['book_lost'] = $bData['book_lost'] + 1;
                     $Bparams['book_status'] = 'lost';
                    endif;
                    
                    $params['update_date'] = currentDateTime();
                    $params['updated_by']  = $this->session->userdata('SMS_LIBRARY_ADMIN_ID');
                   
                    $this->common_model->edit_data('sms_lib_books', $params, $bookId);
        
                    
                     
                $Bparams['update_date'] = currentDateTime();
                $Bparams['updated_by']  = $this->session->userdata('SMS_LIBRARY_ADMIN_ID');
                $this->common_model->edit_data('sms_lib_barcode', $Bparams, $barcodeId);
        
        $this->session->set_flashdata('alert_success', lang('statussuccess'));

        redirect(correctLink('returnBookAdminData', $this->session->userdata('SMS_LIBRARY_ADMIN_PATH') . $this->router->fetch_class() . '/index/?reader_id=' . $reader_id));
    }

}
