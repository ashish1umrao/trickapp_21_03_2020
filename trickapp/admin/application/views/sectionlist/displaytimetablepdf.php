<?php

$timetable    .= '<body>
<div style="width: 85%;   margin: 0 auto;  border-radius: 1px; padding: 40px;">
 
  <table width="100%" style="background:#fff;border-left:1px solid #ddd;text-align:center;border-right:1px solid #ddd;color:#000;border-collapse:collapse;">
     <thead>
      <tr>
        <th width="5%" style="background-color: #d9edf7;border-right: 1px solid #d9edf7;padding:1%;border-top:1px solid #ddd;border-collapse:collapse;"></th>
        <th width="5%" style="background-color: #d9edf7;border-right: 1px solid #d9edf7;padding:1%;border-top:1px solid #ddd;border-collapse:collapse;"></th>
        <th width="5%" style="background-color: #d9edf7;border-right: 1px solid #d9edf7;padding:1%;border-top:1px solid #ddd;border-collapse:collapse;"></th>
        <th width="30%" style="background-color: #d9edf7;border-right: 1px solid #d9edf7;padding:1%;border-top:1px solid #ddd;border-collapse:collapse;"> <b style="font-family:sans-serif;font-size:11px;letter-spacing:.8px;font-weight:600;color:#000000;line-height:15px;">Branch: '.$sectiondata['branch_name'].'</b> </th>
        <th width="10%" style="background-color: #d9edf7;border-right: 1px solid #d9edf7;padding:1%;border-top:1px solid #ddd;border-collapse:collapse;"> <b style="font-family:sans-serif;font-size:11px;letter-spacing:.8px;font-weight:600;color:#000000;line-height:15px;">Class: '.$sectiondata['class_name'].'</b> </th>
        <th width="10%" style="background-color: #d9edf7;border-right: 1px solid #d9edf7;padding:1%;border-top:1px solid #ddd;border-collapse:collapse;"> <b style="font-family:sans-serif;font-size:11px;letter-spacing:.8px;font-weight:600;color:#000000;line-height:15px;">Section: '.$sectiondata['class_section_name'].'</b> </th>
        <th width="25%" style="background-color: #d9edf7;border-right: 1px solid #d9edf7;padding:1%;border-top:1px solid #ddd;border-collapse:collapse;"></th>
        <th width="5%" style="background-color: #d9edf7;border-right: 1px solid #d9edf7;padding:1%;border-top:1px solid #ddd;border-collapse:collapse;"></th>
       <th width="5%" style="background-color: #d9edf7;border-right: 1px solid #d9edf7;padding:1%;border-top:1px solid #ddd;border-collapse:collapse;"></th>
         <th width="5%" style="background-color: #d9edf7;border-right: 1px solid #ddd;padding:1%;border-top:1px solid #ddd;border-collapse:collapse;"></th>
      </tr>
    </thead>';

if($perioddata <> ""):
$timetable    .= '<tbody>';
  $timetable    .= '<tr>';
      $timetable    .= '<th width="11.1111%" style="background-color: #f9f9f9;border-right: 1px solid #ddd;padding:1%;border-top:1px solid #ddd;border-collapse:collapse;"> <b style="font-family:sans-serif;font-size:11px;letter-spacing:.8px;font-weight:600;color:#000000;line-height:15px;">Days</b> </th>';
  foreach($perioddata as $periodinfo):
    $timetable    .= '<td width="11.1111%" style="background-color: #f9f9f9;border-right: 1px solid #ddd;padding:1%;border-top:1px solid #ddd;border-collapse:collapse;"> <b style="font-family:sans-serif;font-size:11px;letter-spacing:.8px;font-weight:600;color:#000000;line-height:15px;">'.$periodinfo['class_period_name'].'<br>['.$periodinfo['class_period_start_time'].'-'.$periodinfo['class_period_end_time'].']</b></td>';
  endforeach;     
  $timetable    .= '</tr>';

if($workdaysdata <> ""):
  foreach($workdaysdata as $workdaysinfo):
$timetable    .= '<tr>
        <th width="11.1111%" style="border-right: 1px solid #ddd;border-bottom: 1px solid #ddd;padding:1%;border-top:1px solid #ddd;border-collapse:collapse;font-family:sans-serif;font-size:11px;letter-spacing:.8px;font-weight:500;color:#000000;line-height:15px;">'.$workdaysinfo['working_day_name'].'</th>';
        
        foreach($perioddata as $periodinfo):

          if($periodinfo['class_period_name']=='Break'): 

            $timetable    .= '<td width="11.1111%" style="border-right: 1px solid #ddd;border-bottom: 1px solid #ddd;padding:1%;border-top:1px solid #ddd;border-collapse:collapse;font-family:sans-serif;font-size:11px;letter-spacing:.8px;font-weight:500;color:#000000;line-height:15px;">Break</td>';
          elseif($periodinfo['class_period_name']=='Lunch'):
            $timetable    .= '<td width="11.1111%" style="border-right: 1px solid #ddd;border-bottom: 1px solid #ddd;padding:1%;border-top:1px solid #ddd;border-collapse:collapse;font-family:sans-serif;font-size:11px;letter-spacing:.8px;font-weight:500;color:#000000;line-height:15px;">Lunch</td>';       
            else: 

              if(!empty($Assiperioddata[$periodinfo['encrypt_id'].'_____'.$workdaysinfo['encrypt_id']])):
              $assignst   =$Assiperioddata[$periodinfo['encrypt_id'].'_____'.$workdaysinfo['encrypt_id']];

                $timetable    .= '<td width="11.1111%" style="border-right: 1px solid #ddd;border-bottom: 1px solid #ddd;padding:1%;border-top:1px solid #ddd;border-collapse:collapse;font-family:sans-serif;font-size:11px;letter-spacing:.8px;font-weight:500;color:#000000;line-height:15px;">'.$assignst['subject'].'<br>'.$assignst['teacher'].'</td>';
              else:
                $timetable    .= '<td width="11.1111%" style="border-right: 1px solid #ddd;border-bottom: 1px solid #ddd;padding:1%;border-top:1px solid #ddd;border-collapse:collapse;font-family:sans-serif;font-size:11px;letter-spacing:.8px;font-weight:500;color:#000000;line-height:15px;"></td>';
              endif;
            endif;

        endforeach;  
$timetable    .= '</tr>
     <tr>';
    endforeach;  
    endif;  endif;
$timetable    .= '</tbody>';
$timetable    .= '</table>
</div>
</body>';

    $html     = $timetable;
 
    $classSection = $sectiondata['class_name'].'_'.$sectiondata['class_section_name'];
    
   $mpdf=new mPDF('utf-8','A4',0,'',10,10,10,24);

$headerpdf='';
$footerpdf='';
  
$mpdf->SetHTMLHeader($headerpdf);
$mpdf->SetHTMLFooter($footerpdf);
$mpdf->AddPage();
$mpdf->WriteHTML($html);
$mpdf->Output($this->config->item("root_path").'assets/downloadpdf/'.$classSection.'.pdf','F');  
?>


