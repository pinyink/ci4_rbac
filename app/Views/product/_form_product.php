<?= form_open('', [], ['id' => $id, 'method' => $method]); ?>
	<div class="form-group">
		<?= form_label('Nama Product'); ?>
		<?= form_input('nama', '', ['class' => 'form-control']); ?>
	</div>
<button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> <?=$button;?></button>
<?= form_close(); ?>