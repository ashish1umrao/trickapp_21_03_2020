<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] 				= 	'login/index';
$route['404_override'] 						= 	'login/error_404';
$route['translate_uri_dashes'] 				= 	FALSE;
$route['logout'] 							= 	'login/logout';

$curUrl						=	explode('?',$_SERVER['REQUEST_URI']); 
$curUrl						=	explode('/',$curUrl[0]); //print_r($curUrl); die;
if($_SERVER['SERVER_NAME']=='localhost'):
	$firstSlug				=	isset($curUrl[3])?$curUrl[3]:'';
	$secondSlug				=	isset($curUrl[4])?$curUrl[4]:'';
	$thirdSlug				=	isset($curUrl[5])?$curUrl[5]:'';
else: 
	$firstSlug				=	isset($curUrl[2])?$curUrl[2]:'';
	$secondSlug				=	isset($curUrl[3])?$curUrl[3]:'';
	$thirdSlug				=	isset($curUrl[4])?$curUrl[4]:'';
endif;

if(isset($firstSlug)):
	require_once(BASEPATH.'database/DB.php');
	$db 					=	& DB();
	$fSlugQuery 			= 	$db->select('encrypt_id')->from('sms_admin')->where("admin_slug = '".$firstSlug."' AND status = 'A'")->get();
	$fSlugData				= 	$fSlugQuery->result();   
	if($fSlugData):	
		if($secondSlug == 'dashboard'):
			$route[$firstSlug.'/dashboard'] 									= 	'admin/dashboard/$1'; 
		else:	
			if(isset($secondSlug)):
				$sSlugQuery 				= 	$db->select('encrypt_id')->from('sms_admin')->where("admin_slug = '".$secondSlug."' AND status = 'A'")->get();
				$sSlugData					= 	$sSlugQuery->result(); 
				if($sSlugData): 
					if($thirdSlug == 'dashboard'): 
						$route[$firstSlug.'/'.$secondSlug.'/dashboard'] 		= 	'admin/dashboard/$1';  
					else:	
						$newCurUrl			=	explode('/'.$firstSlug.'/'.$secondSlug.'/',$_SERVER['REQUEST_URI']);
						if($_SERVER['SERVER_NAME']=='localhost'):
							$classFunction	=	isset($newCurUrl[1])?$newCurUrl[1]:'';
						else: 
							$classFunction	=	isset($newCurUrl[1])?$newCurUrl[1]:'';
						endif;	
						$classFunction		=	strpos($classFunction,'/?')?explode('/?',$classFunction):explode('?',$classFunction);  
						$route[$firstSlug.'/'.$secondSlug.'/'.$classFunction[0]] 	= 	$classFunction[0].'/$1'; 
					endif; 
				else:	
					$newCurUrl				=	explode('/'.$firstSlug.'/',$_SERVER['REQUEST_URI']);
					if($_SERVER['SERVER_NAME']=='localhost'):
						$classFunction		=	isset($newCurUrl[1])?$newCurUrl[1]:'';
					else: 
						$classFunction		=	isset($newCurUrl[1])?$newCurUrl[1]:'';
					endif;
					$classFunction			=	strpos($classFunction,'/?')?explode('/?',$classFunction):explode('?',$classFunction);
					if($classFunction[0]):  
						$defaultClassArray	=	array('profile','mobileapp','franchising','school','branch','designation','department','subadmin','admin','adminsmsusers');
						$classArray			=	explode('/',$classFunction[0]);
						if(isset($classArray[0])): 
							$classQuery 	= 	$db->select('encrypt_id')->from('sms_module')->where("module_name = '".$classArray[0]."' AND show_type = 'Admin'")->get();
							$classData		= 	$classQuery->result();
							$subClassQuery 	= 	$db->select('encrypt_id')->from('sms_child_module')->where("module_name = '".$classArray[0]."' AND show_type = 'Admin'")->get();
							$subClassData	= 	$subClassQuery->result();  
							if($classData):  
								$route[$firstSlug.'/'.$classFunction[0]] 			= 	$classFunction[0].'/$1'; 
							elseif($subClassData):	
								$route[$firstSlug.'/'.$classFunction[0]] 			= 	$classFunction[0].'/$1'; 
							elseif(in_array($classArray[0],$defaultClassArray)):  
								$route[$firstSlug.'/'.$classFunction[0]] 			= 	$classFunction[0].'/$1'; 
							endif;
						endif;
					endif;
				endif;
			endif;
		endif;
	endif;
endif;
//echo '<pre>'; print_r($route); die;
//echo $firstSlug.'  '.$secondSlug; 
//echo $_SERVER['REQUEST_URI']; die;