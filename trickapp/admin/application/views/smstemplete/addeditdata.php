 <!-- Bootstrap core CSS -->        
  <style type="text/css">
#myAssignClassTeacherModal.modal { z-index:999995; margin-top:50px; font-size:14px;}
#myAssignClassTeacherModal .modal-body { text-align:center;}
#myAssignClassTeacherModal .table-bordered tr td { height:40px;}
#myAssignClassTeacherModal .view-detail-title h1 {margin-bottom: 0px; margin-top: 0px;}
#preview{background-color:#8b5c7d; color: white !important;}
  </style>
  <link rel="stylesheet" type="text/css" href="http://demos.codexworld.com/multi-select-dropdown-list-with-checkbox-jquery/multiselect/jquery.multiselect.css"/>
 <ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('smstempleteAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Send SMS</a></li>
  <li class="active">
   SMS Module</li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
       <?php if($EDITDATA):
       echo   $studentDetails ; endif; ?>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <input type="hidden" name="search_type" id="search_type">
            <br clear="all" />
      <fieldset>
            <legend>Send SMS</legend>
            <div class="col-lg-12 class-parent">
               <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Class name<span class="required">*</span></label>
                  <div class="col-lg-6">
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
              <span id="student_data">
           
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Section name<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <?php if(set_value('section_id')): $sectionid = set_value('section_id'); else: $sectionid  = $EDITDATA['section_id'];  endif; ?>
                    <select name="section_id" id="section_id" class="form-control required" onchange="return getStudentInfo(this.value);">
                      <option value="">Select section name</option>
                    </select>
                    <?php if(form_error('section_id')): ?>
                    <p for="section_id" generated="true" class="error"><?php echo form_error('section_id'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>    

               <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Student name<span class="required">*</span></label>
                 <div class="col-lg-6">
                    <span id="MultiCheckBOX"></span>
                    <?php if(set_value('section_id')): $sectionid = set_value('section_id'); else: $sectionid  = $EDITDATA['section_id'];  endif; ?>
                    <!-- <select name="student_id" id="student_id" class="form-control required">
                      
                    </select> -->
                    
                    <select  id="student_id_blank" class="form-control required">
                      <option value="">Select student name</option>
                    </select>                         
                    <?php if(form_error('section_id')): ?>
                    <p for="section_id" generated="true" class="error"><?php echo form_error('section_id'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>    

              <div class="col-lg-6" id="templat_list">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Template<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <?php if(set_value('template_id')): $template_id = set_value('template_id'); else: $classid  = $EDITDATA['class_id'];  endif; ?>
                    <select name="template_id" id="template_id" class="form-control required">
                      <option value="">Select template</option>
                      <?php if($TEMPLATEDATA <> ""): foreach($TEMPLATEDATA as $TEMPLATEDATAINFO): ?>
                      <option value="<?php echo $TEMPLATEDATAINFO['encrypt_id']; ?>" 
                        <?php if($TEMPLATEDATAINFO['encrypt_id'] == $template_id): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($TEMPLATEDATAINFO['sms_type']); ?></option>
                      <?php endforeach; endif; ?>
                    </select>
                    <?php if(form_error('template_id')): ?>
                    <p for="template_id" generated="true" class="error"><?php echo form_error('template_id'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>            
              </span>
            <!-- </span>     -->
            </div> 
                  
          </fieldset>
          <br clear="all" />            
            <div class="form-group">
              <div class="col-lg-offset-1 col-lg-11">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('smstempleteAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a>
                 <span id="preview_button" style="display:none;">
                  <a  class="btn btn-default" id="preview">Preview</a>
                </span>
              <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
          </form>
        </div>
      </section>
      <!-- HERE LIST ---->
        <span id="student_record"></span>
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
<script>
$(function(){
  UploadImage('0');
});

function getClassShow(){
    var assessment_type = document.getElementById('common_to_all').value;
    if(assessment_type=='N'){
        $("#class_area").show();
    }
    else{
        $("#class_area").hide();
    }
}

function getStudentInfo(){

    var class_id = $("#class_id").val();
    var section_id = $("#section_id").val(); 
    var methodURL = 'getStudentList';

     $.ajax({
            type: 'post',
            url:  methodURL,
            data: {class_id: class_id,section_id: section_id},            
            success: function (response) { 
            // alert(response);   
           //  alert(response.length);
            var length = response.length;
            if(length==3){
               // alert('blank');
                $("#student_id_blank").show();
                $("#MultiCheckBOX").hide();
            }else{
                //alert('data');
                $('#MultiCheckBOX').css('display','block');
                $("#MultiCheckBOX").html(response);
                $("#student_id_blank").hide();  
            }
                      
                

                /*
              if(response==0){
//                alert(response);
                $("#student_id").hide();
                $("#student_id_blank").show();
              }else{
                 $("#student_id_blank").hide();
                  $("#student_id").show();
            //  alert(response);
              var len = response.length;
              $("#student_id").empty();
               for( var i = 0; i<len; i++){
                    var id = response[i]['encrypt_id'];
                    var name = response[i]['student_f_name'];                    
                    $("#student_id").append("<option value='"+id+"'>"+name+"</option>");
                }
              }*/
            }
        });
      // return false;
}

 $("#checkAll").click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
 });
</script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
 <script src="http://demos.codexworld.com/includes/js/bootstrap.js"></script>       
 <script src="http://demos.codexworld.com/multi-select-dropdown-list-with-checkbox-jquery/multiselect/jquery.multiselect.js"></script>
 <script>
$('#langOpt3').multiselect({
    columns: 1,
    placeholder: 'Select Student',
    search: true,
    selectAll: true
});


$(document).on('change','#template_id',function(){

var template_id = $("#template_id").val();
  if(template_id!='' ){  
    $("#preview_button").show();
  }else{
   $("#preview_button").hide(); 
  }
}); 

$(document).on('click','#preview',function(){
var class_id = $("#class_id").val();
var section_id = $("#section_id").val();
var student_id = $("#langOpt3").val();
var template_id = $("#template_id").val();
  $("#myAssignClassTeacherModal").modal();
  $("#myAssignClassTeacherModal .view-detail-title").html('<h3>SMS Preview</h3>');
  $("#myAssignClassTeacherModal .view-detail-data").html('<h3>Loading...</h3>');
    $.ajax({
        type: 'post',
         url: 'getPreviewSMS',
        data: {template_id:template_id},
       success: function(response){       
          $("#myAssignClassTeacherModal .view-detail-data").html(response);
        } 
      });
   }); 
</script>


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


