<meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>
<section id="content">
<main class="main">
	<div class="box-content">
		<fieldset><legend><?= $title?></legend>
		<form method="post" role="form" class="form-horizontal well" id="perjanjian">
			<div style="display:<?= $show_except_komen?>;">
			<input type="hidden" name="no_perjanjian" value="<?=$profile['no_perjanjian_operasi']?>">
			<input type="hidden" name="status_to" value="<?= $status_to?>">
			<input type="hidden" name="status_from" value="<?=$profile['status']?>">
			<input type="hidden" name="days_before_surgery" value="<?=$profile['days_before_surgery']?>">
			<div class="form-group">
				<label class="col-sm-2 control-label">Tgl Perkiraan Operasi <span style="color:#F00;" class="help-inline">*</span></label>
				<div class="col-sm-1"><input type="text" class="form-control datepicker_pelaksanaan_op required" name="tgl_pelaksanaan" value="<?= display_date($profile['tgl_pelaksanaan'])?>"></div>
				<?if(!empty($profile['no_pj_carter'])){?>
				<div class="col-sm-2">Lakukan Batal Carter, Nomor : <?= $profile['no_pj_carter']?></div>	
				<?}?>
			</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Alasan <?= $front_title?><span style="color:#F00;" class="help-inline">*</span></label>
				<div class="col-sm-2"><textarea class="form-control required" name="alasan" class="required"></textarea></div>
			</div>
			<?if(isset($additional_field)) echo $additional_field;?>
			<div class="form-group">
				<div class="col-sm-2"></div>
				<div class="col-sm-2"><input type="submit" class="btn btn-primary" value="save">&nbsp;<a href="<?= BASEURL."/registrasi/operasi_terjadwal/"?>" class="btn btn-primary bg-warning" id="adj_negatif">Back</a></div>
			</div>
		</form>
	</div>
</main>
</section>