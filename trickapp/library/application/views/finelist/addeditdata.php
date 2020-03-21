<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('fineListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      fine details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    fine details</li>
  <li class="pull-right"><a href="<?php echo correctLink('fineListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> 
        <span class="tools pull-left"> <?=$EDITDATA?'Edit':'Add'?> fine details</span> 
        </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
           
         <div class="form-group">
                <label class="col-lg-3 control-label">Fine Rule</label>
                <div class="col-lg-6">
                  <?php if(set_value('fine_rule_id')): $fineruleid	=	set_value('fine_rule_id'); elseif($EDITDATA['fine_rule_id']): $fineruleid	=	stripslashes($EDITDATA['fine_rule_id']); else: $fineruleid	=	''; endif; ?>
                  <select name="fine_rule_id" id="fine_rule_id" class="form-control">
                  	<option value="">Select fine rule</option>
                    <?php if($FINERULEDATA <> ""): foreach($FINERULEDATA as $FINERULEINFO): ?>
                    	<option value="<?php echo $FINERULEINFO['encrypt_id']; ?>" <?php if($FINERULEINFO['encrypt_id'] == $fineruleid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($FINERULEINFO['fine_rule']); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('fine_rule_id')): ?>
                  <p for="fine_rule_id" generated="true" class="error"><?php echo form_error('fine_rule_id'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            
         
            <div class="form-group">
              <label class="col-lg-3 control-label">Fine Per Day &#8377<span class="required">*</span></label>
              <div class="col-lg-6">
                  <input type="text" name="fine_per_day" id="product_quantity"value="<?php if(set_value('fine_per_day')): echo set_value('fine_per_day'); else: echo stripslashes($EDITDATA['fine_per_day']);endif; ?>" class="form-control  number" placeholder="fine per day" >
               
              </div>
            </div>
            
          
                
            
          
            
             
            
            
            
            
            
            <div class="form-group" style="margin-top:30px;">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('fineListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a>
                <span class="tools pull-right">
                <span class="btn btn-outline btn-default">Note
                    :- <strong><span style="color:#FF0000;">*</span> Indicates
                    required fields</strong> </span>
                </span>
              </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>
<script>
$(document).on('change','#product_department',function(){
	var	department_id		=	$(this).val();
	var size_id				=	'';
	$.ajax({
		type: 'post',
		 url: FULLSITEURL+CURRENTCLASS+'/get_product_size',
		data: {department_id:department_id,size_id:size_id},
	 success: function(response){
			$('#product_size').html(response);
		}
	});
});
</script>




    
   
    
  

 
    