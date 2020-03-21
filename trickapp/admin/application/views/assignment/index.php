<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li class="active">Manage news details</li>
  <?php if($add_data =='Y'): ?>
  <?php /*?><li class="pull-right"><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditdata" class="btn btn-default">Add news details</a></li><?php */?>
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
            <th>Class</th>
            <th>Section</th>
            <th>Subject</th>
            <th>Title</th>
            <th>Content</th>
            <th>Image</th>         
            <th>From Date</th>
            <th>To Date</th>
            <th>Created Date</th>
           </tr>
        </thead>
        <tbody>
          <?php if($ALLDATA <> ""): $i=$first; foreach($ALLDATA as $ALLDATAINFO): ?>
          <tr class="<?php if($i%2 == 0): echo 'odd'; else: echo 'even'; endif; ?>">
            <td><?=$i++?></td>
            <td><?=stripslashes($ALLDATAINFO['class_name'])?></td>
            <td><?=stripslashes($ALLDATAINFO['class_section_name'])?></td>
            <td><?=stripslashes($ALLDATAINFO['subject_name'])?></td>
            <td><?=stripslashes($ALLDATAINFO['assignments_title'])?></td>
            <td><textarea rows="3" cols="30"><?=stripslashes($ALLDATAINFO['assignments_content'])?>
</textarea></td>
            <td><?php if(!empty($ALLDATAINFO['assignments_file'])){ ?>
              <img src="<?=$ALLDATAINFO['assignments_file']; ?>" width="100" height="100" alt="Home work image"> 
            <?php }else { echo "---"; } ?>
            </td>
           
             <td><?=date('d/m/Y',strtotime($ALLDATAINFO['assignments_from_date']))?></td>
          
             <td><?=date('d/m/Y',strtotime($ALLDATAINFO['assignments_to_date']))?></td>
             <td><?=stripslashes($ALLDATAINFO['creation_date'])?></td>           
          </tr>
          <?php endforeach; else: ?>
            <tr>
              <td colspan="10" style="text-align:center;">No data available in table</td>
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