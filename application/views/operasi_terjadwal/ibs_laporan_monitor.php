<section id="content">
<main class="main">
<form class="form-horizontal well" role="form" method="post" id="form_report">
	<div class="form-group">
		<label class="col-sm-1 control-label">Tgl Operasi Yang Dibatalkan *</label>
		<div class="col-sm-1-half">
			<input type="text" value="<?php echo (isset($_POST['tanggal_op_dari']) ? $_POST['tanggal_op_dari'] : date($date_format_in_field) ); ?>" name="tanggal_op_dari" class="form-control datepicker required"  placeholder="Dari" />
		</div>
		<div class="col-sm-00">
		s.d
		</div>
		<div class="col-sm-1-half">
			<input type="text" value="<?php echo (isset($_POST['tanggal_op_sampai']) ? $_POST['tanggal_op_sampai'] : date($date_format_in_field) ); ?>" name="tanggal_op_sampai" class="form-control datepicker required"  placeholder="Sampai" />
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-1"></div>
		<div class="col-sm-1">
			<input type="submit" name="type_submit" class="btn btn-primary filter-btn" value="submit" />
		</div>
		<div class="col-sm-1">
			<button id="btnExport" name="type_submit" value="excel"  class="btn btn-primary">Excel</button>
		</div>
	</div>
</form>

<?if(!empty($output)){?>
	<table id="tblExport" width="100%">
	<tr>
		<td>
		<table class="table table-bordered table-condensed" style="font-size:12px;" >
			<tr>
				<td colspan="4"><h5><b><?= strtoupper($title)?></b></h5></td>
			</tr>
			<tr>
				<td>Tgl Operasi</td>
				<td><?= $_POST['tanggal_op_dari'].' s.d '.$_POST['tanggal_op_sampai']?></td>
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
					<th rowspan="2">No</th>
					<th rowspan="2">Nama</th>
					<th rowspan="2">No RM</th>
					<th rowspan="2">Ruang Inap Sekarang</th>
					<th colspan="5">Perjanjian Batal</th>
					<th colspan="5">Perjanjian Setelah Batal</th>
				</tr>
				<tr>
					<th>Rencana Operasi</th>
					<th>Operator</th>
					<th>Diagnosa</th>
					<th>Tindakan</th>
					<th>Alasan Pembatalan</th>
					<th>Rencana Operasi</th>
					<th>Operator</th>
					<th>Diagnosa</th>
					<th>Tindakan</th>
					<th>Status Perjanjian</th>
				</tr>
				<?$i=1;foreach($output as $key=>$row){?>
				<tr>
					<td><?= $i?></td>
					<td><?= $row['nama']?></td>
					<td><?= $row['no_rm']?></td>
					<td><?= $row['ruang_inap_sekarang']?></td>
					<td><?= display_date($row['tgl_pelaksanaan'],'d-m-Y H:i')?></td>
					<td><?= $row['nama_pegawai']?></td>
					<td><?= $row['diagnosa']?></td>
					<td><?= $row['tindakan']?></td>
					<td><? $this->rute=$row['rute_decode'];array_walk($this->rute,function($value,$key){if($this->rute[$key]['to'] == -4) echo $this->rute[$key]['alasan'];})?></td>
					<?if(!empty($row['perjanjian_berikutnya'])){ $perjanjian_berikutnya = $row['perjanjian_berikutnya'];
						$json_pra_operasi = json_decode($perjanjian_berikutnya['json_pra_operasi'],true);
					?>
					<td><?= display_date($perjanjian_berikutnya['tgl_pelaksanaan'],'d-m-Y H:i')?></td>
					<td><?= $perjanjian_berikutnya['nama_pegawai']?></td>
					<td><?= $json_pra_operasi['diagnosa_utama']?></td>
					<td><?= $json_pra_operasi['tindakan']?></td>
					<td><?= $perjanjian_berikutnya['desc_status']?></td>
					<?}else{?>
					<td colspan="5"> - </td>
					<?}?>
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