<form id="form-list-perjanjian" method="post" role="form" class="form-horizontal well">
    <div class="row">
		<div class="col-xs-6">
		<div class="form-group ">
			<label class="col-sm-4 control-label">Tgl Cek Kamar inap </label>
			<div class="col-sm-2">
				<input type="text" class="form-control datepicker" name="tgl_cek_kamar" id="tgl_cek_kamar" value="<?= (!empty($_POST['tgl_cek_kamar']) ? $_POST['tgl_cek_kamar'] : '' );  ?>">
			</div>
		</div>
		<div class="form-group ">
			<label class="col-sm-4 control-label">Tgl Pelaksanaan Operasi </label>
			<div class="col-sm-2">
				<input type="text" class="form-control datepicker" name="tgl_kunjungan" id="tgl_kunjungan" value="<?= (!empty($_POST['tgl_kunjungan']) ? $_POST['tgl_kunjungan'] : '' );  ?>">
			</div>
		</div>
		<div class="form-group ">
			<label class="col-sm-4 control-label">Status Perjanjian <span style="color:#F00;" class="help-inline">*</span></label>
			<div class="col-sm-8">
				<select name="status_kunjungan" class="input-large required">
					<?$sts_kunjungan_selected = (!empty($_POST['status_kunjungan']) ? $_POST['status_kunjungan'] : '' );  ?>
					<?foreach($status_kunjungan as $value=>$text){?>
					<option value="<?= $value?>" <?= ($sts_kunjungan_selected==$value)?'selected':''?>><?= $text?></option>
					<?}?>
				</select>
			</div>
		</div>
		</div>
	</div>
	<div class="form-group col-sm-3" style="float:left; margin-right:10px;">
		<input type="submit" value="cari" class="btn btn-primary">
		&nbsp;<a href="<?= BASEURL."/registrasi/operasi_terjadwal/"?>" class="btn btn-primary bg-warning" id="adj_negatif">Back</a>
	</div>
</form>

<script>
	var tahapan_perjanjian = ["","<?= implode('","',$status_kunjungan)?>"];
	function submit_form(obj,no_perjanjian){
		$("#form_"+no_perjanjian).ajaxSubmit({url: BASEURL+'/registrasi/operasi_terjadwal/submit_status/', type: 'post'}).ajaxComplete(function(){$( ".ajax_refresh_and_loading" ).trigger( "click" );});
		return;
	}
	function alertFirst(message,url){
		alert(message);
		window.location = url;
		return;
	}
	function finishkan(no_perjanjian){
		if(confirm('Pasien Perjanjian Operasi Memasuki Ruang OK')){
			submit_form({},no_perjanjian);
			return;
		}
	}
	function showSwalForm(no_perjanjian){
		swal({   
			title: "Isi Jadwal Re-schedule",   
			text: '<form id="form_reschedule_'+no_perjanjian+'"> <div class="row"><div><div class="form-group"> <label class="col-sm-6 control-label">Tgl Cek Kamar inap </label> <div class="col-sm-4"> <input type="text" class="form-control datepicker" name="tgl_cek_kamar" value="aaa" style="display:block;"> </div></div></div></div></form>',   
			html: true });
	}
</script>