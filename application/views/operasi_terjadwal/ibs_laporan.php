<section id="content">
<main class="main">
<form class="form-horizontal well" role="form" method="post" id="form_report">
	<div class="form-group">
		<label class="col-sm-1 control-label">Tgl Operasi *</label>
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
					<th>No</th>
					<th>Ruang OK</th>
					<th>Nama</th>
					<th>No RM</th>
					<th>Tgl Lahir</th>
					<th>Diagnosa</th>
					<th>Tindakan</th>
					<th>Tgl Pelaksanaan</th>
					<th>Kamar Carter</th>
					<th>Operator</th>
					<th>Alat Tambahan</th>
					<th>Pengencer</th>
					<th>Perkiraan Lama Operasi</th>
					<th>Kebutuhan ICU</th>
					<th>Asal Poli</th>
					<th>Jaminan</th>
				</tr>
				<?$i=1;foreach($output as $key=>$row){?>
				<tr>
					<td><?= $i?></td>
					<td><?= $row['ruang_ok']?></td>
					<td><a href="javascript:void(0);" onclick="displayRute(this,'<?= $row['no_perjanjian_operasi']?>')"><?= $row['nama']?></a></td>
					<td><?= $row['no_rm']?></td>
					<td><?= display_date($row['tanggal_lahir'])?></td>
					<td><?= $row['diagnosa']?></td>
					<td><?= $row['tindakan']?></td>
					<td><?= display_date($row['tgl_pelaksanaan'],'d-m-Y H:i')?></td>
					<td><?= empty($row['nama_ruang'])?'':$row['nama_ruang'].':'.$row['no_kasur']?></td>
					<td><?= $row['nama_pegawai']?></td>
					<td><?= $row['kebutuhan_alat']?></td>
					<td><?= $row['encer']?></td>
					<td><?= $row['desc_lama_op']?></td>
					<td><?= $row['is_need_icu']?></td>
					<td><?= $row['nama_poli']?></td>
					<td><?= $row['cara_bayar']?><span class="rute hide" ><div id="rute_<?= $row['no_perjanjian_operasi']?>"><?= $row['rute_desc']?></div></span></td>
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

function displayRute(objClicked,id_perjanjian){
	swal({
		title: "Rute",
		type: "warning",
		text: $('div#rute_'+id_perjanjian).html(),
		html: true
	});
	return;
}
</script>