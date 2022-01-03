<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "registrasi_controller.php";

class Ele extends Registrasi_controller {
	
	public function __construct() {  
		parent::__construct();
		$this->hari_sebelum_tindakan = array_combine(range(1,2),range(1,2));
		$this->default_aksi_dalam_list = 'showLinkPerjanjian';
		$this->default_display_tgl = 'showTglKunjungan';
		$this->default_display_uraian_status = 'uraian';
		$this->additional_condition_in_list = '';
		$this->add_page_form = [];
	}
	
	protected function init_crud_assets(){
		$this->css_theme_project = array('bootstrap','main','DT_bootstrap','override','jquery.slidepanel','style','jquery.sliding_menu','datepicker');
		$this->js_theme_project = array('DT_bootstrap','bootstrap.min','jquery.sliding_menu','bootstrap-typeahead','bootstrap-datepicker','bootbox','jquery.validate.min','jquery.alphanumeric','jquery.alphanumeric.pack','main','media/registrasi/form_list');
	}
	
	//HALAMAN
	function index(){
		$this->title = "Operasi Terjadwal";
		$this->name = 'operasi_terjadwal';
		$this->render('home');
	}
	
	//HALAMAN
	function index_smf(){
		$this->title = "POLI | Operasi Terjadwal";
		$this->name = 'operasi_terjadwal';
		$this->render('home_smf');
	}
	
	//HALAMAN
	function index_irin(){
		$this->title = "IRIN | Operasi Terjadwal";
		$this->name = 'operasi_terjadwal';
		$this->render('home_irin');
	}
	
	//HALAMAN
	function index_ibs(){
		$this->title = "IBS | Operasi Terjadwal";
		$this->name = 'operasi_terjadwal';
		$this->render('home_ibs');
	}
	
	//HALAMAN
	function index_adm_ri(){
		$this->title = "ADM RANAP | Operasi Terjadwal";
		$this->name = 'operasi_terjadwal';
		$this->render('home_adm_ri');
	}
	
	//HALAMAN
	function index_adm_igd(){
		$this->title = "ADM IGD | Operasi Terjadwal";
		$this->name = 'operasi_terjadwal';
		$this->render('home_adm_igd');
	}
	
	//HALAMAN
	function input_data_operasi_terjadwal(){
		if($this->input->post()){
			$this->load->model('pendaftaranperjanjianoperasimodel');
			$submit = $this->input->post();
			
			//cek no rm
			$this->cekEksis($submit['no_rm']);
			
			//generate nomor perjanjian
			$no_perjanjian_operasi = $this->pendaftaranperjanjianoperasimodel->findNoJanjian();
			$status = 'O';
			//generate rute
			$rute = $this->generateRute($status,'','');
			//samakan variabel
			$save_data = [
				'no_perjanjian_operasi'=>$no_perjanjian_operasi,
				'rute'=>$rute,
				'no_rm'=>$submit['no_rm'],
				'no_kontak_pasien'=>$submit['no_kontak_pasien'],
				'poli_asal'=>$submit['poli_asal'],
				'list_konsul_poli'=>(!empty($submit['list_poli_konsul']))?implode(',',$submit['list_poli_konsul']):'',
				'diagnosa'=>$submit['kode_diagnosa'],
				'tindakan'=>$submit['tindakan_kode'],
				'rencana_pembiusan'=>$submit['rencana_pembiusan'],
				'tgl_pelaksanaan'=>display_date($submit['tgl_pelaksanaan'],'Y-m-d'),
				'perkiraan_lama_operasi'=>$submit['lama_operasi'],
				'operator'=>$submit['kode_dokter'],
				'kelas'=>$submit['kelas'],
				'tgl_cek_kamar'=>display_date($submit['tgl_cek_kamar'],'Y-m-d'),
				'tgl_reg_ranap'=>display_date($submit['tgl_reg_ranap'],'Y-m-d'),
				'days_before_surgery'=>$submit['hari_sebelum_tindakan'],
				'is_need_icu'=>$submit['is_need_icu'],
				'catatan_pra_operasi'=>json_encode(['kebutuhan_alat'=>$submit['kebutuhan_alat'],
					'diagnosa_utama'=>$submit['diagnosa'],
					'tindakan'=>$submit['tindakan'],
					'created_date'=>date('Y-m-d H:i:s'),
					'catatan_tambahan_pra_operasi'=>$submit['catatan_tambahan_pra_operasi'],
					'tipe_op'=>isset($submit['is_odc'])?'ELEKTIF-ODC':'ELEKTIF-NON-INAP',
					'blThnrCnsmd'=>$submit['blThnrCnsmd']]
				),
				'status'=>$status
			];
			//save ke tabel
			$this->pendaftaranperjanjianoperasimodel->save($save_data);
			//set flashmassage
			$this->setFlash('Data jadwal operasi berhasil disimpan, <a target="_blank" href="'.site_url('registrasi/operasi_terjadwal/bukti_perjanjian_utk_pasien/'.$no_perjanjian_operasi.'/').'">print bukti</a>', 'alert-success');
			//include js untuk buka print-an
			$this->template->write('js_bottom_scripts','<script>$(document).ready(function() {window.open("'.site_url('registrasi/operasi_terjadwal/bukti_perjanjian_utk_pasien/'.$no_perjanjian_operasi.'/').'","_blank")});</script>', FALSE);
		}
		
		$this->css_theme_project = ['bootstrap','main','DT_bootstrap','override','jquery.slidepanel','style','jquery.sliding_menu','datepicker/1.7.1/bootstrap-datepicker.min'];
		$this->js_theme_project = ['DT_bootstrap','bootstrap.min','jquery.sliding_menu','bootstrap-typeahead','datepicker/1.7.1/bootstrap-datepicker','datepicker/1.7.1/locales/bootstrap-datepicker.id','bootbox','jquery.validate.min','jquery.alphanumeric','jquery.alphanumeric.pack','main','media/registrasi/form_list','media/date-helpers'];
		
		$this->load->model(array('polimodel','mastersumberreferensimodel','kelasmodel'));
		$data['view'] = "operasi_terjadwal/form_input";
		$data['title'] = "Daftar Baru Operasi Terjadwal";
		$data['poli'] = _parseDropdown($this->polimodel->find("id_instalasi in (1,2,19)", $order=null, $limit=null, $offset=null,'concat("[",kode_poli,"]",nama_poli) as nama_poli, kode_poli'),'nama_poli','kode_poli','awal-kosong');
		$this->mastersumberreferensimodel->changeResultMode();
		$data['lama_operasi'] = _parseDropdown($this->mastersumberreferensimodel->find("kode_ref <> '000' and tipe_ref = '[PWO]'", $order=null, $limit=null, $offset=null,'kode_ref,uraian'),'uraian','kode_ref','awal-kosong');
		$this->kelasmodel->re_init_protected_table();
		$data['kelas'] = _parseDropdown($this->kelasmodel->find("id_kelas not in (9999,12)", $order=null, $limit=null, $offset=null,'id_kelas,nama_kelas'),'nama_kelas','id_kelas','awal-kosong');
		$data['hari_sebelum_tindakan'] = [""=>""]+$this->hari_sebelum_tindakan;
		$data_for_js['date_limit'] = $data['date_limit'] = date('d-m-Y',strtotime(date('Y-m-d') . "+".$this->batas_min_cek_kamar." days"));
		$data['batas_min_cek_kamar'] = $this->batas_min_cek_kamar;
		$data_for_js['date_limit_op'] = $data['date_limit_op'] = date('d-m-Y',strtotime(date('Y-m-d') . "+".$this->batas_min_waktu_op_rj." days"));
		$data_for_js['date_limit_op_odc'] = $data['date_limit_op_odc'] = date('d-m-Y',strtotime(date('Y-m-d') . "+".$this->batas_min_waktu_op_odc." days"));
		//$data_for_js['elem_t'] = $data_for_js['elem_ds'] = $data_for_js['elem_tp'] = [];
		$this->template->write('js_bottom_scripts','<script src="'. ASSETURL .'js/media/registrasi/base_perjanjian.js"></script>', FALSE);
		$this->template->write_view("js_bottom_scripts", 'registrasi/operasi_terjadwal/form_js', $data_for_js);
		//$this->template->write_view("js_bottom_scripts", 'medical_record/fill_diagnosa_js', $data_for_js);
		$this->template->write("js_bottom_scripts", "<script>function select_autosuggest(obj,prefix){ 	if(prefix=='pasien'){ 		$('form#perjanjian #no_rm').val(obj.id); 		$('form#perjanjian #no_rm_display').val(obj.id); 		$('form#perjanjian #nama_pelanggan').val(obj.nama); 	    $('form#perjanjian #tgl_lahir').val(obj.tanggal_lahir); 	    $('form#perjanjian #alamat').val(obj.alamat); 	    $('form#perjanjian #jenis_kelamin').val(obj.jenis_kelamin); 	    $('form#perjanjian #no_kontak_pasien').val(obj.no_telpon); 	    $('form#perjanjian #kelas_saat_ini').val(obj.kelas); 	}else{ 		$('form#perjanjian #kode_dokter').val(obj.id_pegawai); 	    $('form#perjanjian #nama_dokter').val(obj.nama_pegawai); 	} 	return; }</script>", FALSE);
		$this->preRender($data);
	}
	
	protected function generateRute($to,$from='',$alasan = '', $arr_before=[]){
		$arr_before[] = ['user'=>$this->session->userdata['userLogin']['id'],'from'=>$from,'to'=>$to,'waktu_aksi'=>date('Y-m-d H:i:s'),'alasan'=>$alasan];
		return json_encode($arr_before);
	}
	
	//HALAMAN
	function cetak_bukti_perjanjian($no_perjanjian_operasi){
		if(empty($no_perjanjian_operasi))
			redirect('pendaftaran/');
		$this->cetak_bukti(array('no_perjanjian_operasi'=>$no_perjanjian_operasi));
	}
	
	//HALAMAN
	function bukti_perjanjian_utk_pasien($no_perjanjian_operasi){
		if(empty($no_perjanjian_operasi))
			redirect('pendaftaran/');
		$this->cetak_bukti(['no_perjanjian_operasi'=>$no_perjanjian_operasi],'bukti_utk_pasien');
	}
	
	private function cetak_bukti($data,$file_view = 'cetak_bukti_perjanjian'){
		$this->layout = "print_layout";
		$this->load->model(array('ProfileModel','pendaftaranperjanjianoperasimodel','mastersumberreferensimodel'));
		$profile = $this->ProfileModel->find();
		$this->set('profile', $profile);
		$data = (array) $this->pendaftaranperjanjianoperasimodel->base_find(['no_perjanjian_operasi'=>$data['no_perjanjian_operasi']]);
		$decoded_data = json_decode($data[0]['catatan_pra_operasi'],true);
		$data[0]['diagnosa'] = isset($decoded_data['diagnosa_utama'])?$decoded_data['diagnosa_utama']:'';
		$data[0]['tindakan'] = isset($decoded_data['tindakan'])?$decoded_data['tindakan']:'';
		$data[0]['catatan_tambahan_pra_operasi'] = isset($decoded_data['catatan_tambahan_pra_operasi'])?$decoded_data['catatan_tambahan_pra_operasi']:'';
		$ref = $this->mastersumberreferensimodel->getInfo(['FLA'=>'uraian']);
		$this->set('data', $data);
		$this->set('ref', $ref);
		// print_r(json_decode($data[0]['rute'],true));exit;
		$this->name = 'operasi_terjadwal';
		$this->render($file_view);
	}
	
	//HALAMAN 
	function smf_list_pasien(){
		$this->kondisi_unit = ' and kode_ref in ("O","1") ';
		$this->default_aksi_dalam_list = 'showLinkPerjanjianSMF';
		$this->additional_condition_in_list = 'poli_asal <> 300';
		$this->list_pasien_operasi_terjadwal();
	}
		
	//HALAMAN 
	function adm_ri_list_pasien(){
		$this->config->load('config_ri');
		$this->kondisi_unit = ' and kode_ref in ("1","3") ';
		$this->default_aksi_dalam_list = 'showLinkPerjanjianAdmRI';
		$this->add_page_form['button'] = '<input class="btn btn_primary" id="show_popup_kkosong" value="Kans Kamar Kosong '.$this->config->item('batas_proyeksi_pasien_kamar').' Jam Kedepan" type="button">&nbsp;';
		$this->add_page_form['js_script'] = '$(\'form #show_popup_kkosong\').on(\'click\',function(){ 		PopupCenter(\'/rawatinap/kandidat_kamar_kosong/\', \'Kandidat Kamar Kosong '.$this->config->item('batas_proyeksi_pasien_kamar').' Jam Kedepan\', 900, 500); 	})';
		$this->list_pasien_operasi_terjadwal();
	}

	//RESCHEDULE
	function reschedule(){	
		$data = $this->input->post();
		$this->render('ibs_laporan');
	}
	
	//HALAMAN 
	function adm_igd_list_pasien(){
		$this->kondisi_unit = ' and kode_ref in ("2") ';
		$this->default_display_uraian_status = "uraian_1";
		$this->list_pasien_operasi_terjadwal();
	}
	
	function list_pasien_operasi_terjadwal(){
		$this->init_crud_assets();
		$this->css_theme_project[] = 'sweetalert';
		$this->js_theme_project[] = 'sweetalert.min';
		$argumen['title'] = 'List Perjanjian Operasi';
		$this->load->model(array('mastersumberreferensimodel','pendaftaranperjanjianoperasimodel'));
		$this->mastersumberreferensimodel->changeResultMode();
		$data_form['status_kunjungan'] = _parseDropdownAllElem($this->mastersumberreferensimodel->find("kode_ref <> '000' and tipe_ref = '[SPO]' ".$this->kondisi_unit, $order='kode_ref_order', $limit=null, $offset=null,'cast(kode_ref as signed) as kode_ref_order,kode_ref,uraian,uraian_1'),$this->default_display_uraian_status,'kode_ref','awal-kosong');
		$data_form['add_page_form'] = $this->add_page_form;
		$output['extra'] = $this->load->view('registrasi/form_list_perjanjian_operasi',$data_form,TRUE);
		$this->pasien_perjanjian_id = '';
		$output['output'] = "";
		if($this->input->post()){
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
			$this->template->write('js_bottom_scripts','<script>$(document).ready(function() {$( ".quickSearchBox" ).append( \''.$text_filter.' \');})</script>', FALSE);		
		}
		$this->preRender($output,$argumen['title'],'Page/grocery_crud_content');
	}
	
	function showTglKunjungan($value, $row){
		return display_date($value);
	}
	
	function showTglKunjunganIBS($value, $row){
		return display_date($value,'d-m-Y H:i');
	}
	
	function showLinkPerjanjian($value, $row){		
		switch($row->status){
			case 'O':
				//popup konfirmasi tgl operasi dan ruangan OK
				$return[] = '<a class="btn btn-mini add-reg-btn" href="'.BASEURL.'/registrasi/operasi_terjadwal/form_konfirmasi_ibs/'.$row->no_perjanjian_operasi.'/" ><i class="icon-plus"></i> Konfirmasi Tgl & Ruang OK</a>';
				break;
			case '2':
				//admisi igd
				$detail_form = $this->buildDetailHiddenForm($row);
				$detail = $detail_form['data'];
				if($this->isTodayButtonActionExist($row)){
					$return[] = '<div style="display:none;">
					<input id="j_no_rm_'.$row->no_perjanjian_operasi.'" value="'.$row->no_rm.'">
					<input id="j_diagnosa_'.$row->no_perjanjian_operasi.'" value="'.$detail[0]['diagnosa'].'">
					<input id="j_topik_'.$row->no_perjanjian_operasi.'" value="'.$detail[0]['topik'].'">
					</div>';
					$return[] = '<div style="display:none;">'.$detail_form['form'].'</div>';
					$return[] = '<a class="btn btn-mini add-reg-btn" onclick="showPopUp(\''.$row->no_perjanjian_operasi.'\',\'op_daftar_poli\',\'Daftarkan Pasien Perjanjian Operasi ke Poli Khusus\')"><i class="icon-plus"></i> Daftar Poli & Ubah Status</a>';
					$return[] = '<a class="btn btn-mini add-reg-btn" onclick="showPopUp(\''.$row->no_perjanjian_operasi.'\',\'daftar_igd\',\'Daftarkan Pasien Perjanjian Operasi ke IGD\')"><i class="icon-plus"></i> Daftar IGD & Ubah Status</a>';
				}
				//'batal-perjanjian-reroute-dan-batal-carter'
				$return[] = '<a class="btn btn-mini edit-btn" onclick="alertFirst(\'Pastikan Anda Membatalkan Carter, No Carter : '.$detail[0]['no_pj_carter'].'\',\''.BASEURL.'/registrasi/operasi_terjadwal/form_ubah_status/'.$row->no_perjanjian_operasi.'/-1/\')">Batal</a>';
				break;
			default:
				//hanya view, mungkin bisa siapa pun
				$return[] = '';
				break;
		}
		return implode('&nbsp;',$return);
	}
		
	function showLinkPerjanjianSMF($value,$row){
		$return[] = '<a class="btn btn-mini edit-btn" target="_blank" href="'.BASEURL.'/registrasi/operasi_terjadwal/bukti_perjanjian_utk_pasien/'.$row->no_perjanjian_operasi.'">Print Bukti Perjanjian</a>';
		switch($row->status){
			case 'O':
				$return[] = 'Tunggu Konfirmasi IBS';
				break;
			default :
				break;
		}
		return implode('&nbsp;',$return);
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
		return implode('&nbsp;',$return);
	}

	//RESCHEDULE
	function showLinkPerjanjianReschedule($value,$row){
		$return[] = '<a class="btn btn-mini edit-btn" href="'.BASEURL.'/registrasi/operasi_terjadwal/form_ubah_status/'.$row->no_perjanjian_operasi.'/O/">Jadwal Ulang</a>';
		return implode('&nbsp;',$return);
	}
	
	protected function buildDetailHiddenForm($row,$append='',$add_status_to=1){
		$this->pendaftaranperjanjianoperasimodel->db->select($this->pendaftaranperjanjianoperasimodel->tabel_master_pasien.'.jenis_kelamin');
		$detail = $this->pendaftaranperjanjianoperasimodel->base_find(['no_perjanjian_operasi'=>$row->no_perjanjian_operasi]);
		return ['data'=>$detail,'form'=>'<form id="form_'.$row->no_perjanjian_operasi.'"><div class="hide"><input name="status_from" value="'.$detail[0]['status'].'"><input name="status_to" value="'.($detail[0]['status']+$add_status_to).'"><input name="no_perjanjian" value="'.$row->no_perjanjian_operasi.'"></div>'.$append.'</form>'];
	}
	
	//halaman
	function submit_status(){
		if($this->input->post()){
			$this->load->model('pendaftaranperjanjianoperasimodel');
			$submit = $this->input->post();
			$data_perjanjian = $this->pendaftaranperjanjianoperasimodel->find('no_perjanjian_operasi = "'.$submit['no_perjanjian'].'"', $order=null, $limit=null, $offset=null,'id,rute,no_rm');
			
			//additional
			$alasan = isset($submit['alasan'])?$submit['alasan']:'';
			//end additional
			
			$save_data = [
				'id'=>$data_perjanjian[0]['id'],
				'rute'=>$this->generateRute($submit['status_to'],$submit['status_from'],$alasan,json_decode($data_perjanjian[0]['rute'])),
				'status'=>$submit['status_to']
			];
			
			//additional act from list
			if(isset($submit['no_pj_carter'])){ $save_data['no_pj_carter'] = $submit['no_pj_carter'];}
			if(isset($submit['no_reg_pasien'])){ $save_data['no_reg_pasien'] = $submit['no_reg_pasien'];}
			//end additional
			
			//additional act from edit form
			// if(isset($submit['days_before_surgery'])){$save_data['tgl_cek_kamar'] = date('Y-m-d',strtotime($submit['tgl_pelaksanaan'] . '-'.$submit['days_before_surgery'].' days'));}
			// if(isset($submit['tgl_pelaksanaan'])){$save_data['tgl_pelaksanaan'] = display_date($submit['tgl_pelaksanaan'],'Y-m-d');}
			//end additional
			//save ke tabel
			$this->pendaftaranperjanjianoperasimodel->save($save_data);
		}
		return;
	}
	
	//halaman
	function form_ubah_status($no_perjanjian='',$status_to="O"){
		if(empty($no_perjanjian)){
			redirect('registrasi/operasi_terjadwal/');
			return;
		}
		$front_title = ($status_to <> 'O')?'Pembatalan':'Reschedule';
		if($this->input->post()){
			$submit = $this->input->post();
			if(isset($submit['ibs_tidak_monitoring_batal']))
				$_POST['status_to'] = '-1';
			//submit
			$this->submit_status();
			$this->parentSetFlash($front_title.' Pasien Perjanjian Operasi Berhasil', 'bg-success');
			redirect('registrasi/operasi_terjadwal/');
			return;
		}
		$this->init_crud_assets();
		$this->load->model(array('pendaftaranperjanjianoperasimodel'));
		$row = new stdClass();
		$row->no_perjanjian_operasi = $no_perjanjian;
		$data_raw = $this->buildDetailHiddenForm($row);
		$data['show_except_komen'] = 'none';
		$data['profile'] = $data_raw['data'][0];
		$data['status_to'] = $status_to;
		$data['view'] = "operasi_terjadwal/form_ubah_status";
		$data['front_title'] = $front_title;
		$data['title'] = $front_title." Operasi Terjadwal Pasien ".$data['profile']['nama']."(".$data['profile']['no_perjanjian_operasi'].")";
		
		switch($status_to){
			case '-4':
				$data['additional_field'] = '<div class="form-group"><div class="col-sm-2">&nbsp;</div><div class="col-sm-4"><input name="ibs_tidak_monitoring_batal" type="checkbox" value="1">&nbsp;<span>Pasien Tidak Dimonitor Oleh IBS Dalam Laporan Operasi Tertunda</span></div></div>';
				break;
			default:
				$data['additional_field'] = '';
				break;
		}
		
		$data_for_js['date_limit'] = date('d-m-Y',strtotime(date('Y-m-d') . "+1 days"));
		$data_for_js['date_limit_op'] = date('d-m-Y',strtotime(date('Y-m-d') . "+3 days"));
		$data_for_js['elem_t'] = $data_for_js['elem_ds'] = $data_for_js['elem_tp'] = [];
		$this->template->write_view("js_bottom_scripts", 'registrasi/operasi_terjadwal/form_js', $data_for_js);
		$this->preRender($data);
	}
	
	//halaman
	function form_konfirmasi_ibs($no_perjanjian=''){
		$this->load->model('pendaftaranperjanjianoperasimodel');
		if($this->input->post()){
			$submit = $this->input->post();
			
			$data_perjanjian = $this->pendaftaranperjanjianoperasimodel->find('no_perjanjian_operasi = "'.$submit['no_perjanjian'].'"', $order=null, $limit=null, $offset=null,'id,rute,poli_asal');
			$status = ($submit['days_before_surgery'] == '0')?'4':'1';
			
			
			/*$status = ($data_perjanjian[0]['poli_asal'] <> '300')?'1':'4';
			$day = date('N',strtotime($submit['tgl_pelaksanaan'] . '-'.$submit['days_before_surgery'].' days'));
			if($data_perjanjian[0]['poli_asal'] <> '300'){
				switch($day){
					case '7' :
						$submit['days_before_surgery'] = $submit['days_before_surgery'] + 3;
						break;
					case '6' : 
						$submit['days_before_surgery'] = $submit['days_before_surgery'] + 2;
						break;
					default  : break;
				}
			}*/
			
			$save_data = [
				'id'=>$data_perjanjian[0]['id'],
				'rute'=>$this->generateRute('1','O','',json_decode($data_perjanjian[0]['rute'])),
				'ruang_ok'=>$submit['ruang_ok'],
				'tgl_pelaksanaan'=>display_date($submit['tgl_pelaksanaan'],'Y-m-d H:i:s'),
				'tgl_reg_ranap'=>display_date($submit['tgl_reg_ranap'],'Y-m-d'),
				'tgl_cek_kamar'=>display_date($submit['tgl_cek_kamar'],'Y-m-d'),
				'status'=>$status
			];
			
			$this->pendaftaranperjanjianoperasimodel->save($save_data);
			return;
		}
		if(empty($no_perjanjian)){
			redirect('registrasi/operasi_terjadwal/');
			return;
		}
		$this->init_crud_assets();
		$this->load->model(array('mastersumberreferensimodel'));
		$row = new stdClass();
		$row->no_perjanjian_operasi = $no_perjanjian;
		$data_raw = $this->buildDetailHiddenForm($row);
		$data['profile'] = $data_raw['data'][0];
		if($data['profile']['status'] != 'O'){
			redirect('registrasi/operasi_terjadwal/');
			return;
		}
		$data['status_to'] = '1';
		$data['view'] = "operasi_terjadwal/form_konfirmasi_ibs";
		$data['title'] = "Set Tgl Operasi Terjadwal Pasien ".$data['profile']['nama']."(".$data['profile']['no_perjanjian_operasi'].")";
		$data['ddruang_ok'] = array_map(function($arr){$row = json_decode($arr['uraian_json'],true);return ['kode_ref'=>$arr['kode_ref'],'nama_gedung'=>$row['nama_gedung'].' - '.$row['nama_ruang']];},$this->mastersumberreferensimodel->find('kode_ref <> "000" and tipe_ref = "[OK]" and uraian_json like \'%"is_active":"1"%\'', $order=null, $limit=null, $offset=null,'uraian_json, kode_ref'));
		$this->css_theme_project[] = 'bootstrap-datetimepicker';
		$this->js_theme_project[] = 'bootstrap-datetimepicker';
		$this->template->write('js_bottom_scripts','<script>$(document).ready(function() {$("form#perjanjian .datetimepicker").datetimepicker({format: time_format,startDate:"'.date('Y-m-d',strtotime(date('Y-m-d') . "+1 days")).'"});$("form#perjanjian .datepicker_cek_kamar").datepicker({format: time_format,startDate:"'.date('Y-m-d',strtotime(date('Y-m-d') . "+1 days")).'"})});$("form#perjanjian").validate({ignore: null});</script>', FALSE);
		
		$this->preRender($data);
	}
	
	//halaman
	function laporan_operasi_terjadwal(){
		$this->init_crud_assets();
		$this->js_theme_project[] = 'js_excel/jquery.battatech.excelexport';
		$this->js_theme_project[] = 'sweetalert.min';
		$this->css_theme_project[] = 'sweetalert';
		$submit = $this->input->post();
		$this->load->model(['mastersumberreferensimodel']);
		$output['status_kunjungan'] = _parseDropdownAllElem($this->mastersumberreferensimodel->find("kode_ref <> '000' and tipe_ref = '[SPO]' ", $order='kode_ref_order', $limit=null, $offset=null,'cast(kode_ref as signed) as kode_ref_order,kode_ref,uraian'),'uraian','kode_ref','awal-kosong');
		$output['output'] = [];
		$output['date_format_in_field'] = 'd-m-Y';
		$output['title'] = 'Laporan Operasi Terjadwal';
		$this->template->write('js_bottom_scripts','<script>$("form#form_report").validate({ignore: null});</script>', FALSE);
		//submit
		if(!empty($submit)){
			$this->load->model(['pendaftaranperjanjianoperasimodel','usermodel']);
			$user_arr = _parseDropdownAllElem($this->usermodel->find('', null, $limit=null, $offset=null,'user.name,user.id'),'name','id');
			$sumber_ref_arr = $this->mastersumberreferensimodel->getInfo(['[SPO]'=>'uraian','FLA'=>'uraian']);
			$output['output'] = $this->pendaftaranperjanjianoperasimodel->data_report($submit);
			foreach($output['output'] as $key=>$data){
				$decoded_data = !empty($data['catatan_pra_operasi'])?json_decode($data['catatan_pra_operasi'],true):[];
				$output['output'][$key]['diagnosa'] = isset($decoded_data['diagnosa_utama'])?$decoded_data['diagnosa_utama']:'';
				$output['output'][$key]['tindakan'] = isset($decoded_data['tindakan'])?$decoded_data['tindakan']:'';
				$output['output'][$key]['rute_desc'] = $this->pendaftaranperjanjianoperasimodel->buildRute($data['rute'],$sumber_ref_arr['[SPO]'],$user_arr);
				$output['output'][$key]['butuh_icu'] = $sumber_ref_arr['FLA'][$data['is_need_icu']];
				}
		}
		$this->preRender($output,"",'operasi_terjadwal/laporan');
	}
	
	protected function isTodayButtonActionExist($obj_perjanjian){
		/*
		if(date('Y-m-d',strtotime($obj_perjanjian->tgl_cek_kamar)) <= date('Y-m-d') and  date('Y-m-d',strtotime($obj_perjanjian->tgl_cek_kamar.' +1 day')) >= date('Y-m-d') and date('Y-m-d',strtotime($obj_perjanjian->tgl_pelaksanaan)) > date('Y-m-d'))
			return TRUE;
		return FALSE;	
		*/
		//(jika tgl ini lebih besar dan sama dgn tgl cek kamar) dan (tgl ini lebih kecil dan sama dengan tgl registrasi ranap)
		if(date('Y-m-d',strtotime($obj_perjanjian->tgl_cek_kamar)) <= date('Y-m-d') and date('Y-m-d') <= date('Y-m-d',strtotime($obj_perjanjian->tgl_pelaksanaan.' -'.$obj_perjanjian->days_before_surgery.' day'))){
			return TRUE;
		}
		return FALSE;
	}
	
	protected function cekEksis($no_rm){
		$this->load->model(['pendaftaranperjanjianoperasimodel','pasienmodel']);
		$this->pasienmodel->db->join('pendaftaran_perjanjian_operasi',$this->pendaftaranperjanjianoperasimodel->qIsPasienAvailableToRegister('join'),'left');
		$this->pasienmodel->changeResultMode('array');
		$pasien_exist = $this->pasienmodel->find('pendaftaran_perjanjian_operasi.no_rm is not null and pasien.no_rm = "'.$no_rm.'"',null,null,null,'pendaftaran_perjanjian_operasi.no_rm,no_perjanjian_operasi');
		if(!empty($pasien_exist)){
			$this->parentSetFlash('Pasien masih dalam proses perjanjian elektif, <a target="_blank" href="'.site_url('registrasi/operasi_terjadwal/bukti_perjanjian_utk_pasien/'.$pasien_exist[0]['no_perjanjian_operasi'].'/').'">print bukti</a>', 'alert-success');
			redirect('registrasi/operasi_terjadwal/','refresh');
		}
		return;
	}
}?>