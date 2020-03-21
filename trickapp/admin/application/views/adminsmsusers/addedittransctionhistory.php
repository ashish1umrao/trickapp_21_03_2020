<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li class="active">Transction History</li>
  <?php if($add_data =='Y'): ?>
  <li class="pull-right"><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditdata" class="btn btn-default">Transction History</a></li>
  <?php endif; ?>
</ol>
{message}
<div class="table-agile-info">
<?php if($EDITDATA <> ""): 
  $user_id  = $EDITDATA['user_id'];
  ?>
<ul class="nav nav-tabs blue_tab">
      <li ><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditdata/<?php echo $user_id; ?>">Personal</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditUserBalence/<?php echo $user_id; ?>">Update User Balance</a></li>
      <li class="active"><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addedittransctionhistory/<?php echo $user_id; ?>">Transaction History</a></li>
      <li ><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditbalancehistory/<?php echo $user_id; ?>">Check User Balance</a></li>
    </ul>
<?php endif; ?>
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
            <th>Transction Date</th>
            <th>From</th>
            <th>To</th>
            <th>Txn Type</th>
            <th>Txn Route</th>
            <th>Txn Sms</th>
            <th>Txn Price</th>
            <th>Txn Amount</th>
          </tr>
        </thead>
        <tbody>
          <?php if($ALLTRANSCTIONHISTORY <> ""): $i=1; foreach($ALLTRANSCTIONHISTORY as $ALLTRANSCTIONHISTORYINFO): //echo "<pre>"; print_r($ALLTRANSCTIONHISTORYINFO); die;?>
          <tr class="<?php if($i%1 == 0): echo 'odd'; else: echo 'even'; endif; ?>">
            <td><?=$i++?></td>
            <?php //if(sessionData('SMS_ADMIN_TYPE') == 'Super admin'): ?>
             <td><?=stripslashes($ALLTRANSCTIONHISTORYINFO->txn_date)?></td>
            <?php //endif; ?>
            <td><?=stripslashes($ALLTRANSCTIONHISTORYINFO->from)?></td>
            <td><?=stripslashes($ALLTRANSCTIONHISTORYINFO->to)?></td>
            <td><?=stripslashes($ALLTRANSCTIONHISTORYINFO->txn_type)?></td>
            <td><?=stripslashes($ALLTRANSCTIONHISTORYINFO->txn_route)?></td>
            <td><?=stripslashes($ALLTRANSCTIONHISTORYINFO->txn_sms)?></td>
            <td><?=stripslashes($ALLTRANSCTIONHISTORYINFO->txn_price)?></td>
            <td><?=stripslashes($ALLTRANSCTIONHISTORYINFO->txn_amount)?></td>
          </tr>
          <?php endforeach; else: ?>
            <tr>
              <td colspan="8" style="text-align:center;">No data available in table</td>
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