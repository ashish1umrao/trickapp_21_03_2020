<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Homework extends CI_Controller {

	public function  __construct()  
	{ 
		parent:: __construct();
		$this->load->helper(array('form','url','html','path','form','cookie'));
		$this->load->library(array('email','session','form_validation','pagination','parser','encrypt'));
		error_reporting(E_ALL ^ E_NOTICE);  
		$this->load->library("layouts");
		$this->load->model(array('admin_model','common_model','emailtemplate_model','sms_model'));
		$this->load->helper('language');
		$this->lang->load('statictext', 'admin');
	} 
	
	/* * *********************************************************************
	 * * Function name : News
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for News
	 * * Date : 14 DECEMBER 2018
	 * * **********************************************************************/
	public function index()
	{	
		$this->admin_model->authCheck('admin','view_data');
		$this->admin_model->get_permission_type($data);
		$data['error'] 						= 	'';
		
		if($this->input->get('searchValue')):
			$sValue							=	$this->input->get('searchValue');
			$whereCon['like']		 		= 	"(news.news_title LIKE '%".$sValue."%' 
												  OR news.current_year LIKE '%".$sValue."%')";
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchValue'] 			= 	'';
		endif;
		
		$asubQuery   = "SELECT home.home_work_id,cl.class_name,sect.class_section_name,sub.subject_name,				home.home_work_title,home.home_work_content,home.home_work_file,
						home.home_work_from_date,home.home_work_to_date,home.creation_date 
						from sms_home_work home INNER JOIN sms_classes cl on home.class_id=cl.encrypt_id
						INNER JOIN sms_class_section sect on home.section_id=sect.encrypt_id
						INNER JOIN sms_subject sub on home.subject_id=sub.encrypt_id
						WHERE home.franchise_id='" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' AND home.school_id='" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
						AND home.branch_id='" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
						AND home.board_id='" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "' 
						AND home.status = 'Y'";
		//print $asubQuery; die;					

        $data['ALLDATA'] = $this->common_model->get_data_by_query('multiple', $asubQuery);
		//print "<pre>"; print_r($data['ALLHOMEWORK']); die;
		$this->load->library('pagination');
		$config['base_url'] 				= 	$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index';
		$this->session->set_userdata('newsAdminData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$config['suffix']					= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'news as news';
		$con 								= 	'';
		$config['total_rows'] 				= 	$this->admin_model->SelectNewsData('count',$tblName,$whereCon,$shortField,'0','0');
		
		if($this->input->get('showLength') == 'All'):
			$config['per_page']	 			= 	$config['total_rows'];
			$data['perpage'] 				= 	$this->input->get('showLength');  
		elseif($this->input->get('showLength')):
			$config['per_page']	 			= 	$this->input->get('showLength'); 
			$data['perpage'] 				= 	$this->input->get('showLength'); 
		else:
			$config['per_page']	 			= 	SHOW_NO_OF_DATA;
			$data['perpage'] 				= 	SHOW_NO_OF_DATA; 
		endif;
		
		$config['uri_segment'] = getUrlSegment();
       $this->pagination->initialize($config);

       if ($this->uri->segment(getUrlSegment())):
           $page = $this->uri->segment(getUrlSegment());
       else:
           $page = 0;
       endif;
		
		$data['forAction'] 					= 	$config['base_url']; 
		if($config['total_rows']):
			$first							=	($page)+1;
			$data['first']					=	$first;
			$last							=	(($page)+$data['perpage'])>$config['total_rows']?$config['total_rows']:(($page)+$data['perpage']);
			$data['noOfContent']			=	'Showing '.$first.'-'.$last.' of '.$config['total_rows'].' items';
		else:
			$data['first']					=	1;
			$data['noOfContent']			=	'';
		endif;
		
		//$data['ALLDATA'] 					= 	$this->admin_model->SelectNewsData('data',$tblName,$whereCon,$shortField,$config['per_page'],$page); 
             
		$this->layouts->set_title('Manage home work details');
		$this->layouts->admin_view('homework/index',array(),$data);
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name : addeditdata
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for add edit data
	 * * Date : 14 DECEMBER 2018
	 * * **********************************************************************/
	public function addeditdata($editid='')
	{		
		$data['error'] 				= 	'';
		
		if($editid):
			$this->admin_model->authCheck('admin','edit_data');
			$data['EDITDATA']		=	$this->common_model->get_data_by_encryptId('news',$editid);
		else:
			$this->admin_model->authCheck('admin','add_data');
		endif;
		
		if($this->input->post('SaveChanges')):
			$error					=	'NO';
			$this->form_validation->set_rules('news_title', 'Title', 'trim|required');
			$this->form_validation->set_rules('news_description', 'Content', 'trim|required');
			$this->form_validation->set_rules('uploadimage0', 'Picture', 'trim|required');
			
			if($this->form_validation->run() && $error == 'NO'):   
			
				$param['news_title']		= 	addslashes($this->input->post('news_title'));
				$param['news_description']	= 	addslashes($this->input->post('news_description'));
				$param['news_image']		= 	addslashes($this->input->post('uploadimage0'));
				
				if($this->input->post('CurrentDataID') ==''):
					$param['franchise_id']		=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
					$param['school_id']			=	$this->session->userdata('SMS_ADMIN_SCHOOL_ID');
					$param['branch_id']			=	$this->session->userdata('SMS_ADMIN_BRANCH_ID');
					$param['current_year']		=	date('Y');
					$param['creation_date']		=	currentDateTime();
					$param['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$param['status']			=	'Y';
                    $param['session_year']		=	CURRENT_SESSION;
					$alastInsertId				=	$this->common_model->add_data('news',$param);
					
					$Uparam['encrypt_id']		=	manojEncript($alastInsertId);
					$Uwhere['news_id']			=	$alastInsertId;
					$this->common_model->edit_data_by_multiple_cond('news',$Uparam,$Uwhere);
					$this->session->set_flashdata('alert_success',lang('addsuccess'));
				else:
					$newsId						=	$this->input->post('CurrentDataID');
					$param['update_date']		=	currentDateTime();
					$param['updated_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$this->common_model->edit_data('news',$param,$newsId);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;
				
				redirect(correctLink('newsAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
			endif;
		endif;
		
		$this->layouts->set_title('Edit news details');
		$this->layouts->admin_view('news/addeditdata',array(),$data);
	}	// END OF FUNCTION	
	
	/***********************************************************************
	** Function name : changestatus
	** Developed By : Manoj Kumar
	** Purpose  : This function used for change status
	** Date : 14 DECEMBER 2018
	************************************************************************/
	function changestatus($changeStatusId='',$statusType='')
	{  
		$this->admin_model->authCheck('admin','edit_data');
		
		$param['status']		=	$statusType;
		$this->common_model->edit_data('news',$param,$changeStatusId);
		
		$this->session->set_flashdata('alert_success',lang('statussuccess'));
		
		redirect(correctLink('newsAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
	}

	/* * *********************************************************************
     * * Function name : uplode_image
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used uplode image
     * * Date : 14 DECEMBER 2018
     * ********************************************************************** */
    function uplode_image() {
        $file_name = $_FILES['uploadfile']['name'];
        if ($file_name):
            $tmp_name = $_FILES['uploadfile']['tmp_name'];

            $ext         = pathinfo($file_name);
            $newfilename = time() . '.' . $ext['extension'];
            $this->load->library("upload_crop_img");
            $return_file_name = $this->upload_crop_img->_upload_image($file_name, $tmp_name, 'newsImage', $newfilename, '');
            echo $return_file_name; die;
        else:
            echo 'UPLODEERROR'; die;
        endif;
    } // END OF FUNCTION

    /* * *********************************************************************
     * * Function name : DeleteCurrentImage
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for delete image by ajax.
     * * Date : 14 DECEMBER 2018
     * ********************************************************************** */
    function DeleteCurrentImage() {
        $imagename = $this->input->post('imagename');
        if ($imagename):
            $this->load->library("upload_crop_img");
            $return = $this->upload_crop_img->_delete_image($imagename);
        endif;
        echo '1';
        die;
    } // END OF FUNCTION	
}