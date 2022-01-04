<section id="content">
<main class="main">
<form class="form-horizontal well" role="form" method="post" id="form_report">
	<div class="form-group">
		<label class="col-sm-1 control-label">Tgl Input *</label>
		<div class="col-sm-1-half">
			<input type="text" value="<?php echo (isset($_POST['tanggal_input']) ? $_POST['tanggal_input'] : date($date_format_in_field) ); ?>" name="tanggal_input" class="form-control datepicker required"  placeholder="Dari" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-1 control-label">Asal Poli *</label>
		<div class="col-sm-3">
			<?php echo myform_dropdown('poli_asal', $ddpoli, isset($_POST['poli_asal'])?$_POST['poli_asal']:null, 'class="input-large required"'); ?>
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
				<td>Tgl Input</td>
				<td><?= $_POST['tanggal_input']?></td>
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
					<th>No RM - Nama</th>
					<th>No Kontak Pasien</th>
					<th>No Perjanjian</th>
					<th>Status Saat Ini</th>
					<th>Diagnosa</th>
					<th>Tindakan</th>
					<th>Tgl Cek Kamar</th>
					<th>Tgl Pelaksanaan</th>
					<th>Operator</th>
					<th>Alat Tambahan</th>
					<th>Perkiraan Lama Operasi</th>
					<th>Kebutuhan ICU</th>
					<th>Asal Poli</th>
				</tr>
				<?$i=1;foreach($output as $key=>$row){?>
				<tr>
					<td><?= $i?></td>
					<td><a href="javascript:void(0);" onclick="displayRute(this,'<?= $row['no_perjanjian_operasi']?>')"><?= $row['no_rm'].' - '.$row['nama']?></a></td>
					<td><?= $row['no_kontak_pasien']?></td>
					<td><?= $row['no_perjanjian_operasi']?></td>
					<td><?= $row['desc_status']?></td>
					<td><?= $row['diagnosa']?></td>
					<td><?= $row['tindakan']?></td>
					<td><?= display_date($row['tgl_cek_kamar'],'d-m-Y')?></td>
					<td><?= display_date($row['tgl_pelaksanaan'],'d-m-Y')?></td>
					<td><?= $row['nama_pegawai']?></td>
					<td><?= $row['kebutuhan_alat']?></td>
					<td><?= $row['desc_lama_op']?></td>
					<td><?= $row['is_need_icu']?></td>
					<td><?= $row['poli_asal']=='300'?'Ruang Inap':$row['nama_poli']?><span class="rute hide" ><div id="rute_<?= $row['no_perjanjian_operasi']?>"><?= $row['rute_desc']?></div></span></td>
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