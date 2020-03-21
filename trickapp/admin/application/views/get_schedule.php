<?php if($calEvent== 'Holidays'): ?>
  <div class="modal-content modal_child">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      <h4 class="modal-title" id="myModalLabel" style="text-align:center;"><?php echo $title; ?></h4>
    </div> 
    <div class="modal-body chile_hero_body row" style="border: 0 !important;">  
        <div class="col-md-12 chile_hero" style="overflow:hidden;">
          <div class="col-md-4">Purpos</div>
          <div class="col-md-8"><?php echo $showdata['purpose']; ?></div>
          <div class="col-md-4">Start date</div>
          <div class="col-md-8"><?php echo YYMMDDtoDDMMYY($showdata['startdate']); ?></div>
          <div class="col-md-4">End date</div>
          <div class="col-md-8"><?php echo YYMMDDtoDDMMYY($showdata['enddate']); ?></div>
        </div>
    </div>
  </div>
<?php elseif($calEvent== 'Events'): ?>
  <div class="modal-content modal_child">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      <h4 class="modal-title" id="myModalLabel" style="text-align:center;"><?php echo $title; ?></h4>
    </div> 
    <div class="modal-body chile_hero_body row" style="border: 0 !important;">  
        <div class="col-md-12 chile_hero" style="overflow:hidden;">
          <div class="col-md-4">Purpos</div>
          <div class="col-md-8"><?php echo $showdata['purpose']; ?></div>
          <div class="col-md-4">Venue</div>
          <div class="col-md-8"><?php echo $showdata['venue']; ?></div>
          <div class="col-md-4">Message</div>
          <div class="col-md-8"><?php echo $showdata['message']; ?></div>
          <div class="col-md-4">About event</div>
          <div class="col-md-8"><?php echo $showdata['about_event']; ?></div>
          <div class="col-md-4">From date and time</div>
          <div class="col-md-8"><?php echo YYMMDDtoDDMMYY($showdata['from_date']).' '.$showdata['time']; ?></div>
          <div class="col-md-4">To date and time</div>
          <div class="col-md-8"><?php echo YYMMDDtoDDMMYY($showdata['to_date']).' '.$showdata['time']; ?></div>
        </div>
    </div>
  </div>
<?php endif; ?>