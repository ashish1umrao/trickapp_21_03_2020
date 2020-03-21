<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visitorlist extends CI_Controller {

	public function  __construct()  
	{  
		parent:: __construct();
		$this->load->helper(array('form','url','html','path','form','cookie'));
		$this->load->library(array('email','session','form_validation','pagination','parser','encrypt'));
		error_reporting(E_ALL ^ E_NOTICE);  
		$this->load->library("layouts");
                 $this->load->library('excel');
		$this->load->model(array('admin_model','common_model','emailtemplate_model','sms_model'));
		$this->load->helper('language');
		$this->lang->load('statictext', 'admin');
	} 
	
	/* * *********************************************************************
	 * * Function name : classlist
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for classlist
	 * * Date : 23 JANUARY 2018
	 * * **********************************************************************/
	public function index()
	{		
		$this->admin_model->authCheck('admin','view_data');
		$this->admin_model->get_permission_type($data);
		$data['error'] 						= 	'';
                $data['start_date'] 						= 	$this->input->get('start_date');
                $data['end_date'] 						= 	$this->input->get('end_date');
		     $startDate =     strtotime(DDMMYYtoYYMMDD($this->input->get('start_date')));
                      $endDate = (strtotime(DDMMYYtoYYMMDD($this->input->get('end_date')))+82800) ;
                       if (($data['start_date'] == '') or  ($data['end_date'] == '') ) :
              
                                 $whereCon['where']		 			= 	"vtr.franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' 
												 AND vtr.school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."'
												 AND vtr.branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."'";
                              
              else :
                 
                     if($startDate != $endDate): 
			
                        $whereCon['where']		 			= 	"vtr.franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' 
												 AND vtr.school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."'
												 AND vtr.branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."' AND UNIX_TIMESTAMP(vtr.in_time) >= '".($startDate)."'AND UNIX_TIMESTAMP(vtr.in_time)  <= '".($endDate)."'";	
		
		
                endif;
                endif ;
		if($this->input->get('searchValue')):
			$sValue							=	$this->input->get('searchValue');
			$whereCon['like']		 		= 	"(vtr.name LIKE '%".$sValue."%' )";
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchValue'] 			= 	'';
		endif;
		
		$shortField 						= 	'vtr.visitor_id DESC';
		
		$this->load->library('pagination');
		$config['base_url'] 				= 	$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index';
		$this->session->set_userdata('visitorListAdminData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
                
		$config['suffix']					= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'visitor as vtr';
		$con 								= 	'';
                
		$config['total_rows'] 				= 	$this->admin_model->SelectVisitorListData('count',$tblName,$whereCon,$shortField,'0','0');
		
             
                
                
                
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
		
		$data['ALLDATA'] 					= 	$this->admin_model->SelectVisitorListData('data',$tblName,$whereCon,$shortField,$config['per_page'],$page); 
                  //// visitor are inside school
                $visitorIN  = 0  ;
                if($data['ALLDATA'] ):
                foreach( $data['ALLDATA']  as  $V):
                    if(!$V['out_time'] ):
                    $visitorNumber =  $visitorNumber  + 1  ;
                        
                        endif ;
                  
                    
                endforeach; 
                endif ;
                $data['visitor_in']   =  $visitorNumber  ;
                    
                    
                    
                    
                    //// download visitor report  execl
                    
                    
                    
                if($this->input->post('report')):
                   $rs					= 	$this->admin_model->VisitorReport('data',$tblName,$whereCon,$shortField,'',''); 
                    
               
                    
                       //excel download start
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle('Sales');
                    //set cell A1 content with some text
                    //   $this->excel->getActiveSheet()->setCellValue('A1', 'Sales Excel Report');
                    //$this->excel->getActiveSheet()->setCellValue('A4', 'S.No.');
                    $this->excel->getActiveSheet()->getStyle('A1:E1')->getFill()->applyFromArray(array(
                        'type'       => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array(
                            'rgb' => 'faca33'
                        )
                    ));

                    $styleArray = array(
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THICK
                            )
                        )
                    );
                    $this->excel->getActiveSheet()->getStyle('A1:E1')->applyFromArray(array(
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                        )
                    ));
                    $this->excel->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setTextRotation(90);


                    $this->excel->getActiveSheet()->setCellValue('A1', 'Visitor Name');
                    $this->excel->getActiveSheet()->setCellValue('B1', 'Mobile');
                    $this->excel->getActiveSheet()->setCellValue('C1', 'In Time');
                    $this->excel->getActiveSheet()->setCellValue('D1', 'Out Time');
                    $this->excel->getActiveSheet()->setCellValue('E1', 'Purpose');
                    //$this->excel->getActiveSheet()->setCellValue('F1', 'Total GST ₹');
                   // $this->excel->getActiveSheet()->setCellValue('G1', 'Grand Total ₹');
                  //  $this->excel->getActiveSheet()->setCellValue('H1', 'Date and Time');
                    //merge cell A1 until C1
                    // $this->excel->getActiveSheet()->mergeCells('A1:C1');
                    // $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    // $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                    // $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
                    // $this->excel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');
                    for ($col = ord('A'); $col <= ord('E'); $col++) {

                        $this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);

                        $this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    }

                   
                    //$exceldata="";
                    $exceldata = array();
            
                    if ($rs <> ""):

                        foreach ($rs as $row) {

                            $exceldata[] = $row;
                        }
                    endif;
                    //Fill data 
                    $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A2');



                    $filename = 'Visitor Report.xls';
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $filename . '"');
                    header('Cache-Control: max-age=0');

                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                    $objWriter->save('php://output');
                    //excel download end 
                    
                    
                    
                    
                endif;
                
                
                

		$this->layouts->set_title('Manage visitor details');
		$this->layouts->admin_view('visitorlist/index',array(),$data);
	}	// END OF FUNCTION
	
	public function report()
	{		
		$this->admin_model->authCheck('admin','view_data');
		$this->admin_model->get_permission_type($data);
		$data['error'] 						= 	'';
		
		if($this->input->get('searchValue')):
			$sValue							=	$this->input->get('searchValue');
			$whereCon['like']		 		= 	"(vtr.name LIKE '%".$sValue."%' )";
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchValue'] 			= 	'';
		endif;
		
		$whereCon['where']		 			= 	"vtr.franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' 
												 AND vtr.school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."'
												 AND vtr.branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."'";		
		$shortField 						= 	'vtr.visitor_id ASC';
		
		$this->load->library('pagination');
		$config['base_url'] 				= 	$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index/';
		$this->session->set_userdata('visitorListAdminData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$config['suffix']					= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'visitor as vtr';
		$con 								= 	'';
		$config['total_rows'] 				= 	$this->admin_model->SelectVisitorListData('count',$tblName,$whereCon,$shortField,'0','0');
		
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
		
		$config['uri_segment']				= 	4;
		$this->pagination->initialize($config);
		
		if($this->uri->segment(4)):
			$page = $this->uri->segment(4);
		else:
			$page =	0;
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
		
		$data['ALLDATA'] 					= 	$this->admin_model->SelectVisitorListData('data',$tblName,$whereCon,$shortField,$config['per_page'],$page); 
                
             
                
                
                

		$this->layouts->set_title('Manage visitor details');
		$this->layouts->admin_view('visitorlist/index',array(),$data);
	}	// END OF FUNCTION
	
	
}
