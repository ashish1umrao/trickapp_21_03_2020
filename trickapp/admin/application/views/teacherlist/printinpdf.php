<?php
$html					=	'<div style="width:100%;   margin: 0 auto;  border-radius: 1px; padding:0;">
							 <table width="100%" style=" color: #000; border-collapse: collapse; background: #fff;border: 1px solid #e9e9e9;">
							  <tbody>
								<tr>
								  <td align="center" colspan="4" style="color: #ff976a;font-size: 17px; padding-bottom: 14px; padding-top: 14px;background-color: #f1f0ea;border-bottom: 1px solid #e9e9e9;"><strong>Teacher details</strong></td>
								</tr>
								<tr>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Subject</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($subjectName).'</td>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Employee id</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['employee_id']).'</td>
								</tr>
								<tr>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>First name</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_f_name']).'</td>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Middle name</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_m_name']).'</td>
								</tr>
								<tr>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Last name</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_l_name']).'</td>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Email</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_email']).'</td>
								</tr>
								<tr>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Phone1</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_phone']).'</td>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Phone2</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_emer_phone']).'</td>
								</tr>
								<tr>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Mobile1</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_mobile']).'</td>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Mobile2</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_emer_mobile']).'</td>
								</tr>
								<tr>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Gender</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_gender']).'</td>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Marital status</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_marital_status']).'</td>
								</tr>
								<tr>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Blood group</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_blood_group']).'</td>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Religion</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_religion']).'</td>
								</tr>
								<tr>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Nationality</strong></td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_nationality']).'</td>
								  <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;">&nbsp;</td>
								  <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">&nbsp;</td>
								</tr>
								</tbody>
                               </table>
                              <table class="table border-none" style="border: 1px solid #e9e9e9;width:100%;padding:0;margin:0;">
                                <thead>
                                <tr>
                                  <th align="left" colspan="4" style="background-color: #ddede0;padding: 10px 10px;text-align: center;color: #999;letter-spacing: 0.5px;font-size: 17px;"><strong>Teacher\'s correspondence address</strong></th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                      <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>State</strong></td>
                                      <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_c_state']).'</td>
                                      <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>City</strong></td>
                                      <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_c_city']).'</td>
                                    </tr>
                                    <tr>
                                      <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Locality</strong></td>
                                      <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_c_locality']).'</td>
                                      <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Address</strong></td>
                                      <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_c_address']).'</td>
                                    </tr>
                                    <tr>
                                      <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Zip code</strong></td>
                                      <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_c_zipcode']).'</td>
                                      <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;">&nbsp;</td>
                                      <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">&nbsp;</td>
                                    </tr>
                                </tbody>
                                </table>
									
                              <table class="table border-none"  style="border: 1px solid #e9e9e9;width:100%;padding:0;margin:0;">
                                <thead>
                                <tr>
                                  <th align="left" colspan="4"  style="background-color: #ddede0;padding: 10px 10px;text-align: center;color: #999;letter-spacing: 0.5px;font-size: 17px;"><strong>Permanent address</strong></th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                      <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>State</strong></td>
                                      <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_p_state']).'</td>
                                      <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>City</strong></td>
                                      <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_p_city']).'</td>
                                    </tr>
                                    <tr>
                                      <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Locality</strong></td>
                                      <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_p_locality']).'</td>
                                      <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Address</strong></td>
                                      <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_p_address']).'</td>
                                    </tr>
                                    <tr>
                                      <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Zip code</strong></td>
                                      <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_p_zipcode']).'</td>
                                      <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;">&nbsp;</td>
                                      <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">&nbsp;</td>
                                    </tr>
                                </tbody>
                                </table>
									
                              <table class="table border-none" style="border: 1px solid #e9e9e9;width:100%;padding:0;margin:0;">
                                <thead>

                                <tr>
                                  <th align="left" colspan="4" style="background-color: #ddede0;padding: 10px 10px;text-align: center;color: #999;letter-spacing: 0.5px;font-size: 17px;"><strong>KYC details</strong></th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                      <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Adhar number</strong></td>
                                      <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_adhar_no']).'</td>
                                      <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Pan number</strong></td>
                                      <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_pan_no']).'</td>
                                    </tr>
                                    <tr>
                                      <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Account number</strong></td>
                                      <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_account_no']).'</td>
                                      <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Name on account</strong></td>
                                      <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_name_on_account']).'</td>
                                    </tr>
                                    <tr>
                                      <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Bank name</strong></td>
                                      <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_bank_name']).'</td>
                                      <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>IFSC code</strong></td>
                                      <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_ifsc_code']).'</td>
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
                                      <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Date of birth</strong></td>
                                      <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.YYMMDDtoDDMMYY($viewData['user_dob']).'</td>
                                      <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Date of joining</strong></td>
                                      <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.YYMMDDtoDDMMYY($viewData['user_joining_date']).'</td>
                                    </tr>
                                    <tr>
                                      <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Pickup location</strong></td>
                                      <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_pickup']).'</td>
                                      <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Drop location</strong></td>
                                      <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;">'.stripslashes($viewData['user_drop']).'</td>
                                    </tr>
                                    <tr>
                                      <td align="left" width="20%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;font-weight: bold;"><strong>Profile picture</strong></td>
                                      <td align="left" width="30%" style="border-bottom: 1px solid #e9e9e9 !important;padding: 10px;color: #999;font-size: 0.9em;"><img src="'.stripslashes($viewData['user_pic']).'" width="100" border="0" alt="" /></td>
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
@unlink($this->config->item("root_path").'assets/downloadpdf/teacher_details_'.$printpdfId.'.pdf');
$mpdf=new mPDF('utf-8','A4',0,'',10,10,10,24);

$headerpdf='';
$footerpdf='';
  
$mpdf->SetHTMLHeader($headerpdf);
$mpdf->SetHTMLFooter($footerpdf);
$mpdf->AddPage();
$mpdf->WriteHTML($html);
$mpdf->Output($this->config->item("root_path").'assets/downloadpdf/teacher_details_'.$printpdfId.'.pdf','F');  
