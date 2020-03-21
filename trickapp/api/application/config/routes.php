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

########################	COMMON SECTION 		######################
$route['user/checkBranchCode'] 				= 	'user/checkbranchcode';
$route['user/getLoginType'] 				= 	'user/getlogintype';
$route['user/userAuthenticate'] 			= 	'user/userauthenticate';
$route['user/getDashboardData'] 			= 	'user/getdashboarddata';
$route['user/updateDeviceId'] 				= 	'user/updatedeviceid';
$route['user/getProfileData'] 				= 	'user/getprofiledata';
$route['user/updateProfileData'] 			= 	'user/updateprofiledata';

$route['user/getGalleryList'] 				= 	'user/getgallerylist';
$route['user/getGalleryDetails'] 			= 	'user/getgallerydetails';
$route['user/getNewsList'] 					= 	'user/getnewslist';
$route['user/getNewsDetails'] 				= 	'user/getnewsdetails';

$route['user/getLeavesTypeList'] 			= 	'user/getleavestypelist';
$route['user/sendLeaveRequest'] 			= 	'user/sendleaverequest';
$route['user/getRequestedLeave'] 			= 	'user/getrequestedleave';

$route['user/getCalenderData'] 				= 	'user/getcalenderdata';

$route['user/sendFeedback'] 				= 	'user/sendfeedback';
$route['user/getFeedbackList'] 				= 	'user/getfeedbacklist';

$route['user/getNoticeBoardList'] 			= 	'user/getnoticeboardlist';
$route['user/getNotificationList'] 			= 	'user/getnotificationlist';
$route['user/getbusroute'] 			        = 	'user/getbusroute';


########################	TEACHER SECTION 	######################
$route['teacher/getClassList'] 				= 	'teacher/getclasslist';
$route['teacher/getSectionList'] 			= 	'teacher/getsectionlist';
$route['teacher/getSubjectList'] 			= 	'teacher/getsubjectlist';
$route['teacher/getSyllabus'] 				= 	'teacher/getsyllabus';
$route['teacher/getStudentList'] 			= 	'teacher/getstudentlist';
$route['teacher/getOwnStudentList'] 		= 	'teacher/getownstudentlist';
$route['teacher/setStudentAttendance'] 		= 	'teacher/setstudentattendance';
$route['teacher/getStudentAttendance'] 		= 	'teacher/getstudentattendance';
$route['teacher/setOwnAttendance'] 			= 	'teacher/setownattendance';
$route['teacher/getOwnAttendance'] 			= 	'teacher/getownattendance';
$route['teacher/getTimetable'] 				= 	'teacher/gettimetable';

$route['teacher/sendMessageToParent'] 		= 	'teacher/sendmessagetoparent';
$route['teacher/getMessageList'] 			= 	'teacher/getmessagelist';

$route['teacher/setHomeWork'] 				= 	'teacher/sethomework';				
$route['teacher/getHomeWorkList'] 			= 	'teacher/gethomeworklist';			
$route['teacher/setAssignment'] 			= 	'teacher/setassignment';			
$route['teacher/getAssignmentList'] 		= 	'teacher/getassignmentlist';		


###################################### ASHISH API #############################################

$route['teacher/getstudentattendancehistorylist'] 		        = 	'teacher/getstudentattendancehistorylist';
$route['user/loginwithqrcode'] 		                            = 	'user/loginwithqrcode';
$route['user/getStudentReportCard'] 		                    = 	'user/getStudentReportCard';
$route['user/getStudentfeereportcard'] 		                    = 	'user/getStudentfeesreportcard';
//$route['user/getStudentfeereportcard'] 		                    = 	'user/getStudentfeesreportcard';

//echo '<pre>'; print_r($route); die;