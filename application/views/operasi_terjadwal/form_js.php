<script>
$(document).ready(function() {
	$("form .datepicker_pelaksanaan_op").datepicker({format: time_format, autoclose: true, startDate:'<?= $date_limit_op?>'});
	$("form .datepicker_op").datepicker({format: time_format, autoclose: true, startDate:'<?= $date_limit?>'})
});

function cari_pasien(){
	$('form#perjanjian #nama_pelanggan').typeahead({
			source: function(typeahead, query) {
				if(!doCheckMaxLength('form#perjanjian #nama_pelanggan'))return;
				$.ajax({
				  url: BASEURL+"/master/pasien/typeahead_adm_op_terjadwal/",
				  dataType: "json",
				  type: "POST",
				  data: {
					  max_rows: max_rows_autosuggest,
					  q: query
				  },
				  success: function(data) {
						//if(data != null)
							typeahead.process(display_return_typeahead(data,'pasien'));
				  }
			   });
			},
			minLength: 3,
			onselect: function(obj) {
				select_autosuggest(obj,'pasien');
			},
			items: max_rows_autosuggest
		 });
}


function select_autosuggest(obj,prefix){
	if(prefix=='pasien'){
		$('form#perjanjian #no_rm').val(obj.id);
		$('form#perjanjian #nama_pelanggan').val(obj.nama);
	    $('form#perjanjian #tgl_lahir').val(obj.tanggal_lahir);
	    $('form#perjanjian #alamat').val(obj.alamat);
	    $('form#perjanjian #jenis_kelamin').val(obj.jenis_kelamin);
	    $('form#perjanjian #no_kontak_pasien').val(obj.no_telpon);
	    $('form#perjanjian #kelas_saat_ini').val(obj.kelas);
	}else{
		$('form#perjanjian #kode_dokter').val(obj.id_pegawai);
	    $('form#perjanjian #nama_dokter').val(obj.nama_pegawai);
	}
	return;
}

function display_return_typeahead(data,prefix){
	if (prefix == 'pasien'){
		 var return_list = [], i = data.length;
		  while (i--) {
			  return_list[i] = {id: data[i].id, value: data[i].no_rm + ' - ' + data[i].nama, no_rm: data[i].no_rm,nama: data[i].nama,type_pasien:'pasien',
			  
			  no_telpon: data[i].no_telpon === '' ?data[i].no_hp:data[i].no_telpon+' / '+data[i].no_hp, 
			  
			  alamat: data[i].alamat_jalan, tanggal_lahir: data[i].tgl_lhr_frmted,kelas: data[i].kelas, jenis_kelamin: data[i].jenis_kelamin == 'P'?'Perempuan':'Laki-laki'};
		  }
	}else{
		var return_list = [], i = data.length;
		  while (i--) {
			  return_list[i] = {id: data[i].id, value: data[i].id_pegawai + ' - ' + data[i].nama, id_pegawai: data[i].id_pegawai,nama_pegawai: data[i].nama, prosentase: data[i].prosentase};
		  }
	}	
	return return_list;
}

function cari_diagnosa_op(obj_sel){
	$(obj_sel).typeahead({
		source: function(typeahead, query) {
			get_source(typeahead, query, "/pemetaandiagnosa/typeahead/");
		},
		onselect: function(obj) {
			$(obj_sel).next("input.kode_icd10").val(obj.id);
			$(obj_sel).val(obj.topik);
		},
		matcher: function () { return true; },
		items: max_rows_autosuggest
	});
}

function cari_icd9_op(obj_sel){
	$(obj_sel).typeahead({
		source: function(typeahead, query) {
			get_source(typeahead, query, "/pemetaan/typeahead/");
		},
		onselect: function(obj) {
			$(obj_sel).next("input.kode_icd9").val(obj.id);
			$(obj_sel).val(obj.topik);
		},
		matcher: function () { return true; },
		items: max_rows_autosuggest
	});
}
</script>