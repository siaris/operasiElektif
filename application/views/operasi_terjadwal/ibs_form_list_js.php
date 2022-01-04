<script>
const isCito = <?= $isCito?>;
$(document).ready(function() {
	$('.datepicker').datepicker({"format": date_format, "autoclose": true,
		orientation:'bottom',language:'id'
	});
	$("form select").select2();
	$('form#form-list-perjanjian').validate({ignore: null});
	initDateTimePicker('div.form_acc .datetimepicker','<?= date('Y-m-d',strtotime(date('Y-m-d') . "+1 days"))?>');
	if(isCito == 1) formCito.init()
});
var formCito = function(){
	function inti(){
		//buang inputan tgl kecuali tgl buat perjanjian
		$('#tgl_cek_kamar,#tgl_kunjungan').closest('div.form-group').addClass('hide').next().html('<div class="form-group"><label class="col-sm-4 control-label">Jenis Perjanjian Operasi </label><div class="col-sm-8">CITO<input type="hidden" value="C" name="jns_janji"></div>')
	}
	return{ init: inti}
}()
var waktiOPEditor = function(){
	function inti(O) {
		$(O).addClass('disabled')
		let o = O
		$.ajax({
			url: BASEURL+"/registrasi/ibs_operasi_terjadwal/get_time_range/",
			dataType: "json",
			type: "POST",
			data: {
				t: $(o).closest('div.form_acc').find('.dateOp').val()
			},
			success: function(d) {
				$(o).closest('div.form_acc').find('.dateOp').datetimepicker({
					format: time_format,
					startDate:d[0],
					endDate:d[1]
				})
			}
		});
	}
	return{init:inti}
}()
$(document).ajaxStop(function () {
	initDateTimePicker('div.form_acc .datetimepicker','<?= date('Y-m-d',strtotime(date('Y-m-d') . "+1 days"))?>');
});
function initDateTimePicker(selecter,startdate){
	$(selecter).datetimepicker({
		format: time_format,
		startDate:startdate,
		language:'id',
		place: function (){
				var zIndex = parseInt(this.element.parents().filter(function() {
										return $(this).css('z-index') != 'auto';
									}).first().css('z-index'))+10;		
				var offset = this.component ? this.component.offset() : this.element.offset();
				this.picker.css({
					top: offset.top + this.height,
					left: offset.left,
					zIndex: zIndex
				});
			}
	});
}

function doIBSAcc(this_obj){
	if($(this_obj).parent().find('.dateOp').val() == ''){
		alert('Mohon Input Waktu Dahulu!');
		$(this_obj).parent().find('input').focus();
		return false;
	}
	
	//munculin facebox
	$.facebox.settings.overlay = 'false';
	//replace
	var tgl_pelaksanaan_no_time = explode(' ',$(this_obj).parent().find('.dateOp').val());
	var poli_asal = $(this_obj).parent().find('.poli_asal').val();
	var cek_kamar = $(this_obj).parent().find('.cek_kamar').val();
	var reg_ranap = $(this_obj).parent().find('.reg_ranap').val();
	var perkiraan_operasi = $(this_obj).parent().find('.perkiraan_operasi').val();
	var show_date = $(this_obj).parent().find('.days_before_surgery').val() == '0'?'hide':'';
	var form_facebox = form_in_facebox
	.replace(/\$no_perjanjian/g,$(this_obj).parent().find('.no_perjanjian_operasi').val())
	.replace(/\$tgl_pelaksanaan/g,$(this_obj).parent().find('.dateOp').val())
	.replace(/\$days_before_surgery/g,$(this_obj).parent().find('.days_before_surgery').val())
	.replace(/\$ruang_ok/g,$(this_obj).parent().find('.ruang_ok :selected').val())
	.replace(/\$pasien/g,$(this_obj).parent().find('.pasien').val())
	.replace(/\$no_time_tgl_pelaksanaan/g,tgl_pelaksanaan_no_time[0])
	.replace(/\$show_set_date/g,show_date);
	
	$.facebox('<div class="box-content">'+form_facebox+'</div><div class="footer-box" style="display: block;"></div>');
	$('.footer-box').empty();
	$('.footer-box').append(' <a class="close" name="close" id="close-facebox" >Close</a>');
	initSubmitAcc();
	if($(this_obj).parent().find('.days_before_surgery').val() == '0'){
		fillDate(0,'.datepicker_pelaksanaan_op','.datepicker_daftar_inap');
		$("form .datepicker_daftar_inap").datepicker('destroy');
		fillDate(0,'.datepicker_daftar_inap','.datepicker_cek_kamar');
		$("form .datepicker_cek_kamar").datepicker('destroy');
	}else{
		initChangeRegister();
		fillDate((-1*$('#days_before_surgery').val()),'.datepicker_pelaksanaan_op','.datepicker_daftar_inap');
		$('input.datepicker_daftar_inap').change();
		if(tgl_pelaksanaan_no_time[0] == perkiraan_operasi){
			$('form .datepicker_daftar_inap').datepicker('setDate',reg_ranap);
		}
	}
	
	
	$('#close-facebox').click(function(){$(this).trigger('close.facebox');})
	$('#facebox_overlay').click(function(){$(this).trigger('close.facebox');});
	return;
}

function initChangeRegister(){
	$('input.datepicker_daftar_inap').change(function(){
		fillDate(-1,'.datepicker_daftar_inap','.datepicker_cek_kamar');
	});
	return;
}

function initSubmitAcc(){
	$('#submit-btn').click(function(){
		var closest_node = $(this).closest('form#perjanjian');
		var tgl_cek_kamar = closest_node.find('#tgl_cek_kamar').val();
		var tgl_reg_ranap = closest_node.find('#tgl_reg_ranap').val();
		if(tgl_cek_kamar == '' || tgl_reg_ranap == ''){
			alert('tidak boleh ada field yang kosong');
			return false;
		}
		if(confirm('Sudah set tanggal operasi dan ruang ok dengan benar?')){
			//proses ajax
			$.ajax({
				  url: BASEURL+"/registrasi/operasi_terjadwal/form_konfirmasi_ibs/",
				  dataType: "json",
				  type: "POST",
				  data: {
					no_perjanjian: closest_node.find('#no_perjanjian').val(),
					tgl_pelaksanaan: closest_node.find('#tgl_pelaksanaan').val(),
					days_before_surgery: closest_node.find('#days_before_surgery').val(),
					ruang_ok: closest_node.find('#ruang_ok').val(),
					tgl_cek_kamar: tgl_cek_kamar,
					tgl_reg_ranap: tgl_reg_ranap
				  },
				  success: function(data) {
					alert('Pasien berhasil dijadwalkan');
					$('#facebox_overlay').trigger( "click" );
					$( ".ajax_refresh_and_loading" ).trigger( "click" );
				  }
		   });
		}
	})
	return;
}

function fillDate(day,date_param,date_filled){
	//calculate
	var myd = new Date(getDateFromFormat($('form '+date_param).val(),'dd-MM-yyyy'));
	myd.setDate(myd.getDate() + day);
	var tgl_fill = ("0" + myd.getDate()).slice(-2) + '-'+ ("0" + (myd.getMonth() + 1)).slice(-2) + '-'+ myd.getFullYear();
	//fill
	//console.log(tgl_fill);
	$('form '+date_filled).val(tgl_fill);
	//initdatepicker
	start_date = '+1d';
	if(date_filled == '.datepicker_daftar_inap')
		start_date = '+1d';
	if(date_filled != '.datepicker_cek_kamar'){
		$("form "+date_filled).datepicker('destroy');
		$("form "+date_filled).datepicker({format: date_format, autoclose: true, startDate:start_date, endDate:tgl_fill, language: 'id',orientation:'bottom'});
	}
	return;
}

var form_in_facebox = `<fieldset><legend>JADWAL OPERASI</legend>
	<form method="post" role="form" class="form-horizontal well" id="perjanjian">
	<fieldset>
			<div class="form-group">
				<label class="col-sm-2 control-label">Pasien</label>
				<div class="col-sm-8">$pasien</div>
				<div class="hide">
					<input id="no_perjanjian" value="$no_perjanjian">
					<input id="tgl_pelaksanaan" value="$tgl_pelaksanaan">
					<input id="ruang_ok" value="$ruang_ok">
					<input id="days_before_surgery" value="$days_before_surgery">
					<input class="datepicker_pelaksanaan_op" value="$no_time_tgl_pelaksanaan">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Waktu Operasi</label>
				<div class="col-sm-8">$tgl_pelaksanaan</div>
			</div>
			<div class="$show_set_date">
			<div class="form-group">
				<label class="col-sm-8" style="font-style: italic;font-weight: normal;">Pasien Harus Registrasi Inap $days_before_surgery Hari Sebelum Tindakan Operasi</label>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Tgl Daftar Ranap <span style="color:#F00;" class="help-inline">*</span></label>
				<div class="col-sm-2 div-datepicker_daftar_inap"><input type="text" id="tgl_reg_ranap" class="form-control datepicker_daftar_inap required" readonly></div>
				<span class="col-sm-8" style="font-style: italic;">harap ganti tgl daftar jika tgl yg tertera adalah hari libur</span>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Tgl Cek Kamar <span style="color:#F00;" class="help-inline">*</span></label>
				<div class="col-sm-2 div-datepicker_cek_kamar"><input type="text" class="form-control datepicker_cek_kamar required" readonly id="tgl_cek_kamar"></div>
			</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2"></div>
				<div class="col-sm-2"><input type="button" id="submit-btn" class="btn btn-primary submit" value="save"></div>
			</div>
		</fieldset>
	</form></fieldset>
	`;
</script>