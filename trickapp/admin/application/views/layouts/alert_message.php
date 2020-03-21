<?php if($this->session->flashdata('alert_warning')): ?>
<div class="alert alert-warning" role="alert">
    <strong>Warning!</strong> <?=$this->session->flashdata('alert_warning')?>
</div>
<?php elseif($this->session->flashdata('alert_error')): ?>
<div class="alert alert-danger" role="alert">
    <strong>Oh snap!</strong> <?=$this->session->flashdata('alert_error')?>
</div>
<?php elseif($this->session->flashdata('alert_success')): ?>
<div class="alert alert-success" role="alert">
    <strong>Well done!</strong> <?=$this->session->flashdata('alert_success')?>
</div>
<?php endif; ?>
