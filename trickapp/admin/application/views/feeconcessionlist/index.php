

<ol class="breadcrumb">
    <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
    <li class="active">Manage fee concession</li>

</ol>
{message}
<div class="table-agile-info">
    <div class="panel panel-default">
        <form id="currentPageForm" name="Data_Form" method="post" action="">

            <div class="table-responsive">
                <table class="table table-striped b-t b-light">
                    <thead>

                        <tr>

                            <th>Fee Head</th>
                            <th>Concession Type</th>
                            <?php
                            if ($CATDATA <> ""):

                                foreach ($CATDATA AS $C):
                                    ?>
                                    <th><?php echo $C['user_category_name']; ?></th>
    <?php endforeach;
endif; ?>

                        </tr>
                    </thead>
                    <tbody>
<?php if ($HEADDATA <> ""): $i = $first;
    foreach ($HEADDATA as $HEADDATAINFO): ?>

                                <tr class="<?php if ($i % 2 == 0): echo 'odd';
        else: echo 'even';
        endif; ?>">
                                    <td> <?php echo $HEADDATAINFO['fee_head_name'] ?></td>
                                    <td><?= stripslashes($HEADDATAINFO['fee_concession_type']) ?></td>

                                    <?php
                                    if($HEADDATAINFO['fee_concession_type'] == 'Percentage'):
                                        $max = 'max=100' ;
                                    else:
                                          $max = '' ;
                                    endif;
                                    if ($CATDATA <> ""):

                                        foreach ($CATDATA AS $C):

                                            $head_id = $HEADDATAINFO['encrypt_id'];
                                            $cat_id  = $C['user_category_name'];
                                            $value   = $CONDATA[$head_id . '_' . $cat_id]['value'] > 0 ? $CONDATA[$head_id . '_' . $cat_id]['value'] : 0.00;
                                            ?>


                                            <td>

                                                <input type="text" name="fee_concession_value_<?= $HEADDATAINFO['encrypt_id'] ?>_<?= $C['user_category_name'] ?>" id="fee_concession_value_<?= $HEADDATAINFO['encrypt_id'] ?>_<?= $C['user_category_name'] ?>" value="<?php if (set_value('fee_concession_value_' . $HEADDATAINFO['encrypt_id'] . '_' . $C['user_category_name'])): echo set_value('fee_concession_value_' . $HEADDATAINFO['encrypt_id'] . '_' . $C['user_category_name']);
                                            else: echo stripslashes($value);
                                            endif; ?>" <?=$max?> class="form-control number" placeholder="value">

            <?php endforeach;
        endif; ?>

                                </tr>
     <?php endforeach; else: ?>
            <tr>
              <td colspan="7" style="text-align:center;">No data available in table</td>
            </tr>
          <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if ($HEADDATA <> ""): ?>
            <?php if($edit_data == 'Y' || $delete_data == 'Y' || $view_data == 'Y'): ?>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-md-12 "> <span>

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                            <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                                    :- <strong><span style="color:#FF0000;">*</span> Indicates required
                                        fields</strong> </span> </span></span>

                    </div>

                   
                </div>
            </footer>
            
            <?php endif; endif; ?>
            
            
        </form>
    </div>
</div>
<script>
    var prevSerchValue = '<?php echo $searchValue; ?>';
</script>