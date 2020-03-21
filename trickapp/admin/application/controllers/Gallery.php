<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery extends CI_Controller {

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
	 * * Function name : Gallery
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for Gallery
	 * * Date : 14 DECEMBER 2018
	 * * **********************************************************************/
	public function index()
	{	
		$this->admin_model->authCheck('admin','view_data');
		$this->admin_model->get_permission_type($data);
		$data['error'] 						= 	'';
		
		if($this->input->get('searchValue')):
			$sValue							=	$this->input->get('searchValue');
			$whereCon['like']		 		= 	"(gall.gallery_name LIKE '%".$sValue."%' 
												  OR gall.current_year LIKE '%".$sValue."%')";
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchValue'] 			= 	'';
		endif;
		
		$whereCon['where']		 			= 	"gall.franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' 
												 AND gall.school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
												 AND gall.branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."'";		
		$shortField 						= 	'UNIX_TIMESTAMP(gall.creation_date) DESC';
		
		$this->load->library('pagination');
		$config['base_url'] 				= 	$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index';
		$this->session->set_userdata('galleryAdminData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$config['suffix']					= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'gallery as gall';
		$con 								= 	'';
		$config['total_rows'] 				= 	$this->admin_model->SelectGalleryData('count',$tblName,$whereCon,$shortField,'0','0');
		
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
		
		$data['ALLDATA'] 					= 	$this->admin_model->SelectGalleryData('data',$tblName,$whereCon,$shortField,$config['per_page'],$page); 
             
		$this->layouts->set_title('Manage gallery details');
		$this->layouts->admin_view('gallery/index',array(),$data);
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
			$data['EDITDATA']		=	$this->common_model->get_data_by_encryptId('gallery',$editid);
			$imgQuery   			= 	"SELECT encrypt_id,image_name
                         				 FROM sms_gallery_image WHERE gallery_id = '" . $editid . "'";
            $data['IMAGES'] 		= 	$this->common_model->get_data_by_query('multiple', $imgQuery);
		else:
			$this->admin_model->authCheck('admin','add_data');
		endif;
		
		if($this->input->post('SaveChanges')):
			$error					=	'NO';
			$this->form_validation->set_rules('gallery_name', 'Name', 'trim|required');
			
			if($this->form_validation->run() && $error == 'NO'):   
			
				$param['gallery_name']		= 	addslashes($this->input->post('gallery_name'));
				
				if($this->input->post('CurrentDataID') ==''):
					$param['franchise_id']		=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
					$param['school_id']			=	$this->session->userdata('SMS_ADMIN_SCHOOL_ID');
					$param['branch_id']			=	$this->session->userdata('SMS_ADMIN_BRANCH_ID');
					$param['current_year']		=	date('Y');
					$param['creation_date']		=	currentDateTime();
					$param['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$param['status']			=	'Y';
                    $param['session_year']		=	CURRENT_SESSION;
					$alastInsertId				=	$this->common_model->add_data('gallery',$param);
					
					$Uparam['encrypt_id']		=	manojEncript($alastInsertId);
					$Uwhere['gallery_id']		=	$alastInsertId;
					$this->common_model->edit_data_by_multiple_cond('gallery',$Uparam,$Uwhere);
					$this->session->set_flashdata('alert_success',lang('addsuccess'));

					$galleryId					=	$Uparam['encrypt_id'];
				else:
					$galleryId					=	$this->input->post('CurrentDataID');
					$param['update_date']		=	currentDateTime();
					$param['updated_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$this->common_model->edit_data('gallery',$param,$galleryId);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;

				if($galleryId && $this->input->post('image_name')):
					$imageNameData				=	explode('_____',$this->input->post('image_name'));
					foreach($imageNameData as $imageValue):
						$Iparam['gallery_id']		=	$galleryId;
						$Iparam['image_name']		=	trim($imageValue);
						$IalastInsertId				=	$this->common_model->add_data('gallery_image',$Iparam);
						
						$IUparam['encrypt_id']		=	manojEncript($IalastInsertId);
						$IUwhere['image_id']		=	$IalastInsertId;
						$this->common_model->edit_data_by_multiple_cond('gallery_image',$IUparam,$IUwhere);
					endforeach;
				endif;
				
				redirect(correctLink('galleryAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
			endif;
		endif;
		
		$this->layouts->set_title('Edit gallery details');
		$this->layouts->admin_view('gallery/addeditdata',array(),$data);
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
		$this->common_model->edit_data('gallery',$param,$changeStatusId);
		
		$this->session->set_flashdata('alert_success',lang('statussuccess'));
		
		redirect(correctLink('galleryAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
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
            $return_file_name = $this->upload_crop_img->_upload_image($file_name, $tmp_name, 'galleryImage', $newfilename, '');
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

    /* * *********************************************************************
     * * Function name : DeletePrevImage
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for delete image by ajax.
     * * Date : 14 DECEMBER 2018
     * ********************************************************************** */
    function DeletePrevImage() {
        $imagename = $this->input->post('imagename');
        $imageid = $this->input->post('imageid');
        if ($imagename && $imageid):
            $this->load->library("upload_crop_img");
            $return = $this->upload_crop_img->_delete_image($imagename);
            $this->common_model->delete_data('gallery_image',$imageid);
        endif;
        echo '1';
        die;
    } // END OF FUNCTION		
}