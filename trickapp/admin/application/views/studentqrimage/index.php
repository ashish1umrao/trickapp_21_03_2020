<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
 
  <li class="active">
    
   Download Student QR Code</li>
 
</ol>
{message}
<div class="form-w3layouts">
  
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
         Download Student QR Code</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <br clear="all" />
         
        
            <br clear="all" />
         
            <div>
              <div class="col-lg-12 class-parent">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Class name<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <?php if(set_value('class_id')): $classid = set_value('class_id'); else: $classid  = $EDITDATA['class_id'];  endif; ?>
                    <select name="class_id" id="class_id" class="form-control required">
                      <option value="">Select class name</option>
                      <?php if($CLASSDATA <> ""): foreach($CLASSDATA as $CLASSINFO): ?>
                      <option value="<?php echo $CLASSINFO['encrypt_id']; ?>" <?php if($CLASSINFO['encrypt_id'] == $classid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($CLASSINFO['class_name']); ?></option>
                      <?php endforeach; endif; ?>
                    </select>
                    <?php if(form_error('class_id')): ?>
                    <p for="class_id" generated="true" class="error"><?php echo form_error('class_id'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Section name<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <?php if(set_value('section_id')): $sectionid = set_value('section_id'); else: $sectionid  = $EDITDATA['section_id'];  endif; ?>
                    <select name="section_id" id="section_id" class="form-control required">
                      <option value="">Select section name</option>
                    </select>
                    <?php if(form_error('section_id')): ?>
                    <p for="section_id" generated="true" class="error"><?php echo form_error('section_id'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
                </br>
                </br>
                </br>
                <?php   if($EDITDATA['qr_pic']):  ?>
              <div class="col-lg-12">
               <div class="form-group">
                <div class="col-lg-6">
                 <label class="col-lg-4 control-label">QRcode</label>
                  <img src="<?php echo base_url();?><?php echo stripslashes($EDITDATA['qr_pic'])?>" width="150" border="0" alt="" />   
                    
              </div>
               </div>
                      <?php endif ;   ?>
                 
                  </br>
      
            <div class="form-group">
              <div class="col-lg-offset-1 col-lg-11">
                  <?php if($edit_data == 'Y'):  ?>
             <input type="submit" name="SaveChanges" id="SaveChanges" value="Genrate Bulk QR Code" class="btn btn-primary"  style="
    background-color: #8b5c7e;
    border-color: #8b5c7e;
" />       <?php endif;  ?>
                    <input type="submit" name="DownloadQR" id="DownloadQR" value="Download" class="btn btn-primary"
                           style="
    background-color: #f59283;
    border-color: #f59283;
"/>
                <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>

<script>
$(document).on('change','#class_id',function(){
  var curobj      =   $(this);
  var classid     =   $(this).val();
  var sectionid   =   '';
  get_section_data(curobj,classid,sectionid);
});
</script>
<script>
<?php if($EDITDATA <> "" || $_POST): ?> 
$(document).ready(function(){ 
  var curobj      =   $('#class_id');
  var classid     =   '<?php echo $classid; ?>';
  var sectionid   =   '<?php echo $sectionid; ?>';
  get_section_data(curobj,classid,sectionid);
});
<?php endif; ?>
</script> 