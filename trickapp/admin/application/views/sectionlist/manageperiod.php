<style>
    
    .tabletd-height tr td,.tabletd-height tr th{height:100px;vertical-align: middle !important; text-align: center ;}.tabletd-height thead tr th{height:auto;}
</style>

<style>

.assignIcon {
    font-size: 20px !important;
	cursor:pointer;
}
#myAssignClassTeacherModal.modal { z-index:999995; margin-top:50px; font-size:14px;}
#myAssignClassTeacherModal .modal-body { text-align:center;}
#myAssignClassTeacherModal .table-bordered tr td { height:40px;}
#myAssignClassTeacherModal .view-detail-title h1 {margin-bottom: 0px; margin-top: 0px;}
</style>
<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('sectionListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage section details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    time table</li>
  <li class="pull-right"><a href="<?php echo correctLink('sessionMonthAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="table-agile-info">
  <div class="panel panel-default">
    <form id="Data_Form" name="Data_Form" method="get" action="<?php echo $forAction; ?>">
    <div class="row w3-res-tb">
      <div class="col-sm-5 m-b-xs">
       
      </div>
      <div class="col-sm-5">
          
      </div>
      <div class="col-sm-2 ">
          <?php  if($workdaysdata):   ?>
       <a class="btn btn-success  "  href="{BASE_URL}sectionlist/displaytimetablepdf/<?php echo $sectiondata['encrypt_id']; ?>" download>Download <i class="fa fa-download" aria-hidden="true"></i></a> 
      <?php  endif; ?>
      
      </div>
    </div>
        </br>
    <div class="table-responsive" style="overflow-x:scroll;">
      <table class="table table-striped b-t b-light table-bordered tabletd-height">
        <thead>
         <tr class="info">
                    <th colspan="<?php echo count($perioddata)+1; ?>" style="text-align:center;">
                    School : <?php echo stripslashes($sectiondata['school_name']); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Branch : <?php echo stripslashes($sectiondata['branch_name']); ?> ( <?php echo stripslashes($sectiondata['branch_board_name']); ?> )&nbsp;&nbsp;&nbsp;
                    Class : <?php echo stripslashes($sectiondata['class_name']); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Section : <?php echo stripslashes($sectiondata['class_section_name']); ?>&nbsp;&nbsp;&nbsp;&nbsp;
                    Date : <?php 
					$fdatethisw			=	date('d-m-Y',strtotime('monday this week'));
					$ldatethisw			=	date('d-m-Y',strtotime('saturday this week'));
					echo '('.$fdatethisw.') to ('.$ldatethisw.')'; ?>
                    </th>
         </tr>
        </thead>
      <tbody>
          
         
                <?php if($perioddata <> ""): 
                    
                    
    ?>
                  <tr>
                    <th scope="row" style="text-align:center;">Days</th>
                   
                    <?php foreach($perioddata as $periodinfo): ?>
                    <td style="text-align:center;">
					<?php echo $periodinfo['class_period_name']!='Lunch'?'<strong>'.$periodinfo['class_period_name'].'</strong>':''; ?><br />
                    [<?php echo $periodinfo['class_period_start_time'].'-'.$periodinfo['class_period_end_time'] ?>]
                    </td>
                    <?php endforeach; ?>
                  </tr>
                  <?php if($workdaysdata <> ""): foreach($workdaysdata as $workdaysinfo): ?>
                  <tr>
                    <th scope="row" style="text-align:center;"><?php echo stripslashes($workdaysinfo['working_day_name']); ?></th>
                    <?php foreach($perioddata as $periodinfo): ?>
                    <td>
                    <?php if($periodinfo['class_period_name']=='Break'): echo '<strong>Break</strong>'; 
                    elseif($periodinfo['class_period_name']=='Lunch'): echo '<strong>Lunch</strong>';
                    else: 
						  if(!empty($Assiperioddata[$periodinfo['encrypt_id'].'_____'.$workdaysinfo['encrypt_id']])):
						  $assignst		=	$Assiperioddata[$periodinfo['encrypt_id'].'_____'.$workdaysinfo['encrypt_id']];
						  echo stripslashes($assignst['subject'].'<br>'.$assignst['teacher']);
					?>
                       
                        
                    <a class="periodsAssign" data="<?php echo $sectiondata['encrypt_id'].'_____'.$sectiondata['class_id'].'_____'.$periodinfo['encrypt_id'].'_____'.$workdaysinfo['encrypt_id']; ?>"><i class="fa fa-pencil assignIcon" aria-hidden="true"></i></a>
                    <?php else: ?>
                    <a class="periodsAssign" data="<?php echo $sectiondata['encrypt_id'].'_____'.$sectiondata['class_id'].'_____'.$periodinfo['encrypt_id'].'_____'.$workdaysinfo['encrypt_id']; ?>"><i class="fa fa-paperclip assignIcon" aria-hidden="true"></i></a>
                    <?php endif; endif; ?>
                    </td>
                    <?php endforeach; ?>
                  </tr>
                  <?php endforeach; else: ?>
                         <td colspan="7" style="text-align:center;">Please add Working days first</td>
                      <?php endif; endif;  ?> 
                  
                  
                         
                  
                
                </tbody>
      </table>
    </div>
    
    </form>
  </div>
</div>
<!-- Message Modal -->
<div class="modal fade" id="myAssignClassTeacherModal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <button type="button" class="close close-view" data-dismiss="modal">&times;</button>
      <div class="modal-body newspop">
        <table class="table table-bordered table-striped">
          <tr class="info">
            <td><span class="view-detail-title">&nbsp;</span></td>
          </tr>
        </table>
        <span class="view-detail-data">&nbsp;</span> 
      </div>
    </div>
  </div>
</div>



<script>
$(document).on('click','.table-responsive .periodsAssign',function(){
	$("#myAssignClassTeacherModal").modal();
	$("#myAssignClassTeacherModal .view-detail-title").html('<h3>Assign Subject And Teacher</h3>');
	$("#myAssignClassTeacherModal .view-detail-data").html('<h3>Loading...</h3>');
	var assigndata		=	$(this).attr('data');
	var assignarray		=	assigndata.split('_____');
        
        

	   if(assignarray[0] && assignarray[1] && assignarray[2] && assignarray[3] )
	{
		$.ajax({
			type: 'post',
			 url: FULLSITEURL+CURRENTCLASS+'/get_period_assign_data',
			data: {csrf_api_key:csrf_api_value,sectionid:assignarray[0], classid:assignarray[1],periodid:assignarray[2],workdayid:assignarray[3]},
		 success: function(response){
				$("#myAssignClassTeacherModal .view-detail-data").html(response);
			}	
		});
	}  
});
</script>

