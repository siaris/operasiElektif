<form id="form-list-perjanjian" method="post" role="form" class="form-horizontal well">
    <div class="row">
		<div class="col-xs-6">
		<div class="form-filter-worksheet">
		<div class="form-group ">
			<label class="col-sm-4 control-label">Tgl Buat Perjanjian </label>
			<div class="col-sm-2">
				<input type="text" class="form-control datepicker" name="created_date" id="created_date" value="<?= (!empty($_POST['created_date']) ? $_POST['created_date'] : '' );  ?>">
			</div>
		</div>
		<div class="form-group ">
			<label class="col-sm-4 control-label">Tgl Cek Kamar Inap </label>
			<div class="col-sm-2">
				<input type="text" class="form-control datepicker" name="tgl_cek_kamar" id="tgl_cek_kamar" value="<?= (!empty($_POST['tgl_cek_kamar']) ? $_POST['tgl_cek_kamar'] : '' );  ?>">
			</div>
		</div>
		</div>
		<div class="form-group ">
			<label class="col-sm-4 control-label">Tgl Pelaksanaan Operasi </label>
			<div class="col-sm-2">
				<input type="text" class="form-control datepicker" name="tgl_kunjungan" id="tgl_kunjungan" value="<?= (!empty($_POST['tgl_kunjungan']) ? $_POST['tgl_kunjungan'] : '' );  ?>">
			</div>
			<?/*<div class="col-sm-4 form-filter-worksheet">
				<input type="checkbox" id="is_odc" name="is_odc" value="Y" <?= isset($_POST['is_odc'])?'checked':''?>/> One Day Care
			</div>*/?>
		</div>
		<div class="form-group ">
			<label class="col-sm-4 control-label">Jenis Perjanjian Operasi </label>
			<div class="col-sm-6">
				<?$jns_slctd = (!empty($_POST['jns_janji']) ? $_POST['jns_janji'] : '' );  ?>
				<select name="jns_janji">
					<option value="" <?= ($jns_slctd=='')?'selected':''?>>TANPA ODC DAN TANPA CITO</option>
					<option value="ODC" <?= ($jns_slctd=='ODC')?'selected':''?>>HANYA ODC</option>
					<?/*<option value="C" <?= ($jns_slctd=='C')?'selected':''?>>HANYA CITO</option>*/?>
				</select>
			</div>
		</div>
		<div class="form-filter-worksheet">
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
	</div>
	<div class="form-group col-sm-3" style="float:left; margin-right:10px;">
		<input type="submit" value="cari" class="btn btn-primary">
		&nbsp;<input class="btn btn_primary" id="show_popup_jadwal" value="Info Ruang OK" type="button">&nbsp;<a href="<?= BASEURL."/registrasi/operasi_terjadwal/"?>" class="btn btn-primary bg-warning">Back</a>
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
		//show 
		$('#form_'+no_perjanjian).parent('div').removeClass('hide');
		//init datetime
		initDateTimePicker('input.datetimepicker','');
		//hide tombol finishkan
		$('#form_'+no_perjanjian).closest('td').find('.finishkan').addClass('hide');
		//init button submit
		$('.submit_to_finish').on('click',function(){
			if($(this).closest('div.form_to_submit').find('.datetimepicker').val() == ''){
				alert('mohon diisi waktu masuk OK');
				return false;
			}
			//submit
			$("#form_"+no_perjanjian).ajaxSubmit({url: BASEURL+'/registrasi/ibs_operasi_terjadwal/submit_status/', type: 'post'}).ajaxComplete(function(){$( ".ajax_refresh_and_loading" ).trigger( "click" );});
			return
		})
		return;
		
		
		// if(confirm('Pasien Perjanjian Operasi Sudah Memasuki Ruang OK')){
			// //tambah inputan date time
			// datetime_input = '<input name="waktu_selesai" class="form-control datetimepicker" type="text">';
			// submit_form({},no_perjanjian);
			// return;
		// }
	}
	function showSwalForm(no_perjanjian){
		swal({   
			title: "Isi Jadwal Re-schedule",   
			text: '<form id="form_reschedule_'+no_perjanjian+'"> <div class="row"><div><div class="form-group"> <label class="col-sm-6 control-label">Tgl Cek Kamar inap </label> <div class="col-sm-4"> <input type="text" class="form-control datepicker" name="tgl_cek_kamar" value="aaa" style="display:block;"> </div></div></div></div></form>',   
			html: true });
	}
	$('form #show_popup_jadwal').on('click',function(){
		PopupCenter('/jadwaloperasi/jadwal_perruangok', 'Jadwal Operasi', 900, 500);
	})
</script>