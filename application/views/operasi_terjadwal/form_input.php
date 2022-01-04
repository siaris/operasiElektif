<meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>
<section id="content">
<main class="main">
	<div class="box-content">
	<fieldset><legend>JADWAL OPERASI BARU (FLOW POLI NON INAP)</legend>
	<form method="post" role="form" class="form-horizontal well" id="perjanjian">
		<fieldset>
			<legend>Data Pasien</legend>
			<div class="form-group">
				<label class="col-sm-2 control-label">Nama Pasien <span style="color:#F00;" class="help-inline">*</span></label>
				<div class="col-sm-2">
					<input name="nama_pelanggan" id="nama_pelanggan" class="form-control required" value="" onkeypress="cari_pasien()" type="text" autocomplete="off">
				</div>
				<div class="col-sm-2">
					<input type="text" id="no_rm_display" name="no_rm_display" class="form-control required" value=""  data-original-title="" title="" readonly>
					<input type="hidden" id="no_rm" name="no_rm" class="form-control required" value=""  data-original-title="" title="" >
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Tgl Lahir</label>
				<div class="col-sm-1"><input type="text" class="form-control" readonly id="tgl_lahir"></div>
			</div><div class="form-group">
				<label class="col-sm-2 control-label">Jenis Kelamin</label>
				<div class="col-sm-1"><input type="text" class="form-control" readonly id="jenis_kelamin"></div>
			</div><div class="form-group">
				<label class="col-sm-2 control-label">Alamat</label>
				<div class="col-sm-2"><textarea class="form-control" readonly id="alamat"></textarea></div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">No Kontak Pasien <span style="color:#F00;" class="help-inline">*</span></label>
				<div class="col-sm-4"><input type="text" class="form-control required" id="no_kontak_pasien" name="no_kontak_pasien" autocomplete="off"></div>
			</div>
			<?/*<div class="form-group">
				<label class="col-sm-2 control-label">No Kartu Jaminan <span style="color:#F00;" class="help-inline">*</span></label>
				<div class="col-sm-4"><input type="text" class="form-control required" id="no_jaminan" name="no_jaminan" autocomplete="off"></div>
			</div>*/?>
		</fieldset>
		<fieldset>
			<legend>Data Operasi</legend>
			<div class="form-group">
				<label class="col-sm-2 control-label">Asal Pasien <span style="color:#F00;" class="help-inline">*</span></label>
				<div class="col-sm-2"><?php echo myform_dropdown('poli_asal', $poli, null, 'class="input-large required"'); ?></div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Konsul Pra Operasi</label>
				<div class="col-sm-2"><?php echo myform_dropdown('list_poli_konsul[]', $poli, null, 'class="input-large" multiple'); ?></div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Butuh ICU <span style="color:#F00;" class="help-inline">*</span></label>
				<div class="col-sm-1">
					<select class="required" name="is_need_icu">
						<option value="T">Tidak</option>
						<option value="Y">Ya</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Apakah Pasien Mengkonsumsi Pengencer Darah <span style="color:#F00;" class="help-inline">*</span></label>
				<div class="col-sm-1">
					<select class="required" name="blThnrCnsmd">
						<option value="T">Tidak</option>
						<option value="Y">Ya</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Diagnosa <span style="color:#F00;" class="help-inline">*</span></label>
				<div class="col-sm-2"><input type="text" style="width:462px" class="form-control icd10 required" <?/*onkeypress="cari_diagnosa_op(this)"*/?> value="" name="diagnosa" autocomplete="off"><input type="hidden" name="kode_diagnosa" class="kode_icd10"></div>
			</div><div class="form-group">
				<label class="col-sm-2 control-label">Tindakan <span style="color:#F00;" class="help-inline">*</span></label>
				<div class="col-sm-2"><input type="text" style="width:462px" class="form-control icd9 required" <?/*onkeypress="cari_icd9_op(this)"*/?> value="" name="tindakan" autocomplete="off"><input type="hidden" name="tindakan_kode" class="kode_icd9"></div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Rencana Pembiusan <span style="color:#F00;" class="help-inline">*</span></label>
				<div class="col-sm-2"><textarea class="form-control required" name="rencana_pembiusan"></textarea></div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Perkiraan Lama Operasi <span style="color:#F00;" class="help-inline">*</span></label>
				<div class="col-sm-2"><?php echo myform_dropdown('lama_operasi', $lama_operasi, null, 'class="input-large required"'); ?></div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Operator Yang Meminta <span style="color:#F00;" class="help-inline">*</span></label>
				<div class="col-sm-2"><input name="nama_dokter" id="nama_dokter" class="form-control nama_dokter" value="" onkeypress="cari_dokter()" type="text" autocomplete="off"><input name="kode_dokter" id="kode_dokter" class="form-control required" value="" tabindex="" type="hidden"></div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Kebutuhan Alat Tambahan</label>
				<div class="col-sm-2"><textarea class="form-control" name="kebutuhan_alat"></textarea></div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Catatan Pra Operasi</label>
				<div class="col-sm-2"><textarea class="form-control" name="catatan_tambahan_pra_operasi"></textarea></div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Tgl Perkiraan Operasi <span style="color:#F00;" class="help-inline">*</span></label>
				<div class="col-sm-2"><input type="text" class="form-control datepicker_pelaksanaan_op required" id="tgl_pelaksanaan" name="tgl_pelaksanaan" ></div>
				<div class="col-sm-2"><input type="checkbox" id="is_odc" name="is_odc" value="Y"/> One Day Care</div>
			</div>
		</fieldset>
		<fieldset id="data-rawat-inap">
			<legend>Data Rawat Inap</legend>
			<div class="form-group">
				<label class="col-sm-2 control-label">Pasien Harus Registrasi Inap <span style="color:#F00;" class="help-inline">*</span></label>
				<??>
				<div class="col-sm-01"><?php echo myform_dropdown('hari_sebelum_tindakan', $hari_sebelum_tindakan, null, 'class="required" id="hari_sebelum_tindakan"'); ?></div>
				<div class="col-sm-2">Hari Sebelum Tindakan Operasi</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Tgl Daftar Ranap <span style="color:#F00;" class="help-inline">*</span></label>
				<div class="col-sm-2 div-datepicker_daftar_inap"><input type="text" class="form-control datepicker_daftar_inap required" readonly name="tgl_reg_ranap"></div>
				<span class="col-sm-4" style="font-style: italic;">harap ganti tgl daftar jika tgl yg tertera adalah hari libur</span>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Tgl Cek Kamar <span style="color:#F00;" class="help-inline">*</span></label>
				<div class="col-sm-2 div-datepicker_cek_kamar"><input type="text" class="form-control datepicker_cek_kamar required" readonly name="tgl_cek_kamar"></div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Kelas Kamar Inap</label>
				<div class="col-sm-2"><?php echo myform_dropdown('kelas', $kelas, null, 'class="input-large"'); ?></div>
			</div>
		</fieldset>
		<div class="form-group">
			<div class="col-sm-2"></div>
			<div class="col-sm-2"><input type="submit" class="btn btn-primary submit" value="save">&nbsp;<a href="<?= BASEURL."/registrasi/operasi_terjadwal/index_smf/"?>" class="btn btn-primary bg-warning" id="adj_negatif">Back</a></div>
		</div>
	</form>
	</fieldset>
	</div>
</main>
<script>
	var optODCAvailable = {'0':'0'};
	var optODCNotAvailable = {
		<?$arr_temp = []; foreach ($hari_sebelum_tindakan as $key=>$hari){?>
			<?$arr_temp[]= '"'.$key.'"'.':"'.$hari.'"'?>
		<?} echo implode(',',$arr_temp);?>
	};
	$(document).ready(function() {
		$("#tgl_pelaksanaan").keyup(function(e){ 
			this.value=""
		})
		$('form#perjanjian').validate({ignore: null});
		$("form .datepicker_pelaksanaan_op").datepicker({format: date_format, autoclose: true, startDate:'<?= $date_limit_op?>',  language: 'id'});
		
		$('input.datepicker_pelaksanaan_op').change(function(){
			$('select#hari_sebelum_tindakan').change();
		});
		$('select#hari_sebelum_tindakan').change(function(){
			if($('select#hari_sebelum_tindakan :selected').val() == '') return;
			day_before = -1*$('select#hari_sebelum_tindakan :selected').val();
			fillDate(day_before,'.datepicker_pelaksanaan_op','.datepicker_daftar_inap');
			$('input.datepicker_daftar_inap').change();
		});
		$('input.datepicker_daftar_inap').change(function(){
			day_reduce = -1;
			if($('input#is_odc').is(':checked'))
				day_reduce = 0;
			fillDate(day_reduce,'.datepicker_daftar_inap','.datepicker_cek_kamar');
		});
		
		$('input#is_odc').prop('checked', false);
		$("input#is_odc").change(function() {
		    $("form .datepicker_pelaksanaan_op").val('');
			//jika checked
			if($(this).is(':checked'))
				doActODC();
			//jika unchecked
			else
				doActNoODC();
		});
	})
	
	function fillDate(day,date_param,date_filled){
		//calculate
		var myd = new Date(getDateFromFormat($('form '+date_param).val(),'dd-MM-yyyy'));
		myd.setDate(myd.getDate() + day);
		var tgl_fill = ("0" + myd.getDate()).slice(-2) + '-'+ ("0" + (myd.getMonth() + 1)).slice(-2) + '-'+ myd.getFullYear();
		//fill
		$('form '+date_filled).val(tgl_fill);
		//initdatepicker
		start_date = '<?= $date_limit?>';
		if(date_filled == '.datepicker_daftar_inap')
			start_date = '<?= date('d-m-Y',strtotime(date('Y-m-d') . "+".($batas_min_cek_kamar-1)." days"))?>';
		if(date_filled != '.datepicker_cek_kamar'){
			$("form "+date_filled).datepicker('destroy');
			$("form "+date_filled).datepicker({format: date_format, autoclose: true, startDate:start_date, endDate:tgl_fill, language: 'id'});
		}
		return;
	}
	
	function doActODC(){
		//change list
		$("#hari_sebelum_tindakan").empty();
		$.each(optODCAvailable, function(key,value) {
			 $("#hari_sebelum_tindakan").append($("<option></option>").attr("value", value).text(key));
		});
		//init
		//select 0
		$("#hari_sebelum_tindakan").select2('val','0');
		//trigger hari_sebelum_tindakan
		$('select#hari_sebelum_tindakan').change();
		//set tgl cek kamar = tgl daftar
		$('form .datepicker_cek_kamar').val($('form .datepicker_daftar_inap').val());
		//hide fieldset
		$('#data-rawat-inap').addClass("hide");
		$("form .datepicker_pelaksanaan_op").datepicker('destroy');
		$("form .datepicker_pelaksanaan_op").datepicker({format: date_format, autoclose: true, startDate:'<?= $date_limit_op_odc?>',  language: 'id'});
		return;
	}
	
	function doActNoODC(){
		$("#hari_sebelum_tindakan").empty();
		//change list to range (blank,1-5)
		$.each(optODCNotAvailable, function(key,value) {
			 $("#hari_sebelum_tindakan").append($("<option></option>").attr("value", value).text(key));
		});
		//init
		//select blank
		$("#hari_sebelum_tindakan").select2('val','');
		//set tgl cek kamar = tgl daftar = ''
		$('form .datepicker_cek_kamar').val(''); 
		$('form .datepicker_daftar_inap').val('');
		//show fieldset
		$('#data-rawat-inap').removeClass("hide");
		$("form .datepicker_pelaksanaan_op").datepicker('destroy');
		$("form .datepicker_pelaksanaan_op").datepicker({format: date_format, autoclose: true, startDate:'<?= $date_limit_op?>',  language: 'id'});
		return;
	}
</script>