<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "ele.php";

class Adm_ri_operasi_terjadwal extends Ele {
	public function __construct() {  
		parent::__construct();
		$this->load->model(['pendaftaranperjanjianoperasimodel']);
		$this->draft_template = [24];
	}
	
	public function tools_penjadwalan_elektif(){
		$this->load->model(['mastersumberreferensimodel']);
		if($this->input->post()){
			$submit = $this->input->post();
			
		}
		$output['status_desc'] = $status_desc = $this->mastersumberreferensimodel->getInfo(['[SPO]'=>'uraian']);
		$output['ddaksi'] = ['3'=>$status_desc['1'].' menjadi '.$status_desc['3'],'4'=>$status_desc['3'].' menjadi '.$status_desc['4']];
		$this->preRender($output,"",'operasi_terjadwal/adm_ri_tools');
		return;
	}
	
	public function ubah_status_by_tools($no_perjanjian,$status_to){
		if($this->input->post()){
			
		}
		switch($status_to){
			case '3':
				
			default :
				
		}
		$this->preRender($output,"",'operasi_terjadwal/adm_ri_tools');
		return;
	}
	
	//HALAMAN 
	function adm_ri_list_pasien(){
		$this->config->load('config_ri');
		$this->kondisi_unit = ' and kode_ref in ("1","3") ';
		$this->default_aksi_dalam_list = 'showLinkPerjanjianAdmRI';
		$this->add_page_form['button'] = '<input class="btn btn_primary" id="show_popup_kkosong" value="Kans Kamar Kosong '.$this->config->item('batas_proyeksi_pasien_kamar').' Jam Kedepan" type="button">&nbsp;';
		$this->add_page_form['js_script'] = '$(\'form #show_popup_kkosong\').on(\'click\',function(){ 		PopupCenter(\'/rawatinap/kandidat_kamar_kosong/\', \'Kandidat Kamar Kosong '.$this->config->item('batas_proyeksi_pasien_kamar').' Jam Kedepan\', 900, 500); 	})';
		
		$this->init_crud_assets();
		$this->css_theme_project[] = 'sweetalert';
		$this->js_theme_project[] = 'sweetalert.min';
		$argumen['title'] = 'List Perjanjian Operasi';
		$this->load->model(array('mastersumberreferensimodel','pendaftaranperjanjianoperasimodel','suratketeranganmcumodel'));
		$this->mastersumberreferensimodel->changeResultMode();
		$data_form['status_kunjungan'] = _parseDropdownAllElem($this->mastersumberreferensimodel->find("kode_ref <> '000' and tipe_ref = '[SPO]' ".$this->kondisi_unit, $order='kode_ref_order', $limit=null, $offset=null,'cast(kode_ref as signed) as kode_ref_order,kode_ref,uraian,uraian_1'),$this->default_display_uraian_status,'kode_ref','awal-kosong');
		$data_form['add_page_form'] = $this->add_page_form;
		$output['extra'] = $this->load->view('registrasi/form_list_perjanjian_operasi',$data_form,TRUE);
		$this->pasien_perjanjian_id = '';
		$output['output'] = "";
		if($this->input->post()){
			$this->js_theme_project[] = 'facebox';
			$this->css_theme_project[] = 'facebox';
			$this->load->library('grocery_crud/grocery_CRUD_extended');
			$crud = new grocery_CRUD_extended();
			$crud->set_theme('flexigrid-aris');
			$crud->set_table($this->pendaftaranperjanjianoperasimodel->janji_operasi);
			$crud->set_relation('operator',$this->pendaftaranperjanjianoperasimodel->master_pegawai,'nama');
			$crud->set_primary_key('no_rm',$this->pendaftaranperjanjianoperasimodel->tabel_master_pasien);
			$crud->set_relation('no_rm',$this->pendaftaranperjanjianoperasimodel->tabel_master_pasien,'{no_rm} - {nama}');
			$crud->set_primary_key('kode_poli',$this->pendaftaranperjanjianoperasimodel->tabel_master_poli);
			$crud->set_relation('poli_asal',$this->pendaftaranperjanjianoperasimodel->tabel_master_poli,'nama_poli');
			$crud->set_relation('kelas','master_kelass','nama_kelas');
			$crud->columns('no_perjanjian_operasi','no_rm','no_kontak_pasien','tgl_cek_kamar','tgl_pelaksanaan','kelas','poli_asal','operator','actions');
			$crud->unset_add();
			$crud->unset_edit();
			$crud->unset_read();
			$crud->unset_delete();
			$crud->unset_columns_order = $crud->unset_columns_filter = array('no_rm','tgl_cek_kamar','tgl_pelaksanaan','actions');
			$text_filter = '';
			
			$submit=$this->input->post();
			if(!empty($this->additional_condition_in_list))
				$crud->where($this->additional_condition_in_list);
			$crud->where($this->pendaftaranperjanjianoperasimodel->janji_operasi.'.status',$submit['status_kunjungan']);
			$text_filter .= '<input type="hidden" name="status_kunjungan" value="'.$submit['status_kunjungan'].'">';
			if(!empty($submit['tgl_kunjungan'])){
				$crud->where('date(tgl_pelaksanaan) =',display_date($submit['tgl_kunjungan'],'Y-m-d'));
				$text_filter .= '<input type="hidden" name="tgl_kunjungan" value="'.$submit['tgl_kunjungan'].'">';
			}
			if(!empty($submit['tgl_cek_kamar'])){
				$crud->where('date(tgl_cek_kamar) =',display_date($submit['tgl_cek_kamar'],'Y-m-d'));
				$text_filter .= '<input type="hidden" name="tgl_cek_kamar" value="'.$submit['tgl_cek_kamar'].'">';
			}
			
			$crud->callback_column('actions',array($this,$this->default_aksi_dalam_list));
			$crud->callback_column('tgl_cek_kamar',array($this,'showTglKunjungan'));
			$crud->callback_column('tgl_pelaksanaan',array($this,$this->default_display_tgl));
			$crud->callback_column('no_kontak_pasien',function($value,$row){return $row->no_kontak_pasien;});
			$crud->display_as('poli_asal','Asal Poli')->display_as('tgl_pelaksanaan','Tgl Operasi');
			// $crud->callback_column('poli',array($this,'showPoli'));
			// $crud->callback_column('Ubah No RM',array($this,'showNoRM'));
			$output = $crud->render($output['extra']);
			$this->template->write_view("js_bottom_scripts", '/registrasi/form_list_js', null);
			
			$this->template->write_view("js_bottom_scripts", '/registrasi/operasi_terjadwal/adm_ri_form_jadwal_js', ['templt'=>$this->suratketeranganmcumodel->get_template('true or id in ('.implode(',',$this->draft_template).')')]);
			$this->template->write('js_bottom_scripts','<script>$(document).ready(function() {$( ".quickSearchBox" ).append( \''.$text_filter.' \');})</script>', FALSE);		
		}
		$this->preRender($output,$argumen['title'],'Page/grocery_crud_content');
		
	}
	
	function showLinkPerjanjianAdmRI($value,$row){
		switch($row->status){
			case '3':
				//admisi rawat inap
				$detail_form = $this->buildDetailHiddenForm($row,'<input id="no_reg_pasien" name="no_reg_pasien" value="">');
				$detail = $detail_form['data'];
				if($this->isTodayButtonActionExist($row)){
					$return[] = '<div style="display:none;">
					<input id="j_no_rm_'.$row->no_perjanjian_operasi.'" value="'.$row->no_rm.'">
					<input id="j_no_pj_carter_'.$row->no_perjanjian_operasi.'" value="'.$row->no_pj_carter.'">
					<input id="j_diagnosa_'.$row->no_perjanjian_operasi.'" value="'.$detail[0]['diagnosa'].'">
					<input id="j_topik_'.$row->no_perjanjian_operasi.'" value="'.$detail[0]['topik'].'">
					<input id="j_dokter_'.$row->no_perjanjian_operasi.'" value="'.$row->operator.'">
					<input id="j_nama_pegawai_'.$row->no_perjanjian_operasi.'" value="'.$detail[0]['nama_pegawai'].'">
					<input id="j_ruang_rawat_'.$row->no_perjanjian_operasi.'" value="'.$detail[0]['ruang_rawat'].'">
					<input id="j_kelas_'.$row->no_perjanjian_operasi.'" value="'.$detail[0]['kelas_carter'].'">
					<input id="j_gedung_'.$row->no_perjanjian_operasi.'" value="'.$detail[0]['id_gedung'].'">
					<input id="j_cara_masuk_'.$row->no_perjanjian_operasi.'" value="5">
					</div>';
					$return[] = '<div style="display:none;">'.$detail_form['form'].'</div>';
					$return[] = '<a class="btn btn-mini add-reg-btn" onclick="showPopUp(\''.$row->no_perjanjian_operasi.'\',\'daftar_ranap\',\'Daftarkan Pasien Perjanjian Operasi ke RI\')"><i class="icon-plus"></i> Daftar Rawat Inap & Ubah Status</a>';
					$return[] = '<a class="btn btn-mini edit-btn" href="'.BASEURL.'/registrasi/operasi_terjadwal/form_ubah_status/'.$row->no_perjanjian_operasi.'/O/">Jadwal Ulang</a>';
				}
				//'batal-perjanjian-yg-bisa-batal-carter'
				$return[] = '<a class="btn btn-mini edit-btn" onclick="alertFirst(\'Pastikan Anda Membatalkan Carter, No Carter : '.$detail[0]['no_pj_carter'].'\',\''.BASEURL.'/registrasi/operasi_terjadwal/form_ubah_status/'.$row->no_perjanjian_operasi.'/-1/\')">Batal</a>';
				break;
			default : 
				//admisi rawat inap
				$return[] = '<a class="btn btn-mini edit-btn" target="_blank" href="'.BASEURL.'/registrasi/operasi_terjadwal/cetak_bukti_perjanjian/'.$row->no_perjanjian_operasi.'">Print Bukti Perjanjian</a>';
				if($this->isTodayButtonActionExist($row)){
						$detail_form = $this->buildDetailHiddenForm($row,'',2);
						$detail = $detail_form['data'];
						$return[] = '<div style="display:none;">
						<input id="j_no_rm_'.$row->no_perjanjian_operasi.'" value="'.$row->no_rm.' - '.$detail[0]['nama'].'">
						<input id="j_alamat_'.$row->no_perjanjian_operasi.'" value="'.$detail[0]['alamat_jalan'].'">
						<input id="j_jenis_kelamin_'.$row->no_perjanjian_operasi.'" value="'.$detail[0]['jenis_kelamin'].'">
						<input id="j_diagnosa_'.$row->no_perjanjian_operasi.'" value="'.$detail[0]['diagnosa'].'">
						<input id="j_topik_'.$row->no_perjanjian_operasi.'" value="'.$detail[0]['topik'].'">
						<input id="j_dokter_'.$row->no_perjanjian_operasi.'" value="'.$row->operator.'">
						<input id="j_nama_pegawai_'.$row->no_perjanjian_operasi.'" value="'.$detail[0]['nama_pegawai'].'">
						<input id="j_kelas_'.$row->no_perjanjian_operasi.'" value="'.$row->kelas.'">
						<input id="j_no_kontak_pasien_'.$row->no_perjanjian_operasi.'" value="'.$row->no_kontak_pasien.'">
						<input id="j_tgl_cek_kamar_'.$row->no_perjanjian_operasi.'" value="'.display_date(date("Y-m-d H:i:s",strtotime(date('Y-m-d H:i:s')." +5 hours")),'Y-m-d H:i:s').'">
						</div>';
						$return[] = '<div style="display:none;">'.$detail_form['form'].'</div>';
						$return[] = '<a class="btn btn-mini add-reg-btn" onclick="showPopUp(\''.$row->no_perjanjian_operasi.'\',\'carter_kamar\',\'Carter Kamar Inap\')"><i class="icon-plus"></i> Carter Kamar & Ubah Status</a>';
						$return[] = '<div style="display:none;"><input id="tgl_cek_kamar_'.$row->no_perjanjian_operasi.'" name="tgl_cek_kamar_'.$row->no_perjanjian_operasi.'" class="form-control datepicker" value="'.$row->tgl_cek_kamar.'" /></div>';
						$return[] = '<a class="btn btn-mini edit-btn" href="'.BASEURL.'/registrasi/operasi_terjadwal/form_ubah_status/'.$row->no_perjanjian_operasi.'/O/">Jadwal Ulang</a>';
				}else{
					$return[] = '<a class="btn btn-mini edit-btn" href="'.BASEURL.'/registrasi/operasi_terjadwal/form_ubah_status/'.$row->no_perjanjian_operasi.'/-1/">Batal</a>';
				}
				break;
		}
		$return[] = '<a class="btn btn-mini edit-btn hehe" onclick="doADMFormSMS(this)" href="javascript:void(0);">Kirim SMS</a><div class="hide">>>'.display_date($row->tgl_reg_ranap).'</div>';
		return implode('&nbsp;',$return);
	}

	//halaman
	function send_pesan(){
		if($this->input->post()){
			$submit = $this->input->post();
			$this->load->helper("send_sms_helper");
			foreach(explode('/',$submit['nomor_tujuan']) as $v){
				$txt = [$submit['no_elek']=>[
					$submit['isi_pesan'],$v,$submit['id_pengirim']
				]];
				
				$data[] = send_sms($submit['isi_pesan'],$v,$submit['id_pengirim'],json_encode($txt));
			}
		}
		return;
	}

	//halaman
	function histori_sms($id){
		$return = $this->pendaftaranperjanjianoperasimodel->db->query('SELECT * FROM sms_outbox where info regexp \'{"'.$id.'":\'')->result_array();
		echo json_encode(empty($return)?[]:$return);
		return;
	}
}