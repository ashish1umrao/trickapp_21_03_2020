
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script src="http://localhost/school-orissa/admin/assets/js/jquery.validate.js"></script>
<script>
$(function(){
	$("#start_date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
	$("#end_date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
  
});
</script>
 <form id="report_Form" name="report_Form" method="post" action="">
<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li class="active">Manage visitor details</li>
</ol>
     
     
     
<span class="right " style="float:right;">
   
 <input type="submit" name="report" id="SaveChanges" value="Visitor Report" class="btn btn-default" />
</span>
  
    
 </form>
{message}
<div class="table-agile-info">
  <div class="panel panel-default">
    <form id="Data_Form" name="Data_Form" method="get" action="<?php echo $forAction; ?>">
        <?php if($visitor_in):  ?>
        &nbsp;  
      <small class="text-muted inline m-t-sm m-b-sm">Visitor Inside : <?=$visitor_in?></small>
      
      
      <?php endif;  ?>
    <div class="row w3-res-tb">
       
      <div class="col-sm-4 m-b-xs">
          
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
      <div class="col-sm-5">
          <input type="text" name="start_date" id="start_date" value="<?php echo $start_date; ?>" class=" input-sm form-control required w-sm inline v-middle"  placeholder="Start Date"> &nbsp;
           <input type="text" name="end_date" id="end_date" value="<?php echo $end_date; ?>" class="input-sm form-control required w-sm inline v-middle"   placeholder="End Date"> 
           <a class="btn btn-sm btn-default dateData">Apply</a>  
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
            <th>Visitor</th>
            <th>Mobile</th>
            <th>In Time</th>
             <th>Out Time</th>
              <th>Purpose</th>
            <th>Status</th>
          
          </tr>
        </thead>
        <tbody>
          <?php if($ALLDATA <> ""): $i=$first; foreach($ALLDATA as $ALLDATAINFO): ?>
          <tr class="<?php if($i%2 == 0): echo 'odd'; else: echo 'even'; endif; ?>">
            <td><?=$i++?></td>
            <td><?=stripslashes($ALLDATAINFO['name'])?></td>
            <td><?=stripslashes($ALLDATAINFO['mobile'])?></td>
               <td> <?=date('d-m-Y H:i:s', strtotime(stripslashes($ALLDATAINFO['in_time']))) ?></td>
               <?php  if($ALLDATAINFO['out_time']):  ?>
              
                <td> <?=date('d-m-Y H:i:s', strtotime(stripslashes($ALLDATAINFO['out_time']))) ?></td>
             <?php else: ?>
                   <td> </td>
                <?php endif;  ?>
            <td><?=stripslashes($ALLDATAINFO['purpose'])?></td>
            <td><?=visitorStatus($ALLDATAINFO['out_time'])?></td>
           
          </tr>
          <?php endforeach; else: ?>
            <tr>
              <td colspan="5" style="text-align:center;">No data available in table</td>
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