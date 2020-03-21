<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload_crop_img
{
	// hold CI intance
	private $CI;
	 
	public function __construct()
	{
		//parent::__construct();
		//$this->CI = & get_instance();
	}
	
	function _upload_image($file_name='',$tmp_name='',$type='',$newfilename='',$subfolder='')
	{ 
		$img_properties			=	$this->_get_image_path_by_referance_and_id($type,$subfolder);
		if(!is_array($img_properties)) die("please set image properties for upload_path ,allowed_types ,max_size etc. in array form");
		
		if($newfilename):
			$file_name	 		= 	$newfilename;
		else:
			$file_name	 		= 	time().sanitized_filename($file_name);
		endif;
               
		if(move_uploaded_file($tmp_name, $img_properties['original']['path'].$file_name)): 

			//For creating original nails perfect size Start,.......
			if($img_properties['original']['perfect']):
				$this->_create_resized_image($file_name,$img_properties['perfect']);
			endif;
			
			//For creating original nails medium size Start,...
			if($img_properties['original']['medium']):
				$this->_create_resized_image($file_name,$img_properties['medium']);
			endif;
			
			//For creating thumb nails Start,.......
			if($img_properties['original']['thumb']):
				$this->_create_resized_image($file_name,$img_properties['thumb']);
			endif;
			
			$imagefolder   = $img_properties['thumb']['path']?$img_properties['thumb']['path']:$img_properties['original']['path'];
			return base_url().$imagefolder.$file_name;
		else:
			return 'UPLODEERROR';
		endif;
	}
	
	function _upload_canvas_image($image_data='',$image_name='',$type='',$subfolder='')
	{ 
		$img_properties			=	$this->_get_image_path_by_referance_and_id($type,$subfolder);
		if(!is_array($img_properties)) die("please set image properties for upload_path ,allowed_types ,max_size etc. in array form");

		if($image_data && $image_name && $img_properties):	
			$photo_name		=	str_replace('./','',$img_properties['original']['path'].$image_name);
			/* Decoding image */
			$image_uri 		=  	substr($image_data,strpos($image_data,",")+1);
			$binary 		= 	base64_decode($image_uri);
			/* Opening image */
			//header('Content-Type: bitmap; charset=utf-8');
			$file 			= 	fopen ($photo_name,'wb');
			/* Writing to server */
			fwrite($file,$binary, strlen($binary));
			/* Closing image file */
			fclose($file);
			if(file_exists($photo_name)):

				//For creating original nails perfect size Start,.......
				if($img_properties['original']['perfect']):
					$this->_create_resized_image($image_name,$img_properties['perfect']);
				endif;
				
				//For creating original nails medium size Start,...
				if($img_properties['original']['medium']):
					$this->_create_resized_image($image_name,$img_properties['medium']);
				endif;
				
				//For creating thumb nails Start,.......
				if($img_properties['original']['thumb']):
					$this->_create_resized_image($image_name,$img_properties['thumb']);
				endif;

				$imagefolder   = $img_properties['thumb']['path']?$img_properties['thumb']['path']:$img_properties['original']['path'];
				return base_url().$imagefolder.$image_name;
			else:
				return false;
			endif;
		endif;
	}

	function _upload_image_from_app($file_name='',$tmp_name='',$newfilename='',$type='',$subfolder='')
	{ 
		$img_properties			=	$this->_get_image_path_by_referance_and_id($type,$subfolder);
		if(!is_array($img_properties)) die("Please Set Image Properties For Upload Path ,Allowed Types ,Max Size etc. In Array Form");

		if($newfilename):
			$file_name	 		= 	sanitizedFilename($newfilename);
		else:
			$file_name	 		= 	time().sanitizedFilename($file_name);
		endif;

		if(move_uploaded_file($tmp_name, $img_properties['original']['path'].$file_name)): 
			$imagefolder   		= 	$img_properties['original']['path'];
			return base_url().$imagefolder.$file_name;
		else:
			return 'UPLODEERROR';
		endif;
	}
	
	function _create_resized_image($fileName,$img_properties) {
			
			$CI =& get_instance();
			$CI->load->library('image_lib');
			
			$config['image_library'] 				= 	'gd2';
			$config['source_image'] 				= 	$img_properties['source_path'].$fileName;       
			$config['create_thumb'] 				= 	TRUE;
			$config['maintain_ratio'] 				= 	TRUE;
			$config['width'] 						= 	$img_properties['max_width'];
			$config['height'] 						= 	$img_properties['max_height'];
			$config['new_image'] 					= 	$img_properties['path'].$fileName;
			
			$CI->image_lib->initialize($config);
			if(!$CI->image_lib->resize()):
				echo $CI->image_lib->display_errors();
			endif;
	}
	
	function _delete_image($imagename='')
	{  
		if(!strpos($imagename,'logo.png') && !strpos($imagename,'com-soon.jpg')):
			$thumbpath		=	str_replace(base_url(),FCPATH,$imagename);
			$originalpath	=	str_replace('thumb/','',$thumbpath);
			$perfectpath	=	str_replace('thumb/','perfect/',$thumbpath);
			$mediumpath		=	str_replace('thumb/','medium/',$thumbpath);
			if(file_exists($originalpath)):
				@unlink($originalpath);
			endif;
			if(file_exists($perfectpath)):
				@unlink($perfectpath);
			endif;
			if(file_exists($mediumpath)):
				@unlink($mediumpath);
			endif;
			if(file_exists($thumbpath)):
				@unlink($thumbpath);
			endif;
		endif;
	}
	
	function _get_image_path_by_referance_and_id($type='',$subfolder='')	
	{	
		$data	=	'';
		switch($type):
			case 'homeWorkFile':
				$data['original']	= 	array("path"=>"./assets/homeWorkFile/","allowed_types"=>"gif|jpg|png","max_size"=>"20000","max_width"=>"","max_height"=>"");//Original
				$this->_check_directory($data['original']['path']);
			break;
			case 'assignmentFile':
				$data['original']	= 	array("path"=>"./assets/assignmentFile/","allowed_types"=>"gif|jpg|png","max_size"=>"20000","max_width"=>"","max_height"=>"");//Original
				$this->_check_directory($data['original']['path']);
			break;
		endswitch;
		return $data;
	}
	
	function _check_directory($path='')
	{
		$patharray	=	explode('/',$path);
		$dirpath	=	"./assets";
		for($i=2; $i < count($patharray); $i++):
			$oldmask = umask(0);
			$dirpath	=	$dirpath.'/'.$patharray[$i];
			if (!file_exists(FCPATH.$dirpath)):
				@mkdir(FCPATH.$dirpath, 0775, true);
				umask($oldmask);
			endif;
		endfor;
		return true;
	}
}