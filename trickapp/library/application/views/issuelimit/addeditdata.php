<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('issueLimitListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      max book issue limit</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    max book issue limit details</li>
  <li class="pull-right"><a href="<?php echo correctLink('issueLimitListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> 
        <span class="tools pull-left"> <?=$EDITDATA?'Edit':'Add'?>  max book issue limit details</span> 
        </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
           
         <div class="form-group">
                <label class="col-lg-3 control-label">Reader Type</label>
                <div class="col-lg-6">
                  <?php if(set_value('reader_type_id')): $readertypeid	=	set_value('reader_type_id'); elseif($EDITDATA['reader_type_id']): $readertypeid	=	stripslashes($EDITDATA['reader_type_id']); else: $readertypeid	=	''; endif; ?>
                  <select name="reader_type_id" id="reader_type_id" class="form-control">
                  	<option value="">Select Reader Type</option>
                    <?php if($READERTYPEDATA <> ""): foreach($READERTYPEDATA as $READERINFO): ?>
                    	<option value="<?php echo $READERINFO['encrypt_id']; ?>" <?php if($READERINFO['encrypt_id'] == $readertypeid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($READERINFO['reader_type']); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('reader_type_id')): ?>
                  <p for="reader_type_id" generated="true" class="error"><?php echo form_error('reader_type_id'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            
         
            <div class="form-group">
              <label class="col-lg-3 control-label">Max book issue limit <span class="required">*</span></label>
              <div class="col-lg-6">
                  <input type="text" name="issue_limit" id="product_quantity"value="<?php if(set_value('issue_limit')): echo set_value('issue_limit'); else: echo stripslashes($EDITDATA['issue_limit']);endif; ?>" class="form-control  number" placeholder="No of book" >
               
              </div>
            </div>
            
          
                
            
          
            
             
            
            
            
            
            
            <div class="form-group" style="margin-top:30px;">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('issueLimitListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a>
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




    
   
    
  

 
    