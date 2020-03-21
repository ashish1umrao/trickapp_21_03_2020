<?php
$studqrdetail = '' ;
//foreach($QRCode as $qrinfo):
//echo "<pre>"; print_r($QRDATA); die;
//$studqrdetail    .= '<table style="width:100%;background: #f8f8f8;color: #000;border-collapse: collapse;padding:20px;text-align: center; border: 1px solid #eaeaea;border-bottom: 0;border-top:0;" width="100%">';
           if($QRDATA) :   foreach($QRDATA as $qrinfo): //echo "<pre>"; print_r($QRDATA); die;
$studqrdetail    .= '<table style="width:100%;background: #f8f8f8;color: #000;border-collapse: collapse;padding:20px;text-align: center; border: 1px solid #eaeaea;border-bottom: 0;border-top:0;" width="100%">
                            <tbody>
                              <tr>
                                <td style="width:30%;padding:20px 5px;text-align:left;padding-left:20px;font-weight: 400;"> 
                                  <b>Enrollment No</b>
                                </td>
                                <td style="width:70%;padding:20px 5px;">'.ucfirst(stripslashes($qrinfo['student_registration_no'].' ['.$qrinfo['student_f_name'].' '.$qrinfo['student_m_name'].' '.$qrinfo['student_l_name'].']')).'</td>
                              </tr>
                              <tr>
                                <td style="width:33%;padding:20px 5px;text-align:left;padding-left:20px;font-weight: 400;"> 
                                  <b>QR Image</b>&nbsp;
                                </td>
                                <td style="width:33%;padding:20px 5px;"><img style="width:270px;height:270px;" src="'.base_url().''.stripslashes($qrinfo['qr_pic']).'"/></td>
                              </tr>';
      $studqrdetail    .= '</tbody>';

$studqrdetail    .= '</table>';
endforeach;
 endif ;
 $html     = $studqrdetail;
 
@unlink($this->config->item("root_path").'assets/downloadpdf/QR_code_class_'.$classSection.'.pdf');
$mpdf=new mPDF('utf-8','A4',0,'',10,10,10,24);


$headerpdf='';
$footerpdf='';
  
$mpdf->SetHTMLHeader($headerpdf);
$mpdf->SetHTMLFooter($footerpdf);
$mpdf->AddPage();
$mpdf->WriteHTML($html);
$mpdf->Output($this->config->item("root_path").'assets/downloadpdf/QR_code_class_'.$classSection.'.pdf','F');  
