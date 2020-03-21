<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class adminclasswisepayfee extends CI_Controller {

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
     * * Function name : Index
     * * Developed By : Ashish UMrao
     * * Purpose  : This function used for index
     * * Date : 08 JULY 2018
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
        $desgQuery		=	"SELECT encrypt_id, name
							FROM sms_set_assessment
							WHERE school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
							AND branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."' 
							AND board_id = '".$this->session->userdata('SMS_ADMIN_BOARD_ID')."' 
							AND status='Y'";

		$data['SHEADDATA']	=	$this->common_model->get_data_by_query('multiple',$desgQuery);
		$desgQuery			=	"SELECT encrypt_id, name
								FROM sms_exam_term
								WHERE school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
								AND branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."' 
								AND board_id = '".$this->session->userdata('SMS_ADMIN_BOARD_ID')."' 
								AND status='Y'";

		$data['SHEADDATA1']			=	$this->common_model->get_data_by_query('multiple',$desgQuery);
        $whereCon['where'] = "fhead.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
								 AND fhead.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
								 AND fhead.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
								 AND fhead.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";
        $shortField        = 'fhead.fee_head_name DESC';
        $this->load->library('pagination');
        $config['base_url']   = $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index';
        $this->session->set_userdata('studentclasswisefeeAdminData', currentFullUrl());
        $qStringdata          = explode('?', currentFullUrl());
        $config['suffix']     = $qStringdata[1] ? '?' . $qStringdata[1] : '';
        $tblName              = 'sms_student_final_paybal_amount as fhead';
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

        $data['ALLDATA'] = $this->admin_model->SelectstudenttotalamountListData('data', $tblName, $whereCon, $shortField, $config['per_page'], $page);
		
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

        $this->layouts->set_title('Manage Pay Fee');
        $this->layouts->admin_view('classwisepayfee/index', array(), $data);
    } // END OF FUNCTION

    /* * *********************************************************************
     * * Function name : addeditdata
     * * Developed By : Ashish UMrao
     * * Purpose  : This function used for add edit data
     * * Date : 08 JULY 2019
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

        $desgQuery		=	"SELECT encrypt_id, name
        FROM sms_set_assessment
        WHERE school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
        AND branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."' 
        AND board_id = '".$this->session->userdata('SMS_ADMIN_BOARD_ID')."' 
        AND status='Y'";

    $data['SHEADDATA']	=	$this->common_model->get_data_by_query('multiple',$desgQuery);

    $desgQuery			=	"SELECT encrypt_id, name
            FROM sms_exam_term
            WHERE school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
            AND branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."' 
            AND board_id = '".$this->session->userdata('SMS_ADMIN_BOARD_ID')."' 
            AND status='Y'";

    $data['SHEADDATA1']			=	$this->common_model->get_data_by_query('multiple',$desgQuery);
//$config['base_url'] 				= 	$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index';
//$this->session->set_userdata('smstempleteAdminData',currentFullUrl());

$smstemplategQuery	  =	 "SELECT encrypt_id, sms_type FROM sms_sms_template WHERE status = 'A'";
$data['SMSTEMPLATE']  =	 $this->common_model->get_data_by_query('multiple',$smstemplategQuery);

$subQuery          = "SELECT encrypt_id, class_name FROM sms_classes
WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
        AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
        AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
        AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";
$data['CLASSDATA'] = $this->common_model->get_data_by_query('multiple', $subQuery);


		if($data['STARTMONTHDATA'] <> ""):
			$data['STARTMONTH']		=	$data['STARTMONTHDATA']['session_month_name'];
		else:
			$data['STARTMONTH']		=	'April';
		endif;
		
        if ($editid):
            $this->admin_model->authCheck('admin', 'edit_data');
            $data['FEEHEADINGLIST']   =  $this->admin_model->get_all_fee_heading_list();
            $data['EDITDATA'] = $this->common_model->get_data_by_encryptId('student_final_paybal_amount', $editid);
        else:
            $this->admin_model->authCheck('admin', 'add_data');
        endif;    
        if ($this->input->post('SaveChanges')):  //echo "<pre>"; print_r($_POST); die;
            $error = 'NO';

            $this->form_validation->set_rules('class_id', 'class id', 'trim|required');
            $this->form_validation->set_rules('section_id', 'section id', 'trim|required');
            $this->form_validation->set_rules('student_id', 'student id', 'trim|required');
            $this->form_validation->set_rules('fee_head_name', 'Fees heading', 'trim|required');
            //$this->form_validation->set_rules('fee_due_date', 'Due date', 'trim|required');
            //$this->form_validation->set_rules('fee_refundable', 'Refundable', 'trim|required');
            //$this->form_validation->set_rules('fee_concession_type', 'Concession type', 'trim|required');
            $this->form_validation->set_rules('per_month_fee', 'per month fee', 'trim|required');
            $this->form_validation->set_rules('feeamount', 'feeamount', 'trim|required');
            $this->form_validation->set_rules('due_fine', 'due fine', 'trim');
            $this->form_validation->set_rules('total_paybal_amount', 'total paybal amount', 'trim|required');
            $this->form_validation->set_rules('month_name[]', 'month name[]', 'trim|required');
          
            /* $month	=	date("m", strtotime($data['STARTMONTH']));
         
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
    endfor;*/
          
            if ($this->form_validation->run() && $error == 'NO'): echo "<pre>"; print_r($_POST); die;
                $param['class_id']                   = addslashes($this->input->post('class_id'));
                $param['section_id']                 = addslashes($this->input->post('section_id'));
                $param['student_id']                 = addslashes($this->input->post('student_id')); 
                $param['fee_head_name']              = addslashes($this->input->post('fee_head_name'));
                //$param['fee_refundable']             = addslashes($this->input->post('fee_refundable'));
                //$param['fee_concession_type']        = addslashes($this->input->post('fee_concession_type'));
                $param['per_month_fee']             = addslashes($this->input->post('per_month_fee'));
                $param['feeamount']                 = addslashes($this->input->post('feeamount'));
                $param['due_fine']                  = addslashes($this->input->post('due_fine'));  
                $param['total_paybal_amount']       = addslashes($this->input->post('total_paybal_amount')); 

                if ($this->input->post('CurrentDataID') == ''):
                    $param['franchise_id']  = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                    $param['school_id']     = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                    $param['branch_id']     = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                    $param['board_id']      = $this->session->userdata('SMS_ADMIN_BOARD_ID');
                    $param['creation_date'] = currentDateTime();
                    $param['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                     $param['session_year']			=	CURRENT_SESSION;
                    $param['status']        = 'Y';
                    $alastInsertId          = $this->common_model->add_data('student_final_paybal_amount', $param);

                    $Uparam['encrypt_id']       = manojEncript($alastInsertId);
                    $Uwhere['id'] = $alastInsertId;
                    $this->common_model->edit_data_by_multiple_cond('student_final_paybal_amount', $Uparam, $Uwhere);
                    $this->session->set_flashdata('alert_success', lang('addsuccess'));
                else:
                    $TDPROGId             = $this->input->post('CurrentDataID');
                    $param['update_date'] = currentDateTime();
                    $param['updated_by']  = $this->session->userdata('SMS_ADMIN_ID');
                    $this->common_model->edit_data('student_final_paybal_amount', $param, $TDPROGId);
                    $this->session->set_flashdata('alert_success', lang('updatesuccess'));
                endif;
                ////////////////////////////////////ADD Multiple Month /////////////////////////////////////////

                $monthName   = $this->input->post('month_name');
                //print_r($monthName); die;
                if($monthName<>""):
                        foreach ($monthName as $name) :
                            $Tparam['student_pay_fee_id']     =  $alastInsertId;
                            $Tparam['month_name']             = $name;
                            $Tparam['created_date']          = currentDateTime();
                            $Tparam['submission_date']          = currentDateTime();
                            //echo "<pre>"; print_r($Tparam); die;
                            $this->common_model->add_data('fee_paybal_month', $Tparam);
                        endforeach;
                endif;
                redirect(correctLink('studentclasswisefeeAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
            endif;
        endif;

        $this->layouts->set_title('Edit Pay Fee');
        $this->layouts->admin_view('classwisepayfee/addeditdata', array(), $data);
    } // END OF FUNCTION	

    /* * *********************************************************************
     * * Function name : changestatus
     * * Developed By : Ashish UMrao
     * * Purpose  : This function used for change status
     * * Date : 8 JULY 2019
     * ********************************************************************** */

    function changestatus($changeStatusId = '', $statusType = '') {
        $this->admin_model->authCheck('admin', 'edit_data');

        $param['status'] = $statusType;
        $this->common_model->edit_data('student_final_paybal_amount', $param, $changeStatusId);

        $this->session->set_flashdata('alert_success', lang('statussuccess'));

        redirect(correctLink('feeheadListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
    }

    /* * *********************************************************************
     * * Function name : getStudentList
     * * Developed By : Ashish UMrao
     * * Purpose  : This function used for get Student List
     * * Date : 8 JULY 2019
     * ********************************************************************** */
    function getStudentList(){
		//echo "okk"; die;
		 $class_id = $this->input->post('class_id');
		 $section_id = $this->input->post('section_id');	
		$studentQuery	=	"SELECT stu.* FROM sms_student_class class 
                    		INNER JOIN sms_students stu on class.student_qunique_id=stu.student_qunique_id 
                    		WHERE class.class_id='".$class_id."' AND class.section_id='".$section_id."' 
                    		AND class.school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
                    		AND class.branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."' 
                    		AND class.board_id = '".$this->session->userdata('SMS_ADMIN_BOARD_ID')."' 
                    		AND class.status='Y'
                    		GROUP By stu.student_qunique_id 
                            ORDER BY stu.student_id DESC 
                            ";
		$studentData	=	$this->common_model->getDataByQuery('multiple',$studentQuery);
		//echo "<pre>"; print_r($studentData); die;
		$count = $this->common_model->getDataByQuery('count',$studentQuery);
		echo   '<select name="student_id"  id="langOpt3" class="form-control">';
		foreach ($studentData as $key => $value) {
			echo '<option value='.$value['student_qunique_id'].'>'.$value['student_f_name'].'</option>';
		}                
         echo   '</select>';         
    }
 /* * *********************************************************************
     * * Function name : getclasswisefeee
     * * Developed By : Ashish UMrao
     * * Purpose  : This function used for get Class wise fee List
     * * Date : 8 JULY 2019
     * ********************************************************************** */
        function getclasswisefeee(){
            error_reporting(0);
		 $class_id = $this->input->post('class_id');
		 $fee_heading_id = $this->input->post('fee_heading_id');	
        $studentfeeQuery	=	"SELECT cwfee.id,cwfee.encrypt_id,cwfee.class_id,cwfee.per_month_fee FROM sms_class_wise_fee as cwfee 
		LEFT JOIN sms_classes as c on cwfee.class_id=c.encrypt_id 
		WHERE cwfee.class_id='".$class_id."' AND cwfee.fee_head_id='".$fee_heading_id."'"; 
		$studentData	=	$this->common_model->getDataByQuery('multiple',$studentfeeQuery);
		$count = $this->common_model->getDataByQuery('count',$studentfeeQuery);
		//echo   '<input name="per_month_fee"  id="langOpt4">';
		foreach ($studentData as $key => $value) { //print_r($value['per_month_fee']); die;
        //	echo '<option value='.$value['id'].'>'.$value['per_month_fee'].'</option>';
            echo $value['per_month_fee'];
        }  
    }
    /***********************************************************************
	** Function name : get_view_data
	** Developed By : Ashish UMrao
	** Purpose  : This function used for get view data.
	** Date : 09 JULY 2019
	************************************************************************/
	function get_view_data()
	{
        $html					=	'';
		if($this->input->post('viewid')): 
            $viewId				=	$this->input->post('viewid'); 
            //echo $viewId; die;
			$viewData			=	$this->common_model->get_student_fee_data_by_encryptId($viewId);
			if($viewData <> ""):
				$html			.=	'<table class="table border-none">
								  <tbody>
									<tr>
									  <td align="left" width="20%"><strong>Subject</strong></td>
									  <td align="left" width="30%">'.stripslashes($subjectName).'</td>
									  <td align="left" width="20%"><strong>Employee id</strong></td>
									  <td align="left" width="30%">'.stripslashes($viewData['employee_id']).'</td>
									
									</tr>';
				$html			.=	'</tbody>
								</table>';
			endif;
		endif;
		echo $html; die;
	}              
                 
}