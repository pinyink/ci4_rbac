<?= form_open($url, [], ['id' => $id, 'method' => $method]); ?>
	<div class="form-group">
                    
		<?= form_label('Nama Product'); ?>
                    
		<?php $invalid = session('_ci_validation_errors.nama') ? 'is-invalid' : ''; ?>
                    
		<?= form_input('nama', '', ['class' => 'form-control '.$invalid]); ?>
                    
		<?php if(session('_ci_validation_errors.nama')):?>
                        
			<div class="invalid-feedback"><?=session('_ci_validation_errors.nama')?></div>
                    
		<?php endif ?>
                
	</div>
	<div class="form-group">
                    
		<?= form_label('Harga'); ?>
                    
		<?php $invalid = session('_ci_validation_errors.harga') ? 'is-invalid' : ''; ?>
                    
		<?= form_input('harga', '', ['class' => 'form-control '.$invalid]); ?>
                    
		<?php if(session('_ci_validation_errors.harga')):?>
                        
			<div class="invalid-feedback"><?=session('_ci_validation_errors.harga')?></div>
                    
		<?php endif ?>
                
	</div>
<button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> <?=$button;?></button>
<?= form_close(); ?>