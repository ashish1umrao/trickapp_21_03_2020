<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('shelfListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      shelf details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    shelf details</li>
  <li class="pull-right"><a href="<?php echo correctLink('shelfListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> 
        <span class="tools pull-left"> <?=$EDITDATA?'Edit':'Add'?> shelf details</span> 
        </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
           
          
            <div class="form-group">
              <label class="col-lg-3 control-label">Shelf No.<span class="required">*</span></label>
              <div class="col-lg-6">
                <input type="text" name="shelf" id="shelf"value="<?php if(set_value('shelf')): echo set_value('shelf'); else: echo stripslashes($EDITDATA['shelf']);endif; ?>" class="form-control required" placeholder="shelf">
                  <?php if(form_error('shelf')): ?>
                  <p for="shelf" generated="true" class="error"><?php echo form_error('shelf'); ?></p>
                  <?php endif; ?>
              </div>
            </div>
           
            
         
            
            
            
            
            
            <div class="form-group" style="margin-top:30px;">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('shelfListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a>
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

   
    
  

 
    