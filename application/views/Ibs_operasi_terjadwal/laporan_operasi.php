
<div class="col-xs-12 col-sm-12">
  <div class="box">
    <div class="box-content">
      <h3 class="page-header">Laporan</h3>
      <form name="form_search" class="form-horizontal" method="post" action="">
       <div class="row">
  
  <div class="col-xs-6">
    <div class="form-group">
      <label class="col-sm-3 control-label">
        Perkiraan Tgl Operasi
      </label>
      <div class="col-sm-3">
        <input type="text" name="tgl1" id="tgl1" placeholder="Dari" autocomplete="off" class="form-control" />
      </div>
      
      <div class="col-sm-3">
        <input  type="text" name="tgl2" id="tgl2" class="form-control" placeholder="Sampai">
      </div>
    </div>
    <button class="btn btn-primary" type="submit" name="submit" value="save" style="margin-bottom:10px; margin-left:180px;">
      View
    </button>   
    
  </div>
</div>
  
</form>
<?php if(!empty($data2['tgl1'])) { 
  echo '<b>Periode '.$data2['tgl1'].' s/d '.$data2['tgl2'].'</b>'; } 
  else {echo '<b>Periode '.date('d-m-Y').'</b>';} ?>
<table class="table table-bordered table-condensed table-fixed-header" style="font-size:12px;" id="tblExport">
  <thead class="header">
   
  <tr>
     <th><center>No</center></th>
     <th><center>No. RM</center></th>
     <th><center>No. Kontak Pasien</center></th>
     <th><center>Diagnosa</center></th>
     <th><center>Tindakan</center></th>
     <th><center>Operator</center></th>
     
     <th><center>Perkiraan Lama Operasi</center></th>
     <th><center>Kebutuhan ICU</center></th>
     <th><center>Asal Poli</center></th>
     <th><center>Tgl Cek Kamar</center></th>
     <th><center>Perkiraan Tgl Operasi</center></th>
    </tr>
        
    <?php
if(!empty($data)){
	$no=0;
	foreach($data as $key=>$values)
	{
	$no=$no+1;
	
	echo '
    <tr>
	   <td><center>'.$no.'</center></td>
     <td>'.$values['nama_pasien'].'</td>
     <td>'.$values['no_kontak_pasien'].'</td>
     <td>'.$values['diagnosa'].'</td>
     <td>'.$values['tindakan'].'</td>
     <td>'.$values['nama_pegawai'].'</td>
     
     <td>'.$values['desc_lama_op'].'</td>
     <td>'.$values['is_need_icu'].'</td>
     <td>'.$values['nama_poli'].'</td>
     <td>'.display_date($values['tgl_cek_kamar']).'</td>
     <td>'.display_date($values['tgl_pelaksanaan']).'</td>
     </tr>';
   }
}
   ?>
   
  </thead>
</table>
<button class="btn btn-primary" type="submit" name="submit" id="btnExport" style="margin-bottom:10px;">Excel</button>
</div>
</div>
</div>

<script src="<?php echo base_url('assets/js/bootstrap-typeahead.js');?>"></script>

<script>
$(document).ready(function() {

	//var extraFilter = '<label>Poli: <select name="poli"></select></label><label>Tanggal: <input type="text" name="tgl" /></label>';
	//$('.extra-filter').attr('id', 'extra-filter').html(extraFilter);
      
	$('#instalasi').change(function() {
      $.ajax({
         url: "<?php echo site_url('master/poli/dropdown')?>/" + $(this).val(),
         dataType: "json",
         type: "GET",
         success: function(data) { //
            addOption($('#smf'), data, 'id_poli', 'nama_poli');

         }
      });
   });
   
   function addOption(ele, data, key, val) { //alert(data.length);
   $('option', ele).remove();
  
   ele.append(new Option('', 9999));
   $(data).each(function(index) { //alert(eval('data[index].' + nama));
      ele.append(new Option(eval('data[index].' + val), eval('data[index].' + key)));
	 
   });
}
   
});

</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#btnExport").click(function () {
            $("#tblExport").btechco_excelexport({
                containerid: "tblExport"
               , datatype: $datatype.Table
            });
        });
    });
</script>

	<script type="text/javascript">
	$('#tgl1').datepicker({"format": "dd-mm-yyyy", "weekStart": 1, "autoclose": true});
	$('#tgl2').datepicker({"format": "dd-mm-yyyy", "weekStart": 1, "autoclose": true});
</script>


<script type="text/javascript">	
 $('#namadokter').typeahead({
            source: function(typeahead, query) {
               $.ajax({
                  url: "<?php echo site_url('master/pegawai/typeahead');?>",
                  dataType: "json",
                  type: "POST",
                  data: {
                      max_rows: 15,
                      q: query,
                  },
                  success: function(data) {
                      var return_list = [], i = data.length;
                      while (i--) {
                          return_list[i] = {id: data[i].id, value: data[i].id_pegawai + ' - ' + data[i].nama, id_pegawai: data[i].id_pegawai,nama_pegawai: data[i].nama, prosentase: data[i].prosentase};
                      }
                      typeahead.process(return_list);
                  }
               });
            },
            onselect: function(obj) {
			   $('#kode_dokter').val(obj.id_pegawai);
			   $('#namadokter').val(obj.nama_pegawai);
			   
            },
            items: 15
         });
</script>


<script type="text/javascript">	
 $('#namapegawai').typeahead({
            source: function(typeahead, query) {
               $.ajax({
                  url: "<?php echo site_url('master/pegawai/typeahead_pegawai');?>",
                  dataType: "json",
                  type: "POST",
                  data: {
                      max_rows: 15,
                      q: query,
                  },
                  success: function(data) {
                      var return_list = [], i = data.length;
                      while (i--) {
                          return_list[i] = {id: data[i].id, value: data[i].id_user + ' - ' + data[i].nama, id_user: data[i].id_user,nama_pegawai: data[i].nama, prosentase: data[i].prosentase};
                      }
                      typeahead.process(return_list);
                  }
               });
            },
            onselect: function(obj) {
			   $('#kodepegawai').val(obj.id_user);
			   $('#namapegawai').val(obj.nama_pegawai);
			   
            },
            items: 15
         });
</script>
<script type="text/javascript">	
if ($('#jenis_cara_bayar').val() != 1) {
      $('.jaminan_form').hide();
   }
   $('.hubungan_keluarga').hide();
   if ($('#jenis_cara_bayar').val() == 2) {
      $('.jaminan_form').show();
   }
    
   $('#jenis_cara_bayar').change(function() {
      var klp = $(this).val();
      $.ajax({
         url: "<?php echo site_url('master/carabayar/dropdown')?>/" + $(this).val(),
         dataType: "json",
         type: "GET",
         success: function(data) { //
            addOption($('#cara_bayar'), data, 'kd_bayar', 'cara_bayar');
            var val = '';
            if (klp == 0) val = '001';
            else if (klp == 3) val = '008';
            $('#cara_bayar').select2('val', val);
         }
      });
      
    $('.hubungan_keluarga').hide();
      if (klp == 0) { // Tunai
         $('#cara_bayar').select2('val', '001');
         $('.jaminan_form').hide();
      } else if (klp == 1) {
         $('.jaminan_form').show();
      } else if (klp == 2) {
         $('.jaminan_form').show();
      } else if (klp == 3) {
         $('#cara_bayar').select2('val', '008');
         $('.jaminan_form').hide();
      }
      
   });
</script>


<?php layout_header(array(
   'js' => 'bootstrap-datepicker.js',
   'css' => 'datepicker.css',
)); ?>