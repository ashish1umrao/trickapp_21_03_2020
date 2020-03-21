<?php
$html					=	'<div style="width:100%;   margin: 0 auto;  border-radius: 1px; padding:0;">
							 <table width="100%" style=" color: #000; border-collapse: collapse; background: #fff;border: 1px solid #e9e9e9;">
							  <tbody>
								<tr>
								  <td align="center" colspan="4" style="color: #ff976a;font-size: 17px; padding-bottom: 14px; padding-top: 14px;background-color: #f1f0ea;border-bottom: 1px solid #e9e9e9;"><strong>Student details</strong></td>
								</tr>
								<tr>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Class name</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['class_name']).'</td>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Section name</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['class_section_name']).'</td>
								</tr>
								<tr>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Roll no</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['student_roll_no']).'</td>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Registration no</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['student_registration_no']).'</td>
								</tr>
								<tr>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Admission date</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.YYMMDDtoDDMMYY($viewData['student_admission_date']).'</td>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Relieving date</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.YYMMDDtoDDMMYY($viewData['student_relieving_date']).'</td>
								</tr>
								<tr>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>First name</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['student_f_name']).'</td>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Middle name</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['student_m_name']).'</td>
								</tr>
								<tr>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Last name</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['student_l_name']).'</td>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Date of birth</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.YYMMDDtoDDMMYY($viewData['student_dob']).'</td>
								</tr>
                                                                <tr>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Unique Id</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['student_qunique_id']).'</td>
								 
								</tr>
								</tbody>  
                </table> 
                <table class="table border-none"  style="border: 1px solid #e9e9e9;width:100%;padding:0;margin:0;">
                  <thead>
                  <tr>
                    <th align="left" colspan="4" style="background-color: #ddede0;padding: 10px 10px;text-align: center;color: #999;letter-spacing: 0.5px;font-size: 17px;"><strong>Other Details</strong></th>
                  </tr>
                  </thead>
                  <tbody>
                      <tr>
                        <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Gender</strong></td>
                        <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['student_gender']).'</td>
                        <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Religion</strong></td>
                        <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['student_religion']).'</td>
                      </tr>
                      <tr>
                        <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Category</strong></td>
                        <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['student_category']).'</td>
                        <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Visible mark (if any)</strong></td>
                        <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['student_visible_mark']).'</td>
                      </tr>
                      <tr>
                        <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Profile picture</strong></td>
                        <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;"><img src="'.stripslashes($viewData['student_image']).'" width="100" border="0" alt="" /></td>
                        <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;">&nbsp;</td>
                        <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">&nbsp;</td>
                      </tr>
                  </tbody>
                  </table>
                  <table class="table border-none"  style="border: 1px solid #e9e9e9;width:100%;padding:0;margin:0;">
                  <tbody>
								<tr>

								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Status</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.showStatus($viewData['status']).'</td>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;">&nbsp;</td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">&nbsp;</td>
								</tr>
								</tbody>
							</table>
						</div>';	
							
													  								  
//echo $html; die;
@unlink($this->config->item("root_path").'assets/downloadpdf/student_details_'.$printpdfId.'.pdf');
$mpdf=new mPDF('utf-8','A4',0,'',10,10,10,24);

$headerpdf='';
$footerpdf='';
  
$mpdf->SetHTMLHeader($headerpdf);
$mpdf->SetHTMLFooter($footerpdf);
$mpdf->AddPage();
$mpdf->WriteHTML($html);
$mpdf->Output($this->config->item("root_path").'assets/downloadpdf/student_details_'.$printpdfId.'.pdf','F');  
