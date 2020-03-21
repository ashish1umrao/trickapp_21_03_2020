
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>

<script>
    
$(function(){
	
  $("#return_date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,minDate: 0 ,changeYear: true, yearRange: "1960:<?php echo date('Y'); ?>"});
});

</script>

<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  
  <li class="active">
   
   Issue book</li>
 
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> 
        <span class="tools pull-left"> Issue book </span> 
        </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
         
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
             <div class="form-group">
                  <label class="col-lg-3 control-label"></label>
                  <div class="col-lg-8">
           <label class="col-lg-3 control-label radio inline">
    <input id="student_radio" type="radio" name="readertype" value="student" checked>
    Student </input>
           </label>

 <label class="col-lg-3 control-label  radio inline">
    <input id="ov_radio" type="radio" name="readertype" value="staff">  
    Staff </input>
</label>
          </div>
             </div>
              <div class="form-group">
              <label  id="cont" class="col-lg-3 control-label">Student Registration No.<span class="required">*</span></label>
              <div class="col-lg-6">
                <input type="text" name="reader_id" id="reader_id"value="<?php if(set_value('reader_id')): echo set_value('reader_id'); endif; ?>" class="form-control required" placeholder="Registration no">
                  <?php if(form_error('reader_id')): ?>
                  <p for="reader_id" generated="true" class="error"><?php echo form_error('reader_id'); ?></p>
                  <?php  elseif($readerError): ?>
                  <p for="reader_id" generated="true" class="error"><?php echo $readerError; ?></p>
                  <?php endif; ?>
                  
              </div>
            </div>            
            
            <div class="form-group">
              <label class="col-lg-3 control-label">Book Barcode<span class="required">*</span></label>
              <div class="col-lg-6">
                <input type="text" name="barcode" id="barcode"value="<?php if(set_value('barcode')): echo set_value('barcode'); endif; ?>" class="form-control required" placeholder="Barcode Number">
                  <?php if(form_error('barcode')): ?>
                  <p for="barcode" generated="true" class="error"><?php echo form_error('barcode'); ?></p>
                   <?php
                   elseif($barcodeError): ?>
                    <p for="barcode" generated="true" class="error"><?php echo $barcodeError; ?></p>
                    <?php endif; ?>
              </div>
            </div>
       
         
               <div class="form-group">
                  <label class="col-lg-3 control-label">Book Return Date<span class="required">*</span></label>
                  <div class="col-lg-6">
                    <input type="text" name="return_date" id="return_date"value="<?php if(set_value('return_date')): echo set_value('return_date');else: echo $return_date ; endif; ?>" class="form-control required" placeholder="Return Date">
                    <?php if(form_error('return_date')): ?>
                    <p for="return_date" generated="true" class="error"><?php echo form_error('return_date'); ?></p>
                    <?php endif; ?>
                    
                  </div>
                </div>
            
             
            
             
          
            
            
            
            
            
            <div class="form-group" style="margin-top:30px;">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Issue Book" class="btn btn-primary" />
                <a href="<?php echo correctLink('issueBookAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a>
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
$(document).on('change','#barcode',function(){
	var	barcode		=	$(this).val();
	
	$.ajax({
		type: 'post',
		 url: FULLSITEURL+CURRENTCLASS+'/get_book_detail',
		data: {barcode:#barcode},
	 success: function(response){
			$('#product_size').html(response);
		}
	});
});
</script>


<script>
$(".radio input[type='radio']").on( 'click', function(){
    if ($("#student_radio").is(":checked")) {
         $("#cont").html( "Student Registration No." );
         $("#reader_id").attr("placeholder", "Registration no").val("").focus().blur();
    } else if ($("#ov_radio").is(":checked")) {
         $("#cont").html( "Staff Employee Id" );
         $("#reader_id").attr("placeholder", "Employee id").val("").focus().blur();
    }
});
</script>


 

   
   
  

 
    