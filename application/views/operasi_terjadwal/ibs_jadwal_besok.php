<section id="content">
<main class="main">
<?if(!empty($output)){?>
	<div>
	<table id="tblExport" width="100%">
	<tr>
		<td>
		<table class="table table-bordered table-condensed" style="font-size:12px;" >
			<tr>
				<td colspan="4"><h5><b><?= strtoupper($title)?></b></h5></td>
			</tr>
			<tr>
				<td>Tgl Operasi</td>
				<td><?= $data['tanggal_op']?></td>
			</tr>
			<tr>
				<td>User Yg Meng-generate Report</td>
				<td><?= $this->session->userdata['userLogin']['name']?></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" style="font-size:12px;" class="table table-bordered table-condensed">
				<tr>
					<th>No</th>
					<th>OK</th>
					<th>Nama OS</th>
					<th>Tgl Lahir</th>
					<th>Ruang Inap</th>
					<th>Jaminan</th>
					<th>Jam OP</th>
					<th>ICU</th>
					<th>No RM</th>
					<th>Diagnosa</th>
					<th>Tindakan</th>
					<th>OPR</th>
					<th>ANEST</th>
					<th>INST</th>
					<th>CIRC</th>
					<th>PENATA</th>
					<th>Teknik Anestesi</th>
				</tr>
				<?$i=1;foreach($output as $key=>$row){?>
				<tr>
					<td><?= $i?></td>
					<td><?= $row['ruang_ok']?></td>
					<td><?= $row['nama']?></td>
					<td><?= display_date($row['tanggal_lahir'])?></td>
					<td><?= $row['nama_ruang_reg']?></td>
					<td><?= $row['cara_bayar']?></td>
					<td><?= display_date($row['tgl_pelaksanaan'],'H:i')?></td>
					<td><?= $row['is_need_icu']?></td>
					<td><?= $row['no_rm']?></td>
					<td><?= $row['diagnosa']?></td>
					<td><?= $row['tindakan']?></td>
					<td><?= $row['opr']?></td>
					<td><?= $row['anest']?></td>
					<td><?= $row['inst']?></td>
					<td><?= $row['circ']?></td>
					<td><?= $row['penata']?></td>
					<td><?= $row['rencana_pembiusan']?></td>
				</tr>
			<?$i+=1;}?>
			</table>
		</td>
	</tr>
	</table>
	<table width="100%">
		<tr>
			<td>
				<div class="col-sm-1"></div>
			</td>
		</tr>
	</table>
	</div>
	<div>
		<div class="form-group">
			<div class="col-sm-1"><button id="btnExport" name="type_submit" value="excel"  class="btn btn-primary">Excel</button></div>
		</div>
	</div>
<?}else{?>
Data untuk besok belum selesai terjadwal
<?}?>
</main>
</section>
<script>
$(document).ready(function () {
	$("#btnExport").click(function () {
		$("#tblExport").btechco_excelexport({
			containerid: "tblExport", 
			datatype: $datatype.Table
		});
	});
});
</script>
<?= isset($jscript)?$jscript:'' ?>