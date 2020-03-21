
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script>

$(function(){
	
        $("#return_date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,minDate: 0 ,yearRange: "1960:<?php echo date('Y'); ?>"});
  
});

</script>
<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li class="active">Issue a book</li>
  
</ol>
{message}
<div class="table-agile-info">
  <div class="panel panel-default">
      <div class="row w3-res-tb">
       
      <div class="col-sm-4 m-b-xs">
          
           <input type="text" name="barcode_id" id="date" value="<?php echo $barcode_id; ?>" class=" input-sm form-control  w-sm inline v-middle"   placeholder="Book  Barcode "> 
             
       
      </div>
      <div class="col-sm-3"  >
      
            <input type="text" name="reader_id" id="date" value="<?php echo $reader_id; ?>" class=" input-sm form-control  w-sm inline v-middle"   placeholder="Student Registration No"> 
           
        
           
      </div>
           
      <div class="col-sm-5">
          <input type="text" name="return_date" id="return_date" value="<?php echo $return_date; ?>" class=" input-sm form-control  w-sm inline v-middle"   placeholder="Return Date"> 
        <a class="btn btn-sm btn-default pull-right dateData">Issue Book</a>    
          
      </div>
    </div>
    <form id="Data_Form" name="Data_Form" method="get" action="<?php echo $forAction; ?>">
    <div class="row w3-res-tb">
      <div class="col-sm-5 m-b-xs">
        <select name="showLength" id="showLength" class="input-sm form-control w-sm inline v-middle">
          <option value="2" <?php if($perpage == '2')echo 'selected="selected"'; ?>>2</option>
          <option value="10" <?php if($perpage == '10')echo 'selected="selected"'; ?>>10</option>
          <option value="25" <?php if($perpage == '25')echo 'selected="selected"'; ?>>25</option>
          <option value="50" <?php if($perpage == '50')echo 'selected="selected"'; ?>>50</option>
          <option value="100" <?php if($perpage == '100')echo 'selected="selected"'; ?>>100</option>
          <option value="All" <?php if($perpage == 'All')echo 'selected="selected"'; ?>>All</option>
        </select>
        <a class="btn btn-sm btn-default showdataLength">Apply</a>                
      </div>
      <div class="col-sm-4">
      </div>
      <div class="col-sm-3">
        <div class="input-group">
          <input type="text" name="searchValue" id="searchValue" value="<?php echo $searchValue; ?>" class="input-sm form-control" placeholder="Search">
          <span class="input-group-btn">
            <a class="btn btn-sm btn-default searchData">Go!</a>
          </span>
        </div>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th>Sr.No.</th>
            <th>Book Barcode</th>
            <th>Issued Book</th>
            <th>Author Name</th>
            <th>Return  Date</th>
            
            <th>Reader Name</th>
        <!--    <th>Price &#8377</th>   -->
            <th>Status</th>
            <?php if($edit_data == 'Y' || $delete_data == 'Y'): ?>
            <th>--</th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php if($ALLDATA <> ""): $i=$first; foreach($ALLDATA as $ALLDATAINFO): ?>
          <tr class="<?php if($i%2 == 0): echo 'odd'; else: echo 'even'; endif; ?>">
            <td><?=$i++?></td>
            <td><?=stripslashes($ALLDATAINFO['book_name'])?></td>
            <td><?=stripslashes($ALLDATAINFO['book_author'])?></td>
        
              <td><?=stripslashes($ALLDATAINFO['book_quantity'] - $ALLDATAINFO['book_issued'] )?></td>
            <td><?=stripslashes($ALLDATAINFO['book_quantity'])?></td>
                <!--   <td><?=stripslashes($ALLDATAINFO['book_price'])?></td>   -->
            <td><?=showStatus($ALLDATAINFO['status'])?></td>
            <?php if($edit_data == 'Y' || $delete_data == 'Y'): ?>
            <td><div class="btn-group"> <a class="btn btn-success dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">Action <i class="fa fa-arrow-down icon-white"></i></a>
                  <ul class="dropdown-menu">
                    <?php if($edit_data == 'Y'): ?>
                    <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditdata/<?=$ALLDATAINFO['encrypt_id']?>"><i class="fa fa-pencil"></i> Edit details</a></li>
                     <li class="divider"></li>
                       <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/barcodegenrate/<?=$ALLDATAINFO['encrypt_id']?>"><i class="fa fa-barcode"></i> Generate barcode</a></li>
                    <li class="divider"></li>
                    <?php if($ALLDATAINFO['status'] == 'Y'): ?>
                    <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/changestatus/<?=$ALLDATAINFO['encrypt_id']?>/N"><i class="fa fa-thumbs-down"></i> Inactive</a></li>
                    <?php elseif($ALLDATAINFO['status'] == 'N'): ?>
                    <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/changestatus/<?=$ALLDATAINFO['encrypt_id']?>/Y"><i class="fa fa-thumbs-up"></i> Active</a></li>
                    <?php endif; endif; ?>
                  </ul>
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
    </form>
  </div>
</div>
<script>
var prevSerchValue	=	'<?php echo $searchValue; ?>';
</script>