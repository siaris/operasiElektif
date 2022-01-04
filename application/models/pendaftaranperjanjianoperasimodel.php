<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH."models/pustakatabelmodel.php";

class PendaftaranPerjanjianOperasiModel extends PustakaTabelModel {
  
	public function __construct() {
        parent::inisiasi_tabel();
		$this->tableName = $this->janji_operasi;
		$this->primaryKey = 'id';
		$this->resultMode = 'array';
		$this->day_before = '7';
		$this->day_after = '60';
		$this->separatorRute = '<br />';
    }
	
	public function findNoJanjian() {
		$this->load->helper('str');
		$this->tableName = $this->all_seq;
		$conditions = "tgl like '".date('Y-m-d')."%' and peruntukkan = ''";
		$this->do_empty_fields();
		$no_urut = $this->queryOne($conditions, 'seq', 'id DESC');		
		$this->save(array('tgl'=>date('Y-m-d'),'seq'=>$no_urut+1,'peruntukkan'=>''));
		$no_urut = setNumeric(3, $no_urut+1);
		$this->tableName = $this->janji_operasi;
		$this->do_empty_fields();
		return 'JO'.date("ymd").$no_urut; 
	}
	
	public function base_find($filter_arr = array()){
		if(!empty($filter_arr)){
			$filter = $filter_arr;
		}
		$fields = array(
			$this->tableName.'.*',
			$this->tabel_master_pasien.'.nama',
			$this->tabel_master_pasien.'.tanggal_lahir',
			$this->tabel_master_pasien.'.alamat_jalan',
			$this->master_pegawai.'.nama nama_pegawai',
			'CAST('.$this->tableName.'.ruang_ok AS UNSIGNED) AS urutan',
			'if(poli_asal<>300,nama_poli,mrr.nama_ruang) as nama_poli',
			'NM_ICD9CM',
			'topik',
			'ref_status.uraian desc_status',
			'nama_kelas',
			'catatan_pra_operasi json_pra_operasi',
			'pendaftaran_carteran.kelas kelas_carter',
			'pendaftaran_carteran.ruang_rawat',
			'IFNULL(pasien.no_kartu_jaminan,pendaftaran.no_peserta_jaminan) AS no_peserta_jaminan',
			'mastercr_ruang_rawat.id_gedung',
			'lama_op.uraian desc_lama_op',
			'r_ok.uraian_json json_r_ok',
			'master_cara_bayar.cara_bayar cara_bayar',
			'if(poli_asal = 300,"",tgl_cek_kamar) as tgl_cek_kamar',
			'if(pendaftaran_perjanjian_operasi.status>1,mastercr_ruang_rawat.nama_ruang,"") as nama_ruang',
			'if(pendaftaran_perjanjian_operasi.status>1,mastercr_no_kamar.no_kasur,"") as no_kasur'
		);
		$join = array(
			$this->tabel_master_pasien => array($this->tableName.'.no_rm = '.$this->tabel_master_pasien.'.no_rm', 'inner'),
			$this->tabel_sumber_referensi.' ref_status' => array($this->tableName.'.status = ref_status.kode_ref AND ref_status.tipe_ref = "[SPO]"', 'inner'),
			$this->tabel_sumber_referensi.' lama_op' => array($this->tableName.'.perkiraan_lama_operasi = lama_op.kode_ref AND lama_op.tipe_ref = "[PWO]"', 'inner'),
			$this->tabel_sumber_referensi.' r_ok' => array($this->tableName.'.ruang_ok = r_ok.kode_ref AND r_ok.tipe_ref = "[OK]"', 'left'),
			$this->master_pegawai => array($this->master_pegawai.'.id_pegawai='.$this->tableName.'.operator', 'left'),
			$this->tabel_diagnosa => array($this->tabel_diagnosa.'.kode='.$this->tableName.'.diagnosa', 'left'),
			$this->tabel_tindakan_icd => array('KD_ICD9CM='.$this->tableName.'.tindakan', 'left'),
			'pendaftaran_carteran' => array('pendaftaran_carteran.no_pj='.$this->tableName.'.no_pj_carter', 'left'),
			'mastercr_no_kamar' => array('pendaftaran_carteran.irna_tempat_tidur=mastercr_no_kamar.id', 'left'),
			'mastercr_ruang_rawat' => array('mastercr_no_kamar.id_ruang=mastercr_ruang_rawat.id', 'left'),
			$this->tabel_master_poli => array($this->tabel_master_poli.'.kode_poli='.$this->tableName.'.poli_asal', 'inner'),
			'mastercr_no_kamar mnk' => array('mnk.no_pengisi='.$this->tableName.'.no_reg_pasien AND mnk.no_pengisi<>""','left'),
			'mastercr_ruang_rawat mrr' => array('mnk.id_ruang=mrr.id', 'left'),
			'master_kelass' => array('master_kelass.id_kelas='.$this->tableName.'.kelas', 'left'),
			'pendaftaran' => array('pendaftaran.no_reg='.$this->tableName.'.no_reg_pasien', 'left'),
			'master_cara_bayar' => array('pendaftaran.cara_bayar=master_cara_bayar.kd_bayar', 'left')
		);
		$order = 'urutan ASC, tgl_pelaksanaan ASC';
		
		return $data = $this->find(compact('fields', 'join', 'filter','order'));
	}
	
	public function data_report($param){
		$kondisi[] = 'date(tgl_pelaksanaan) >= "'.display_date($param['tanggal_op_dari'],'Y-m-d').'" and date(tgl_pelaksanaan) <= "'.display_date($param['tanggal_op_sampai'],'Y-m-d').'"';
		$kondisi[] = !empty($param['tanggal_cek_dari'])?'date(tgl_cek_kamar) >= "'.display_date($param['tanggal_cek_dari'],'Y-m-d').'"':'1=1';
		$kondisi[] = !empty($param['tanggal_cek_sampai'])?'date(tgl_cek_kamar) <= "'.display_date($param['tanggal_cek_sampai'],'Y-m-d').'"':'1=1';
		$kondisi[] = !empty($param['status_kunjungan'])?$this->tableName.'.status = "'.$param['status_kunjungan'].'"':'1=1';
		return $this->base_find(implode(' and ',$kondisi));
	}
	
	public function data_report_ibs($param){
		$kondisi[] = 'date(tgl_pelaksanaan) >= "'.display_date($param['tanggal_op_dari'],'Y-m-d').'" and date(tgl_pelaksanaan) <= "'.display_date($param['tanggal_op_sampai'],'Y-m-d').'"';
		$kondisi[] = $this->tableName.'.status > 0';
		return $this->base_find(implode(' and ',$kondisi));
	}
	
	public function data_jadwal_ibs($param){
		$kondisi[] = 'date(tgl_pelaksanaan) >= "'.display_date($param['tanggal_op_dari'],'Y-m-d').'" and date(tgl_pelaksanaan) <= "'.display_date($param['tanggal_op_sampai'],'Y-m-d').'"';
		$kondisi[] = $this->tableName.'.status = 4';
		return $this->base_find(implode(' and ',$kondisi));
	}
	
	function data_report_monitor_batal_ibs($param){
		$kondisi[] = 'date(tgl_pelaksanaan) >= "'.display_date($param['tanggal_op_dari'],'Y-m-d').'" and date(tgl_pelaksanaan) <= "'.display_date($param['tanggal_op_sampai'],'Y-m-d').'"';
		$kondisi[] = $this->tableName.'.status = "-4"';
		$this->db->join('poli_kunjungan_pasien','no_reg_pasien = poli_kunjungan_pasien.no_reg and poli_kunjungan_pasien.instalasi_id=3 and poli_kunjungan_pasien.status_ok=1','inner');
		$this->db->join('mastercr_ruang_rawat mcr_r_r','mcr_r_r.id = poli_kunjungan_pasien.ruang_rawat','inner');
		$this->db->select('mcr_r_r.nama_ruang ruang_inap_sekarang');
		return $this->base_find(implode(' and ',$kondisi));
	}
	
	function data_perjanjian_setelah_batal($id,$no_reg){
		$kondisi[] = $this->tableName.'.id > '.$id;
		$kondisi[] = 'no_reg_pasien = "'.$no_reg.'"';
		$this->db->order_by($this->tableName.'.id','asc');
		$this->db->limit(1);
		return $this->base_find(implode(' and ',$kondisi));
	}

	public  function laporan_operasi($tgl1=null, $tgl2=null){
    	$this->db->select('CONCAT("", COALESCE('.$this->tableName.'.no_rm, ""), "&nbsp;-&nbsp;", COALESCE('.$this->tabel_master_pasien.'.nama, ""), "") AS nama_pasien',false);
		
		$kondisi[] = $this->tableName.'.status = "O" ';
		$kondisi[] = empty($tgl1)?' DATE('.$this->tableName.'.tgl_pelaksanaan) = CURDATE() ':' DATE('.$this->tableName.'.tgl_pelaksanaan) BETWEEN "'.date('Y-m-d',strtotime($tgl1)).'" and "'.date('Y-m-d',strtotime($tgl2)).'" ';
		
		return $this->base_find(implode(' and ',$kondisi));
    }

	function qIsPasienAvailableToRegister($return='filter'){
		if($return=='join')
			return $this->tabel_master_pasien.'.no_rm='.$this->tableName.'.no_rm and '.$this->tableName.'.status in ("O","1","2","3","4") and date(tgl_pelaksanaan) > "'.date('Y-m-d',strtotime(date('Y-m-d').' - '.$this->day_before.' days')).'" and date(tgl_pelaksanaan) < "'.date('Y-m-d',strtotime(date('Y-m-d').' + '.$this->day_after.' days')).'"';
		return $this->tableName.'.no_rm is null'; 
	}

	public function data_report_harian_poli($param){
		if(isset($param['tanggal_input_sd']))
			$kondisi[] = 'catatan_pra_operasi regexp \'"created_date":"('.implode("|",$this->extractDt($param['tanggal_input'],$param['tanggal_input_sd'])).')\'';
		else
			$kondisi[] = 'catatan_pra_operasi like \'%"created_date":"'.display_date($param['tanggal_input'],'Y-m-d').'%\'';
		if(!empty($param['poli_asal']))
			$kondisi[] = 'poli_asal = "'.$param['poli_asal'].'"';
		return $this->base_find(implode(' and ',$kondisi));
	}
	
	private function extractDt($f,$t){
		$R = [];
		$Di = $f;
		while(date('Y-m-d',strtotime($Di)) <= date('Y-m-d',strtotime($t))){
			$R[] = date('Y-m-d',strtotime($Di));
			$Di = date('Y-m-d',strtotime($Di.' +1 day'));
		}
		
		return $R;
	}
	
	function buildRute($ruteJson,$arrDescStatus,$arrUser){
		$return = [];
		foreach(json_decode($ruteJson,true) as $key=>$rute){
			$status = ($key > 0 && $rute['to'] == 'O')?'Pasien Dijadwal Ulang ('.$rute['alasan'].')':$arrDescStatus[$rute['to']];
			$return[] = '['.display_date($rute['waktu_aksi'],'d-m-Y H:i').'] '.$status.' : '.$arrUser[$rute['user']];
		}
		return implode($this->separatorRute,$return);
	}
}?>