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
		<label class="col-sm-1 control-label">Tgl Cek Kamar </label>
		<div class="col-sm-1-half">
			<input type="text" value="<?php echo (isset($_POST['tanggal_cek_dari']) ? $_POST['tanggal_cek_dari'] : '' ); ?>" name="tanggal_cek_dari" class="form-control datepicker"  placeholder="Dari" />
		</div>
		<div class="col-sm-00">
		s.d
		</div>
		<div class="col-sm-1-half">
			<input type="text" value="<?php echo (isset($_POST['tanggal_cek_sampai']) ? $_POST['tanggal_cek_sampai'] : '' ); ?>" name="tanggal_cek_sampai" class="form-control datepicker"  placeholder="Sampai" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-1 control-label">Status Perjanjian </label>
		<div class="col-sm-5">
			<select name="status_kunjungan" class="input-large">
					<option value=""></option>
					<?$sts_kunjungan_selected = (!empty($_POST['status_kunjungan']) ? $_POST['status_kunjungan'] : '' );  ?>
					<?foreach($status_kunjungan as $value=>$text){?>
					<option value="<?= $value?>" <?= ($sts_kunjungan_selected==$value)?'selected':''?>><?= $text?></option>
					<?}?>
				</select>
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
				<td>Tgl Cek Kamar</td>
				<td><?= $_POST['tanggal_op_dari'].' s.d '.$_POST['tanggal_op_sampai']?></td>
				<td>Tgl Operasi</td>
				<td><?= !empty($_POST['tanggal_cek_dari'])?$_POST['tanggal_cek_dari']:'-'?><?= !empty($_POST['tanggal_cek_sampai'])?' s.d '.$_POST['tanggal_cek_sampai']:' s.d -'?></td>
			</tr>
			<tr>
				<td>User Input</td>
				<td colspan="3"><?= $this->session->userdata['userLogin']['name']?></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" style="font-size:12px;" class="table table-bordered table-condensed">
				<tr>
					<th>No</th>
					<th>No Perjanjian</th>
					<th>No RM</th>
					<th>Nama</th>
					<th>No. Peserta</th>
					<th>Asal Poli</th>
					<th>Status Perjanjian</th>
					<th>Diagnosa</th>
					<th>Tindakan</th>
					<th>ICU</th>
					<th>Tgl Cek Kamar</th>
					<th>Tgl Operasi</th>
				</tr>
				<?$i=1;foreach($output as $row){?>
				<tr>
					<td><?= $i?></td>
					<td><?= $row['no_perjanjian_operasi']?></td>
					<td><?= $row['no_rm']?></td>
					<td><a href="javascript:void(0);" onclick="displayRute(this,'<?= $row['no_perjanjian_operasi']?>')"><?= $row['nama']?></a></td>
					<td><?= $row['no_peserta_jaminan']?></td>
					<td><?= $row['nama_poli']?></td>
					<td><?= $row['desc_status']?></td>
					<td><?= $row['diagnosa']?></td>
					<td><?= $row['tindakan']?></td>
					<td><?= $row['butuh_icu']?></td>
					<td><?= display_date($row['tgl_cek_kamar'])?></td>
					<td><?= display_date($row['tgl_pelaksanaan'])?><span class="rute hide" ><div id="rute_<?= $row['no_perjanjian_operasi']?>"><?= $row['rute_desc']?></div></span></td>
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