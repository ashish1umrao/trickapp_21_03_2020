

<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('shelfRListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage shelf row</a></li>
  <li class="active">
   
   Relocate Books</li>
  <li class="pull-right"><a href="<?php echo correctLink('shelfRListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> 
        <span class="tools pull-left"> Relocate Books of Shelf No   <?php echo $currentShelfInfo['shelf']  ?>  </span> 
        </header>
        <div class="panel-body">
          <form id="currentPageForm" method="get" class="form-horizontal" role="form" action="">
         
           
           
          
         <div class="col-lg-12  " style="margin-top:30px;">
              <div class="col-lg-5">
          <div class="form-group">
                  <label class="col-lg-6 control-label"> Current Shelf Row No.<span class="required">*</span></label>
                  <div class="col-lg-6">
                    <?php if(set_value('current_row_id')): $cRowid = set_value('current_row_id'); else: $cRowid  = $curentRowId;  endif; ?>
                    <select name="current_row_id" id="shelf_id" class="form-control required">
                      <option value="">Select shelf </option>
                      <?php if($cShelfRow <> ""): foreach($cShelfRow as $CRINFO): ?>
                      <option value="<?php echo $CRINFO['encrypt_id']; ?>" <?php if($CRINFO['encrypt_id'] == $cRowid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($CRINFO['shelf_row']); ?></option>
                      <?php endforeach; endif; ?>
                    </select>
                    <?php if(form_error('shelf_id')): ?>
                    <p for="shelf_id" generated="true" class="error"><?php echo form_error('shelf_id'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
</div>     
             
             
             
         
              
             
           <div class="col-lg-5" >
               <div class="col-lg-6">
            <div class="form-group" >
              
               
               <a id="submit" class="btn btn-primary ">Submit</a>  
               
             
            </div>
               </div>
                 </div>
         </div>
          </form>
            </div>
          <br>
        
    <?php  if($curentRowId):  ?> 
  <div class="panel panel-default">
    <form id="Data_Form" name="Data_Form" method="post" >
              <div class="col-lg-12 class-parent">
              <div class="col-lg-5">
                <div class="form-group">
                  <label class="col-lg-6 control-label"> New  Shelf No.<span class="required">*</span></label>
                  <div class="col-lg-6">
                    <?php if(set_value('shelf_id')): $shelfid = set_value('shelf_id'); else: $shelfid  = $EDITDATA['shelf_id'];  endif; ?>
                    <select name="shelf_id" id="shelf_id" class="form-control required">
                      <option value="">Select  shelf </option>
                      <?php if($SHELFDATA <> ""): foreach($SHELFDATA as $SHELFINFO): ?>
                      <option value="<?php echo $SHELFINFO['encrypt_id']; ?>" <?php if($SHELFINFO['encrypt_id'] == $shelfid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($SHELFINFO['shelf']); ?></option>
                      <?php endforeach; endif; ?>
                    </select>
                    <?php if(form_error('shelf_id')): ?>
                    <p for="shelf_id" generated="true" class="error"><?php echo form_error('shelf_id'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="col-lg-7">
                  <div class="form-group"  style="
    overflow: hidden;" >
                  <label class="col-lg-4 control-label"> New Shelf Row No.<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <?php if(set_value('shelf_row_id')): $shelfrowid = set_value('shelf_row_id'); else: $shelfrowid  = $EDITDATA['shelf_row_id'];  endif; ?>
                    <select name="shelf_row_id" id="shelf_row_id" class="form-control required">
                      <option value="">Select shelf row  ( Available Book slots )  </option>
                    </select>
                    <?php if(form_error('shelf_row_id')): ?>
                    <p for="shelf_row_id" generated="true" class="error"><?php echo form_error('shelf_row_id'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            </div>
    <div class="table-responsive">
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th>Sr.No.</th>
            <th>Book Id</th>
              <th>Book</th>
            <th>Designated Book Location </th>
            <th>Status</th>
           
          </tr>
        </thead>
        <tbody>
          <?php  if($ALLDATA <> ""): $i=$first; foreach($ALLDATA as $ALLDATAINFO): ?>
         <th scope="row" <?php if($i%2 == 0): echo 'odd'; else: echo 'even'; endif; ?>><?=$i++?>
              &nbsp;&nbsp;
              <input type="checkbox" name="DataId[]" id="DataId<?=$i?>" value="<?=$ALLDATAINFO['barcode_id']?>" class="flat" /></th>
            <td><?=stripslashes($ALLDATAINFO['barcode'])?></td>
            <td><?=stripslashes($ALLDATAINFO['book_name'])?>  (  <?=stripslashes($ALLDATAINFO['book_author'])?> ) </td>
            <td>Shelf No. : <?=stripslashes($ALLDATAINFO['shelf'])?>  Row No. <?=stripslashes($ALLDATAINFO['shelf_row'])?> </td>
       
            <td><?=showbookcopyStatus($ALLDATAINFO['book_status'])?></td>
            
          </tr>
          <?php endforeach; else: ?>
            <tr>
              <td colspan="6" style="text-align:center;">No data available in table</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <?php if($ALLDATA <> ""): ?>
    <footer class="panel-footer">
      <div class="row">
           <div class="col-md-4 "> <span>
          <label  for="check_all">
          <input name="check_all" id="check_all" type="checkbox" value="check_all" class="flat"/>
          Select All</label>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="btn btn-primary deletemiltiple">Relocate Books</span> </span> </div>
        <div class="col-sm-5">
          <small class="text-muted inline m-t-sm m-b-sm"><?php echo $noOfContent; ?></small>
        </div>
        <div class="col-sm-7 text-right text-center-xs">                
          <?=$this->pagination->create_links()?>
        </div>
      </div>
    </footer>
    <?php endif; ?>
    </form>
  </div>
<?php endif;  ?>
  
</div>
<script>
$(document).on('click','#submit',function(){
	$('#currentPageForm').submit();														 
});

</script>


    
    <script>
$(document).on('change','#shelf_id',function(){
  var curobj      =   $(this);
  var shelfid     =   $(this).val();
  var shelfrowid   =   '';
  get_shelf_row_data(curobj,shelfid,shelfrowid);
});
</script>
<script>
<?php if($EDITDATA <> "" || $_POST): ?> 
$(document).ready(function(){ 
  var curobj      =   $('#shelf_id');
  var shelfid     =   '<?php echo $shelfid ; ?>';
  var shelfrowid   =   '<?php echo $shelfrowid ; ?>';
  get_shelf_row_data(curobj,shelfid,shelfrowid);
});
<?php endif; ?>
</script> 




<script>
$(document).on('click',"#check_all",function(){
	if($(this).is(":checked"))
		var	checkvalue	=	'true';
	else
		var	checkvalue	=	'false';
		
	$("form#Data_Form input:checkbox").each(function(){
		if($(this).attr('id')!='check_all'){ 
			if(checkvalue == 'true'){ 
				$(this).prop("checked",true);
				$(this).parent().parent().addClass('selected');
			}
			else if(checkvalue == 'false'){ 
				$(this).prop("checked",false);
				$(this).parent().parent().removeClass('selected');
			}
		}
	});
});

$(document).on('click',"#Data_Form input[id*='DataId']",function(){
	if($(this).is(":checked"))
		$(this).parent().parent().addClass('selected');
	else
		$(this).parent().parent().removeClass('selected');
	var total = 0;
	var count = 0;
	$("form#Data_Form input:checkbox").each(function(){
		if($(this).attr('id')!='check_all'){ 
			if(!$(this).is('input#check_all'))	total++;	
			if(!$(this).is('input#check_all') && $(this).is(":checked"))	count++;	
		}
	});
	if(total!=0 && count!=0 && total==count)
		$('#Data_Form #check_all').prop("checked",true);
	else
		$('#Data_Form #check_all').prop("checked",false);
});

$(document).on('click',"#Data_Form .deletemiltiple",function(){
	var count =	0;
	$("form input:checkbox").each(function(){
		if($(this).is(":checked")){   
			count++;
		}
	});
	if(count ==	0){
		alert('Please check at least one checkbox.');
	}
	else{
		if(confirm('Are you sure to delete this data?')){
			var deleteids	= [];
			 $("form#Data_Form input:checkbox").each(function(){
				if($(this).attr('id')!='check_all')
					if(!$(this).is('input#check_all') && $(this).is(":checked"))
						deleteids.push($(this).val());
			});	
			$('#ShowAllDataListDivImage').show();
			$.ajax({
				type: 'post',
				 url: FULLSITEURL+CURRENTCLASS+'/deletemultipledata/',
				 data: {deletemiltipledata:'Yes',deleteids:deleteids},
			 success: function(datamessage){
					$.ajax({
						type: 'post',
						 url: FULLSITEURL+CURRENTCLASS+'/getalldatalist',
						data: {showalldata:'Yes'},
					 success: function(data){
							$('#ShowAllDataListDiv').html(data);
							$("#pageInner .panel.panel-default.panel-topbar").after(datamessage);
							$('#ShowAllDataListDivImage').hide();
							return false;
						}
					});
				}
			});
			return false;
		}
	}
});
</script>


 

   
   
  

 
    