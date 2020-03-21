
<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('feegroupListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage fee group</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    fee group</li>
  <li class="pull-right"><a href="<?php echo correctLink('feegroupListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="table-agile-info">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
               
          fee group</span> </header>
        <div class="panel-body">
        
            </div>
     
<div class="panel panel-default">
    <form id="Data_Form" name="Data_Form" method="post" action="">
       <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
         <div class="col-lg-12 ">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Group Name<span class="required">*</span></label>
                  <div class="col-lg-8">
                      
                           <input type="text" name="fee_group_name" id="fee_head_name" value="<?php if(set_value('fee_group_name')): echo set_value('fee_group_name'); else: echo stripslashes($EDITDATA['fee_group_name']);endif; ?>" class="form-control required" placeholder="Fees Group">
                  <?php if(form_error('fee_head_name')): ?>
                  <p for="fee_group_name" generated="true" class="error"><?php echo form_error('fee_group_name'); ?></p>
                  <?php endif; ?>
                  </div>
                </div>
              </div>
          
              <div class="col-lg-6">
                  <div class="form-group"  style="
    overflow: hidden;" >
                  <label class="col-lg-4 control-label">Class<span class="required">*</span></label>
                  <div class="col-lg-8">
                      
                   
                    
                    
                      <?php    if(set_value('class_id')): $classId	= set_value('class_id'); elseif($ACTIVECLASS): $classId	= $ACTIVECLASS; else: $classId	= ''; endif; ?>
                    
                    <select name="class_id[]" id="class_id[]" multiple="multiple" class="form-control required">
                      <?php if($EDITDATA <> ""): if($CLASSDATA <> ""): foreach($CLASSDATA as $CLASSINFO): ?>
                      <option value="<?php echo $CLASSINFO['encrypt_id']; ?>" <?php if(in_array($CLASSINFO['encrypt_id'],$ACTIVECLASS)): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($CLASSINFO['class_name']); ?></option>
                      <?php endforeach; endif;  ?>
                      <?php else: if($CLASSDATA <> ""): foreach($CLASSDATA as $CLASSINFO): //print_r($CLASSINFO); die;?>
                      <option value="<?php echo $CLASSINFO['encrypt_id']; ?>" <?php if($classId == $CLASSINFO['encrypt_id']): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($CLASSINFO['class_name']); ?></option>
                      <?php endforeach; endif; endif; ?>
                    </select>
                    <?php if(form_error('class_id')): ?>
                    <label for="class_id" generated="true" class="error"><?php echo form_error('class_id'); ?></label>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
        
      <div class="table-responsive">
      <table class="table table-striped b-t b-light">
        <thead>
          
          <tr>
           <th>Sr.No.</th>
            <th>Head Name</th>
              <th>Rate &#8377</th>
           
          </tr>
        </thead>
        <tbody>
          <?php if($HEADDATA <> ""): $i=1; foreach($HEADDATA as $HEADINFO): 
		
              $head_id	=	$HEADINFO['encrypt_id'];
						$fee		=	$FEEDATA[$head_id]['fee_amount']>0?$FEEDATA[$head_id]['fee_amount']:0.00;
                                             
                                                	$active		=	$FEEDATA[$head_id]['active']?$FEEDATA[$head_id]['active']:'N';

              
              
		  ?>
          <tr>
            <th scope="row" <?php if($i%2 == 0): echo 'odd'; else: echo 'even'; endif; ?>><?=$i++?>
              &nbsp;&nbsp;
              
              <input type="checkbox" name="active_<?=$HEADINFO['encrypt_id']?>" id="active_<?=$HEADINFO['encrypt_id']?>" value="Y" <?php if($active == 'Y'): echo 'checked="checked"'; endif; ?> class="flat" /></th>
           <td><?=stripslashes($HEADINFO['fee_head_name'])?></td>
            <td>  <div class="col-lg-4"> <input type="text" name="fee_amount_<?=$HEADINFO['encrypt_id']?>" id="fee_amount_<?=$HEADINFO['encrypt_id']?>" value="<?php if(set_value('fee_amount_'.$HEADINFO['encrypt_id'])): echo set_value('fee_amount_'.$HEADINFO['encrypt_id']); else: echo stripslashes($fee);endif; ?>" class="form-control number" placeholder="Rate">
                  </div></td>
           
          </tr>
          <?php endforeach; else: ?>
          <tr>
            <td colspan="5" style="text-align:center;">Please Add Fee Head  .</td>
          </tr>
          <?php endif; ?>
        </tbody>
       
      </table>
          </div>

    <footer class="panel-footer">
      <div class="row">
           <div class="col-md-12 "> <span>
          <label  for="check_all">
          <input name="check_all" id="check_all" type="checkbox" value="check_all" class="flat"/>
          Select All</label>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
            <a href="<?php echo correctLink('feegroupListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span></span>
           
           </div>
        <div class="col-sm-5">
          <small class="text-muted inline m-t-sm m-b-sm"><?php echo $noOfContent; ?></small>
        </div>
        <div class="col-sm-7 text-right text-center-xs">                
          <?=$this->pagination->create_links()?>
        </div>
      </div>
    </footer>
   
    </form>
  </div>

     
  </div>
      
</div>



    
   


<script>
$(document).on('click',"#check_all",function(){
	if($(this).is(":checked"))
		var	checkvalue	=	'true';
	else
		var	checkvalue	=	'false';
		
	$("form#Data_Form input:checkbox").each(function(){
		if($(this).attr('id')!='check_all'){ 
			if(checkvalue == 'true'){ 
				$(this).prop("checked",true);
				$(this).parent().parent().addClass('selected');
			}
			else if(checkvalue == 'false'){ 
				$(this).prop("checked",false);
				$(this).parent().parent().removeClass('selected');
			}
		}
	});
});

$(document).on('click',"#Data_Form input[id*='DataId']",function(){
	if($(this).is(":checked"))
		$(this).parent().parent().addClass('selected');
	else
		$(this).parent().parent().removeClass('selected');
	var total = 0;
	var count = 0;
	$("form#Data_Form input:checkbox").each(function(){
		if($(this).attr('id')!='check_all'){ 
			if(!$(this).is('input#check_all'))	total++;	
			if(!$(this).is('input#check_all') && $(this).is(":checked"))	count++;	
		}
	});
	if(total!=0 && count!=0 && total==count)
		$('#Data_Form #check_all').prop("checked",true);
	else
		$('#Data_Form #check_all').prop("checked",false);
});


</script>

<script type="text/javascript">
$(document).ready(function(){
	$("#Data_Form").validate();
});
</script>


 

   
   
  

 
    