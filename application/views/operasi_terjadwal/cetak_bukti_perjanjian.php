<style>
    table {
      width: 100px;
    }
    td.kepala {
      font-size: 25px;
    }
    
    td.isi {
      font-size: 13px;
    }
  
  #kotak {
    border: 2px solid #a1a1a1;
    padding: 10px 40px; 
    background: #F9F9F9;
    width: 850px;
  }</style>
<div><?$D = $data[0]; $DJ = json_decode($D['catatan_pra_operasi'],true);?>
<? $ttl = ($DJ['tipe_op'] == 'ELEKTIF-CITO')?'CITO':'';?>
<?php foreach($profile as $key => $rest) {?>
<table class="table">
  <tr>
    <th><img src="<?php echo base_url('assets/img/logo_d.png');?>" width="63" style="margin-right:50px;" /></th>
    <th><?= $rest['rs_nama']; ?><br /><?= $rest['rs_alamat']; ?><br /><?= $rest['telepon']; ?></th>
    <td width="30%"></td>
    <th>&nbsp;</th>
  </tr>
  <th width="20px" align="left"></th>
</table>
<?php } ?>
</div>
<?php foreach($data as $key => $ja) :?>
<div id="kotak">
  <table class="table table-condensed" border="0">
    <tr>
		<td colspan="6" class="kepala"><center>PERMINTAAN JADWAL OPERASI <?= $ttl?></center><br /></td>
    </tr>
    <tr>
    <td width="16%">Nomor RM / Nama</td>
    <td width="2%">:</td>
    <td width="40%"><?= $data[0]['no_rm'] .' / '.ucwords($ja['nama'])?></td>
    <td width="10%">No. Perjanjian</td><td width="2%">:</td><td><?=$ja['no_perjanjian_operasi'];?></td>
    </tr>
	<tr>
    <td>Tgl Lahir</td>
    <td width="2%">:</td>
    <td class="isi"><?= display_date($ja['tanggal_lahir'])?></td>
    <td width="10%"></td><td width="2%"></td><td></td>
    </tr>
	<tr>
    <td>Alamat</td>
    <td>:</td>
    <td class="isi">
      <?=$ja['alamat_jalan']?><br />
      <?=isset($ja['nama_kota'])?$ja['nama_kota']:''?>
    </td>
	<td width="10%"></td><td width="2%"></td><td></td>
    </tr>
	<tr>
    <td>Telpon</td>
    <td>:</td>
    <td class="isi" colspan="4"><?=$ja['no_kontak_pasien']?></td>
    </tr>
    <tr>
    <td width="16%">Asal Poli</td>
    <td width="2%">:</td>
    <td width="40%" class="isi"><?= $data[0]['nama_poli']?></td>
    <td width="20%"></td><td width="2%"></td><td></td>
    </tr>
	<tr>
    <td width="16%">Diagnosa</td>
    <td width="2%">:</td>
    <td width="40%" class="isi"><?= $data[0]['diagnosa']?></td>
    <td width="10%">Tindakan</td><td width="2%">:</td><td><?= $data[0]['tindakan']?></td>
    </tr>
	<tr>
    <td width="16%">Rencana Pembiusan</td>
    <td width="2%">:</td>
    <td width="40%" class="isi"><?= $data[0]['rencana_pembiusan']?></td>
    <td width="20%"></td><td width="2%"></td><td></td>
    </tr>
	<tr>
	<td width="16%">Perkiraan Lama Operasi</td>
    <td width="2%">:</td>
    <td width="40%" class="isi"><?= $data[0]['desc_lama_op']?></td>
    <td width="20%"></td><td width="2%"></td><td></td>
    </tr>
	<tr>
	<td width="16%">Tgl Operasi</td>
    <td width="2%">:</td>
    <td width="40%" class="isi"><?= indonesia_hari(display_date($data[0]['tgl_pelaksanaan'],'N'))?> / <?= display_date($data[0]['tgl_pelaksanaan'])?></td>
    <td width="20%"></td><td width="2%"></td><td></td>
    </tr>
	<tr>
	<td width="16%">Tgl Daftar Kamar Inap</td>
    <td width="2%">:</td>
    <td width="40%" class="isi"><?= indonesia_hari(display_date($data[0]['tgl_cek_kamar'],'N'))?> / <?= display_date($data[0]['tgl_cek_kamar'])?></td>
    <td width="20%"></td><td width="2%"></td><td></td>
    </tr>
  </table>
</div>
<?php endforeach; ?>
<table class="table table-condensed">
<tr>
  <td>
    Cetak Oleh: <?=$this->auth->get_user_name();?>
  </td>
  <td width="60%"></td>
  <td align="right">
     Jakarta, <?= date('d-m-Y')?> <br />Operator yang meminta
	 <br /><br />
	 <br /><br />
	 
     (<?=$ja['nama_pegawai']?>)
  </td>
 </tr>
</table>