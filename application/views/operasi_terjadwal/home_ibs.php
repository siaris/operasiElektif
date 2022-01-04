<div id="ow-server-footer">
<?php if (is_auth('ibs_operasi_terjadwal', 'ibs_list_pasien')){?>
	<a href="<?php echo site_url('registrasi/ibs_operasi_terjadwal/ibs_list_pasien/');?>" class="col-xs-4 col-sm-2 btn-default text-center"><i class="icon-folder-close icon-jumbo"></i><br><b>List Terjadwal</b></a>
<?php }?>
<?php if (is_auth('ibs_operasi_terjadwal', 'ibs_list_pasien')){?>
	<a href="<?php echo site_url('registrasi/ibs_operasi_terjadwal/ibs_list_pasien/cito');?>" class="col-xs-4 col-sm-2 btn-default text-center"><i class="icon-folder-close icon-jumbo"></i><br><b>CITO Operasi</b></a>
<?php }?>
<?php if (is_auth('ibs_operasi_terjadwal', 'frm_jadwal_pasien')){?>
	<a href="<?php echo site_url('registrasi/ibs_operasi_terjadwal/frm_jadwal_pasien/');?>" class="col-xs-4 col-sm-2 btn-default text-center"><i class="icon-folder-close icon-jumbo"></i><br><b>Set Jadwal Besok</b></a>
<?php }?>
<?php if (is_auth('ibs_operasi_terjadwal', 'print_jadwal_pasien')){?>
	<a href="<?php echo site_url('registrasi/ibs_operasi_terjadwal/print_jadwal_pasien/');?>" class="col-xs-4 col-sm-2 btn-default text-center"><i class="icon-folder-close icon-jumbo"></i><br><b>Print Jadwal Besok</b></a>
<?php }?>
</div>