<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>[Notification] Student Career Point Rejection</title>
</head>
<style>
table {
  border-collapse: collapse;
}

table, td, th {
  border: 1px solid black;
}
</style>
<body>
	<p>Dear Saudara/i {{ $data['nama'] }} [{{ $data['nim'] }}],
	<br/><br/>
	Melalui email ini kami ingin menginformasikan bahwa Career Point saudara/i mendapatkan penolakan dari mentor.<br/>
	Adapun Career Point yang mendapatkan penolakan sebagai berikut.<br/>
	</p>
	<table border="0">
		<tr>
			<td width="25%">Nama Kegiatan</td>
			<td>:</td>
			<td style="font-weight: bold">{{ $data['activity_name'] }}</td>
		</tr>
		<tr>
			<td >Jenis Kegiatan</td>
			<td>:</td>
			<td style="font-weight: bold">{{ $data['cp_name'] }}</td>
		</tr>
		<tr>
			<td >Keterangan</td>
			<td>:</td>
			<td style="font-weight: bold">{{ $data['reject_text'] }}</td>
		</tr>
		<tr>
			<td >Tanggal Penolakan</td>
			<td>:</td>
			<td style="font-weight: bold">{{ $data['updated_at'] }}</td>
		</tr>
	</table>
	<br/><br/>
	<p>Mohon untuk tidak membalas email ini.<br/>
	Terima Kasih.
	<br/><br/>
	Unit Kemahasiswaan Universitas Prasetiya Mulya
	</p>
</body>
</html>