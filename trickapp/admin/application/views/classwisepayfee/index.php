<style>html, body{font-size: 14px !important;}</style>
<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li class="active">Manage Student fee</li>
  <?php if($add_data =='Y'): ?>
  <li class="pull-right"><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditdata" class="btn btn-default">Add Student fee </a></li>
  <?php endif; ?>
</ol>
{message}
<div class="table-agile-info">
  <div class="panel panel-default">
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
            <th>Head name</th>
            <th>Student Name</th>
            <th>Class Name</th>
            <th>Section Name</th>
            <th>Total Fee</th>
            <th>Due Fee</th>
            <th>Pay Month</th>
            <th>Paybal Amount</th>
            <?php if($edit_data == 'Y' || $delete_data == 'Y'|| $view_data == 'Y' ): ?>
             <th>Status</th>
            <!-- <th>--</th> -->
            <?php endif; ?>
          </tr>
        </thead>
        <tbody>
          
          <?php 
        //  print "<pre>"; print_r($ALLDATA); die;
          if($ALLDATA <> ""): $i=$first; foreach($ALLDATA as $ALLDATAINFO): 
            $FEEWISEMONTH = $this->common_model->get_all_fee_submitted_month($ALLDATAINFO['id']);
             
           
            //foreach($FEEWISEMONTH as $month): 
              //echo "<pre>"; print_r($FEEWISEMONTH); //die;
            //$a = $month->month_name;
            $arrayName = array('April','May');
            //$arrayName = array("'.$month->month_name.'");
            $monthName = implode(",",$arrayName);
         //echo $monthName; die;
            ?>
          <tr class="<?php if($i%2 == 0): echo 'odd'; else: echo 'even'; endif; ?>">
            <td><?=$i++?></td>
            <td><?=stripslashes($ALLDATAINFO['heading_type'])?></td>
            <td><?=stripslashes($ALLDATAINFO['student_f_name'])?></td>
            <td><?=stripslashes($ALLDATAINFO['class_name'])?></td>
            <td><?=stripslashes($ALLDATAINFO['section_name'])?></td>
            <td><?=stripslashes($ALLDATAINFO['per_month_fee'])?></td>
            <td>(<?=stripslashes($monthName)?>)</td>
            <td><?=stripslashes($ALLDATAINFO['due_fine'])?></td>
            <td><?=stripslashes($ALLDATAINFO['total_paybal_amount'])?></td>
            <td><?=showStatus($ALLDATAINFO['status'])?></td>
            <?php //if($edit_data == 'Y' || $delete_data == 'Y'|| $view_data == 'Y' ): ?>
            <?php /*?> <td><div class="btn-group"> <a class="btn btn-success dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">Action <i class="fa fa-arrow-down icon-white"></i></a>
             <ul class="dropdown-menu">
                    <?php if($edit_data == 'Y'): ?>
                   <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditdata/<?=$ALLDATAINFO['encrypt_id']?>"><i class="fa fa-pencil"></i> Edit details</a></li>
                    <li class="divider"></li>
                    <?php if($ALLDATAINFO['status'] == 'Y'): ?>
                    <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/changestatus/<?=$ALLDATAINFO['encrypt_id']?>/N"><i class="fa fa-thumbs-down"></i> Inactive</a></li>
                    <?php elseif($ALLDATAINFO['status'] == 'N'): ?>
                    <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/changestatus/<?=$ALLDATAINFO['encrypt_id']?>/Y"><i class="fa fa-thumbs-up"></i> Active</a></li>
                    <?php endif; endif; ?>
                    <?php if($view_data == 'Y'): ?>
                    <li class="divider"></li>
                    <li><a href="javascript:void(0);" class="view-details-data" title="Student Fee Structure details" data-id="<?=$ALLDATAINFO['encrypt_id']?>"><i class="fa fa-gear"></i> View details</a></li>
                    <?php endif; ?>
                  </ul>
                </div>
            </td><?php */?>
            <?php //endif; ?>
          </tr>
          <?php //endforeach; ?>
          <?php endforeach; else: ?>
            <tr>
              <td colspan="16" style="text-align:center;">No data available in table</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    </form>
  </div>
</div>
<script>
var prevSerchValue	=	'<?php echo $searchValue; ?>';
</script>