<script>

var templt = {
<?foreach($templt as $t){?>
	'<?= $t['id']?>':`<?= strip_tags(str_replace('<br />','\n',$t['template']))?>`
<?}?>
}
var c_t = 159


function doADMFormSMS(this_obj){
	//munculin facebox
	$.facebox.settings.overlay = 'false';
	val = $(this_obj).closest('tr').find('td').map(function() {return $(this).text().trim();}).get()
	var form_value = {
		pasien : val[1],
		no_perjanjian : val[0],
		tgl_registrasi : val[8].split('>>')[1],
		tgl_pelaksanaan : val[4],
		operator : val[7],
		no_hp : val[2]
	}
	var form_facebox = histori = ''
	$.ajax({
			url: BASEURL+"/registrasi/adm_ri_operasi_terjadwal/histori_sms/"+form_value.no_perjanjian,
			dataType: "json",
			type: "GET",
			data: {},
			success: function(data) {
					for (d of data) {
						histori += '<tr>'+'<td>1</td><td>'+d.nohp+'</td><td>'+d.send_date+'</td><td>'+d.content+'</td><td>'+d.status+'</td></tr>'
					}
				  }
		}).then(function(){
			form_facebox = form_in_facebox
			.replace(/\$no_perjanjian/g,form_value.no_perjanjian)
			.replace(/\$pasien/g,form_value.pasien)
			.replace(/\$tgl_registrasi/g,form_value.tgl_registrasi)
			.replace(/\$tgl_pelaksanaan/g,form_value.tgl_pelaksanaan)
			.replace(/\$operator/g,form_value.operator)
			.replace(/\$no_hp/g,form_value.no_hp)
			.replace(/\$c_t/g,c_t)
			.replace(/\$histori/g,histori)
			
			$.facebox('<div class="box-content">'+form_facebox+'</div><div class="footer-box" style="display: block;"></div>');
			$('.footer-box').empty();
			$('.footer-box').append(' <a class="close" name="close" id="close-facebox" >Close</a>');
			
			$('#close-facebox').click(function(){$(this).trigger('close.facebox');})
			$('#facebox_overlay').click(function(){$(this).trigger('close.facebox');});
			initUpdateTextPesan();
			initKirimSMS();
			initMSGKeyup();
		});
	
	return;
	
}

function initMSGKeyup(){
	$('#message').keyup(function(){
		$('#c_t').html(c_t-($(this).val().length))
		return
	})
}

function initUpdateTextPesan(){
	$('#update-btn').click(function(){
		this_obj = $(this)
		if($('#temp_sms').val() !== ''){
			if(confirm('Isi pesan akan diganti?')){
				//load text message
				//replace
				var text_msg_raw = templt[$('#temp_sms').val()]
				.replace(/\{{elek_nama}}/g,this_obj.closest('form#perjanjian').find('#elek_nama').val())
				.replace(/\{{elek_tgl_reg}}/g,this_obj.closest('form#perjanjian').find('#elek_tgl_reg').val())
				.replace(/\{{elek_day}}/g,this_obj.closest('form#perjanjian').find('#elek_day').val())
				.replace(/\{{elek_jam_reg}}/g,this_obj.closest('form#perjanjian').find('#elek_jam_reg').val())
				.replace(/\{{elek_phone_konfirm}}/g,this_obj.closest('form#perjanjian').find('#elek_phone_konfirm').val())
				//hitung karakter
				//jika lebih dari 150, potong karakter ke 150 keatas
				if(text_msg_raw.length > 159){
					text_msg_raw = text_msg_raw.substring(0, 159)
				}
				//fill di text area
				$('#message').val(text_msg_raw)
				$('#message').keyup()
			}
		}
	})
	return;
}

function initKirimSMS(){
	$('#submit-btn').click(function(){
		var closest_node = $(this).closest('form#perjanjian');
		if(confirm('Isi pesan sudah diisi dengan benar?')){
			$(this).addClass('disabled');
			//proses ajax
			$.ajax({
				  url: BASEURL+"/registrasi/adm_ri_operasi_terjadwal/send_pesan/",
				  dataType: "json",
				  type: "POST",
				  data: {
					no_elek: closest_node.find('#no_perjanjian').val(),
					nomor_tujuan: closest_node.find('#no_hp').val(),
					isi_pesan: closest_node.find('#message').val(),
					id_pengirim: closest_node.find('#id_pengirim').val()
				  },
				  success: function(data) {
					$(this).removeClass('disabled');
					$('#facebox_overlay').trigger( "click" );
				  }
		   });
		}
	})
	return
}

var form_in_facebox = `<fieldset><legend>KIRIM SMS</legend>
	<form method="post" role="form" class="form-horizontal well" id="perjanjian">
	<fieldset>
			<fieldset>
				<legend>VARIABEL PESAN</legend>
			<div class="form-group">
				<label class="col-sm-2 control-label">Pasien</label>
				<div class="col-sm-8"><input value="$pasien" class="form-control" id="elek_nama"></div>
				<div class="hide">
					<input id="no_perjanjian" value="$no_perjanjian">
					<input id="id_pengirim" value="`+PrimaSession.user_id+`">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Tgl Registrasi</label>
				<div class="col-sm-8"><input value="$tgl_registrasi" class="form-control" id="elek_tgl_reg"></div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">elek_day</label>
				<div class="col-sm-8"><input value="Pagi" class="form-control" id="elek_day"></div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">elek_jam_reg</label>
				<div class="col-sm-8"><input value="7:00" class="form-control" id="elek_jam_reg"></div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">elek_phone_konfirm</label>
				<div class="col-sm-8"><input value="021 47866666" class="form-control" id="elek_phone_konfirm"></div>
			</div>
			</fieldset>
			<div class="form-group">
				<label class="col-sm-2 control-label">Tgl Operasi</label>
				<div class="col-sm-8">$tgl_pelaksanaan</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Operator</label>
				<div class="col-sm-8">$operator</div>
			</div>
			<fieldset>
				<legend>FORM PESAN</legend>
			<div class="form-group">
				<label class="col-sm-2 control-label">Template SMS</label>
				<div class="col-sm-8"><select id="temp_sms"><option value=""></option>
				<?foreach($templt as $t){
					echo '<option value="'.$t['id'].'">'.$t['jenis'].'</option>';
				}?>
				</select></div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Pengirim SMS</label>
				<div class="col-sm-8">`+PrimaSession.name+`</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label">Nomor Tujuan(pisahkan dgn '/')<span style="color:#F00;" class="help-inline">*</span></label>
				<div class="col-sm-10">
				<input type="text" class="form-control required" id="no_hp" value="$no_hp">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Pesan<span style="color:#F00;" class="help-inline">*</span></label>
				<div class="col-sm-4">
				<textarea class="form-control" id="message" id="message" placeholder="" rows="10" cols="600" maxlength="159" style="width: 574px; height: 210px;"></textarea>
				</div>
			</div>
			<div class="form-group">
			<div class="col-sm-2"></div>
				<div class="col-sm-4"><span id="c_t">$c_t</span></div>
			</div>
			</fieldset>
			<div class="form-group">
				<div class="col-sm-2"></div>
				<div class="col-sm-4"><input type="button" id="update-btn" class="btn btn-primary update" value="UPDATE PESAN SESUAI VARIABEL"></div>
				<div class="col-sm-2"><input type="button" id="submit-btn" class="btn btn-success submit" value="KIRIM"></div>
			</div>
			<div class="form-group">
				<table class="table table-bordered">
					<tr>
						<td>No</td>
						<td>Tujuan</td>
						<td>Waktu Kirim</td>
						<td>Pesan</td>
						<td>Status</td>
					</tr>
					$histori
				</table>
			</div>
		</fieldset>
	</form></fieldset>
	`;
	</script>