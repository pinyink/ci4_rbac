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
		<th style="width: 75%;"><?=$product['foto']; ?></th>
	</tr>
</table>