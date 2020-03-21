<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script>
$(function(){
	 $("#startdate").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,minDate: 0,yearRange: "2017:2050"});
  $("#enddate").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,minDate: 0,yearRange: "2017:2050"});
 
});
</script    >
<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('roomListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      room details</a></li>
  <li class="active">
    
    Room  allocation details</li>
  <li class="pull-right"><a href="<?php echo correctLink('roomListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> 
        <span class="tools pull-left">   Room No.  <?php  echo $EDITDATA['room_name']  ?> allocation </span> 
        </header>
        <div class="panel-body">
          
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
               <input type="hidden" name="hosteler_id" id="hosteler_id" value=""/>
              <div class="form-group">
                <label for="name" class="col-sm-3 control-label">Hosteler Type<span class="required">*</span></label>
                <div class="col-sm-8">
             
                  <input   type="radio" name="hosteler_type"  value="Student" class=" required" checked="checked"/>
                  &nbsp; Student   &nbsp;
                <input   type="radio" name="hosteler_type"  value=" Staff" class=" required" />
                  &nbsp; Staff
                  <?php if(form_error('hosteler_type')): ?>
                  <label for="hosteler_type" generated="true" class="error"><?php echo form_error('hosteler_type'); ?></label>
                  <?php endif; ?>
                </div>
              </div>
             <div class="col-lg-12 class-parent">
              <div class="col-lg-8">
                    <div class="form-group">
                  <label class="col-lg-4 control-label">Hosteler Name<span class="required">*</span> </label>
                  <div class="col-lg-8">
                     <input type="text" id="searchtext" name="searchtext" placeholder="Name , Registration Id ,Unique Id or Employee Id" value="<?php echo $searchKey;?>" class="search-form form-control required searchfields" maxlength="128"  autocomplete="off">
                    <div class="searchres"></div>
                <?php if(form_error('startdate')): ?>
                  <p for="searchtext" generated="true" class="error"><?php echo form_error('searchtext'); ?></p>
                  <?php endif; ?>
                  </div>
                </div>
              </div>
             
            </div>
      
             <div class="col-lg-12 ">
              <div class="col-lg-8">
    <div class="form-group">
                <label class="col-lg-4 control-label">Allocation Start Date<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="startdate" id="startdate" value="<?php if(set_value('startdate')): echo set_value('startdate'); else: echo YYMMDDtoDDMMYY($EDITDATA['startdate']);endif; ?>" class="form-control required" placeholder="From Date">
                  <?php if(form_error('startdate')): ?>
                  <p for="startdate" generated="true" class="error"><?php echo form_error('startdate'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
    </div>
      <div class="col-lg-12 ">
       <div class="col-lg-8">
                <div class="form-group">
                <label class="col-lg-4 control-label">Allocation End Date</label>
                <div class="col-lg-8">
                  <input type="text" name="enddate" id="enddate" value="<?php if(set_value('enddate')): echo set_value('enddate'); else: echo YYMMDDtoDDMMYY($EDITDATA['enddate']);endif; ?>" class="form-control required" placeholder="End Date">
                  <?php if(form_error('enddate')): ?>
                  <p for="enddate" generated="true" class="error"><?php echo form_error('enddate'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
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

   
    <script>  
   $("[name='hosteler_type']").on("change", function (e) {
            
   searchtext.value = '';
    enddate.value = '';
     startdate.value = '';
    
});

$(document).on('keyup','#currentPageForm .searchfields',function(){
	var search_string 	= $(this).val();
      var type =   $('input[name=hosteler_type]:checked').val();
    
		if(search_string == ''){
		$("#currentPageForm .searchres").html('');
	}else{ 
           
		$(".fadeshado").show();
		$(this).parent().addClass('fadeshadoZOomup');
		postdata = {csrf_api_key:csrf_api_value,'string':search_string,'type':type}
               
		$.post(FULLSITEURL+CURRENTCLASS+'/search_ajax',postdata,function(data){
			if(data == 'ERROR')
			{
                          
				$("#currentPageForm .searchres").html('');
				$("#currentPageForm .searchres").hide();
				$(".fadeshado").hide();
				$('#currentPageForm .searchfields').parent().removeClass('fadeshadoZOomup');
			}
			else
			{
				$("#currentPageForm .searchres").show();
				$("#currentPageForm .searchres").html(data);
			} 
		});
	}
});
</script>
<script>  
   
$(document).on('click','#currentPageForm .searchres li p',function(){
 
	var	curgdata	=	$(this).html();
	var	curgid		=	$(this).attr('data-id');
      alert(curgid);
	if(curgid != ''){
		$("#currentPageForm .searchres").html('');
		$("#currentPageForm .searchres").hide();
		$(".fadeshado").hide();
		$('#currentPageForm .searchfields').val(curgdata);
		$('#currentPageForm .searchfields').parent().removeClass('fadeshadoZOomup');
              $("#currentPageForm #hosteler_id").val(curgid);
		
	}
});
</script>
  
	


