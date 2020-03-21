<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
 
  <li class="active">
    
   Bulk Upload</li>
 
</ol>
{message}

<div class="form-w3layouts">
  
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
         Student CSV  Data Upload</span> </header>
        <div class="panel-body">
             <?php  if($csv_error): 
               
       for ($i = 1; $i <= $num; $i++) : ?>
                   <p for="error" generated="true" class="error"><?php echo ${"error_csv_".$i}; ?></p>
      <?php  endfor;
          endif;   ?>
            <a class="btn btn-success tools pull-right "  href="{ASSET_URL}demo_doc/students_data.csv" download>Demo Upload CSV<i class="fa fa-upload" aria-hidden="true"></i></a>
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="" enctype="multipart/form-data">
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
              <div class="col-lg-12">
               <div class="form-group">
                <div class="col-lg-6">
                 <label class="col-lg-4 control-label">Upload Student CSV </label>
                       <div class="col-lg-8">
                           
                    <input type="file" name="studentFile" id="studentFile"   accept=".csv" class="form-control required ">
                       </div>
              </div>
               </div>
              </div>
                  </br>
            <div class="form-group">
              <div class="col-lg-offset-1 col-lg-11">
                  <?php if($add_data == 'Y'):  ?>
               <button type="submit" name="SaveStudentExcelUpload" id="SaveChanges" value="upload" class="btn btn-success "><i class="fa fa-upload" aria-hidden="true"></i> Bulk Upload </button>
            <?php endif;  ?>
                <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
                <div class="col-lg-12">
                <div class="form-group">
                  <label class="col-lg-12 "><span class="required">*</span>Student First Name ,Date of birth ,Gender,Roll No,Registration No,Admission Date,Father First Name, Father Mobile are Mandatory Fileds In csv .</label>
                  <label class="col-lg-12 "><span class="required">*</span> Date of birth and Admission Date  are in <b>yyyy-mm-dd </b> formet. </label>
                  <label class="col-lg-12 "><span class="required">*</span> Student Religion can be Buddh ,Christian,Hindu,Jain,Muslim and Sikh in case sensitive formet. </label>
                  <label class="col-lg-12 "><span class="required">*</span> Student Gender can be Male and Female  in case sensitive formet. </label>
                  <label class="col-lg-12 "><span class="required">*</span> Student Category can be General ,OBC,SC and ST in case sensitive formet. </label>
                </div>
              </div>
          </form>
        </div>
      </section>
    </div>
  </div>
       <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
        Student Images Upload</span> </header>
        <div class="panel-body">
              <?php  if($error_image)  :
      foreach ($error_image as $E): ?>
                   <p for="error" generated="true" class="error"><?php echo $E; ?></p>
      <?php  endforeach;
          endif;   ?>
          <form id="currentPageFormimage" method="post" class="form-horizontal" role="form" action="" enctype="multipart/form-data">
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
              <div class="col-lg-12">
                   <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Browse Multiple Images<span class="required">*</span></label>
                  <div class="col-lg-8">
                   <input type="file" name="uploadfile[]" id="uploadfile" class="form-control required "   multiple  accept="image/*" />
                 </div>
                </div>
              </div>
               </div>
                <br>
                <br>
                <br>
                 <div class="form-group">
              <div class="col-lg-offset-1 col-lg-11">
                  <?php if($add_data == 'Y'):  ?>
               <button type="submit" name="uploadimage" id="SaveChanges" value="uploadimage" class="btn btn-success "><i class="fa fa-upload" aria-hidden="true"></i> Bulk Upload </button>
            <?php endif;  ?>
                    
                <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
                     
            </div>
             
              
                
                   <div class="col-lg-12">
                <div class="form-group">
                  <label class="col-lg-12 "><span class="required">*</span>Image Size Should be less then 50 KB .</label>
                  <label class="col-lg-12 "><span class="required">*</span>image Name should be student Unique Id . if Unique Id of student is 2012001RE09042018 then image name will be 2012001RE09042018.jpg </label>
                 
                </div>
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
<?php if( $_POST): ?> 
$(document).ready(function(){ 
  var curobj      =   $('#class_id');
  var classid     =   '<?php echo $classid; ?>';
  var sectionid   =   '<?php echo $sectionid; ?>';
  get_section_data(curobj,classid,sectionid);
});
<?php endif; ?>
</script> 



<script type="text/javascript">
$(document).ready(function(){
	$("#currentPageFormimage").validate();
});
</script>
