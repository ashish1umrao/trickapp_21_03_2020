<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class issuebook extends CI_Controller {

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
        $data['error'] = '';
        $this->admin_model->authCheck('admin', 'view_data');
        $this->admin_model->get_permission_type($data);


        $data['return_date'] = date('d-m-Y', strtotime(' +15 day'));

        if ($this->input->post('SaveChanges')):

            $error = 'NO';
            $this->form_validation->set_rules('barcode', 'book name', 'trim');
            $this->form_validation->set_rules('reader_id', 'book author', 'trim|required');
            $this->form_validation->set_rules('return_date', 'book price', 'trim|required');


            $validateBarcode = $this->input->post('barcode');

            ///validate  barcode exist


            $existingQuery = "SELECT barcode_id FROM sms_lib_barcode WHERE barcode ='" . $validateBarcode . "' AND STATUS = 'Y' ;";
            $existing      = $this->common_model->get_data_by_query('single', $existingQuery);

            if (!$existing['barcode_id']):
                $error                = 'YES';
                $data['barcodeError'] = 'Sorry! No existing book barcode found';

            endif;




            /// validate barcode already assign
            $alreadyAQuery   = "SELECT issue_return_id FROM sms_lib_issue_return_book WHERE barcode ='" . $validateBarcode . "'  AND STATUS = 'issued' ;";
            $alreadyAssigned = $this->common_model->get_data_by_query('single', $alreadyAQuery);

            if ($alreadyAssigned['issue_return_id']):
                $error                = 'YES';
                $data['barcodeError'] = ' Sorry! Book is alredy issued';

            endif;


            /// validate active book for barcode
            $BookQuery  = "SELECT  sms_lib_books.encrypt_id , barcode_id , sms_lib_barcode.encrypt_id as barcode_encrypt_id ,book_lost ,book_issued ,book_quantity FROM `sms_lib_barcode` LEFT JOIN `sms_lib_books` ON sms_lib_barcode.book_id = sms_lib_books.encrypt_id WHERE (barcode ='" . $validateBarcode . "' AND sms_lib_books.status = 'Y' ) ;";
            $activeBook = $this->common_model->get_data_by_query('single', $BookQuery);

            if (!$activeBook['barcode_id']):
                $error                = 'YES';
                $data['barcodeError'] = ' Sorry! No active book found corrosponing to barcode';

            endif;


            if (($activeBook['book_quantity'] - ($activeBook['book_lost'] + $activeBook['book_issued'])) <= 0):
                $error                = 'YES';
                $data['barcodeError'] = ' Sorry! No book avaliable to issue';

            endif;


            //valid student 





            $subQuery = "SELECT br.student_qunique_id ,stud.student_f_name ,stud.student_m_name ,stud.student_l_name  ,CONCAT(stud.student_f_name,' ',stud.student_m_name,' ',stud.student_l_name) AS NAME FROM `sms_student_branch` AS br  
                                                                        LEFT JOIN `sms_students` AS stud ON br.student_qunique_id = stud.student_qunique_id 
                                                                                 WHERE br.student_registration_no = '" . $this->input->post('reader_id') . "' 
										AND br.school_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID') . "' 
										AND br.branch_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID') . "' 
                                                                                    AND stud.status = 'A'
										";
            $studData = $this->common_model->get_data_by_query('single', $subQuery);



            $subQuery  = "SELECT encrypt_id ,user_type FROM `sms_users` 
                                                                                 WHERE sms_users.employee_id = '" . $this->input->post('reader_id') . "' 
                                                                                     AND sms_users.user_type != 'Parent' 
										AND sms_users.school_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID') . "' 
										AND sms_users.branch_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID') . "' 
                                                                                    AND sms_users.status = 'A'
										";
            $staffData = $this->common_model->get_data_by_query('single', $subQuery);


            if (($studData['student_qunique_id'] == '') and ( $staffData['encrypt_id'] == '')):

                $error               = 'YES';
                $data['readerError'] = ' Sorry! No User found';

            endif;
            //max book issue limit 
             $cQuery  = "SELECT  COUNT(reader_id) AS issued_book FROM  `sms_lib_issue_return_book`   WHERE reader_id  = '" . $this->input->post('reader_id') . "' AND STATUS ='issued' 
										
										";
            $issuedcount = $this->common_model->get_data_by_query('single', $cQuery);
           
            if($studData['student_qunique_id']):
                
                $lQuery  = "SELECT issue_limit FROM `sms_lib_issue_limit_at_atime`  WHERE STATUS='Y'  and  reader_type_id='TUFOT0oxS1VNQVI=' and franchise_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID') . "' 
								 AND school_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID') . "' 
								 AND branch_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID') . "' 
								  " ;
                $issuelimit = $this->common_model->get_data_by_query('single', $lQuery);
                
                elseif($staffData['encrypt_id']):
                    
                $lQuery  = "SELECT issue_limit FROM `sms_lib_issue_limit_at_atime`  WHERE STATUS='Y'  and  reader_type_id='TUFOT0oyS1VNQVI=' and franchise_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID') . "' 
								 AND school_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID') . "' 
								 AND branch_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID') . "' 
								  " ;
                    $issuelimit = $this->common_model->get_data_by_query('single', $lQuery);
                
            endif;
            
            
										
									
        
            
            
               if ($issuelimit['issue_limit'] <= $issuedcount['issued_book']  ):

                $error               = 'YES';
                $data['issueLimitError'] = ' Sorry! Maximum Number of books are already  issued';

            endif;
            //  same book of diffrent bookid is assigned
            
             $bQuery  = "SELECT 1 FROM `sms_lib_issue_return_book` AS ir
LEFT JOIN `sms_lib_barcode` AS   br ON br.barcode = ir.barcode
 WHERE ir.status ='issued' AND ir.reader_id= '".$this->input->post('reader_id')."' AND br.book_id =(SELECT book_id  FROM `sms_lib_barcode` WHERE barcode='".$this->input->post('barcode')."') and ir.franchise_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID') . "' 
								 AND ir.school_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID') . "' 
								 AND ir.branch_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID') . "' 
								  " ;
          
            
            
              $bookAissue = $this->common_model->get_data_by_query('single', $bQuery);
             if ($bookAissue):

                $error               = 'YES';
                $data['bookAissue'] = ' Sorry! Same book of diffrent book id is alredy issued';

            endif;

            if ($this->form_validation->run() && $error == 'NO'):

                $param['barcode']   = addslashes($this->input->post('barcode'));
                $param['reader_id'] = addslashes($this->input->post('reader_id'));
                if ($studData['student_qunique_id']):
                    $param['reader_type'] = 'student';
                else:
                    $param['reader_type'] = $staffData['user_type'];
                endif;
                $param['return_date'] = \DDMMYYtoYYMMDD($this->input->post('return_date'));
                $param['issue_date']  = currentDateTime();

                $param['franchise_id'] = $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID');
                $param['school_id']    = $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID');
                $param['branch_id']    = $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID');

                $param['creation_date'] = currentDateTime();
                $param['created_by']    = $this->session->userdata('SMS_LIBRARY_ADMIN_ID');
                $param['status']        = 'issued';
                $param['session_year']			=	CURRENT_SESSION;
                $alastInsertId             = $this->common_model->add_data('lib_issue_return_book', $param);
                $Uparam['encrypt_id']      = manojEncript($alastInsertId);
                $Uwhere['issue_return_id'] = $alastInsertId;
                $bookaddId                 = $Uparam['encrypt_id'];
                $this->common_model->edit_data_by_multiple_cond('lib_issue_return_book', $Uparam, $Uwhere);

                // add assign book to book table
                $bookId                = $activeBook['encrypt_id'];
                $params['book_issued'] = $activeBook['book_issued'] + 1;
                $params['update_date'] = currentDateTime();
                $params['updated_by']  = $this->session->userdata('SMS_LIBRARY_ADMIN_ID');
                $this->common_model->edit_data('sms_lib_books', $params, $bookId);
                
                
                //update book barcode table
                
                 $Bparams['book_status'] = 'issued';
                $Bparams['update_date'] = currentDateTime();
                $Bparams['updated_by']  = $this->session->userdata('SMS_LIBRARY_ADMIN_ID');
                $this->common_model->edit_data('sms_lib_barcode', $Bparams, $activeBook['barcode_encrypt_id']);

                $this->session->set_flashdata('alert_success', lang('addsuccess'));






            endif;
        endif;

        $this->layouts->set_title('Issue book');
        $this->layouts->admin_view('issuebook/index', array(), $data);
    }

    /*     * *********************************************************************
     * * Function name : get_book_detail
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for issuebook
     * * Date : 16 JANUARY 2018
     * * ********************************************************************* */

    function get_book_detail() {
        $html = '';
        if ($this->input->post('barcode')):
            $barcode = $this->input->post('barcode');


            $bookQuery = "SELECT  br.*  , shr.shelf_row , sh.shelf ,b.book_name ,b.book_author FROM `sms_lib_barcode` AS br  
LEFT JOIN  `sms_lib_shelf_row` AS shr  ON  shr.encrypt_id = br.shelf_row_id
LEFT JOIN   `sms_lib_shelf` AS sh  ON  sh.encrypt_id = shr.shelf_id
LEFT JOIN  `sms_lib_books` AS b  ON  b.encrypt_id = br.book_id
							   WHERE br.barcode  = '" . $barcode . "' and br.franchise_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID') . "' 
								 AND br.school_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID') . "' 
								 AND br.branch_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID') . "' 
								 ";

            $bookData = $this->common_model->get_data_by_query('single', $bookQuery);

          
            if ($bookData <> ""):
                $html = '<p style="color: #8b5c7e;">Book Name  - <span style="color: #db5e4b;"> '.$bookData['book_name'].' </span> </p>
          <p style="color: #8b5c7e;"> Book Author -<span style="color: #db5e4b;"> '.$bookData['book_author'].' </span> </p>
           <p style="color: #8b5c7e;"> Book Location - <span style="color: #db5e4b;"> Shelf No.- '.$bookData['shelf'].' ,Row No -'.$bookData['shelf_row'].'  </span></p>';


            endif;
        endif;
        echo $html;
        die;
    }

    /*     * *********************************************************************
     * * Function name : get_reader_detail
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for issuebook
     * * Date : 16 JANUARY 2018
     * * ********************************************************************* */

    function get_reader_detail() {
        $html = '';
        if ($this->input->post('reader_id')):
         



            $subQuery = "SELECT br.student_qunique_id ,stud.student_f_name ,stud.student_m_name ,stud.student_l_name  ,CONCAT(stud.student_f_name,' ',stud.student_m_name,' ',stud.student_l_name) AS NAME FROM `sms_student_branch` AS br  
                                                                        LEFT JOIN `sms_students` AS stud ON br.student_qunique_id = stud.student_qunique_id 
                                                                                 WHERE br.student_registration_no = '" . $this->input->post('reader_id') . "' 
										AND br.school_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID') . "' 
										AND br.branch_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID') . "' 
                                                                                    AND stud.status = 'A'
										";
            $readerData = $this->common_model->get_data_by_query('single', $subQuery);
   $user_type = 'Student' ;
if(!$readerData['student_qunique_id']):

            $subQuery  = "SELECT encrypt_id ,user_type , user_m_name ,user_l_name  ,CONCAT(user_f_name,' ',user_m_name,' ',user_l_name) AS NAME FROM `sms_users` 
                                                                                 WHERE sms_users.employee_id = '" . $this->input->post('reader_id') . "' 
                                                                                     AND sms_users.user_type != 'Parent' 
										AND sms_users.school_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID') . "' 
										AND sms_users.branch_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID') . "' 
                                                                                    AND sms_users.status = 'A'
										";
            $readerData = $this->common_model->get_data_by_query('single', $subQuery);
           
               $user_type = $readerData['user_type'] ;
endif;
  $cQuery  = "SELECT  COUNT(reader_id) AS issued_book FROM  `sms_lib_issue_return_book`   WHERE reader_id  = '" . $this->input->post('reader_id') . "' AND STATUS ='issued' 
										
										";
            $count = $this->common_model->get_data_by_query('single', $cQuery);
      
; 
            if ($readerData <> ""):
                 $html = '<p style="color: #8b5c7e;">Reader Name  - <span style="color: #db5e4b;"> '.$readerData['NAME'].' ( '.$user_type.'  ) </span> </p>
         <p style="color: #8b5c7e;"> Number of Books Already Issued -<span style="color: #db5e4b;"> '.$count['issued_book'].' </span> </p> ';



            endif;
        endif;
        echo $html;
        die;
    }

}
