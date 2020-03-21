<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('roomListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      room details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    room details</li>
  <li class="pull-right"><a href="<?php echo correctLink('roomListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> 
        <span class="tools pull-left"> <?=$EDITDATA?'Edit':'Add'?> room details</span> 
        </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
           
          
            <div class="form-group">
              <label class="col-lg-3 control-label">Room Number<span class="required">*</span></label>
              <div class="col-lg-6">
                <input type="text" name="room_name" id="room_name"value="<?php if(set_value('room_name')): echo set_value('room_name'); else: echo stripslashes($EDITDATA['room_name']);endif; ?>" class="form-control required" placeholder="Room No.">
                  <?php if(form_error('room_name')): ?>
                  <p for="room_name" generated="true" class="error"><?php echo form_error('room_name'); ?></p>
                  <?php endif; ?>
              </div>
            </div>
             
            
             <div class="form-group">         
                <label class="col-lg-3 control-label"> Room Type<span class="required">*</span></label>
                <div class="col-lg-6">
                  <?php if(set_value('room_type_id')): $roomCatid	=	set_value('room_type_id'); elseif($EDITDATA['room_type_id']): $roomCatid	=	stripslashes($EDITDATA['room_type_id']); else: $roomCatid	=	''; endif; ?>
                  <select name="room_type_id" id="room_type_id" class="form-control required">
                  	<option value="">Select Room Type</option>
                    <?php if($ROOMTYPEDATA <> ""): foreach($ROOMTYPEDATA as $ROOMTYPEINFO): 
						
					?>
                    	<option value="<?php echo $ROOMTYPEINFO['encrypt_id']; ?>" <?php if($ROOMTYPEINFO['encrypt_id'] == $roomCatid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($ROOMTYPEINFO['room_type_name']); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('room_type_id')): ?>
                  <p for="room_type_id" generated="true" class="error"><?php echo form_error('room_type_id'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
         
              
              
               
             <div class="form-group">
              <label class="col-lg-3 control-label">Room Location</label>
              <div class="col-lg-6">
                <input type="text" name="location" id="location"value="<?php if(set_value('location')): echo set_value('location'); else: echo stripslashes($EDITDATA['location']);endif; ?>" class="form-control " placeholder="location">
                  <?php if(form_error('location')): ?>
                  <p for="location" generated="true" class="error"><?php echo form_error('location'); ?></p>
                  <?php endif; ?>
              </div>
            </div>
         
          
          
            
              
            
               <div class="form-group">
                    
                      <label class="col-lg-3 control-label">Room Description</label>
                       <div class="col-lg-6">
                      <?php if(set_value('description')): $content	= set_value('description'); elseif($EDITDATA['description']): $content	= $EDITDATA['description']; else: $content	= ''; endif; ?>
                      <textarea style="
    height: 100px;
    width: 463px;
" name="description"  class="" placeholder="Description"><?php echo stripslashes($content); ?></textarea>
                      <?php if(form_error('description')): ?>
                      <label for="description" generated="true" class="error"><?php echo form_error('description'); ?></label>
                      <?php endif; ?>
                    </div>
                 </div>
          
            
            
            
            
            
            <div class="form-group" style="margin-top:30px;">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('roomListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a>
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

   
    
  

 
    