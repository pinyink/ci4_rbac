<table class="table">
	<tr>
		<td style="width: 25%;">Nama Product</td>
		<td style="width: 1%;">:</td>
		<th style="width: 75%;"><?=$product['nama']; ?></th>
	</tr>
	<tr>
		<td style="width: 25%;">Harga</td>
		<td style="width: 1%;">:</td>
		<th style="width: 75%;"><?='Rp '.number_format($product['harga'], 0, ',', '.'); ?></th>
	</tr>
	<tr>
		<td style="width: 25%;">Tanggal</td>
		<td style="width: 1%;">:</td>
		<th style="width: 75%;"><?=date('d-m-Y', strtotime($product['tanggal'])); ?></th>
	</tr>
	<tr>
		<td style="width: 25%;">Deskripsi Product</td>
		<td style="width: 1%;">:</td>
		<th style="width: 75%;"><?=$product['deskripsi']; ?></th>
	</tr>
	<tr>
		<td style="width: 25%;">Foto Product</td>
		<td style="width: 1%;">:</td>
		<th style="width: 75%;"><img src="<?=base_url($product['foto']); ?>" class="img img-thumbnail" style="width: 240px; height: 240px;"></th>
	</tr>
	<tr>
		<td style="width: 25%;">Dokumen Pdf</td>
		<td style="width: 1%;">:</td>
		<th style="width: 75%;"><?=$product['dokumen']; ?></th>
	</tr>
	<tr>
		<td style="width: 25%;">Kategori</td>
		<td style="width: 1%;">:</td>
		<th style="width: 75%;"><?=$product['categories_id']; ?></th>
	</tr>
</table>