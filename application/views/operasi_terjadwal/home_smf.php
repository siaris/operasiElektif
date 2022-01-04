<div id="ow-server-footer">
<?php if (is_auth('operasi_terjadwal', 'input_data_operasi_terjadwal')){?>
	<a href="<?php echo site_url('registrasi/operasi_terjadwal/input_data_operasi_terjadwal/');?>" class="col-xs-4 col-sm-2 btn-default text-center"><i class="icon-folder-close icon-jumbo"></i><br><b>Daftarkan Pasien</b></a>
<?php }?>
<?php if (is_auth('operasi_terjadwal', 'smf_list_pasien')){?>
	<a href="<?php echo site_url('registrasi/operasi_terjadwal/smf_list_pasien/');?>" class="col-xs-4 col-sm-2 btn-default text-center"><i class="icon-folder-close icon-jumbo"></i><br><b>List Terjadwal</b></a>
<?php }?>
</div>