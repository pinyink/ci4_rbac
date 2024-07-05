<?= form_open_multipart($url, [], ['id' => $id, 'method' => $method]); ?>
	<div class="form-group">
                    
		<?= form_label('Nama Kategories'); ?>
                    
		<?php $invalid = session('_ci_validation_errors.nama') ? 'is-invalid' : ''; ?>
                    
		<?php $value = isset($product_categories['nama']) ? $product_categories['nama'] : old('nama'); ?>
                    
		<div class="input-group">
                        
			<?= form_input('nama', trim($value), ['class' => 'form-control '.$invalid, 'placeholder' => 'Nama Kategories']); ?>
                    
		</div>
                    
		<?php if(session('_ci_validation_errors.nama')):?>
                        
			<div class="text-danger"><?=session('_ci_validation_errors.nama')?></div>
                    
		<?php endif ?>
                
	</div>
<button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> <?=$button;?></button>
<?= form_close(); ?>

<?php $this->section('css'); ?>

<?php $this->endSection(); ?>

<?php $this->section('js'); ?>

<script>
</script>
<?php $this->endSection(); ?>