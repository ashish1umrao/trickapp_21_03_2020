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
         
            <th>Email Id</th>
            <th>Mobile No</th>
            <th>--</th>
          </tr>
        </thead>
        <tbody>
          <?php if($ADMINDATA <> ""): $i=1; foreach($ADMINDATA as $ADMININFO): ?>
          <tr data-expanded="true" class="<?php if($i%2 == 0): echo 'odd'; else: echo 'even'; endif; ?>">
            <td><?=$i++?></td>
            <td><?=stripslashes($ADMININFO['user_f_name']. ' '.$ADMININFO['user_m_name'].' '.$ADMININFO['user_l_name'])?></td>
           
            <td><?=stripslashes($ADMININFO['user_email'])?></td>
            <td><?=stripslashes($ADMININFO['user_mobile'])?></td>
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
