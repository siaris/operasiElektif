<script>
//buat bypass
function doIBSBypasser(this_obj){
	//munculin facebox
	$.facebox.settings.overlay = 'false';
	
	//replace
	var rm = $(this_obj).parent().find('.no_rm').val();
	
	var form_bypass_display = form_bypass
	.replace(/\$no_perjanjian/g,$(this_obj).parent().find('.no_perjanjian').val())
	.replace(/\$status_skrg/g,$(this_obj).parent().find('.status').val())
	.replace(/\$days_before_surgery/g,$(this_obj).parent().find('.days_before_surgery').val())
	.replace(/\$pasien/g,$(this_obj).parent().find('.pasien').val())
	var form_facebox = form_in_facebox_bypass
	.replace(/\$title/g,'BY PASS PASIEN INI')
	.replace(/\$form/g,form_bypass_display);
	
	$.facebox('<div class="box-content">'+form_facebox+'</div><div class="footer-box" style="display: block;"></div>');
	$('.footer-box').empty();
	$('.footer-box').append(' <a class="close" name="close" id="close-facebox" >Close</a>');
	initByPassThic();
	initSubmitByPass();
	callHistory(rm);
	$('#close-facebox').click(function(){$(this).trigger('close.facebox');})
	$('#facebox_overlay').click(function(){$(this).trigger('close.facebox');});
	return;
}

function initByPassThic(){
	$("input#is_by_pass").change(function() {
		    //jika checked
			if($(this).is(':checked')){
				$('#submit-btn').removeClass('disabled');
				$('form#perjanjian #no_reg').val($('#no_reg_chosen').val());
			//jika unchecked
			}else
				$('#submit-btn').addClass('disabled');
	});
}

function callHistory(rm){
	var no_reg = '';
	$.ajax({
        url: BASEURL+"/master/pasien/registrasi/"+rm+"/no/",
        type: "GET",
		dataType: "json",
        success: function(data) {
			var table_info = '<table style="font-weight:bold; margin-bottom:5px;" class="table table-bordered" width="100%">';
			table_info += '<tr><th colspan="4">HISTORI KUNJUNGAN TERAKHIR</th></tr><tr><th>Registrasi</th><th>Tanggal</th><th>Poli</th><th>Dokter</th></tr>'
			if(data != null){
			for (var i = 0; i < data.length; i++) {
					table_info += '<tr><td>'+data[i]['no_reg']+'</td><td>'+javascript_date(data[i]['tanggal'])+'</td><td>'+data[i]['instalasi']+' - '+data[i]['poli']+'</td><td>'+data[i]['nama']+'</td></tr>';
					no_reg = data[i]['no_reg'];
				}
			}
			table_info += '</table><input type="hidden" id="no_reg_chosen" value="'+no_reg+'">';
			$('fieldset div#info').html(table_info);
		}
    })
}

function initSubmitByPass(){
	$('#submit-btn').click(function(){
		if($('form#perjanjian #no_reg').val() == ''){
			alert('tidak ada registrasi yg terpilih!');
			return false;
		}
		if(confirm('Beneran mau di bypass?')){
			//proses ajax	
			$.ajax({
				  url: BASEURL+"/registrasi/ibs_operasi_terjadwal/frm_bypass_ibs/",
				  dataType: "json",
				  type: "POST",
				  data: $('form#perjanjian').serialize(),
				  success: function(data) {
					alert('Pasien berhasil diByPass');
					$('#facebox_overlay').trigger( "click" );
					$( ".ajax_refresh_and_loading" ).trigger( "click" );
				  }
		   });
		}
	})
}

var form_bypass = `<div class="form-group">
				<div class="form-group">
					<label class="col-sm-3 control-label">Nama</label>
					<div class="col-sm-8">$pasien</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">No Registrasi</label>
					<div class="col-sm-8"><input id="no_reg" name="no_reg" value="" readonly class="form-control"></div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">By Pass pasien ini</label>
					<div class="col-sm-8"><input id="is_by_pass" name="is_by_pass" value="Y" type="checkbox"></div>
					<div class="hide">
						<input id="no_perjanjian" name="no_perjanjian" value="$no_perjanjian">
						<input id="status" name="status" value="4">
						<input id="status_skrg" name="status_skrg" value="$status_skrg">
						<input id="days_before_surgery" name="days_before_surgery" value="$days_before_surgery">
					</div>		
				</div>
				<div class="form-group">
				<div class="col-sm-3"></div>
				<div class="col-sm-2"><input type="button" id="submit-btn" class="btn btn-primary submit disabled" value="submit"></div>
				</div>
			</div>`;
//end bypass

//buat jadwal
function doIBSScheduller(this_obj){
	//munculin facebox
	$.facebox.settings.overlay = 'false';
	console.log($(this_obj).parent().find('.dd_ruang_ok'));
	var note = $.parseJSON($(this_obj).parent().find('.note').val());
	val_opr = (note == null)?'':note.opr;
	val_anest = (note == null)?'':note.anest;
	val_inst = (note == null)?'':note.inst;
	val_circ = (note == null)?'':note.circ;
	val_penata = (note == null)?'':note.penata;
	dd_ruang_ok = $(this_obj).parent().find('.dd_ruang_ok').html();
	
	//replace
	var form_scheduller_display = form_scheduller
	.replace(/\$no_perjanjian/g,$(this_obj).parent().find('.no_perjanjian').val())
	.replace(/\$status_skrg/g,$(this_obj).parent().find('.status').val())
	.replace(/\$days_before_surgery/g,$(this_obj).parent().find('.days_before_surgery').val())
	.replace(/\$pasien/g,$(this_obj).parent().find('.pasien').val())
	.replace(/\$operator/g,$(this_obj).parent().find('.operator').val())
	.replace(/\$rencana_pembiusan/g,$(this_obj).parent().find('.rencana_pembiusan').val())
	.replace(/\$tgl_op_id/g,$(this_obj).parent().find('.tgl_op_id').val())
	.replace(/\$opr/g,val_opr)
	.replace(/\$anest/g,val_anest)
	.replace(/\$inst/g,val_inst)
	.replace(/\$circ/g,val_circ)
	.replace(/\$penata/g,val_penata)
	.replace(/\$dd_ruang_ok/g,dd_ruang_ok)
	.replace(/\$tindakan/g,$(this_obj).parent().find('.tindakan').val())
	
	
	var form_facebox = form_in_facebox_bypass
	.replace(/\$title/g,'FORM JADWAL PASIEN')
	.replace(/\$info/g,'')
	.replace(/\$form/g,form_scheduller_display);
	
	$.facebox('<div class="box-content">'+form_facebox+'</div><div class="footer-box" style="display: block;"></div>');
	$('.footer-box').empty();
	$('.footer-box').append(' <a class="close" name="close" id="close-facebox" >Close</a>');
	initSubmitScheduller();
	$('[name="is_need_icu"]').val($(this_obj).parent().find('.is_need_icu').val());
	$('[name="ruang_ok"]').val($(this_obj).parent().find('.ruang_ok').val());
	jam = $(this_obj).parent().find('.jam_pelaksanaan').val().split(':');
	$('[name="hour"]').val(jam[0].replace(/^0+/g, ""));
	if(jam[1] == '00')
		$('[name="minute"]').val('0');
	else
		$('[name="minute"]').val(jam[1].replace(/^0+/g, ""));
	$('#close-facebox').click(function(){$(this).trigger('close.facebox');})
	$('#facebox_overlay').click(function(){$(this).trigger('close.facebox');});
	return;
}

function initSubmitScheduller(){
	$('#submit-btn').click(function(){
		if(confirm('Save jadwal?')){
			//proses ajax	
			$.ajax({
				  url: BASEURL+"/registrasi/ibs_operasi_terjadwal/frm_jadwal_ibs/",
				  dataType: "json",
				  type: "POST",
				  data: $('form#perjanjian').serialize(),
				  success: function(data) {
					alert('Pasien berhasil set jadwal');
					$('#facebox_overlay').trigger( "click" );
					$( ".ajax_refresh_and_loading" ).trigger( "click" );
				  }
		   });
		}
	})
}

var hour_arr = new Array(24);
var minute_arr = new Array(60);
var hour_view = '<select name="hour">';
var minute_view = '<select name="minute">';

for(var i = 0; i < hour_arr.length; i++){
	hour_view += '<option value="'+i+'">'+i+'</option>';
}
for(var i = 0; i < minute_arr.length; i++){
	minute_view += '<option value="'+i+'">'+i+'</option>';
}
hour_view += '</select>';
minute_view += '</select>';

var form_scheduller = `<div class="form-group">
				<div class="form-group">
					<label class="col-sm-3 control-label">Nama</label>
					<div class="col-sm-8">$pasien</div>
					<div class="hide">
						<input id="no_perjanjian" name="no_perjanjian" value="$no_perjanjian">
						<input id="status" name="status" value="4">
						<input id="status_skrg" name="status_skrg" value="$status_skrg">
						<input id="days_before_surgery" name="days_before_surgery" value="$days_before_surgery">
						<input id="tgl_op_id" name="tgl_op_id" value="$tgl_op_id">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Operator</label>
					<div class="col-sm-8">$operator</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Tindakan</label>
					<div class="col-sm-8"><input type="text" name="tindakan" value="$tindakan" class="form-control"></div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Butuh ICU</label>
					<div class="col-sm-8"><select name="is_need_icu"><option value="T">Tidak</option><option value="Y">Ya</option></select></div>		
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Ruang OK</label>
					<div class="col-sm-8">$dd_ruang_ok</div>		
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Waktu Operasi</label>
					<div class="col-sm-8">$tgl_op_id&nbsp;`+hour_view+`&nbsp;:&nbsp;`+minute_view+`</div>		
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">OPR</label>
					<div class="col-sm-2"><input type="text" name="opr" value="$opr" class="form-control"></div>		
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">ANEST</label>
					<div class="col-sm-2"><input type="text" name="anest" value="$anest" class="form-control"></div>		
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">INST</label>
					<div class="col-sm-2"><input type="text" name="inst" value="$inst" class="form-control"></div>		
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">CIRC</label>
					<div class="col-sm-2"><input type="text" name="circ" value="$circ" class="form-control"></div>		
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">PENATA</label>
					<div class="col-sm-2"><input type="text" name="penata" value="$penata" class="form-control"></div>		
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Rencana Pembiusan</label>
					<div class="col-sm-8"><input type="text" name="rencana_pembiusan" value="$rencana_pembiusan" class="form-control"></div>		
				</div>
				<div class="form-group">
				<div class="col-sm-3"></div>
				<div class="col-sm-2"><input type="button" id="submit-btn" class="btn btn-primary submit" value="save"></div>
				</div>
			</div>`;
//end jadwal

var form_in_facebox_bypass = `<fieldset><legend>$title</legend><div id="info">$info</div>
	<form method="post" role="form" class="form-horizontal well" id="perjanjian">
	<fieldset>$form</fieldset>
	</form></fieldset>`;
</script>