CREATE TABLE IF NOT EXISTS `ele` (
`id` int(10) NOT NULL,
  `no_rm` varchar(11) NOT NULL,
  `no_kontak_pasien` varchar(127) NOT NULL,
  `poli_asal` varchar(8) NOT NULL,
  `list_konsul_poli` varchar(225) NOT NULL,
  `diagnosa` varchar(100) NOT NULL,
  `tindakan` varchar(10) NOT NULL,
  `rencana_pembiusan` varchar(225) NOT NULL,
  `tgl_pelaksanaan` datetime NOT NULL,
  `perkiraan_lama_operasi` varchar(2) NOT NULL,
  `operator` int(11) NOT NULL,
  `kelas` varchar(5) NOT NULL,
  `tgl_cek_kamar` datetime NOT NULL,
  `tgl_reg_ranap` datetime DEFAULT NULL,
  `days_before_surgery` int(1) NOT NULL DEFAULT '1',
  `no_perjanjian_operasi` varchar(11) NOT NULL,
  `status` varchar(2) NOT NULL,
  `rute` text,
  `no_pj_carter` varchar(100) NOT NULL,
  `no_reg_pasien` varchar(100) NOT NULL,
  `ruang_ok` varchar(2) NOT NULL,
  `is_need_icu` enum('Y','T') NOT NULL DEFAULT 'T',
  `catatan_pra_operasi` text NOT NULL,
  `catatan_pasca_operasi` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

ALTER TABLE `ele`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `no_perjanjian_operasi` (`no_perjanjian_operasi`);

ALTER TABLE `ele`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS `master_sumber_referensi` (
`ref_id` int(10) NOT NULL,
  `tipe_ref` varchar(5) NOT NULL,
  `kode_ref` varchar(50) NOT NULL,
  `uraian` varchar(255) DEFAULT NULL,
  `uraian_1` varchar(60) DEFAULT NULL,
  `uraian_json` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=latin1;


INSERT INTO `master_sumber_referensi` (`ref_id`, `tipe_ref`, `kode_ref`, `uraian`, `uraian_1`, `uraian_json`) VALUES
(42, '[SPO]', 'O', 'Usulan Perjanjian Operasi', 'Pasien Menunggu Tanggal Operasi dari IBS', ''),
(62, '[SPO]', '000', 'Status Perjanjian Operasi', 'Status Perjanjian Operasi', ''),
(63, '[SPO]', '1', 'Tanggal Operasi Dikonfirmasi IBS', 'Pasien Siap Carter Kamar Inap', ''),
(64, '[SPO]', '2', 'Pasien Mengkonfirmasi Kehadiran & Kamar Rawat', 'Pasien Akan Hadir Ke Poli / IGD', ''),
(65, '[SPO]', '3', 'Pasien Siap Mendaftar Rawat Inap', 'Pasien Siap Mendaftar Rawat Inap', ''),
(66, '[SPO]', '4', 'Pasien Menunggu Hari Operasi', 'Pasien Menunggu Hari Operasi', ''),
(67, '[SPO]', '5', 'Perjanjian Operasi Selesai', 'Perjanjian Operasi Selesai', ''),
(68, '[SPO]', '-1', 'Perjanjian Operasi Batal, Harus Proses Perjanjian Baru', 'Perjanjian Operasi Batal, Harus Proses Perjanjian Baru', ''),
(69, '[SPO]', '-4', 'Perjanjian Operasi Batal, Akan Didaftarkan Perjanjian Kembali Dari IRIN', 'Perjanjian Operasi Batal, Pasien Sudah Inap', '');

ALTER TABLE `master_sumber_referensi`
 ADD PRIMARY KEY (`ref_id`), ADD KEY `kode_ref` (`kode_ref`);

ALTER TABLE `master_sumber_referensi`
MODIFY `ref_id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=70;