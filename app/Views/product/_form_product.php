<?= form_open($url, [], ['id' => $id, 'method' => $method]); ?>
	<div class="form-group">
                    
		<?= form_label('Nama Product'); ?>
                    
		<?php $invalid = session('_ci_validation_errors.nama') ? 'is-invalid' : ''; ?>
                    
		<?php $value = isset($product['nama']) ? $product['nama'] : old('nama'); ?>
                    
		<?= form_input('nama', trim($value), ['class' => 'form-control '.$invalid]); ?>
                    
		<?php if(session('_ci_validation_errors.nama')):?>
                        
			<div class="invalid-feedback"><?=session('_ci_validation_errors.nama')?></div>
                    
		<?php endif ?>
                
	</div>
	<div class="form-group">
                    
		<?= form_label('Harga'); ?>
                    
		<?php $invalid = session('_ci_validation_errors.harga') ? 'is-invalid' : ''; ?>
                    
		<?php $value = isset($product['harga']) ? $product['harga'] : old('harga'); ?>
                    
		<?php $value = number_format($value, 0, ',', '.'); ?>
                    
		<div class="input-group">
			            
			<div class="input-group-addon bg-white">Rp</div>
                        
			<?= form_input('harga', trim($value), ['class' => 'form-control '.$invalid]); ?>
                    
		</div>
                    
		<?php if(session('_ci_validation_errors.harga')):?>
                        
			<div class="invalid-feedback"><?=session('_ci_validation_errors.harga')?></div>
                    
		<?php endif ?>
                
	</div>
<button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> <?=$button;?></button>
<?= form_close(); ?>

<?php $this->section('js'); ?>
<script>
	$('[name="harga"]').keyup(function (e) { 
		this.value = formatRupiah(this.value);
	});
</script>
<?php $this->endSection(); ?>