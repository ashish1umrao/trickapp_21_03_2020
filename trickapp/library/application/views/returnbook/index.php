

<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  
  <li class="active">
   
   Return book</li>
  
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> 
        <span class="tools pull-left"> Return book </span> 
        </header>
        <div class="panel-body">
          <form id="currentPageForm" method="get" class="form-horizontal" role="form" action="">
         
           
           
          
         
            <div class="form-group">
              <label class="col-lg-3 control-label">Student Registration No. Or Staff Employee Id<span class="required">*</span></label>
              <div class="col-lg-6">
                <input type="text" name="reader_id" id="reader_id"value="<?php if(set_value('reader_id')): echo set_value('reader_id');else: echo $reader_id ; endif; ?>" class="form-control required" placeholder="Registration No. Or Employee Id">
                  <?php if(form_error('reader_id')): ?>
                  <p for="reader_id" generated="true" class="error"><?php echo form_error('reader_id'); ?></p>
                  <?php  elseif($readerError): ?>
                  <p for="reader_id" generated="true" class="error"><?php echo $readerError; ?></p>
                  <?php endif; ?>
                  
              </div>
            </div>
        
            
            <div class="form-group" style="margin-top:30px;">
              <div class="col-lg-offset-3 col-lg-9">
               
               <a id="submit" class="btn btn-primary ">Submit</a>  
               
              </div>
            </div>
          </form>
            </div>
          <?php if($readerData['student_qunique_id'] or $readerData['encrypt_id']  ) : if($ALLDATA):   ?>
          
          <ol class="breadcrumb">
  <li>Name  </li>
  <?php   if($readerData['student_qunique_id'] ):  ?>
  <li class="active"><?php echo $readerData['NAME']  ?> ( Student  )</li>
  
  <?php elseif($readerData['encrypt_id']) : ?>
   <li class="active"><?php echo $readerData['NAME']  ?> ( <?php echo $readerData['user_type']  ?>  )</li>
  
  <?php   endif;  if($total_fine): ?>
  <li class="pull-right">Total fine - <span style="color:red;"><?php echo $total_fine ; ?> &#8377</span> </li>
  <?php endif;  ?>
</ol>  <?php  endif;  ?>
               <div class="table-responsive">
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th>Sr.No.</th>
            <th>Book Id</th>
            <th>Book Name ( Author )</th>
             <th>Designated Book Location </th>
               <th>Return date</th>
              <th>Fine &#8377</th>
              
           
           
     
         
            <?php if($edit_data == 'Y' || $delete_data == 'Y'): ?>
           <th></th>
               <th></th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody>
       
          <?php   if($ALLDATA <> ""): $i=$first; foreach($ALLDATA as $ALLDATAINFO): ?>
          <tr class="<?php if($i%2 == 0): echo 'odd'; else: echo 'even'; endif; ?>">
            <td><?=$i++?></td>
            <td><?=stripslashes($ALLDATAINFO['barcode'])?></td>
            <td><?=stripslashes($ALLDATAINFO['book_name'])?> ( <?=stripslashes($ALLDATAINFO['book_author'])?> ) </td>
               <td>Shelf No. : <?=stripslashes($ALLDATAINFO['shelf'])?>  Row No. <?=stripslashes($ALLDATAINFO['shelf_row'])?> </td>
              <td><?=YYMMDDtoDDMMYY($ALLDATAINFO['return_date'])?></td>
          
              <td><?=stripslashes($ALLDATAINFO['fine'])?></td>
         <?php if($edit_data == 'Y' || $delete_data == 'Y'): ?>barcode_encypt_id
            <td><div class="btn-group"> <a class="btn btn-success " href="{FULL_SITE_URL}{CURRENT_CLASS}/changestatus/<?=$ALLDATAINFO['encrypt_id']?>/return/<?=$ALLDATAINFO['book_id']?>/<?=$ALLDATAINFO['barcode_encypt_id']?>/<?=$ALLDATAINFO['fine']?>">Return</a>
                     <td><div class="btn-group"> <a class="btn btn-warning " href="{FULL_SITE_URL}{CURRENT_CLASS}/changestatus/<?=$ALLDATAINFO['encrypt_id']?>/lost/<?=$ALLDATAINFO['book_id']?>/<?=$ALLDATAINFO['barcode_encypt_id']?>/<?=$ALLDATAINFO['fine']?>">Lost</a>
                
                </div>
            </td>
            <?php endif; ?>
            
           
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
        <div class="col-sm-5">
          <small class="text-muted inline m-t-sm m-b-sm"><?php echo $noOfContent; ?></small>
        </div>
        <div class="col-sm-7 text-right text-center-xs">                
          <?=$this->pagination->create_links()?>
        </div>
      </div>
    </footer>
    <?php endif; ?>
        
         <?php endif;  ?> 
      </section>
    </div>
      
  </div>
  
</div>
<script>
$(document).on('click','#submit',function(){
	$('#currentPageForm').submit();														 
});

</script>

 

   
   
  

 
    