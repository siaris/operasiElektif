<div id="ow-server-footer">
<?php if (is_auth('irin_operasi_terjadwal', 'irin_input_data_operasi_terjadwal')){?>
	<a href="<?php echo site_url('registrasi/irin_operasi_terjadwal/irin_input_data_operasi_terjadwal/');?>" class="col-xs-4 col-sm-2 btn-default text-center"><i class="icon-folder-close icon-jumbo"></i><br><b>Daftarkan Pasien</b></a>
<?php }?>
<?php if (is_auth('irin_operasi_terjadwal', 'irin_input_data_operasi_terjadwal')){?>
	<a href="<?php echo site_url('registrasi/irin_operasi_terjadwal/irin_input_data_operasi_terjadwal/cito');?>" class="col-xs-4 col-sm-2 btn-default text-center"><i class="icon-folder-close icon-jumbo"></i><br><b>Operasi Cito</b></a>
<?php }?>
<?php if (is_auth('irin_operasi_terjadwal', 'irin_list_pasien')){?>
	<a href="<?php echo site_url('registrasi/irin_operasi_terjadwal/irin_list_pasien/');?>" class="col-xs-4 col-sm-2 btn-default text-center"><i class="icon-folder-close icon-jumbo"></i><br><b>List Terjadwal</b></a>
<?php }?>
<?php if (is_auth('irin_operasi_terjadwal', 'irin_print_jadwal_pasien')){?>
	<a href="<?php echo site_url('registrasi/irin_operasi_terjadwal/irin_print_jadwal_pasien/');?>" class="col-xs-4 col-sm-2 btn-default text-center"><i class="icon-folder-close icon-jumbo"></i><br><b>Print Jadwal Besok</b></a>
<?php }?>
<?php if (is_auth('irin_operasi_terjadwal', 'cari_jadwal')){?>
	<a href="<?php echo site_url('registrasi/irin_operasi_terjadwal/cari_jadwal/');?>" class="col-xs-4 col-sm-2 btn-default text-center"><i class="icon-folder-close icon-jumbo"></i><br><b>Cari Jadwal</b></a>
<?php }?>
</div>