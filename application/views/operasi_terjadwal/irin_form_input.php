<meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>
<section id="content">
<main class="main">
	<div class="box-content">
	<fieldset><legend id="title-form">JADWAL OPERASI BARU (FLOW PASIEN INAP)</legend>
	<form method="post" role="form" class="form-horizontal well" id="perjanjian">
		<fieldset>
			<legend>Data Pasien</legend>
			<div class="form-group">
				<label class="col-sm-2 control-label">Nama Pasien <span style="color:#F00;" class="help-inline">*</span></label>
				<div class="col-sm-2">
					<input name="nama_pelanggan" id="nama_pelanggan" class="form-control required" value="" onkeypress="cari_pasien_ranap()" type="text" autocomplete="off">
					<input type="hidden" name="no_reg_pasien" id="no_reg_pasien" value="">
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
				<label class="col-sm-2 control-label">No Kontak Pasien </label>
				<div class="col-sm-4"><input type="text" class="form-control" id="no_kontak_pasien" name="no_kontak_pasien" autocomplete="off" readonly></div>
			</div>
		</fieldset>
		<fieldset>
			<legend>Data Operasi</legend>
			<div class="form-group">
				<label class="col-sm-2 control-label">Asal Pasien <span style="color:#F00;" class="help-inline">*</span></label>
				<div class="col-sm-2"><?= $poli[$poli_selected]?><input type="hidden" name="poli_asal" value="<?= $poli_selected?>"></div>
			</div>
			<div class="form-group" hidden>
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
				<label class="col-sm-2 control-label">Tgl Perkiraan Pelaksanaan <span style="color:#F00;" class="help-inline">*</span></label>
				<div class="col-sm-2"><input type="text" class="form-control datepicker_op_irin required" id="tgl_pelaksanaan" name="tgl_pelaksanaan"></div>
				<div class="col-sm-2 hide"><input onclick="return false;" type="checkbox" id="is_cito" name="is_cito" value="Y"/> Cito</div>
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
		</fieldset>
		<fieldset hidden>
			<legend>Data Rawat Inap</legend>
			<div class="form-group">
				<label class="col-sm-2 control-label">Kelas Inap Saat Ini</label>
				<div class="col-sm-2"><input type="hidden" name="kelas" id="kelas_saat_ini" value=""></div>
			</div>
		</fieldset>
		<div class="form-group">
			<div class="col-sm-2"></div>
			<div class="col-sm-2"><input type="submit" class="btn btn-primary" value="save">&nbsp;<a href="<?= BASEURL."/registrasi/operasi_terjadwal/index_irin/"?>" class="btn btn-primary bg-warning" id="adj_negatif">Back</a></div>
		</div>
	</form>
	</fieldset>
	</div>
</main>
<script>
let ddcito = ['<?= date('d-m-Y')?>','<?= date('d-m-Y',strtotime('+1 day'))?>']
var identifyOP = function(){
	function inti(){
		if(location.href.indexOf('cito') > -1){
			//do_cito()
			$('#title-form').html('JADWAL OPERASI CITO BARU')
			$("form .datepicker_op_irin").datetimepicker({format: time_format, autoclose: true, startDate:'<?= $date_limit?>', endDate:'<?= $date_limit_end?>'})
			$('#is_cito').attr('checked',true).closest('div').removeClass('hide')
		}else{
			$("form .datepicker_op_irin").datepicker({format: time_format, autoclose: true, startDate:'<?= $date_limit?>'})
		}
		return
	}
	function do_cito(){
		$('#is_cito').attr('checked',true).closest('div').removeClass('hide')
		//hapus
		$('#is_cito').closest('div.form-group').find('#tgl_pelaksanaan').remove()
		//ganti dgn dropdown
		$('#is_cito').closest('div.col-sm-2').prev().prepend('<select name="tgl_pelaksanaan" id="tgl_pelaksanaan"></select>')
		dd = []
		for(i in ddcito) {dd[i] = {'k':ddcito[i],'v':ddcito[i]};}
		
		addOption($('#tgl_pelaksanaan'),dd,'k','v')
		$('select#tgl_pelaksanaan option')
		.filter(function() {
			return !this.value || $.trim(this.value).length == 0 || $.trim(this.text).length == 0;
		}).remove()
		$('#title-form').html('JADWAL OPERASI CITO BARU')
		return
	}
	return{init:inti}
}()
$(document).ready(function() {
	$("form select").select2();
	$('form#form-list-perjanjian').validate({ignore: null});
	$('form#perjanjian').validate({ignore: null});
	$("#tgl_pelaksanaan").keyup(function(e){ 
		this.value=""
	})
	identifyOP.init()
})
function cari_pasien_ranap(){
	$('form#perjanjian #nama_pelanggan').typeahead({
			source: function(typeahead, query) {
				if(!doCheckMaxLength('form#perjanjian #nama_pelanggan'))return;
				$.ajax({
				  url: BASEURL+"/master/pasien/typeahead_adm_operasi/",
				  dataType: "json",
				  type: "POST",
				  data: {
					  max_rows: max_rows_autosuggest,
					  q: query
				  },
				  success: function(data) {
						typeahead.process(irin_display_return_typeahead(data,'pasien'));
				  }
			   });
			},
			minLength: 3,
			onselect: function(obj) {
				irin_select_autosuggest(obj,'pasien');
			},
			items: max_rows_autosuggest
		 });
}
function irin_select_autosuggest(obj,prefix){
	if(prefix=='pasien'){
		$('form#perjanjian #no_rm').val(obj.id);
		$('form#perjanjian #no_rm_display').val(obj.id);
		$('form#perjanjian #nama_pelanggan').val(obj.nama);
	    $('form#perjanjian #tgl_lahir').val(obj.tanggal_lahir);
	    $('form#perjanjian #alamat').val(obj.alamat);
	    $('form#perjanjian #jenis_kelamin').val(obj.jenis_kelamin);
	    $('form#perjanjian #no_kontak_pasien').val(obj.no_telpon);
	    $('form#perjanjian #kelas_saat_ini').val(obj.kelas);
	    $('form#perjanjian #no_reg_pasien').val(obj.no_reg);
	}else{
		$('form#perjanjian #kode_dokter').val(obj.id_pegawai);
	    $('form#perjanjian #nama_dokter').val(obj.nama_pegawai);
	}
	return;
}

function irin_display_return_typeahead(data,prefix){
	if (prefix == 'pasien'){
		 var return_list = [], i = data.length;
		  while (i--) {
			  return_list[i] = {id: data[i].id, value: data[i].no_rm + ' - ' + data[i].nama, no_rm: data[i].no_rm,nama: data[i].nama,type_pasien:'pasien',no_telpon: data[i].no_telpon === '' ?data[i].no_hp:data[i].no_telpon+' / '+data[i].no_hp, alamat: data[i].alamat_jalan, tanggal_lahir: data[i].tgl_lhr_frmted,kelas: data[i].kelas,no_reg: data[i].no_reg, jenis_kelamin: data[i].jenis_kelamin == 'P'?'Perempuan':'Laki-laki'};
		  }
	}else{
		var return_list = [], i = data.length;
		  while (i--) {
			  return_list[i] = {id: data[i].id, value: data[i].id_pegawai + ' - ' + data[i].nama, id_pegawai: data[i].id_pegawai,nama_pegawai: data[i].nama, prosentase: data[i].prosentase};
		  }
	}	
	return return_list;
}
</script>