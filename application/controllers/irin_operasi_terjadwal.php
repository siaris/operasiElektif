<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "ele.php";

class Irin_operasi_terjadwal extends Ele {
	public function __construct() {  
		parent::__construct();
	}
	
	//HALAMAN 
	function irin_list_pasien(){
		$this->kondisi_unit = ' and kode_ref in ("O","4") ';
		$this->default_aksi_dalam_list = 'showLinkPerjanjianSMF';
		$this->additional_condition_in_list = 'poli_asal = 300';
		$this->list_pasien_operasi_terjadwal();
	}
	
	//HALAMAN
	function irin_input_data_operasi_terjadwal(){
		if($this->input->post()){
			$this->load->model('pendaftaranperjanjianoperasimodel');
			$submit = $this->input->post();
			//cek no rm
			$this->cekEksis($submit['no_rm']);
			//generate nomor perjanjian
			$no_perjanjian_operasi = $this->pendaftaranperjanjianoperasimodel->findNoJanjian();
			$status = 'O';
			$submit['tgl_cek_kamar'] = $submit['tgl_reg_ranap'] = $submit['tgl_pelaksanaan'];
			//generate rute
			$rute = $this->generateRute($status,'','');
			//samakan variabel
			$save_data = [
				'no_perjanjian_operasi'=>$no_perjanjian_operasi,
				'rute'=>$rute,
				'no_rm'=>$submit['no_rm'],
				'no_kontak_pasien'=>$submit['no_kontak_pasien'],
				'poli_asal'=>$submit['poli_asal'],
				'diagnosa'=>$submit['kode_diagnosa'],
				'tindakan'=>$submit['tindakan_kode'],
				'rencana_pembiusan'=>$submit['rencana_pembiusan'],
				'tgl_pelaksanaan'=>isset($submit['is_cito'])?display_date($submit['tgl_pelaksanaan'],'Y-m-d H:i:s'):display_date($submit['tgl_pelaksanaan'],'Y-m-d'),
				'perkiraan_lama_operasi'=>$submit['lama_operasi'],
				'operator'=>$submit['kode_dokter'],
				'kelas'=>$submit['kelas'],
				'tgl_cek_kamar'=>display_date($submit['tgl_cek_kamar'],'Y-m-d'),
				'tgl_reg_ranap'=>display_date($submit['tgl_reg_ranap'],'Y-m-d'),
				'days_before_surgery'=>0,
				'is_need_icu'=>$submit['is_need_icu'],
				'catatan_pra_operasi'=>json_encode(['kebutuhan_alat'=>$submit['kebutuhan_alat'],
					'diagnosa_utama'=>$submit['diagnosa'],
					'tindakan'=>$submit['tindakan'],
					'created_date'=>date('Y-m-d H:i:s'),
					'tipe_op'=>isset($submit['is_cito'])?'ELEKTIF-CITO':'ELEKTIF-INAP']),					
				'no_reg_pasien'=>$submit['no_reg_pasien'],
				'status'=>$status
			];
			//save ke tabel
			$this->pendaftaranperjanjianoperasimodel->save($save_data);
			//set flashmassage
			$this->setFlash('Data jadwal operasi berhasil disimpan, <a target="_blank" href="'.site_url('registrasi/operasi_terjadwal/cetak_bukti_perjanjian/'.$no_perjanjian_operasi.'/').'">print bukti</a>', 'alert-success');
			//include js untuk buka print-an
			$this->template->write('js_bottom_scripts','<script>$(document).ready(function() {window.open("'.site_url('registrasi/operasi_terjadwal/cetak_bukti_perjanjian/'.$no_perjanjian_operasi.'/').'","_blank")});</script>', FALSE);
		}
		$this->init_crud_assets();
		//pop asset
		array_diff($this->js_theme_project,['media/registrasi/form_list']);
		
		if(preg_match('/\/cito/',$_SERVER[REQUEST_URI])){
			//change datepicker to datetimepicker
			array_diff($this->js_theme_project,['bootstrap-datepicker']);$this->js_theme_project[] = 'bootstrap-datetimepicker';
			array_diff($this->css_theme_project,['datepicker']);$this->css_theme_project[] = 'bootstrap-datetimepicker';
			//set limit
			$data_for_js['date_limit'] = $data['date_limit'] = date('d-m-Y H:i',strtotime(date('d-m-Y H:i') . "+1 minutes"));
			$data_for_js['date_limit_end'] = $data['date_limit_end'] = date('d-m-Y H:i',strtotime(date('d-m-Y H:i') . "+120 minutes"));
		}else{
			$data_for_js['date_limit'] = $data['date_limit'] = date('d-m-Y',strtotime(date('Y-m-d') . "+".$this->batas_min_waktu_op_ri." days"));
		}
		$this->load->model(['polimodel','mastersumberreferensimodel','kelasmodel']);
		$data['view'] = "operasi_terjadwal/irin_form_input";
		$data['title'] = "Daftar Baru Operasi Terjadwal";
		$data['poli'] = _parseDropdown($this->polimodel->find("id_instalasi in (3)", $order=null, $limit=null, $offset=null,'concat("[",kode_poli,"]",nama_poli) as nama_poli, kode_poli'),'nama_poli','kode_poli','awal-kosong');
		$data['poli_selected'] = '300';
		$this->mastersumberreferensimodel->changeResultMode();
		$data['lama_operasi'] = _parseDropdown($this->mastersumberreferensimodel->find("kode_ref <> '000' and tipe_ref = '[PWO]'", $order=null, $limit=null, $offset=null,'kode_ref,uraian'),'uraian','kode_ref','awal-kosong');
		$this->kelasmodel->re_init_protected_table();
		$data['kelas'] = _parseDropdown($this->kelasmodel->find("id_kelas not in (9999,12)", $order=null, $limit=null, $offset=null,'id_kelas,nama_kelas'),'nama_kelas','id_kelas','awal-kosong');
		$data['hari_sebelum_tindakan'] = [""=>""]+$this->hari_sebelum_tindakan;
		
		$data_for_js['date_limit_op'] = date('d-m-Y',strtotime(date('Y-m-d') . "+".$this->batas_min_waktu_op_ri." days"));
		$data_for_js['elem_t'] = $data_for_js['elem_ds'] = $data_for_js['elem_tp'] = [];
		$this->template->write('js_bottom_scripts','<script src="'. ASSETURL .'js/media/registrasi/base_perjanjian.js"></script>', FALSE);
		$this->template->write_view("js_bottom_scripts", 'registrasi/operasi_terjadwal/form_js', $data_for_js);
		$this->preRender($data);
	}
	
	//halaman
	function irin_print_jadwal_pasien($date_target = ''){
		// $this->init_crud_assets();
		// $this->js_theme_project[] = 'js_excel/jquery.battatech.excelexport';
		
		$this->layout = "print_layout";
		$today_target = !empty($date_target)?$date_target:date('Y-m-d');
		$adding_day = /*(date('w',strtotime($today_target) == 4))?'3':'1';*/ '1';
		$output['data']['tanggal_op'] = $param['tanggal_op_sampai'] = $param['tanggal_op_dari'] = date('d-m-Y',strtotime($today_target .'+'.$adding_day.' days'));
		$this->load->model(array('pendaftaranperjanjianoperasimodel','usermodel','mastersumberreferensimodel'));
		$this->pendaftaranperjanjianoperasimodel->db->select('mrr.nama_ruang nama_ruang_reg');
		$this->pendaftaranperjanjianoperasimodel->db->where('pendaftaran_perjanjian_operasi.status','4');
		$raw_data = $this->pendaftaranperjanjianoperasimodel->data_report_ibs($param);
		$output['output'] = [];
		$this->title = 'Cetak Jadwal Untuk Operasi Tanggal '.$output['data']['tanggal_op'];
		if(!empty($raw_data)){
			$user_arr = _parseDropdownAllElem($this->usermodel->find('', null, $limit=null, $offset=null,'user.name,user.id'),'name','id');
			$sumber_ref_arr = $this->mastersumberreferensimodel->getInfo(['[SPO]'=>'uraian']);
			foreach($raw_data as $key=>$data){
				$json_pra_operasi = !empty($data['json_pra_operasi'])?json_decode($data['json_pra_operasi'],true):'';
				$raw_data[$key]['kebutuhan_alat'] = isset($json_pra_operasi['kebutuhan_alat'])?$json_pra_operasi['kebutuhan_alat']:'';
				$raw_data[$key]['diagnosa'] = isset($json_pra_operasi['diagnosa_utama'])?$json_pra_operasi['diagnosa_utama']:'';
				$raw_data[$key]['tindakan'] = isset($json_pra_operasi['tindakan'])?$json_pra_operasi['tindakan']:'';
				$json_ruang_ok = !empty($data['json_r_ok'])?json_decode($data['json_r_ok'],true):'';
				$raw_data[$key]['ruang_ok'] = isset($json_ruang_ok['nama_ruang'])?$json_ruang_ok['nama_gedung'].'-'.$json_ruang_ok['nama_ruang']:'';
				$raw_data[$key]['rute_desc'] = $this->pendaftaranperjanjianoperasimodel->buildRute($data['rute'],$sumber_ref_arr['[SPO]'],$user_arr);
				if(isset($json_pra_operasi[date('Y-m-d',strtotime($output['data']['tanggal_op'])).'-info'])){
					$arr_j = $json_pra_operasi[date('Y-m-d',strtotime($output['data']['tanggal_op'])).'-info'];
					$raw_data[$key]['opr'] = $arr_j['opr'];
					$raw_data[$key]['anest'] = $arr_j['anest'];
					$raw_data[$key]['inst'] = $arr_j['inst'];
					$raw_data[$key]['circ'] = $arr_j['circ'];
					$raw_data[$key]['penata'] = $arr_j['penata'];
				}else{
					$raw_data[$key]['opr'] = '';
					$raw_data[$key]['anest'] = '';
					$raw_data[$key]['inst'] = '';
					$raw_data[$key]['circ'] = '';
					$raw_data[$key]['penata'] = '';
				}
			}
			$output['output'] = $raw_data;
		}
		//var_dump($output);
		$this->set('output', $output['output']);
		$this->set('data', $output['data']);
		$this->set('jscript', '<script>$(document).ready(function() { 	$("#btnExport").addClass("hide"); })</script>');
		$this->name = 'operasi_terjadwal';
		$this->render('ibs_jadwal_besok');
		// $this->preRender($output,"",'operasi_terjadwal/ibs_jadwal_besok');
		return;
	}
	
	//halaman
	function cari_jadwal(){
		if($this->input->post()){
			$date_op = date('Y-m-d',strtotime($this->input->post('tanggal_input') .'-1 days'));
			redirect(BASEURL.'/registrasi/irin_operasi_terjadwal/irin_print_jadwal_pasien/'.$date_op,'refresh');
			return;
		}
		$this->init_crud_assets();
		$output['output'] = [];
		$output['title'] = 'Laporan Harian Operasi Terjadwal';
		$this->template->write('js_bottom_scripts','<script>$("form#form_report").validate({ignore: null});</script>', FALSE);
		$this->preRender($output,"",'operasi_terjadwal/irin_cari_jadwal');
		return;
	}
}?>