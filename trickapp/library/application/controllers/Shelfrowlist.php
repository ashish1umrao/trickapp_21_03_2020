<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class shelfrowlist extends CI_Controller {

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
     * * Function name : classlist
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for classlist
     * * Date : 23 JANUARY 2018
     * * ********************************************************************* */

    public
            function index() {
        $this->admin_model->authCheck('admin', 'view_data');
        $this->admin_model->get_permission_type($data);
        $data['error'] = '';

        if ($this->input->get('searchValue')):
            $sValue              = $this->input->get('searchValue');
            $whereCon['like']    = "(sh.shelf LIKE '%" . $sValue . "%' 
			                                      )";
            $data['searchValue'] = $sValue;
        else:
            $whereCon['like']    = "";
            $data['searchValue'] = '';
        endif;

        $whereCon['where'] = "sh.franchise_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID') . "'
												 AND sh.school_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID') . "' 
												 AND sh.branch_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID') . "'
												 ";
        $shortField        = 'sh.shelf_id ASC';

        $this->load->library('pagination');
        $config['base_url']   = $this->session->userdata('SMS_LIBRARY_ADMIN_PATH') . $this->router->fetch_class() . '/index/';
        $this->session->set_userdata('shelfRowListAdminData', currentFullUrl());
        $qStringdata          = explode('?', currentFullUrl());
        $config['suffix']     = $qStringdata[1] ? '?' . $qStringdata[1] : '';
        $tblName              = 'lib_shelf as sh';
        $con                  = '';
        $config['total_rows'] = $this->admin_model->SelectshelfListData('count', $tblName, $whereCon, $shortField, '0', '0');

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

        $data['ALLDATA'] = $this->admin_model->SelectshelfListData('data', $tblName, $whereCon, $shortField, $config['per_page'], $page);

        $this->layouts->set_title('Manage shelf details');
        $this->layouts->admin_view('shelfrowlist/index', array(), $data);
    }

// END OF FUNCTION

    /*     * *********************************************************************
     * * Function name : addeditdata
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for add edit data
     * * Date : 23 JANUARY 2018
     * * ********************************************************************* */

    public
            function addeditdata($editid = '') {
        $data['error']     = '';
        $subQuery          = "SELECT sh.*  FROM `sms_lib_shelf`  AS sh   where
sh.franchise_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID') . "'
												 AND sh.school_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID') . "' 
												 AND sh.branch_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID') . "'
                                                                                                     AND status='Y'
												 ";
        $data['SHELFDATA'] = $this->common_model->get_data_by_query('multiple', $subQuery);




        if ($editid):
            $this->admin_model->authCheck('admin', 'edit_data');
            $data['EDITDATA'] = $this->common_model->get_data_by_encryptId('lib_shelf', $editid);


            $sRowQuery           = "SELECT *
										 FROM sms_lib_shelf_row WHERE shelf_id = '" . $editid . "'";
            $data['SDETAILDATA'] = $this->common_model->get_data_by_query('multiple', $sRowQuery);
        else:
            $this->admin_model->authCheck('admin', 'add_data');
        endif;

        if ($this->input->post('SaveChanges')):


            $error = 'NO';
            $this->form_validation->set_rules('shelf', 'Shelf No.', 'trim|required');


            $serror        = 0;
            $TotalRowCount = $this->input->post('TotalRowCount');

            if ($TotalRowCount):

                for ($i = 1; $i <= $TotalRowCount; $i++):
                    $this->form_validation->set_rules('shelf_row_id_' . $i, 'lang:Row id', 'trim');
                    $this->form_validation->set_rules('shelf_row_' . $i, 'lang:shelf row', 'trim');
                    $this->form_validation->set_rules('max_books_' . $i, 'lang: Max books', 'trim');

                    if ($this->input->post('shelf_row_' . $i)):
                        $serror = '1';

                    endif;

                    $data['shelf_row_id_' . $i] = $this->input->post('shelf_row_id_' . $i);
                    $data['shelf_row_' . $i]    = $this->input->post('shelf_row_' . $i);
                    $data['max_books_' . $i]    = $this->input->post('max_books_' . $i);
                endfor;
                $this->form_validation->set_rules('TotalRowCount', 'lang:Total Shelf Row', 'trim');
            endif;

            $data['suberror'] = 'Please fill atleast one shelf row details.';
            if ($serror == 0):
                $error = 'YES';
            endif;

            if ($this->form_validation->run() && $error == 'NO'):


                $param['shelf'] = addslashes($this->input->post('shelf'));


                if ($this->input->post('CurrentDataID') == ''):

                    $param['franchise_id'] = $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID');
                    $param['school_id']    = $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID');
                    $param['branch_id']    = $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID');

                    $param['creation_date'] = currentDateTime();
                    $param['created_by']    = $this->session->userdata('SMS_LIBRARY_ADMIN_ID');
                    $param['status']        = 'Y';
                     $param['session_year']			=	CURRENT_SESSION;
                    $alastInsertId          = $this->common_model->add_data('lib_shelf', $param);
                    $Uparam['encrypt_id']   = manojEncript($alastInsertId);
                    $Uwhere['shelf_id']     = $alastInsertId;
                    $shelfaddId             = $Uparam['encrypt_id'];
                    $this->common_model->edit_data_by_multiple_cond('lib_shelf', $Uparam, $Uwhere);



                    $SRparams['shelf_id'] = $Uparam['encrypt_id'];

                    if ($TotalRowCount):
                        for ($i = 1; $i <= $TotalRowCount; $i++):
                            if ($this->input->post('shelf_row_' . $i)):

                                $SRparams['shelf_row'] = addslashes($this->input->post('shelf_row_' . $i));

                                $SRparams['max_books']    = $this->input->post('max_books_' . $i);
                                $SRparams['franchise_id'] = $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID');
                                $SRparams['school_id']    = $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID');
                                $SRparams['branch_id']    = $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID');

                                $SRparams['creation_date'] = currentDateTime();
                                $SRparams['created_by']    = $this->session->userdata('SMS_LIBRARY_ADMIN_ID');

                                $SRlastInsertId = $this->common_model->add_data('lib_shelf_row', $SRparams);

                                $SRUparam['encrypt_id']   = manojEncript($SRlastInsertId);
                                $SRUwhere['shelf_row_id'] = $SRlastInsertId;
                                $this->common_model->edit_data_by_multiple_cond('lib_shelf_row', $SRUparam, $SRUwhere);
                            endif;
                        endfor;
                    endif;

                else:

                    $shelfId              = $this->input->post('CurrentDataID');
                    $param['update_date'] = currentDateTime();
                    $param['updated_by']  = $this->session->userdata('SMS_LIBRARY_ADMIN_ID');
                    $this->common_model->edit_data('lib_shelf', $param, $shelfId);
                    $this->session->set_flashdata('alert_success', lang('updatesuccess'));


                    $rowdataarray = array();
                    if ($TotalRowCount):
                        for ($i = 1; $i <= $TotalRowCount; $i++):
                            if ($this->input->post('shelf_row_' . $i)):
                                if ($this->input->post('shelf_row_id_' . $i)):
                                    $rowid = $this->input->post('shelf_row_id_' . $i);

                                    $SRUparam['shelf_row']    = addslashes($this->input->post('shelf_row_' . $i));
                                    $SRUparam['max_books']    = $this->input->post('max_books_' . $i);
                                    $SRUparam['franchise_id'] = $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID');
                                    $SRUparam['school_id']    = $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID');
                                    $SRUparam['branch_id']    = $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID');

                                    $SRUparam['creation_date'] = currentDateTime();
                                    $SRUparam['created_by']    = $this->session->userdata('SMS_LIBRARY_ADMIN_ID');
                                    $SRUwhere['encrypt_id']    = $rowid;
                                    $SRUwhere['shelf_id']      = $shelfId;
                                    $this->common_model->edit_data_by_multiple_cond('lib_shelf_row', $SRUparam, $SRUwhere);

                                    array_push($rowdataarray, $rowid);
                                else:
                                    $UCPparams['franchise_id']  = $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID');
                                    $UCPparams['school_id']     = $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID');
                                    $UCPparams['branch_id']     = $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID');
                                    $UCPparams['creation_date'] = currentDateTime();
                                    $UCPparams['created_by']    = $this->session->userdata('SMS_LIBRARY_ADMIN_ID');
                                    $UCPparams['shelf_id']      = $shelfId;
                                    $UCPparams['shelf_row']     = addslashes($this->input->post('shelf_row_' . $i));
                                    $UCPparams['max_books']     = $this->input->post('max_books_' . $i);

                                    $UCPlastInsertId = $this->common_model->add_data('lib_shelf_row', $UCPparams);

                                    $UCPUparam['encrypt_id']   = manojEncript($UCPlastInsertId);
                                    $UCPUwhere['shelf_row_id'] = $UCPlastInsertId;
                                    $this->common_model->edit_data_by_multiple_cond('lib_shelf_row', $UCPUparam, $UCPUwhere);
                                endif;
                            endif;
                        endfor;
                        if ($data['SDETAILDATA'] <> "" && count($rowdataarray) > 0):
                            foreach ($data['SDETAILDATA'] as $SDETAILINFO):
                                if (!in_array($SDETAILINFO['encrypt_id'], $rowdataarray)):
                                    $this->common_model->delete_data('lib_shelf_row', $SDETAILINFO['encrypt_id']);
                                endif;
                            endforeach;
                        endif;
                    endif;

                endif;

                redirect(correctLink('shelfRowListAdminData', $this->session->userdata('SMS_LIBRARY_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
            endif;
        endif;

        $this->layouts->set_title('Edit shelf row details');
        $this->layouts->admin_view('shelfrowlist/addeditdata', array(), $data);
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

        $bookCountQuery = "SELECT COUNT(barcode_id) as count FROM `sms_lib_barcode`  WHERE shelf_id =  '" . $changeStatusId . "'  AND STATUS = 'Y'";
        $bookCountData  = $this->common_model->get_data_by_query('single', $bookCountQuery);

        if (($bookCountData['count'] != '0') and ( $statusType == 'N')):

            $this->session->set_flashdata('alert_error', lang('shelfInactiveError'));


        else:
            $param['status']      = $statusType;
            $param['update_date'] = currentDateTime();
            $param['updated_by']  = $this->session->userdata('SMS_LIBRARY_ADMIN_ID');
            $this->common_model->edit_data('lib_shelf', $param, $changeStatusId);


            $this->session->set_flashdata('alert_success', lang('statussuccess'));
        endif;




        redirect(correctLink('shelfRowListAdminData', $this->session->userdata('SMS_LIBRARY_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
    }

    /*     * *********************************************************************
     * * Function name : get_view_data
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for get view data.
     * * Date : 15 JANUARY 2018
     * ********************************************************************** */

    function get_view_data() {
        $html = '';
        if ($this->input->post('viewid')):
            $viewId = $this->input->post('viewid');

            $viewquery = "SELECT * ,(SELECT COUNT(barcode_id)  FROM `sms_lib_barcode`  WHERE shelf_row_id = sh.encrypt_id  AND STATUS = 'Y') as count FROM `sms_lib_shelf_row` as sh WHERE sh.shelf_id = '" . $viewId . "'   ";

            $viewData = $this->common_model->get_data_by_query('multiple', $viewquery);

            if ($viewData <> ""):

                $html .= '<table class="table border-none">
								  <tbody>';
                foreach ($viewData as $V):
                    $html .= '<tr>
									
									  <td  align="left" width="20%"><strong>Shelf Row No.</strong></td>
									  <td align="left" width="10%">' . stripslashes($V['shelf_row']) . '</td>
                                                                                <td align="left" width="30%"><strong>Max Book Capacity   </strong></td>
									  <td align="left" width="10%">' . stripslashes($V['max_books']) . '</td>
									  
                                                                              <td align="left" width="300%"><strong>Number Of Books  </strong></td>
									  <td align="left" width="10%">' . stripslashes($V['count']) . '</td>
									
									</tr>';
                endforeach;
                $html .= '</tbody>
								</table>';
            endif;
        endif;
        echo $html;
        die;
    }

    /*     * *********************************************************************
     * * Function name : relocatebook
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for issuebook
     * * Date : 16 JANUARY 2018
     * * ********************************************************************* */

    public
            function relocatebook($currentShelfId = '') {


        $this->admin_model->authCheck('admin', 'view_data');
        $this->admin_model->get_permission_type($data);
        $data['error']        = '';
        $data['curentRowId']  = $this->input->get('current_row_id');
        $data['shelf_id']     = $this->input->post('shelf_id');
        $data['shelf_row_id'] = $this->input->post('shelf_row_id');
        $subQuery             = "SELECT sh.*  FROM `sms_lib_shelf`  AS sh   where
sh.franchise_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID') . "'
												 AND sh.school_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID') . "' 
												 AND sh.branch_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID') . "'
                                                                                                     AND status='Y'
												 ";
        $data['SHELFDATA']    = $this->common_model->get_data_by_query('multiple', $subQuery);



        $CshelfRowQuery    = "SELECT shr.*  FROM `sms_lib_shelf_row`  AS shr   where   shelf_id = '" . $currentShelfId . "' and
shr.franchise_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID') . "'
												 AND shr.school_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID') . "' 
												 AND shr.branch_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID') . "'
                                                                                                     AND shr.status='Y'
												 ";
        $data['cShelfRow'] = $this->common_model->get_data_by_query('multiple', $CshelfRowQuery);




        $data['currentShelfInfo'] = $this->common_model->get_data_by_encryptId('lib_shelf', $currentShelfId);


        if ($this->input->get('current_row_id')):




            $whereCon['like']    = "";
            $data['searchValue'] = '';

            $whereCon['where'] = "  bk.shelf_row_id = '" . $this->input->get('current_row_id') . "' and bk.franchise_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID') . "'
												 AND bk.school_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID') . "' 
												 AND bk.branch_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID') . "'
												 ";
            $shortField        = 'bk.barcode_id ASC';

            $this->load->library('pagination');
            $config['base_url'] = $this->session->userdata('SMS_LIBRARY_ADMIN_PATH') . $this->router->fetch_class() . '/relocatebook/' . $currentShelfId . '/';
            $this->session->set_userdata('bookcopyListAdminData', currentFullUrl());
            $qStringdata        = explode('?', currentFullUrl());

            $config['suffix'] = $qStringdata[1] ? '?' . $qStringdata[1] : '';

            $tblName              = 'lib_barcode as bk';
            $con                  = '';
            $config['total_rows'] = $this->admin_model->SelectshelfbookListData('count', $tblName, $whereCon, $shortField, '0', '0');


            $config['per_page'] = $config['total_rows'];
            $data['perpage']    = $this->input->get('showLength');


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

            if ($this->input->post('DataId')):
                  $ldata = $this->input->post('DataId');
                   $error              = 'No';
                 $countQuery             = "SELECT COUNT(*) as count FROM `sms_lib_barcode` WHERE (  shelf_row_id = '" . $this->input->post('shelf_row_id') . "'  AND STATUS='Y')";
        $existingBookShelfArray = $this->common_model->get_data_by_query('single', $countQuery);
        $existingBookShelf      = $existingBookShelfArray['count'];
        $MaxQuery               = "SELECT max_books FROM `sms_lib_shelf_row`    WHERE (  encrypt_id = '" . $this->input->post('shelf_row_id') . "'  AND STATUS='Y')";

        $maxbshelfArry = $this->common_model->get_data_by_query('single', $MaxQuery);
        $maxbshelfrow  = $maxbshelfArry['max_books'];

        if (($maxbshelfrow - $existingBookShelf ) < count($ldata)):
            $error              = 'Yes';
            $data['quntyError'] = " Maximum '" . ($maxbshelfrow - $existingBookShelf) . "' Books can be added to shelf Row  ";
          
        endif;
                
                
                
                
                
              

                $this->form_validation->set_rules('shelf_id', 'shelf id', 'trim|required');
                $this->form_validation->set_rules('shelf_row_id', 'shelf row id', 'trim|required');
                if ($this->form_validation->run() and $error == 'No'):
                    $param['shelf_row_id'] = addslashes($this->input->post('shelf_row_id'));
                    $param['shelf_id']     = addslashes($this->input->post('shelf_id'));
                    $param['update_date']  = currentDateTime();

                    $param['updated_by'] = $this->session->userdata('SMS_LIBRARY_ADMIN_ID');


                    if ($this->input->post('check_all') == 'check_all'):
                        $whecon['shelf_row_id'] = $data['curentRowId'];

                        $this->common_model->edit_data_by_multiple_cond('lib_barcode', $param , $whecon);



                    else:

                        for ($i = 0; $i < count($ldata); $i++):
                            $id = $ldata[$i];

                            $this->common_model->edit_data('lib_barcode', $param, $id);




                        endfor;
                    endif;
                  
$this->session->set_flashdata('alert_success', lang('statussuccess'));
                endif;
  
            endif;


            $data['ALLDATA'] = $this->admin_model->SelectshelfbookListData('data', $tblName, $whereCon, $shortField, $config['per_page'], $page);


            







        endif;

        $this->layouts->set_title('relocate  book');
        $this->layouts->admin_view('shelfrowlist/relocatebook', array(), $data);
    }

}
