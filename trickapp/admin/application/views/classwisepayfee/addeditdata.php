<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script>
$(function(){
  	$("#fee_start_date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
	  $("#fee_end_date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
    $("#fee_due_date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
});
</script>
<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('classwisepayfee','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      fee </a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    fee </li>
  <li class="pull-right"><a href="<?php echo correctLink('classwisepayfee','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          fee </span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/><?php //echo "<pre>"; print_r($EDITDATA); die;?>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <fieldset>
            <legend>Student Personal Details</legend>
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
                    <?php if(set_value('section_id')): $sectionid = set_value('section_id'); else: $sectionid  = $EDITDATA['student_id'];  endif; ?>
                    <select id="student_id_blank" class="form-control required">
                      <option value="">Select student name</option>
                    </select>                         
                    <?php if(form_error('section_id')): ?>
                    <p for="section_id" generated="true" class="error"><?php echo form_error('section_id'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>    
              </span>
            <!-- </span>     -->
            </div> 
          </fieldset>
          <fieldset>
            <legend>Student Pay Fee Section</legend>
             <div class="col-lg-12 class-parent">
              <div class="form-group">
              <div class="col-lg-6">
                <label class="col-lg-4 control-label">Fee Heading</label>
                <div class="col-lg-8">
                <span id="MultiCheckBOX1"></span>
                  <?php if(set_value('fee_head_name')): $feeheadid	=	set_value('fee_head_name'); elseif($EDITDATA['fee_head_name']): $feeheadid	=	stripslashes($EDITDATA['fee_head_name']); else: $feeheadid	=	''; endif; ?>
                  <select name="fee_head_name" id="fee_head_name" class="form-control" onchange="return getStudentfeepayInfo(this.value);">
                  	<option value="">Select Fee Heading</option>
                    <?php if($FEEHEADINGLIST <> ""):  foreach($FEEHEADINGLIST as $FEEHEAD): ?>
                    	<option value="<?php echo $FEEHEAD->encrypt_id; ?>" <?php if($FEEHEAD->encrypt_id == $feeheadid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($FEEHEAD->heading_type); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('fee_head_name')): ?>
                  <p for="fee_head_name" generated="true" class="error"><?php echo form_error('fee_head_name'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              </div>
              <div class="form-group">
              <div class="form-group">
              <div class="form-group">
              <div class="col-lg-6">
                <label class="col-lg-4 control-label">Per Month Fee</label>
                <div class="col-lg-5">                   
                    <input type="text" name="per_month_fee" id="per_month_fee" value="<?php $value['per_month_fee']; ?>" class="form-control" placeholder="Amount" autocomplete="off" readonly>
                  </div>
              </div>
              </div>
              </div>
              </div>
              <?php $ALLMONTHNAME = array("0"=>"January","1"=>"Febuary","2"=>"March" ,"3"=>"April","4"=>"May","5"=>"June","6"=>"July","7"=>"August","8"=>"September","9"=>"October","10"=>"November","11"=>"December"); ?>
             <?php  //print_r($ALLMONTHNAME); die; ?>
              <div class="col-lg-12 class-parent">
              <label class="col-lg-3 control-label"> Month Chart</label>
                      <?php if($ALLMONTHNAME<>""): foreach ($ALLMONTHNAME as $venue['month_name']):  //echo "<pre>"; print_r($venue['month_name']); die; ?>
                      <label class="checkbox-inline"><input type="checkbox" name="month_name[]" id="month_name" value="<?php echo $venue['month_name']; ?>" class="required"><?php echo $venue['month_name']; ?></label>
              <?php endforeach; endif;?>
                    </div>
              </div>
              </fieldset>
              <fieldset>
            <legend>Total Paybal Amount</legend>
            <div class="col-lg-12 class-parent">
            <div class="col-lg-4">
                <label class="col-lg-4 control-label">Per Month Fee</label>
                <div class="col-lg-8"> 
                <input type="hidden" id="edit-count-checked-checkboxes" name="totalfeeamount" value="" class="form-text" />                  
                    <input type="text" name="feeamount" id="feeamount" value="" class="form-control required" placeholder="Amount" autocomplete="off" readonly >
                  </div>
              </div>
             <div class="col-lg-4">
                <label class="col-lg-4 control-label">Due Fee(Fine)</label>
                <div class="col-lg-8">                   
                    <input type="text" name="due_fine" id="due_fine" value="<?php $value['per_month_fee']; ?>" class="form-control " placeholder="Fine Amount" autocomplete="off" >
                  </div>
              </div>
              <div class="col-lg-4">
                <label class="col-lg-4 control-label">Total Paybal Amount<span class="required">*</span></label>
                <div class="col-lg-8">                   
                    <input type="text" name="total_paybal_amount" id="total_paybal_amount" value="<?php $value['per_month_fee']; ?>" class="form-control required" placeholder="Total Paybal Amount" autocomplete="off" >
                  </div>
              </div>
              </div>
             
            <div class="col-lg-12">
            <div class="col-lg-4">
                <label class="col-lg-4 control-label">Payment Type</label>
                <div class="col-lg-8">
                <?php $PAYMENTTYPE = array("0"=>"Cash","1"=>"Internet Banking","2"=>"Paytm" ,"3"=>"Check"); ?>
                 
                    <?php if($PAYMENTTYPE<> ""):  foreach($PAYMENTTYPE as $payment['payment_type']): ?>
                    <label class="checkbox-inline"><input type="radio" onclick="javascript:yesnoCheck();" name="payment_type" id="payment_type" value="<?php echo $payment['payment_type']; ?>" class="required"><?php echo $payment['payment_type']; ?></label>
                    <?php endforeach; endif; ?>
                </div>
              </div>
              <div class="col-lg-4" >
                <label class="col-lg-4 control-label">Trnx No.<span class="required">*</span></label>
                <div class="col-lg-8">                   
                    <input type="text" name="total_paybal_amount_no" id="" value="<?php $value['total_paybal_amount_no']; ?>" class="form-control required" placeholder="Total Paybal Recipt No" autocomplete="off" >
                  </div>
              </div>
              </div>
              </div>
              </div>
          </fieldset>
            <div class="form-group">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('classwisepayfee','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
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
   
  $(document).ready(function(){
    var $checkboxes =  $('input[type="checkbox"]').click(function(){
  $checkboxes.change(function(){
    var countCheckedCheckboxes = $checkboxes.filter(':checked').length;
    var MonthFees = $("#per_month_fee").val();
    var Total = parseInt(MonthFees)*parseInt(countCheckedCheckboxes);
    $("#feeamount").val(Total);
    $('#edit-count-checked-checkboxes').val(countCheckedCheckboxes);
});
});
});
/////////////////////////////////////////Multiplication month and per month fee//////////////////////////////////////////
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
            var length = response.length;
            if(length==3){
              $('#MultiCheckBOX').css('display','block');
                $("#MultiCheckBOX").html(response);
                $("#student_id_blank").show();
               
            }else{
                $('#MultiCheckBOX').css('display','block');
                $("#MultiCheckBOX").html(response);
                $("#student_id_blank").hide();  
            }
            }
        });
      // return false;
}
</script>
<<script>
  function getStudentfeepayInfo(){
var class_id = $("#class_id").val();
var fee_heading_id = $("#fee_head_name").val(); 
var methodURL = 'getclasswisefeee';
 $.ajax({
        type: 'post',
        url:  methodURL,
        data: {class_id: class_id,fee_heading_id: fee_heading_id},            
        success: function (response) { 
          //alert(response);
          $("#per_month_fee").val(response);
        var length = response.length;
        if(length==3){
            $("#fee_heading_id").show();
            
        }else{
            $("#MultiCheckBOX1").html(response);
            $("#fee_heading_id").hide();  
        }
        }
    });
  // return false;
}
</script>
