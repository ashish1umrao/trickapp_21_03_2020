<?php
if($action=='StudentInfo'){
$html	=	'<div style="width:100%;margin: 0 auto;  border-radius: 1px;padding:20px;">
  <table width="100%" style=" color: #000; border-collapse: collapse; background: #fff; border: 0;">
    <tr>
      <td style="border-radius: 5px;  background-color: #fff;border: 2px solid #000;padding: 0.5em 0;">
	  <table style="color: #000;  border-collapse: collapse;width: 100%;">
          <tr>
            <td style="padding:0 15px;text-align: left;width:70%;"><b style="font-weight: bold;display:block;font-weight:600;letter-spacing:2px;font-size:14px;padding:2px 10px;color:#000;font-family:Segoe UI,Calibri,Arial,sans-serif;text-transform:uppercase;">
              '.ucwords($student_name).' ('.ucwords($class_name).'-'.ucwords($section_name).') Report Card
              </b>
			</td>
			
			<td style="text-align: right;padding:0%;width:30%;padding:0 15px;"><b style="display:block;font-weight:300;letter-spacing:2px;font-size:12px;padding:2px 10px;color:#000;font-family:Segoe UI,Calibri,Arial,sans-serif;">
             <b>Assessment Name :</b> '.ucwords($assesment_name).' </b><br>
			  <b style="display:block;font-weight:300;letter-spacing:2px;font-size:12px;padding:2px 10px;color:#000;font-family:Segoe UI,Calibri,Arial,sans-serif;"><b>Term Name :</b> '.ucwords($term_name).'
              </b>
			</td>
			  
			  
          </tr>
        </table>
		</td>
    </tr>
  </table>
  <table width="100%" style="background:#fff;border-left:2px solid #000;border-right:2px solid #000;color:#000;border-collapse:collapse;">
    <tr>
      <td width="26%" style="padding: 1%;border: none;text-align: left;font-weight:600;color:#000;"><img src="'.$logo.'" alt="Site logo" style="vertical-align:middle;max-width:100%;height:auto;width:70px;"/><br>'.ucwords($school_name).'</td>
      <td width="48%" style="text-align:center;padding:1%;border:0;font-family:sans-serif;font-size:13.3px;text-transform: capitalize;letter-spacing:.8px;font-weight:600;color:#000000;line-height:21px;"> </td>
      <td width="26%" style="text-align:right;padding:1%;border:0;font-family:sans-serif;font-size:12px;letter-spacing:.8px;font-weight:600;color:#000000;line-height:21px;">
	  <b style="text-align:right;padding:1%;border:0;font-family:sans-serif;font-size:12px;letter-spacing:.8px;font-weight:600;color:#000000;line-height:21px;">Email:</b> '.$email.'</br>
        <b style="text-align:right;padding:1%;border:0;font-family:sans-serif;font-size:12px;letter-spacing:.8px;font-weight:600;color:#000000;line-height:21px;">Phone:</b>  '.$contact_no.'
        <br>
         <b style="text-align:right;padding:1%;border:0;font-family:sans-serif;font-size:12px;letter-spacing:.8px;font-weight:600;color:#000000;line-height:21px;">Mobile:</b>  '.$contact_no.'<br>
 </td>
    </tr>
  </table>
   
 
  <table width="100%" style="background:#fff;border-left:0;border-right:0;color:#000;border-collapse:collapse;">
     <thead>
      <tr>
        <th width="33%" style="border-right: 2px solid #000;border-left: 2px solid #000;padding:1%;border-top:2px solid #000;border-collapse:collapse;background: #578ebe;"> <b style="font-family:sans-serif;font-size:12px;letter-spacing:.8px;font-weight:600;color:#fff;line-height:15px;text-align:center;">Subject</b> </th>
        <th width="33%" style="background: #578ebe;border-right: 2px solid #000;padding:1%;border-top:2px solid #000;border-collapse:collapse;text-align:right;"> <b style="font-family:sans-serif;font-size:12px;letter-spacing:.8px;font-weight:600;color:#fff;line-height:15px;text-align:right;">Max.Mark</b> </th>
        <th width="33%" style="background: #578ebe;border-right: 2px solid #000;padding:1%;border-top:2px solid #000;border-collapse:collapse;text-align:right;"> <b style="font-family:sans-serif;font-size:12px;letter-spacing:.8px;font-weight:600;color:#fff;line-height:15px;text-align:right;">Mark</b> </th>       
     </tr>
    </thead>
    <tbody>';
	$sum = 0;
	$sum1 = 0;
	foreach($STUDENTDATA as $row){
   $html	.=	'<tr>
        <td width="33%" style="border-bottom: 2px solid #000;border-left: 2px solid #000;border-right: 2px solid #000;padding:1%;border-top:2px solid #000;border-collapse:collapse;font-family:sans-serif;font-size:12px;letter-spacing:.8px;font-weight:500;color:#000000;line-height:15px;text-align:center;">'.$row['subject_name'].'</td>
        <td width="33%" style="border-bottom: 2px solid #000;background-color: #fff;text-transform: uppercase;border-right: 2px solid #000;padding:1%;border-top:2px solid #000;border-collapse:collapse;font-family:sans-serif;font-size:12px;letter-spacing:.8px;font-weight:500;color:#000000;line-height:15px;text-align:right;"> '.$row['max_mark'].'</td>
        <td width="33%" style="border-bottom: 2px solid #000;border-right: 2px solid #000;padding:1%;border-top:2px solid #000;border-collapse:collapse;font-family:sans-serif;font-size:12px;letter-spacing:.8px;font-weight:500;color:#000000;line-height:15px;text-align:right;">'.$row['mark'].'</td>             
      </tr>';
	  $sum = $row['max_mark']+$sum;
	  $sum1 = $row['mark']+$sum1;
	}
    $html	.=	'</tbody>
	<thead>
      <tr>
        <th width="33%" style="border-right: 2px solid #000;border-left: 2px solid #000;padding:1%;border-top:2px solid #000;border-collapse:collapse;background: #578ebe;text-align:center;"> <b style="font-family:sans-serif;font-size:12px;letter-spacing:.8px;font-weight:600;color:#fff;line-height:15px;text-align:center;">Total</b> </th>
        <th width="33%" style="background: #578ebe;border-right: 2px solid #000;padding:1%;border-top:2px solid #000;border-collapse:collapse;text-align:right;"> <b style="font-family:sans-serif;font-size:12px;letter-spacing:.8px;font-weight:600;color:#fff;line-height:15px;text-align:right;">'.$sum.'</b> </th>
        <th width="33%" style="background: #578ebe;border-right: 2px solid #000;padding:1%;border-top:2px solid #000;border-collapse:collapse;text-align:right;"> <b style="font-family:sans-serif;font-size:12px;letter-spacing:.8px;font-weight:600;color:#fff;line-height:15px;text-align:right;">'.$sum1.'</b> </th>       
     </tr>
    </thead>
  </table>
</div>';	
}
else{
$html	=	'<div style="width: 85%;   margin: 0 auto;  border-radius: 1px;  box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5); padding: 40px;">
  <table width="100%" style=" color: #000; border-collapse: collapse; background: #fff; border: 0;">
    <tr>
      <td style="border-radius: 5px;  background-color: #fff;border: 2px solid #000;padding: 0.5em 0;">
	  <table style="color: #000;  border-collapse: collapse;width: 100%;">
          <tr>
            <td style="padding:0 15px;text-align: left;width:70%;"><b style="font-weight: bold;display:block;font-weight:600;letter-spacing:2px;font-size:14px;padding:2px 10px;color:#000;font-family:Segoe UI,Calibri,Arial,sans-serif;text-transform:uppercase;">
              '.ucwords($student_name).' ('.ucwords($class_name).'-'.ucwords($section_name).') Report Card
              </b>
			</td>
			
			<td style="text-align: right;padding:0%;width:30%;padding:0 15px;"><b style="display:block;font-weight:300;letter-spacing:2px;font-size:12px;padding:2px 10px;color:#000;font-family:Segoe UI,Calibri,Arial,sans-serif;">
             <b>Assessment Name :</b> '.ucwords($assesment_name).' </b><br>
			  <b style="display:block;font-weight:300;letter-spacing:2px;font-size:12px;padding:2px 10px;color:#000;font-family:Segoe UI,Calibri,Arial,sans-serif;"><b>Term Name :</b> '.ucwords($term_name).'
              </b><br>
			  <b style="display:block;font-weight:300;letter-spacing:2px;font-size:12px;padding:2px 10px;color:#000;font-family:Segoe UI,Calibri,Arial,sans-serif;"><b>Subject Name :</b> '.ucwords($subject_name).'
              </b><br>
			</td>
			  
			  
          </tr>
        </table>
		</td>
    </tr>
  </table>
  <table width="100%" style="background:#fff;border-left:2px solid #000;border-right:2px solid #000;color:#000;border-collapse:collapse;">
    <tr>
      <td width="26%" style="padding: 1%;border: none;text-align: left;font-weight:600;color:#000;"><img src="'.$logo.'" alt="Site logo" style="vertical-align:middle;max-width:100%;height:auto;width:70px;"/><br>'.ucwords($school_name).'</td>
      <td width="48%" style="text-align:center;padding:1%;border:0;font-family:sans-serif;font-size:13.3px;text-transform: capitalize;letter-spacing:.8px;font-weight:600;color:#000000;line-height:21px;"> </td>
      <td width="26%" style="text-align:right;padding:1%;border:0;font-family:sans-serif;font-size:12px;letter-spacing:.8px;font-weight:600;color:#000000;line-height:21px;">
	  <b style="text-align:right;padding:1%;border:0;font-family:sans-serif;font-size:12px;letter-spacing:.8px;font-weight:600;color:#000000;line-height:21px;">Email:</b> '.$email.'</br>
        <b style="text-align:right;padding:1%;border:0;font-family:sans-serif;font-size:12px;letter-spacing:.8px;font-weight:600;color:#000000;line-height:21px;">Phone:</b>  '.$contact_no.'
        <br>
         <b style="text-align:right;padding:1%;border:0;font-family:sans-serif;font-size:12px;letter-spacing:.8px;font-weight:600;color:#000000;line-height:21px;">Mobile:</b>  '.$contact_no.'<br>
 </td>
    </tr>
  </table>
   
 
  <table width="100%" style="background:#fff;border-left:0;border-right:0;color:#000;border-collapse:collapse;">
     <thead>
      <tr>
        <th width="25%" style="text-align:left;border-right: 2px solid #000;border-left: 2px solid #000;padding:1%;border-top:2px solid #000;border-collapse:collapse;background: #578ebe;"> <b style="font-family:sans-serif;font-size:12px;letter-spacing:.8px;font-weight:600;color:#fff;line-height:15px;text-align:center;">Student Name</b> </th>
        <th width="25%" style="text-align:right;background: #578ebe;border-right: 2px solid #000;padding:1%;border-top:2px solid #000;border-collapse:collapse;"> <b style="font-family:sans-serif;font-size:12px;letter-spacing:.8px;font-weight:600;color:#fff;line-height:15px;text-align:right;">Roll Number</b> </th>
        <th width="25%" style="text-align:right;background: #578ebe;border-right: 2px solid #000;padding:1%;border-top:2px solid #000;border-collapse:collapse;"> <b style="font-family:sans-serif;font-size:12px;letter-spacing:.8px;font-weight:600;color:#fff;line-height:15px;text-align:right;">Max Mark</b> </th>       
		<th width="25%" style="text-align:right;background: #578ebe;border-right: 2px solid #000;padding:1%;border-top:2px solid #000;border-collapse:collapse;"> <b style="font-family:sans-serif;font-size:12px;letter-spacing:.8px;font-weight:600;color:#fff;line-height:15px;text-align:right;">Mark</b> </th> 
	 </tr>
    </thead>
    <tbody>';
	$sum = 0;
	$sum1 = 0;
	foreach($STUDENTDATA as $row){
   $html	.=	'<tr>
        <td width="25%" style="text-align:left;border-bottom: 2px solid #000;border-left: 2px solid #000;border-right: 2px solid #000;padding:1%;border-top:2px solid #000;border-collapse:collapse;font-family:sans-serif;font-size:12px;letter-spacing:.8px;font-weight:500;color:#000000;line-height:15px;">'.$row['student_name'].'</td>
        <td width="25%" style="text-align:right;border-bottom: 2px solid #000;background-color: #fff;text-transform: uppercase;border-right: 2px solid #000;padding:1%;border-top:2px solid #000;border-collapse:collapse;font-family:sans-serif;font-size:12px;letter-spacing:.8px;font-weight:500;color:#000000;line-height:15px;text-align:right;"> '.$row['student_roll_no'].'</td>
        <td width="25%" style="text-align:right;border-bottom: 2px solid #000;border-right: 2px solid #000;padding:1%;border-top:2px solid #000;border-collapse:collapse;font-family:sans-serif;font-size:12px;letter-spacing:.8px;font-weight:500;color:#000000;line-height:15px;text-align:right;">'.$row['max_mark'].'</td>
		<td width="25%" style="text-align:right;border-bottom: 2px solid #000;border-right: 2px solid #000;padding:1%;border-top:2px solid #000;border-collapse:collapse;font-family:sans-serif;font-size:12px;letter-spacing:.8px;font-weight:500;color:#000000;line-height:15px;text-align:right;">'.$row['mark'].'</td>		
      </tr>';
	  $sum = $row['max_mark']+$sum;
	  $sum1 = $row['mark']+$sum1;
	}
    $html	.=	'</tbody>	
  </table>
</div>';
}	
													  								  
echo $html; 

@unlink($this->config->item("root_path").'assets/downloadpdf/report_card/student_mark_details.pdf');
$mpdf=new mPDF('utf-8','A4',0,'',10,10,10,24);

$headerpdf='';
$footerpdf='';
  
$mpdf->SetHTMLHeader($headerpdf);
$mpdf->SetHTMLFooter($footerpdf);
$mpdf->AddPage();
$mpdf->WriteHTML($html);
$mpdf->Output($this->config->item("root_path").'assets/downloadpdf/report_card/student_mark_details.pdf','F');  
