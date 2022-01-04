<div id="ow-server-footer">
	<?php if (is_auth('operasi_terjadwal', 'index_smf')){?>
		<a href="<?php echo site_url('registrasi/operasi_terjadwal/index_smf/');?>" class="col-xs-4 col-sm-2 btn-default text-center"><i class="icon-folder-close icon-jumbo"></i><br><b>POLI</b></a>
    <?php }?>
	<?php if (is_auth('operasi_terjadwal', 'index_irin')){?>
		<a href="<?php echo site_url('registrasi/operasi_terjadwal/index_irin/');?>" class="col-xs-4 col-sm-2 btn-default text-center"><i class="icon-folder-close icon-jumbo"></i><br><b>IRIN</b></a>
    <?php }?>
	<?php if (is_auth('operasi_terjadwal', 'index_ibs')){?>
		<a href="<?php echo site_url('registrasi/operasi_terjadwal/index_ibs/');?>" class="col-xs-4 col-sm-2 btn-default text-center"><i class="icon-folder-close icon-jumbo"></i><br><b>IBS</b></a>
    <?php }?>
	<?php if (is_auth('operasi_terjadwal', 'index_adm_ri')){?>
		<a href="<?php echo site_url('registrasi/operasi_terjadwal/index_adm_ri/');?>" class="col-xs-4 col-sm-2 btn-default text-center"><i class="icon-folder-close icon-jumbo"></i><br><b>ADM Ranap</b></a>
    <?php }?>
	<?php if (is_auth('operasi_terjadwal', 'index_adm_igd')){?>
		<a href="<?php echo site_url('registrasi/operasi_terjadwal/index_adm_igd/');?>" class="col-xs-4 col-sm-2 btn-default text-center"><i class="icon-folder-close icon-jumbo"></i><br><b>ADM POLI/IGD</b></a>
    <?php }?>
</div>