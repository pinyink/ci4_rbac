<?= form_open_multipart($url, [], ['id' => $id, 'method' => $method]); ?>
	<div class="form-group">
                    
		<?= form_label('Nama Product'); ?>
                    
		<?php $invalid = session('_ci_validation_errors.nama') ? 'is-invalid' : ''; ?>
                    
		<?php $value = isset($product['nama']) ? $product['nama'] : old('nama'); ?>
                    
		<div class="input-group">
                        
			<?= form_input('nama', trim($value), ['class' => 'form-control '.$invalid, 'placeholder' => 'Nama Product']); ?>
                    
		</div>
                    
		<?php if(session('_ci_validation_errors.nama')):?>
                        
			<div class="text-danger"><?=session('_ci_validation_errors.nama')?></div>
                    
		<?php endif ?>
                
	</div>
	<div class="form-group">
                    
		<?= form_label('Harga'); ?>
                    
		<?php $invalid = session('_ci_validation_errors.harga') ? 'is-invalid' : ''; ?>
                    
		<?php $value = isset($product['harga']) ? $product['harga'] : old('harga'); ?>
                    
		<?php $value = $value != null ? number_format($value, 0, ',', '.') : 0; ?>
                    
		<div class="input-group">
			            
			<div class="input-group-addon bg-white">Rp</div>
                        
			<?= form_input('harga', trim($value), ['class' => 'form-control '.$invalid, 'placeholder' => 'Harga']); ?>
                    
		</div>
                    
		<?php if(session('_ci_validation_errors.harga')):?>
                        
			<div class="text-danger"><?=session('_ci_validation_errors.harga')?></div>
                    
		<?php endif ?>
                
	</div>
	<div class="form-group">
                    
		<?= form_label('Tanggal'); ?>
                    
		<?php $invalid = session('_ci_validation_errors.tanggal') ? 'is-invalid' : ''; ?>
                    
		<?php $value = isset($product['tanggal']) ? date('d-m-Y', strtotime($product['tanggal'])) : old('tanggal'); ?>
                    
		<div class="input-group">
			            
			<div class="input-group-addon bg-white"><i class="fa fa-calendar"></i></div>
                        
			<?= form_input('tanggal', trim($value), ['class' => 'form-control '.$invalid, 'placeholder' => 'Tanggal', 'autocomplete' => 'off']); ?>
                    
		</div>
                    
		<?php if(session('_ci_validation_errors.tanggal')):?>
                        
			<div class="text-danger"><?=session('_ci_validation_errors.tanggal')?></div>
                    
		<?php endif ?>
                
	</div>
	<div class="form-group">
                    
		<?= form_label('Deskripsi Product'); ?>
                    
		<?php $invalid = session('_ci_validation_errors.deskripsi') ? 'is-invalid' : ''; ?>
                    
		<?php $value = isset($product['deskripsi']) ? $product['deskripsi'] : old('deskripsi'); ?>
                    
		<div class="input-group">
                        
			<?= form_textarea('deskripsi', trim($value), ['class' => 'form-control '.$invalid, 'rows' => '3', 'placeholder' => 'Deskripsi Product']); ?>
                    
		</div>
                    
		<?php if(session('_ci_validation_errors.deskripsi')):?>
                        
			<div class="text-danger"><?=session('_ci_validation_errors.deskripsi')?></div>
                    
		<?php endif ?>
                
	</div>
	<div class="form-group">
                    
		<?php $invalid = session('_ci_validation_errors.foto') ? 'is-invalid' : ''; ?>
                    
		<?php $value = isset($product['foto']) ? $product['foto'] : 'assets/admincast/dist/assets/img/image.jpg'; ?>
                    
		<img src="<?=base_url($value);?>" style="width: 230px; height: 230px" class="img img-thumbnail" id='img-gambar-foto'><br>

                    
		<?= form_label('Foto Product', '', ['class' => 'mt-2']); ?>
                    
			<?= form_upload('foto', trim($value), ['class' => 'form-control '.$invalid, 'accept' => ".png,.jpg,.jpeg", 'onchange' => "readURL(this, 'img-gambar-foto');"]); ?>
                    
		<?php if(session('_ci_validation_errors.foto')):?>
                        
			<div class="text-danger"><?=session('_ci_validation_errors.foto')?></div>
                    
		<?php endif ?>
                
	</div>
	<div class="form-group">
                    
		<?php $invalid = session('_ci_validation_errors.dokumen') ? 'is-invalid' : ''; ?>
                    
		<?php $value = isset($product['dokumen']) ? '<a href="'.base_url($product['dokumen']).'" target="_blank" class="text-primary">( Download )</a>' : ''; ?>

                    
		<?= form_label('Dokumen Pdf'.' '.$value, '', ['class' => 'mt-2']); ?>
                    
			<?= form_upload('dokumen', '', ['class' => 'form-control '.$invalid, 'accept' => ".pdf"]); ?>
                    
		<?php if(session('_ci_validation_errors.dokumen')):?>
                        
			<div class="text-danger"><?=session('_ci_validation_errors.dokumen')?></div>
                    
		<?php endif ?>
                
	</div>
<button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> <?=$button;?></button>
<?= form_close(); ?>

<?php $this->section('css'); ?>
<link href="<?=base_url();?>/assets/admincast/dist/assets/vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
<?php $this->endSection(); ?>

<?php $this->section('js'); ?>
<script src="<?=base_url(); ?>/assets/admincast/dist/assets/vendors/moment/min/moment.min.js" type="text/javascript"> </script>
<script src="<?=base_url(); ?>/assets/admincast/dist/assets/vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"> </script>
<script>
	$('[name="harga"]').keyup(function (e) { 
		this.value = formatRupiah(this.value);
	});
	$('[name="tanggal"]').datepicker({
		todayBtn: "linked",
		keyboardNavigation: false,
		forceParse: false,
		calendarWeeks: true,
		autoclose: true,
		format: "dd-mm-yyyy"
	});
</script>
<?php $this->endSection(); ?>