<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "ele.php";

class Ibs_operasi_terjadwal extends Ele {
	protected $allows = ['get_time_range'];
	public function __construct() {  
		parent::__construct();
		$this->kondisi_unit = ' and kode_ref in ("O","1","4","5") ';
	}
	
	//HALAMAN 
	function ibs_list_pasien($isCito = ''){
		$this->isCito = $isCito;
		$this->default_display_tgl = 'showTglKunjunganIBS';
		$this->default_aksi_dalam_list = 'showLinkPerjanjianIBS';
		$this->ibs_list_pasien_operasi_terjadwal();
	}
	
	function ibs_list_pasien_operasi_terjadwal(){
		$this->css_theme_project = ['bootstrap','main','DT_bootstrap','override','jquery.slidepanel','style','jquery.sliding_menu','bootstrap-datetimepicker','datepicker/1.7.1/bootstrap-datepicker.min'];
		$this->js_theme_project = ['DT_bootstrap','bootstrap.min','jquery.sliding_menu','bootstrap-typeahead','bootstrap-datetimepicker-inside-td','datepicker/1.7.1/bootstrap-datepicker','datepicker/1.7.1/locales/bootstrap-datepicker.id','bootbox','jquery.validate.min','jquery.alphanumeric','jquery.alphanumeric.pack','main'/*,'media/registrasi/form_list'*/,'media/date-helpers'];
		
		$this->css_theme_project[] = 'sweetalert';
		$this->js_theme_project[] = 'sweetalert.min';
		$argumen['title'] = 'List Perjanjian Operasi';
		$this->load->model(array('mastersumberreferensimodel','pendaftaranperjanjianoperasimodel'));
		$this->mastersumberreferensimodel->changeResultMode();
		$data_form['status_kunjungan'] = _parseDropdownAllElem($this->mastersumberreferensimodel->find("kode_ref <> '000' and tipe_ref = '[SPO]' ".$this->kondisi_unit, $order='kode_ref_order', $limit=null, $offset=null,'cast(kode_ref as signed) as kode_ref_order,kode_ref,uraian'),'uraian','kode_ref','awal-kosong');
		$output['extra'] = $this->load->view('registrasi/operasi_terjadwal/ibs_form_list_perjanjian_operasi',$data_form,TRUE);
		$this->pasien_perjanjian_id = '';
		$output['output'] = "";
		if($this->input->post()){
			$this->js_theme_project[] = 'facebox';
			$this->css_theme_project[] = 'facebox';
			$this->dd_ruang_ok = '';
			$this->mastersumberreferensimodel->changeResultMode('array');
			$this->getSumberRefValue(['[PWO]'=>'uraian','[OK]'=>'uraian']);
			$list_ruang_ok = array_map(function($arr){$row = json_decode($arr['uraian_json'],true);return ['kode_ref'=>$arr['kode_ref'],'nama_gedung'=>$row['nama_gedung'].' - '.$row['nama_ruang']];},$this->mastersumberreferensimodel->find('kode_ref <> "000" and tipe_ref = "[OK]" and uraian_json like \'%"is_active":"1"%\'', $order=null, $limit=null, $offset=null,'uraian_json, kode_ref'));
			foreach($list_ruang_ok as $ruang_ok){
				$this->dd_ruang_ok .= '<option value="'.$ruang_ok['kode_ref'].'">'.$ruang_ok['nama_gedung'].'</option>';
			}
			$this->load->library('grocery_crud/grocery_CRUD_extended');
			$crud = new grocery_CRUD_extended();
			$crud->set_theme('flexigrid-aris');
			$crud->set_table($this->pendaftaranperjanjianoperasimodel->janji_operasi);
			$crud->set_relation('operator',$this->pendaftaranperjanjianoperasimodel->master_pegawai,'nama');
			$crud->set_primary_key('no_rm',$this->pendaftaranperjanjianoperasimodel->tabel_master_pasien);
			$crud->set_relation('no_rm',$this->pendaftaranperjanjianoperasimodel->tabel_master_pasien,'{no_rm} - {nama}<br/><b>{tanggal_lahir}</b>');
			$crud->set_primary_key('kode_poli',$this->pendaftaranperjanjianoperasimodel->tabel_master_poli);
			$crud->set_relation('poli_asal',$this->pendaftaranperjanjianoperasimodel->tabel_master_poli,'nama_poli');
			$crud->set_relation('kelas','master_kelass','nama_kelas');			
			$crud->set_primary_key('no_perjanjian_operasi','v_operasi_ruang_asal');
			$crud->set_relation('no_perjanjian_operasi','v_operasi_ruang_asal','nama_ruang');
			//$crud->set_relation('diagnosa','icd10','topik');
			//$crud->set_relation('tindakan','icd9','NM_ICD9CM');
			$crud->columns('no_rm','no_kontak_pasien','diagnosa','tindakan','operator','ruang_ok','alat tambahan','perkiraan lama operasi','is_need_icu','pengencer_darah','poli_asal','no_perjanjian_operasi','Tgl Buat Perjanjian','tgl_pelaksanaan','actions');
			$crud->display_as('no_perjanjian_operasi','Nama Ruangan');
			$crud->unset_add();
			$crud->unset_edit();
			$crud->unset_read();
			$crud->unset_delete();
			$crud->unset_columns_order = $crud->unset_columns_filter = array('no_rm','s97d3371e','tgl_pelaksanaan','alat tambahan','perkiraan lama operasi','is_need_icu','pengencer_darah','actions','Tgl Buat Perjanjian');
			$text_filter = '';
			
			$submit=$this->input->post();
			
			if($submit['status_kunjungan'] == '> 0'){
				$crud->where($this->pendaftaranperjanjianoperasimodel->janji_operasi.'.status >',0);
				$this->template->write('js_bottom_scripts','<script>$(document).ready(function() {$(".form-filter-worksheet").addClass("hide");})</script>', FALSE);
				$this->template->write_view("js_bottom_scripts", '/registrasi/operasi_terjadwal/ibs_form_jadwal_js', null);
			}else{
				$crud->where($this->pendaftaranperjanjianoperasimodel->janji_operasi.'.status',$submit['status_kunjungan']);
				$this->jns_janji = $submit['jns_janji'];
				switch($submit['jns_janji']){
					case'ODC':
						$text_filter .= '<input type="hidden" name="jns_janji" value="'.$submit['jns_janji'].'">';
						$crud->where('catatan_pra_operasi REGEXP "\"tipe_op\":\"ELEKTIF-ODC\""');
						break;
					case'C':
						$text_filter .= '<input type="hidden" name="jns_janji" value="'.$submit['jns_janji'].'">';
						$crud->where('catatan_pra_operasi REGEXP "\"tipe_op\":\"ELEKTIF-CITO\""');
						break;
					default:
						$crud->where('catatan_pra_operasi NOT REGEXP "\"tipe_op\":\"(ELEKTIF-ODC|ELEKTIF-CITO)\""');
						break;
				}
				// if(isset($submit['is_odc'])){
				// 	$regex = 'regexp';
				// 	$text_filter .= '<input type="hidden" name="is_odc" value="'.$submit['is_odc'].'">';
				// }
				// $crud->where('catatan_pra_operasi '.$regex.' "\"tipe_op\":\"ELEKTIF-ODC\""');
			}
			
			$text_filter .= '<input type="hidden" name="status_kunjungan" value="'.$submit['status_kunjungan'].'">';
			if(!empty($submit['created_date'])){
				$crud->where('catatan_pra_operasi like','\'%"created_date":"'.display_date($submit['created_date'],'Y-m-d').'%\'',false);
				$text_filter .= '<input type="hidden" name="created_date" value="'.$submit['created_date'].'">';
			}
			if(!empty($submit['tgl_kunjungan'])){
				$crud->where('date(tgl_pelaksanaan) =',display_date($submit['tgl_kunjungan'],'Y-m-d'));
				$text_filter .= '<input type="hidden" name="tgl_kunjungan" value="'.$submit['tgl_kunjungan'].'">';
			}
			if(!empty($submit['tgl_cek_kamar'])){
				$crud->where('date(tgl_cek_kamar) =',display_date($submit['tgl_cek_kamar'],'Y-m-d'));
				$text_filter .= '<input type="hidden" name="tgl_cek_kamar" value="'.$submit['tgl_cek_kamar'].'">';
			}
			
			
			$crud->callback_column('actions',array($this,$this->default_aksi_dalam_list));
			$crud->callback_column('tgl_pelaksanaan',array($this,$this->default_display_tgl));
			$crud->callback_column('perkiraan lama operasi',array($this,'showLamaOperasi'));
			$crud->callback_column('ruang_ok',array($this,'showRuangOK'));
			$crud->callback_column('is_need_icu',array($this,'showNeedIcu'));
			$crud->callback_column('pengencer_darah',array($this,'showPengencerDarah'));
			$crud->callback_column('diagnosa',array($this,'showDiagnosa'));
			$crud->callback_column('tindakan',array($this,'showTindakan'));
			$crud->callback_column('Tgl Buat Perjanjian',array($this,'showCreatedDate'));
			$crud->callback_column('alat tambahan',array($this,'showAlatTambahan'));
			$crud->display_as('poli_asal','Asal Poli')->display_as('tgl_pelaksanaan','Tgl Operasi')->display_as('is_need_icu','Kebutuhan ICU');
			if($submit['status_kunjungan'] == 'O')
				$crud->display_as('tgl_pelaksanaan','Perkiraan Tgl Operasi');
			$output = $crud->render($output['extra']);
			$this->template->write_view("js_bottom_scripts", '/registrasi/form_list_js', null);
			$this->template->write('js_bottom_scripts','<script>$(document).ready(function() {$( ".quickSearchBox" ).append( \''.$text_filter.' \');})</script>', FALSE);
		}
		$forJS['isCito'] = ($this->isCito == 'cito')?1:0;
		$this->template->write_view("js_bottom_scripts", '/registrasi/operasi_terjadwal/ibs_form_list_js', $forJS);
		$this->ibsPreRender($output,$argumen['title'],'Page/grocery_crud_content');
		return;
	}

	//HALAMAN
	function get_time_range(){
		if($this->input->post()){
			$r = [];$s = $this->input->post();
			$r[] = date('Y-m-d H:i',strtotime($s['t'] .'+1 minutes'));
			$H = 60 * 12;
			$r[] = date('Y-m-d H:i',strtotime($s['t'] .'+'.$H.' minutes'));
			echo json_encode($r);
		}
	}
	
	function showLinkPerjanjianIBS($value,$row){
		switch($row->status){
			case 'O':
				$cl = 'datetimepicker';
				$additionalC = $vD = '';
				if($this->jns_janji == 'C'){
					$vD = date('d-m-Y H:i',strtotime($row->tgl_pelaksanaan));
					$cl = '';
					$additionalC = '<input type="button" value="saya mau ubah waktu" class="btn btn-mini btn-warning" onclick="waktiOPEditor.init(this)">';
					// $dD = [date('d-m-Y'),date('d-m-Y',strtotime('+1 day'))];
					// array_walk(range(0,23),function($v) use(&$hD){$hD[] = str_pad($v, 2, '0', STR_PAD_LEFT);});
					// array_walk(range(0,59),function($v) use(&$mD){$mD[] = str_pad($v, 2, '0', STR_PAD_LEFT);});
					
					// $additionalC = '<br>'.myform_dropdown('',$dD,'','class="ddH"').myform_dropdown('',$hD,'','class="ddH"').myform_dropdown('',$mD,'','class="ddM"');
				}


				$return [] = '<div id="form_acc_'.$row->no_perjanjian_operasi.'" class="form_acc"><select class="ruang_ok" name="ruang_ok_'.$row->no_perjanjian_operasi.'" id="ruang_ok_'.$row->no_perjanjian_operasi.'">'.$this->dd_ruang_ok.'</select><input class="form-control dateOp '.$cl.'" type="text" readonly name="tgl_pelaksanaan_'.$row->no_perjanjian_operasi.'" id="tgl_pelaksanaan_'.$row->no_perjanjian_operasi.'" value="'.$vD.'" placeholder="tgl operasi">'.$additionalC.'<input type="hidden" value="'.$row->days_before_surgery.'" class="days_before_surgery"><input type="hidden" value="'.$row->no_perjanjian_operasi.'" class="no_perjanjian_operasi"><input type="hidden" value="'.$row->s97d3371e.'" class="pasien">
				<input type="hidden" class="poli_asal" value="'.$row->poli_asal.'">
				<input type="hidden" class="cek_kamar" value="'.display_date(preg_replace('/\s[0-9]{2}:.{1,}/','',$row->tgl_cek_kamar),'d-m-Y').'">
				<input type="hidden" class="reg_ranap" value="'.display_date(preg_replace('/\s[0-9]{2}:.{1,}/','',!empty($row->tgl_reg_ranap)?$row->tgl_reg_ranap:date('Y-m-d',strtotime($row->tgl_cek_kamar .'+0 days'))),'d-m-Y').'">
				<input type="hidden" class="perkiraan_operasi" value="'.preg_replace('/\s[0-9]{2}:.{1,}/','',$row->tgl_pelaksanaan).'">
				<br/><br/><a class="btn btn-success edit-btn" onclick="doIBSAcc(this)" href="javascript:void(0);" id="do_acc_'.$row->no_perjanjian_operasi.'"> Next</a></div>';
				break;
			case '4':
				//belum diketahui
				if(date('Y-m-d',strtotime($row->tgl_pelaksanaan)) <= date('Y-m-d')){
					$detail_form = $this->buildDetailHiddenForm($row,'<input class="form-control datetimepicker" type="text" readonly value="" placeholder="waktu masuk OK" name="waktu_masuk_ok"><input type="button" value="submit" class="submit_to_finish">');
					$return[] = '<div class="hide form_to_submit">'.$detail_form['form'].'</div>';
					$return[] = '<a class="btn btn-mini add-reg-btn finishkan" onclick="finishkan(\''.$row->no_perjanjian_operasi.'\')"><i class="icon-plus"></i> Proses Perjanjian Selesai</a>';
				}
				//'batal-perjanjian-yg-bisa-dimonitor-oleh-ibs'
				$return[] = '<a class="btn btn-mini edit-btn" href="'.BASEURL.'/registrasi/operasi_terjadwal/form_ubah_status/'.$row->no_perjanjian_operasi.'/-4/">Batal</a>';
				break;
			default :
				$return[] = '<a class="btn btn-mini edit-btn" target="_blank" href="'.BASEURL.'/registrasi/operasi_terjadwal/cetak_bukti_perjanjian/'.$row->no_perjanjian_operasi.'">Print Bukti Perjanjian</a>';
				break;
		}
		return implode('&nbsp;',$return);
	}
	
	function showLinkFormJadwalIBS($value,$row){
		$return [] = '<input type="hidden" class="no_rm" value="'.$row->no_rm.'">';
		$return [] = '<input type="hidden" class="no_perjanjian" value="'.$row->no_perjanjian_operasi.'">';
		$return [] = '<input type="hidden" class="status" value="'.$row->status.'">';
		$return [] = '<input type="hidden" class="days_before_surgery" value="'.$row->days_before_surgery.'">';
		$return [] = '<input type="hidden" value="'.$row->s97d3371e.'" class="pasien">';
		$return [] = '<input type="hidden" value="'.$row->s4b583376.'" class="operator">';
		$return [] = '<input type="hidden" value="'.$row->rencana_pembiusan.'" class="rencana_pembiusan">';
		$return [] = '<input type="hidden" value="'.displayAcronim($row->is_need_icu,array_flip(['Y'=>'Ya','T'=>'Tidak'])).'" class="is_need_icu">';
		$return [] = '<input type="hidden" value="'.display_date($row->tgl_pelaksanaan,'H:i').'" class="jam_pelaksanaan">';
		$return [] = '<input type="hidden" value="'.$row->ruang_ok.'" class="ruang_ok">';
		$return [] = '<input type="hidden" value="'.$this->tgl_op_id.'" class="tgl_op_id">';
		switch($row->status){
			case '4':
				//act fill jadwal
				//if jadwal sudah di set, tampilkan text 'jadwal untuk besok terlengkapi'
				$note = json_decode($row->catatan_pra_operasi,true);
				$note_info = '';
				if(isset($note[$this->tgl_op_id.'-info'])){
					$note_info = json_encode($note[$this->tgl_op_id.'-info']);
					$return [] = 'jadwal untuk besok terlengkapi';
				}
				$return [] = '<input type="hidden" class="tindakan" value="'.$note['tindakan'].'">';
				$return [] = '<input type="hidden" value=\''.$note_info.'\' class="note">';
				$return [] = '<span class="hide dd_ruang_ok"><select class="ruang_ok" name="ruang_ok" id="ruang_ok">'.$this->dd_ruang_ok.'</select></span>';
				$return [] = '<a class="btn btn-mini edit-btn" onclick="doIBSScheduller(this)" href="javascript:void(0);" id="do_acc_'.$row->no_perjanjian_operasi.'">Input Keterangan Pra Tindakan</a>';
				break;
			default :
				//act bypass
				$return [] = '<a class="btn btn-mini edit-btn" onclick="doIBSBypasser(this)" href="javascript:void(0);" id="do_bypass_'.$row->no_perjanjian_operasi.'">By Pass Perjanjian</a>';
				break;
		}
		return implode('&nbsp;',$return);
	}

	function showNeedIcu($value,$row){
		return displayYesNo($value);
	}
	
	function showDiagnosa($value,$row){
		$this->catatan_pra_operasi = $this->pendaftaranperjanjianoperasimodel->decodedJsonField('id = '.$row->id,'catatan_pra_operasi');
		return isset($this->catatan_pra_operasi['diagnosa_utama'])?$this->catatan_pra_operasi['diagnosa_utama']:'';
	}
	
	function showTindakan($value,$row){
		return isset($this->catatan_pra_operasi['tindakan'])?$this->catatan_pra_operasi['tindakan']:'';
	}
	
	function showAlatTambahan($value,$row){
		return isset($this->catatan_pra_operasi['kebutuhan_alat'])?$this->catatan_pra_operasi['kebutuhan_alat']:'';
	}
	
	function showPengencerDarah($value,$row){
		// if($row->poli_asal == '300')
			// return 'Flow Ranap';
		// $return = '<span style="color:#rplc_color">#replace</span>';
		
		// $replace = isset($this->catatan_pra_operasi['blThnrCnsmd'])?displayYesNo($this->catatan_pra_operasi['blThnrCnsmd']):'Undefined';
		// $rplc_color = $replace=='Ya'?'red':'black';
		// return str_replace(['#rplc_color','#replace'],[$rplc_color,$replace],$return);
		return $this->wrap_pengencer($row->poli_asal);
	}
	
	protected function wrap_pengencer($pa){
		if($pa == '300') return 'Flow Ranap';
		$return = '<span style="color:#rplc_color">#replace</span>';
		
		$replace = isset($this->catatan_pra_operasi['blThnrCnsmd'])?displayYesNo($this->catatan_pra_operasi['blThnrCnsmd']):'Undefined';
		$rplc_color = $replace=='Ya'?'red':'black';
		return str_replace(['#rplc_color','#replace'],[$rplc_color,$replace],$return);
	}
	
	function showCreatedDate($value,$row){
		return isset($this->catatan_pra_operasi['created_date'])?display_date($this->catatan_pra_operasi['created_date'],'d-m-Y H:i'):'';
	}
	
	function showLamaOperasi($value,$row){
		return isset($this->master_ref['[PWO]'][$row->perkiraan_lama_operasi])?$this->master_ref['[PWO]'][$row->perkiraan_lama_operasi]:'';
	}
	
	function showRuangOK($value,$row){
		if($this->default_aksi_dalam_list == 'showLinkFormJadwalIBS')
			return $value;
		return isset($this->master_ref['[OK]'][$value])?$this->master_ref['[OK]'][$value]:'';
	}
	
	function getSumberRefValue($arr_info){
		$this->master_ref = $this->mastersumberreferensimodel->getInfo($arr_info);
	}
	
	//HALAMAN
	function form_final($no_perjanjian = ''){
		if(empty($no_perjanjian)){
			redirect('registrasi/ibs_operasi_terjadwal/laporan_operasi_terjadwal/');
			return;
		}
		//kalau bukan status 5 kembali ke laporan
		if($this->input->post()){
			
		}
		return;
	}
	
	//HALAMAN
	function frm_jadwal_pasien(){
		$date_target = isset($_GET['date_target'])?$_GET['date_target']:'';
		$today_target = !empty($date_target)?$date_target:date('Y-m-d');
		$adding_day = /*(date('w',strtotime($today_target) == 4))?'3':'1';*/ '1';
		$this->default_display_tgl = 'showTglKunjunganIBS';
		$_POST['tgl_kunjungan'] = !isset($_POST['tgl_kunjungan'])?date('d-m-Y',strtotime($today_target .'+'.$adding_day.' days')):$_POST['tgl_kunjungan'];
		$_POST['status_kunjungan'] = '> 0';
		$this->tgl_op_id = !isset($_POST['tgl_kunjungan'])?date('Y-m-d',strtotime($today_target .'+'.$adding_day.' days')):date('Y-m-d',strtotime($_POST['tgl_kunjungan']));
		$this->default_aksi_dalam_list = 'showLinkFormJadwalIBS';
		$this->ibs_list_pasien_operasi_terjadwal();
		return;
	}
	
	//HALAMAN
	function laporan_operasi_terjadwal(){
		$this->init_crud_assets();
		$this->js_theme_project[] = 'js_excel/jquery.battatech.excelexport';
		$this->js_theme_project[] = 'sweetalert.min';
		$this->css_theme_project[] = 'sweetalert';
		$submit = $this->input->post();
		$output['output'] = [];
		$output['date_format_in_field'] = 'd-m-Y';
		$output['title'] = 'Laporan Operasi Terjadwal';
		$this->template->write('js_bottom_scripts','<script>$("form#form_report").validate({ignore: null});</script>', FALSE);
		//submit
		if(!empty($submit)){
			$this->load->model(array('pendaftaranperjanjianoperasimodel','usermodel','mastersumberreferensimodel'));
			$raw_data = $this->pendaftaranperjanjianoperasimodel->data_report_ibs($submit);
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
					$this->catatan_pra_operasi = $json_pra_operasi;
					$raw_data[$key]['encer'] = $this->wrap_pengencer($data['poli_asal']);
				}
				$output['output'] = $raw_data;
			}
		}
		$this->preRender($output,"",'operasi_terjadwal/ibs_laporan');
		return;
	}
	
	//halaman
	public function laporan_operasi(){
		$this->load->model('PendaftaranPerjanjianOperasiModel');
		$data = $this->input->post();		
		$data = $this->PendaftaranPerjanjianOperasiModel->laporan_operasi($data['tgl1'], $data['tgl2']);		
		$this->set('data',$data);
		$data2 = $this->input->post();
	    $this->set('data2',$data2);
		$this->render('laporan_operasi');
    }
	
	function reschedule(){
		if($this->input->post()){
			$data = $this->input->post();
			echo '<script>window.location = "'.BASEURL.'/registrasi/operasi_terjadwal/form_ubah_status/'.$data['no_perjanjian'].'/'.$data['aksi'].'/";</script>';
		}
		$this->render('jadwal_ulang');
	}

	//halaman
	function submit_status(){
		if($this->input->post()){
			$this->load->model('pendaftaranperjanjianoperasimodel');
			$submit = $this->input->post();
			$data_perjanjian = $this->pendaftaranperjanjianoperasimodel->find('no_perjanjian_operasi = "'.$submit['no_perjanjian'].'"', $order=null, $limit=null, $offset=null,'id,rute,no_rm');
			$save_data = [
				'id'=>$data_perjanjian[0]['id'],
				'status'=>$submit['status_to']
			];
			switch($submit['status_to']){
				case '5':
					//status selesai perjanjian
					$save_data['rute'] = $this->generateRute($submit['status_to'],$submit['status_from'],'',json_decode($data_perjanjian[0]['rute']));
					$save_data['catatan_pasca_operasi'] = json_encode(['waktu_masuk_ok'=>display_date($submit['waktu_masuk_ok'],'Y-m-d H:i:s')]);
					break;
				case '-4':
					//status batal sebelum operasi tapi sudah inap
					
					break;
				default :
					break;
			}
			$this->pendaftaranperjanjianoperasimodel->save($save_data);
		}
		return;
	}
	
	//halaman
	function laporan_monitor_pasien_batal_op_sudah_inap(){
		$this->init_crud_assets();
		$this->js_theme_project[] = 'js_excel/jquery.battatech.excelexport';
		$output['output'] = [];
		$output['date_format_in_field'] = 'd-m-Y';
		$output['title'] = 'Laporan';
		$this->template->write('js_bottom_scripts','<script>$("form#form_report").validate({ignore: null});</script>', FALSE);
		if($this->input->post()){
			$this->load->model(array('pendaftaranperjanjianoperasimodel','usermodel','mastersumberreferensimodel'));
			$submit = $this->input->post();
			$raw_data = $this->pendaftaranperjanjianoperasimodel->data_report_monitor_batal_ibs($submit);
			if(!empty($raw_data)){
				foreach($raw_data as $key=>$data){
					$json_pra_operasi = !empty($data['json_pra_operasi'])?json_decode($data['json_pra_operasi'],true):'';
					$raw_data[$key]['rute_decode'] = json_decode($data['rute'],true);
					$raw_data[$key]['diagnosa'] = isset($json_pra_operasi['diagnosa_utama'])?$json_pra_operasi['diagnosa_utama']:'';
					$raw_data[$key]['tindakan'] = isset($json_pra_operasi['tindakan'])?$json_pra_operasi['tindakan']:'';
					$perjanjian_berikutnya = $this->pendaftaranperjanjianoperasimodel->data_perjanjian_setelah_batal($data['id'],$data['no_reg_pasien']);
					$raw_data[$key]['perjanjian_berikutnya'] = [];
					if(!empty($perjanjian_berikutnya)){
						$raw_data[$key]['perjanjian_berikutnya'] = $perjanjian_berikutnya[0];
					}
				}
			}
			$output['output'] = $raw_data;
		}
		//form
		$this->preRender($output,"",'operasi_terjadwal/ibs_laporan_monitor');
		return;
	}
	
	private function ibsPreRender($data,$title = null,$view = null){
		$title = (!empty($title))?$title:$data['title'];
		$view = (!empty($view))?$view:$data['view'];
		
		$this->writeTitle($title);
		$this->addCss();
        $this->addJquery();
		//$this->template->write('js_top_scripts','<script src="'. ASSETURL .'js/datepicker/1.7.1/bootstrap-datepicker.js?.'.$this->config->item('asset_js_version').'"></script>', FALSE);
		//$this->template->write('js_bottom_scripts','<link rel="stylesheet" type="text/css" href="'.ASSETURL.'css/datepicker/1.7.1/bootstrap-datepicker.min.css?.'.$this->config->item('asset_css_version').'" />', FALSE);
        $this->addJs('js');
        $this->addBaseJs();
		
        $this->addTopPanel($this->config->item('company_name'));
		$this->addLeftPanel();
		
		$this->template->write_view("content", "registrasi/".$view,$data);
		$this->template->render();
	}
	
	//halaman
	function frm_bypass_ibs(){
		$list_rute['elektif_poli'] = [['from'=>'O','to'=>'1'],['from'=>'1','to'=>'3'],['from'=>'3','to'=>'4']];
		$list_rute['elektif_selain_poli'] = [['from'=>'O','to'=>'4']];
		if($this->input->post()){
			$this->load->model('pendaftaranperjanjianoperasimodel');
			$submit = $this->input->post();
			$data_perjanjian = $this->pendaftaranperjanjianoperasimodel->find('no_perjanjian_operasi = "'.$submit['no_perjanjian'].'"', $order=null, $limit=null, $offset=null,'id,rute,poli_asal');
			$arr_selected = ($submit['days_before_surgery'] == '0')?$list_rute['elektif_selain_poli']:$list_rute['elektif_poli'];
			$key_selected = 0;
			$status_skrg = $submit['status_skrg'];
			array_walk($arr_selected,function ($val,$k) use (&$key_selected,$status_skrg) {if($status_skrg== $val['from']){$key_selected = $k;}});
			//bangun rute
			$save_data['rute'] = $data_perjanjian[0]['rute'];
			for($i=$key_selected;$i<=(count($arr_selected)-1);$i++){
				$save_data['rute'] = $this->generateRute($arr_selected[$i]['to'],$arr_selected[$i]['from'],$alasan = 'bypass', json_decode($save_data['rute']));
				switch($arr_selected[$i]['to']){
					case ($arr_selected[$i]['from'] == '1' && $arr_selected[$i]['to'] == '3'):
						$save_data['no_pj_carter'] = 'Cbypass';
						break;
					case ($arr_selected[$i]['from'] == '3' && $arr_selected[$i]['to'] == '4'):
						$save_data['no_reg_pasien'] = $submit['no_reg'];
						break;
					default: break;
				}
			}
			$save_data['status'] = $submit['status'];
			$save_data['id'] = $data_perjanjian[0]['id'];
			$this->pendaftaranperjanjianoperasimodel->save($save_data);
		}
		return;
	}
	
	//halaman
	function frm_jadwal_ibs(){
		if($this->input->post()){
			$this->load->model('pendaftaranperjanjianoperasimodel');
			$submit = $this->input->post();
			
			$data_perjanjian = $this->pendaftaranperjanjianoperasimodel->find('no_perjanjian_operasi = "'.$submit['no_perjanjian'].'"', $order=null, $limit=null, $offset=null,'id,catatan_pra_operasi');
			$catatan_pra_operasi = json_decode($data_perjanjian[0]['catatan_pra_operasi'],true);
			array_walk($catatan_pra_operasi,function ($val,$k) use (&$key_selected) {if(preg_match('/-info/',$k)){$key_selected = $k;}});
			unset($catatan_pra_operasi[$key_selected]);
			
			$catatan_pra_operasi[$submit['tgl_op_id'].'-info'] = ['opr'=>$submit['opr'],'anest'=>$submit['anest'],'inst'=>$submit['inst'],'circ'=>$submit['circ'],'penata'=>$submit['penata']];
			$catatan_pra_operasi['tindakan'] = $submit['tindakan'];
			
			$save_data['is_need_icu'] = $submit['is_need_icu'];
			$save_data['rencana_pembiusan'] = $submit['rencana_pembiusan'];
			$save_data['catatan_pra_operasi'] = json_encode($catatan_pra_operasi);
			$save_data['id'] = $data_perjanjian[0]['id'];
			$save_data['ruang_ok'] = $submit['ruang_ok'];
			$save_data['tgl_pelaksanaan'] = $submit['tgl_op_id'].' '.$submit['hour'].':'.$submit['minute'].':0';
			$this->pendaftaranperjanjianoperasimodel->save($save_data);
		}
		return;
	}
	
	//halaman
	function print_jadwal_pasien($date_target = ''){
		$this->init_crud_assets();
		$this->js_theme_project[] = 'js_excel/jquery.battatech.excelexport';
		$today_target = !empty($date_target)?$date_target:date('Y-m-d');
		$adding_day = /*(date('w',strtotime($today_target) == 4))?'3':'1';*/'1';
		$output['data']['tanggal_op'] = $param['tanggal_op_sampai'] = $param['tanggal_op_dari'] = date('d-m-Y',strtotime($today_target .'+'.$adding_day.' days'));
		$this->load->model(array('pendaftaranperjanjianoperasimodel','usermodel','mastersumberreferensimodel'));
		$this->pendaftaranperjanjianoperasimodel->db->select('mrr.nama_ruang nama_ruang_reg');
		$this->pendaftaranperjanjianoperasimodel->db->where('pendaftaran_perjanjian_operasi.status','4');
		$raw_data = $this->pendaftaranperjanjianoperasimodel->data_report_ibs($param);
		$output['output'] = [];
		$output['title'] = 'Cetak Jadwal Untuk Operasi Tanggal '.$output['data']['tanggal_op'];
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
		//var_dump($raw_data);
		$this->preRender($output,"",'operasi_terjadwal/ibs_jadwal_besok');
		return;
	}
}?>