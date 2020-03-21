<?php
$studqrdetail = '' ;
                    $studqrdetail    .='<h2 style=" text-align: center;">   '.$book.'  <h2> '     ;
           if($barcode) :   foreach($barcode as $barcodeinfo):
$studqrdetail    .= '<table style="width:100%;background: #f8f8f8;color: #000;border-collapse: collapse;padding:20px;text-align: center; border: 1px solid #eaeaea;border-bottom: 0;border-top:0;" width="100%">
                            <tbody>
                              <tr>
                                <td style="width:70%;padding:20px 5px;">'.$book.'</td>   
                             <td    style="padding: 30px;"><img  src="'.base_url().''.stripslashes($folder).''.stripslashes($barcodeinfo).'.png"/> </td>
                             


                              </tr>
                              
                           ';
      $studqrdetail    .= '</tbody>';

$studqrdetail    .= '</table>';
endforeach;
 endif ;
 $html     = $studqrdetail;
 

  $book         =  str_replace(' ', '_', $book); 
  
@unlink($this->config->item("root_path").'assets/downloadpdf/'.$book.'.pdf');
$mpdf=new mPDF('utf-8','A4',0,'',10,10,10,24);

$headerpdf='';
$footerpdf='';
  
$mpdf->SetHTMLHeader($headerpdf);
$mpdf->SetHTMLFooter($footerpdf);
$mpdf->AddPage();
$mpdf->WriteHTML($html);
$mpdf->Output($this->config->item("root_path").'assets/downloadpdf/'.$book.'.pdf','F');  
   