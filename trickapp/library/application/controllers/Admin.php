<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

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
	 * * Function name : dashboard
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for admin dashboard
	 * * Date : 12 JANUARY 2018
	 * * **********************************************************************/
	public function dashboard()
	{$this->admin_model->authCheck('admin');
		
		$data['error'] 						= 	'';

		$this->layouts->set_title('Dashboard');
		$this->layouts->admin_view('dashboard',array(),$data);
	}	// END OF FUNCTION
}
