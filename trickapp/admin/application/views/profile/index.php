<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li class="active">Profile details</li>
</ol>
{message}
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="table-responsive">
      <table class="table" ui-jq="footable">
        <thead>
          <tr>
            <th>Sr.No.</th>
            <th>Name</th>
            <th>Display name</th>
            <th>Email Id</th>
            <th>Mobile No</th>
            <?php if($ADMINDATA[0]['admin_type']=='Branch' && $ADMINDATA[0]['admin_level']=='3'): ?>
              <th>Branch code</th>
            <?php endif; ?> 
            <th>--</th>
          </tr>
        </thead>
        <tbody>
          <?php if($ADMINDATA <> ""): $i=1; foreach($ADMINDATA as $ADMININFO): ?>
          <tr data-expanded="true" class="<?php if($i%2 == 0): echo 'odd'; else: echo 'even'; endif; ?>">
            <td><?=$i++?></td>
            <td><?=stripslashes($ADMININFO['admin_name'])?></td>
            <td><?=stripslashes($ADMININFO['admin_display_name'])?></td>
            <td><?=stripslashes($ADMININFO['admin_email_id'])?></td>
            <td><?=stripslashes($ADMININFO['admin_mobile_number'])?></td>
            <?php if($ADMININFO['admin_type']=='Branch' && $ADMININFO['admin_level']=='3'): ?>
              <td><?=stripslashes($ADMININFO['branch_code'])?></td>
            <?php endif; ?> 
            <td><div class="btn-group"> <a class="btn btn-success dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">Action <i class="fa fa-arrow-down icon-white"></i></a>
                  <ul class="dropdown-menu">
                    <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/editprofile/<?=$ADMININFO['encrypt_id']?>"><i class="fa fa-pencil"></i> Edit details</a></li>
                  </ul>
                </div>
            </td>
          </tr>
          <?php $i++; endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
