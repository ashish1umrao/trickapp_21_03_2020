<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class booklist extends CI_Controller {

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
     * * Function name : booklist
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for booklist
     * * Date : 16 JANUARY 2018
     * * ********************************************************************* */

    public function index() {
        $this->admin_model->authCheck('admin', 'view_data');
        $this->admin_model->get_permission_type($data);
        $data['error'] = '';

        if ($this->input->get('searchValue')):
            $sValue              = $this->input->get('searchValue'); 
            $whereCon['like']    = "(bk.book_name LIKE '%" . $sValue . "%' OR bk.book_author LIKE '%" . $sValue . "%' OR lib_book_category.category_name LIKE '%" . $sValue . "%'
			                                      )";
            $data['searchValue'] = $sValue;
        else:
            $whereCon['like']    = "";
            $data['searchValue'] = '';
        endif;

        $whereCon['where'] = "bk.franchise_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID') . "'
												 AND bk.school_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID') . "' 
												 AND bk.branch_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID') . "'
												 ";
        $shortField        = 'bk.book_id ASC';

        $this->load->library('pagination');
        $config['base_url']   = $this->session->userdata('SMS_LIBRARY_ADMIN_PATH') . $this->router->fetch_class() . '/index/';
        $this->session->set_userdata('bookListAdminData', currentFullUrl());
        $qStringdata          = explode('?', currentFullUrl());
     
        $config['suffix']     = $qStringdata[1] ? '?' . $qStringdata[1] : ''; 
      
        $tblName              = 'lib_books as bk';
        $con                  = '';
        $config['total_rows'] = $this->admin_model->SelectbookListData('count', $tblName, $whereCon, $shortField, '0', '0');

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

        $data['ALLDATA'] = $this->admin_model->SelectbookListData('data', $tblName, $whereCon, $shortField, $config['per_page'], $page);

        $this->layouts->set_title('Manage book details');
        $this->layouts->admin_view('booklist/index', array(), $data);
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

        
        	$btypeQuery					=	"SELECT * FROM sms_lib_book_type WHERE status = 'Y'";
		$data['BTYPEDATA']			=	$this->common_model->get_data_by_query('multiple',$btypeQuery);
                $blQuery					=	"SELECT * FROM sms_lib_book_language WHERE status = 'Y'";
		$data['BLDATA']			=	$this->common_model->get_data_by_query('multiple',$blQuery);
                
              $subQuery          = "SELECT cat.*  FROM `sms_lib_book_category`  AS cat   where
cat.franchise_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID') . "'
												 AND cat.school_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID') . "' 
												 AND cat.branch_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID') . "'
                                                                                                     AND cat.status='Y'
												 ";
        $data['BookCatData'] = $this->common_model->get_data_by_query('multiple', $subQuery);
        
        
        

        if ($editid):
            $this->admin_model->authCheck('admin', 'edit_data');
            $data['EDITDATA'] = $this->common_model->get_data_by_encryptId('lib_books', $editid);
        else:
            $this->admin_model->authCheck('admin', 'add_data');
        endif;

        if ($this->input->post('SaveChanges')):


            $error = 'NO';
            $this->form_validation->set_rules('book_name', 'book name', 'trim');
            $this->form_validation->set_rules('book_author', 'book author', 'trim|required');
            $this->form_validation->set_rules('book_price', 'book price', 'trim|required');
            $this->form_validation->set_rules('book_type_id', 'book type', 'trim|required');
               $this->form_validation->set_rules('book_language_id', 'book language', 'trim|required');
              $this->form_validation->set_rules('book_category_id', 'book category', 'trim|required');



            if ($this->form_validation->run() && $error == 'NO'):

                $param['book_name']   = addslashes($this->input->post('book_name'));
                $param['book_author'] = addslashes($this->input->post('book_author'));
                $param['book_price']  = addslashes($this->input->post('book_price'));
                  $param['book_type_id']  = addslashes($this->input->post('book_type_id'));
                   $param['book_language_id']  = addslashes($this->input->post('book_language_id'));
                   $param['book_category_id']  = addslashes($this->input->post('book_category_id'));

                $param['book_description'] = addslashes($this->input->post('book_description'));
               

                if ($this->input->post('CurrentDataID') == ''):
                    $param['franchise_id'] = $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID');
                    $param['school_id']    = $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID');
                    $param['branch_id']    = $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID');

                    $param['creation_date'] = currentDateTime();
                    $param['created_by']    = $this->session->userdata('SMS_LIBRARY_ADMIN_ID');
                    $param['status']        = 'Y';
                       $param['session_year']			=	CURRENT_SESSION;
                    $alastInsertId          = $this->common_model->add_data('lib_books', $param);
                    $Uparam['encrypt_id']   = manojEncript($alastInsertId);
                    $Uwhere['book_id']      = $alastInsertId;
                    $bookaddId              = $Uparam['encrypt_id'];
                    $this->common_model->edit_data_by_multiple_cond('lib_books', $Uparam, $Uwhere);
                    $this->session->set_flashdata('alert_success', lang('addsuccess'));
                else:
                  
                    $param['update_date'] = currentDateTime();
                    $param['updated_by']  = $this->session->userdata('SMS_LIBRARY_ADMIN_ID');
                    $this->common_model->edit_data('lib_books', $param, $this->input->post('CurrentDataID'));
                    
                    $this->session->set_flashdata('alert_success', lang('updatesuccess'));
                endif;




                redirect(correctLink('bookListAdminData', $this->session->userdata('SMS_LIBRARY_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
            endif;
        endif;

        $this->layouts->set_title('Edit book details');
        $this->layouts->admin_view('booklist/addeditdata', array(), $data);
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
        $this->common_model->edit_data('lib_books', $param, $changeStatusId);

        $this->session->set_flashdata('alert_success', lang('statussuccess'));

        redirect(correctLink('bookListAdminData', $this->session->userdata('SMS_LIBRARY_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
    }

    /*     * *********************************************************************
     * * Function name : barcodegenrate
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for Teacherlist
     * * Date : 01 FEBRUARY 2018
     * * ********************************************************************* */

    public
            function addcopy($bookid) {
   
        $this->admin_model->authCheck('admin', 'view_data');
        $this->admin_model->get_permission_type($data);
        $data['error']     = '';
        
        
           $bookData = $this->common_model->get_data_by_encryptId('lib_books', $bookid);
      
$subQuery          = "SELECT sh.*  FROM `sms_lib_shelf`  AS sh   where
sh.franchise_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID') . "'
												 AND sh.school_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID') . "' 
												 AND sh.branch_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID') . "'
                                                                                                     AND status='Y'
												 ";
        $data['SHELFDATA'] = $this->common_model->get_data_by_query('multiple', $subQuery);


        if ($this->input->post('submit')):
            
            
            
            $error = 'NO';
            $this->form_validation->set_rules('shelf_id', 'shelf id', 'trim|required');
            $this->form_validation->set_rules('shelf_row_id', 'shelf row id', 'trim|required');
            $this->form_validation->set_rules('barcode_no', 'book quantity', 'trim|required');
  

        //  create bar code prefix


        $subQuery           = "SELECT b.book_id AS book,b.book_name,b.book_author,b.book_quantity, branch.admin_id AS branch , s.admin_id  AS school ,f.admin_id  AS franchise  FROM `sms_lib_books`  AS b   
LEFT JOIN        `sms_admin`AS branch ON  b.branch_id =  branch.encrypt_id 
LEFT JOIN      `sms_admin`AS s  ON b.school_id =  s.encrypt_id 
LEFT JOIN      `sms_admin`AS f ON  b.franchise_id =  f.encrypt_id 
WHERE b.encrypt_id = '".$bookid."'";
       
        $barcodeEncryptData = $this->common_model->get_data_by_query('single', $subQuery);
        $data['BOOKDATA']   = $barcodeEncryptData;
       
        //   number of books can be added to row shelf        
        $countQuery             = "SELECT COUNT(*) as count FROM `sms_lib_barcode` WHERE (  shelf_row_id = '" . $this->input->post('shelf_row_id') . "'  AND STATUS='Y')";
        $existingBookShelfArray = $this->common_model->get_data_by_query('single', $countQuery);
        $existingBookShelf      = $existingBookShelfArray['count'];
        $MaxQuery               = "SELECT max_books FROM `sms_lib_shelf_row`    WHERE (  encrypt_id = '" . $this->input->post('shelf_row_id') . "'  AND STATUS='Y')";

        $maxbshelfArry = $this->common_model->get_data_by_query('single', $MaxQuery);
        $maxbshelfrow  = $maxbshelfArry['max_books'];

        if (($maxbshelfrow - $existingBookShelf ) < $this->input->post('barcode_no')):
            $error              = 'Yes';
            $data['quntyError'] = " Maximum '" . ($maxbshelfrow - $existingBookShelf) . "' Books can be added to shelf Row  ";

        endif;

        //last barcode of book
        $lastBookQuery = "SELECT barcode FROM `sms_lib_barcode` WHERE book_id = '" . $bookid . "'   ORDER BY barcode_id DESC LIMIT 0,1";

        $lastBookArray = $this->common_model->get_data_by_query('single', $lastBookQuery);

        $lastBPostfix = substr($lastBookArray['barcode'], 5);

            if ($this->form_validation->run() && $error == 'NO'):

                $param['shelf_id']     = addslashes($this->input->post('shelf_id'));
                $param['shelf_row_id'] = addslashes($this->input->post('shelf_row_id'));
                $barcode_quantity      = addslashes($this->input->post('barcode_no'));
               
                $librarian_id  = $this->common_model->get_data_by_encryptId('users', $this->session->userdata('SMS_LIBRARY_ADMIN_ID'));
                $barcodeprefix = $barcodeEncryptData['book'] . "" . $barcodeEncryptData['branch'] . "" . $barcodeEncryptData['school'] . "" . $barcodeEncryptData['franchise'] . "" . $librarian_id['user_id'];
                $PNG_TEMP_DIR  = './assets/barcode/S_' . manojDecript($this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID')) . '/B_' . manojDecript($this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID')) . DIRECTORY_SEPARATOR . $barcodeEncryptData['book'] . DIRECTORY_SEPARATOR;
                ;
               
                if (!file_exists($PNG_TEMP_DIR)):
                    mkdir($PNG_TEMP_DIR, 0777, true);
                endif;
                $barcode_array = array();
                for ($i = 1; $i <= $barcode_quantity; $i++):
                    $barcode                = $barcodeprefix . "" . ($i + $lastBPostfix);
                    $param['shelf_id']      = addslashes($this->input->post('shelf_id'));
                    $param['shelf_row_id']  = addslashes($this->input->post('shelf_row_id'));
                    $param['franchise_id']  = $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID');
                    $param['school_id']     = $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID');
                    $param['branch_id']     = $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID');
                    $param['barcode']       = $barcode;
                    $param['book_id']       = $bookid;
                    $param['creation_date'] = currentDateTime();
                    $param['created_by']    = $this->session->userdata('SMS_LIBRARY_ADMIN_ID');
                    $param['status']        = 'Y';
                       $param['session_year']			=	CURRENT_SESSION;
                    $alastInsertId          = $this->common_model->add_data('lib_barcode', $param);
                    $Uparam['encrypt_id']   = manojEncript($alastInsertId);
                    $Uwhere['barcode_id']   = $alastInsertId;
                    $bookaddId              = $Uparam['encrypt_id'];
                    $this->common_model->edit_data_by_multiple_cond('lib_barcode', $Uparam, $Uwhere);

                    array_push($barcode_array, $barcode);
                    $this->set_barcode($barcode, $PNG_TEMP_DIR);

                endfor;
                /// add books quantity in sms_lib_books_add table
                $params['franchise_id']  = $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID');
                $params['school_id']     = $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID');
                $params['branch_id']     = $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID');
                $params['book_id']       = $bookid;
                $params['book_quantity'] = $barcode_quantity;
                $params['creation_date'] = currentDateTime();
                $params['created_by']    = $this->session->userdata('SMS_LIBRARY_ADMIN_ID');
                $params['status']        = 'Y';
                   $params['session_year']			=	CURRENT_SESSION;
                $alastInseraddtId        = $this->common_model->add_data('lib_books_add', $params);
                $Uparams['encrypt_id']   = manojEncript($alastInseraddtId);
                $Uwheres['books_add_id'] = $alastInseraddtId;

                $this->common_model->edit_data_by_multiple_cond('lib_books_add', $Uparams, $Uwheres);

                $UBparam['book_quantity'] = $barcode_quantity + $data['BOOKDATA']['book_quantity'];
                $UBparam['update_date']   = currentDateTime();
                $UBparam['updated_by']    = $this->session->userdata('SMS_LIBRARY_ADMIN_ID');
                $this->common_model->edit_data('lib_books', $UBparam, $bookid);






                $this->load->library('Mpdf');
                $book            = $barcodeEncryptData['book_name'];
                $data['book']    = $book;
                $bookname        = str_replace(' ', '_', $barcodeEncryptData['book_name']);
                $data['barcode'] = $barcode_array;
                $data['folder']  = $PNG_TEMP_DIR;
                $this->layouts->set_title('Display barcode');
                $this->load->view('booklist/printinpdf', $data);
                $this->download_pdf($bookname . '.pdf');



            endif;
        endif;
        $this->layouts->set_title('Download Add copy & Genrate barcode');
        $this->layouts->admin_view('booklist/addcopy', array(), $data);
    }

// END OF FUNCTION


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

    private
            function set_barcode($code, $PNG_TEMP_DIR) {
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $file = Zend_Barcode::draw('code128', 'image', array('text' => $code), array());


        $store_image = imagepng($file, "$PNG_TEMP_DIR{$code}.png");
        return true;
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
			   $viewData = $this->common_model->get_data_by_encryptId('lib_books', $viewId);
                          $BooktypeQuery = "SELECT book_type FROM `sms_lib_book_type` WHERE encrypt_id = '" . $viewData['book_type_id'] . "'   ";
                            $BooklQuery = "SELECT book_language FROM `sms_lib_book_language` WHERE encrypt_id = '" . $viewData['book_language_id'] . "'   ";
           $BooktypeArrey = $this->common_model->get_data_by_query('single', $BooktypeQuery);
        $BooklArrey = $this->common_model->get_data_by_query('single', $BooklQuery);
        
        
           $BookinventryQuery = "SELECT  COUNT(*) AS current_book_shelf_row ,   s.shelf ,sr.shelf_row  FROM `sms_lib_barcode`  AS br
LEFT JOIN `sms_lib_shelf` AS s ON s.encrypt_id =  br.shelf_id
LEFT JOIN `sms_lib_shelf_row` AS sr ON sr.encrypt_id =  br.shelf_row_id
 WHERE book_id = '".$viewId."'  AND book_status = 'library'
 GROUP BY br.shelf_row_id
 ORDER BY br.shelf_id ,br.shelf_row_id 
";
           
       
        $BookinventryArrey = $this->common_model->get_data_by_query('multiple', $BookinventryQuery);
        
      
			if($viewData <> ""):
			
				if($BookinventryArrey):
                                    
                                    foreach ($BookinventryArrey as $Binventry):
				$html			.=	'    <table class="table border-none">
											<thead>
											<tr>
											  <th align="left" colspan="4"><strong>Book\'s Inventory</strong></th>
											</tr>
											</thead>
											<tbody>
<tr>
												  <td align="left" width="20%"><strong>Location</strong></td>
												  <td align="left" width="30%">shelf No. -'.stripslashes($Binventry['shelf']).' row No. -'.stripslashes($Binventry['shelf_row']).'</td>
												  <td align="left" width="20%"><strong>current Quantity</strong></td>
												  <td align="left" width="30%">'.stripslashes($Binventry['current_book_shelf_row']).'</td>
												</tr>  ';                                                                                     
                               endforeach;  endif;
									$html			.=	'	  <table class="table border-none">
											<thead>
											<tr>
											  <th align="left" colspan="4"><strong>Book\'s Detail</strong></th>
											</tr>
											</thead>
											<tbody>
                                    

                                                                      <tr>
									  <td align="left" width="30%"><strong>Book Name</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['book_name']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Book Name</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['book_author']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Total Books</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['book_quantity']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>   Avaliable Books  </strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['book_quantity'] - ($viewData['book_issued'] + $viewData['book_lost'] )).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Issued Books</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['book_issued']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Lost Books</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['book_lost']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Book Price   </strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['book_price']).' &#8377</td>
									</tr>
									
									<tr>
									  <td align="left" width="30%"><strong>Discription</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['book_description']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Book Type</strong></td>
									  <td align="left" width="70%">'.stripslashes($BooktypeArrey['book_type']).'</td>
									</tr>
                                                                        <tr>
									  <td align="left" width="30%"><strong>Book Language</strong></td>
									  <td align="left" width="70%">'.stripslashes($BooklArrey['book_language']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Status</strong></td>
									  <td align="left" width="70%">'.showStatus($viewData['status']).'</td>
									</tr>';
				$html			.=	'</tbody>
										
									';
			endif;
		endif;
		echo $html; die;
	}
    
    /*     * *********************************************************************
     * * Function name : bookcopy
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for booklist
     * * Date : 16 JANUARY 2018
     * * ********************************************************************* */

    public function bookcopy($bookId) {
        
   
        $this->admin_model->authCheck('admin', 'view_data');
        $this->admin_model->get_permission_type($data);
        $data['error'] = '';
        $data['book_id'] =     $bookId ; 
         $bookData = $this->common_model->get_data_by_encryptId('lib_books', $bookid);
        if ($this->input->get('searchValue')):
            $sValue              = $this->input->get('searchValue');
            $whereCon['like']    = "(bk.barcode LIKE '%" . $sValue . "%' 
			                                      )";
            $data['searchValue'] = $sValue;
        else:
            $whereCon['like']    = "";
            $data['searchValue'] = '';
        endif;

        $whereCon['where'] = "  bk.book_id = '".$bookId."' and bk.franchise_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID') . "'
												 AND bk.school_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID') . "' 
												 AND bk.branch_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID') . "'
												 ";
        $shortField        = 'bk.barcode_id ASC';

        $this->load->library('pagination');
        $config['base_url']   = $this->session->userdata('SMS_LIBRARY_ADMIN_PATH') . $this->router->fetch_class() . '/bookcopy/'.$bookId .'/' ;
        $this->session->set_userdata('bookcopyListAdminData', currentFullUrl());
      $qStringdata          = explode('?', currentFullUrl());

        $config['suffix']     = $qStringdata[1] ? '?' . $qStringdata[1] : ''; 
       
        $tblName              = 'lib_barcode as bk';
        $con                  = '';
        $config['total_rows'] = $this->admin_model->SelectbookcopyListData('count', $tblName, $whereCon, $shortField, '0', '0');

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

     
        $data['ALLDATA'] = $this->admin_model->SelectbookcopyListData('data', $tblName, $whereCon, $shortField, $config['per_page'], $page);

        $this->layouts->set_title('Manage book copy  details');
        $this->layouts->admin_view('booklist/bookcopy', array(), $data);
    }
 /*     * *********************************************************************
     * * Function name : downloadbarcode
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for change status
     * * Date : 16 JANUARY 2018
     * ********************************************************************** */

    function downloadbarcode($barcodeId = '', $bookid = '') {
      
        $this->admin_model->authCheck('admin', 'edit_data');
            $bookData = $this->common_model->get_data_by_encryptId('lib_books', $bookid);
             $PNG_TEMP_DIR  = './assets/barcode/S_' . manojDecript($this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID')) . '/B_' . manojDecript($this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID')) . DIRECTORY_SEPARATOR . $bookData['book_id'] . DIRECTORY_SEPARATOR;
          $this->load->library('Mpdf');
                $book            = $bookData['book_name'];
                $data['book']    = $book;
                $bookname        = str_replace(' ', '_', $bookData['book_name']);
                $barcode_array = array() ;
                   array_push($barcode_array, $barcodeId);
                $data['barcode'] = $barcode_array;
                $data['folder']  = $PNG_TEMP_DIR;
                $this->layouts->set_title('Display barcode');
                $this->load->view('booklist/printinpdf', $data);
                $this->download_pdf($bookname . '.pdf');

        

        redirect(correctLink('bookcopyListAdminData', $this->session->userdata('SMS_LIBRARY_ADMIN_PATH') . $this->router->fetch_class() . '/bookcopy/'.$barcodeId .'/' ));
    }
    

}
