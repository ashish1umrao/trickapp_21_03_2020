<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class User_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct(); 
		$this->load->database(); 
	}

	/* * *********************************************************************
	 * * Function name : encriptPassword
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for encript_password
	 * * Date : 04 DECEMBER 2018
	 * * **********************************************************************/
	public function encriptPassword($password)
	{
		return $this->encrypt->encode($password, $this->config->item('encryption_key'));
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name : decryptsPassword
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for encript_password
	 * * Date : 04 DECEMBER 2018
	 * * **********************************************************************/
	public function decryptsPassword($password)
	{
		return $this->encrypt->decode($password, $this->config->item('encryption_key'));
	}	// END OF FUNCTION
}	