<meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>
<section id="content">
<main class="main">
	<div class="box-content">
		<fieldset><legend><?= $title?></legend>
		<form method="post" role="form" class="form-horizontal well" id="perjanjian">
			<input type="hidden" name="no_perjanjian" value="<?=$profile['no_perjanjian_operasi']?>">
			<input type="hidden" name="status_to" value="<?= $status_to?>">
			<input type="hidden" name="status_from" value="<?=$profile['status']?>">
			<div class="form-group">
				<label class="col-sm-2 control-label">Diagnosa</label>
				<span class="col-sm-2"><?= $profile['topik']?></span>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Tindakan</label>
				<span class="col-sm-2"><?= $profile['NM_ICD9CM']?></span>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Perkiraan Lama Operasi</label>
				<span class="col-sm-2"><?= $profile['desc_lama_op']?></span>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Operator Yang Meminta</label>
				<span class="col-sm-2"><?= $profile['nama_pegawai']?></span>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Ruang OK <span style="color:#F00;" class="help-inline">*</span></label>
				<div class="col-sm-2">
					<select class="required" name="ruang_ok">
						<option value=""></option>
						<?foreach($ddruang_ok as $ruang_ok){?>
						<option value="<?=$ruang_ok['kode_ref']?>"><?=$ruang_ok['nama_gedung']?></option>
						<?}?>
					</select>
				</div>
				<div class="col-sm-2">
					<input type="button" class="btn btn_primary" id="show_popup_jadwal" value="tekan" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Butuh ICU </label>
				<div class="col-sm-1">
					<select class="required" name="is_need_icu">
						<option value="T">Tidak</option>
						<option value="Y">Ya</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Tgl Perkiraan Cek Kamar </label>
				<div class="col-sm-1-half"><input type="text" class="form-control datepicker_op required" name="tgl_cek_kamar_perkiraan" value="<?= display_date($profile['tgl_cek_kamar'])?>" readonly></div>
				<label class="col-sm-2 control-label">Tgl Cek Kamar <span style="color:#F00;" class="help-inline">*</span></label>
				<div class="col-sm-1-half"><input type="text" class="form-control datepicker_cek_kamar required" name="tgl_cek_kamar" value="" autocomplete="off"></div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Waktu Perkiraan Pelaksanaan</label>
				<div class="col-sm-1-half"><input type="text" class="form-control datepicker_op" name="tgl_pelaksanaan_perkiraan" value="<?= display_date($profile['tgl_pelaksanaan'])?>" readonly></div>
				<label class="col-sm-2 control-label">Waktu Pelaksanaan <span style="color:#F00;" class="help-inline">*</span></label>
				<div class="col-sm-1-half"><input type="text" class="form-control datetimepicker required" name="tgl_pelaksanaan" value="" autocomplete="off"></div>
			</div>
			<div class="form-group">
				<div class="col-sm-2"></div>
				<div class="col-sm-2"><input type="submit" class="btn btn-primary" value="save">&nbsp;<a href="<?= BASEURL."/registrasi/operasi_terjadwal/ibs_list_pasien/"?>" class="btn btn-primary bg-warning" id="adj_negatif">Back</a></div>
			</div>
		</form>
	</div>
</main>
</section>
<script>
	$('form #show_popup_jadwal').on('click',function(){
		PopupCenter('/jadwaloperasi', 'Jadwal Operasi', 900, 500);
	})
</script>