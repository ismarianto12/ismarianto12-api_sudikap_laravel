-- Adminer 5.3.0 MySQL 5.7.41 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `anggaran_perjalanan`;
CREATE TABLE `anggaran_perjalanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_anggaran` varchar(50) DEFAULT NULL,
  `nama_kegiatan` varchar(255) DEFAULT NULL,
  `tahun_anggaran` int(11) DEFAULT NULL,
  `pagu_anggaran` decimal(15,2) DEFAULT NULL,
  `sisa_anggaran` decimal(15,2) DEFAULT NULL,
  `status` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_anggaran` (`kode_anggaran`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `arsip`;
CREATE TABLE `arsip` (
  `id_arsip` int(15) NOT NULL AUTO_INCREMENT,
  `id_jenis` varchar(15) DEFAULT NULL,
  `id_pejabat` int(20) DEFAULT NULL,
  `nama_arsip` varchar(50) DEFAULT NULL,
  `file_arsip` varchar(50) DEFAULT NULL,
  `jumlah` varchar(20) DEFAULT NULL,
  `id_satuan` varchar(20) DEFAULT NULL,
  `lokasi` varchar(50) DEFAULT NULL,
  `ket_isi` text,
  `tanggal` date DEFAULT NULL,
  `permision` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_arsip`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `histori`;
CREATE TABLE `histori` (
  `id_histori` int(15) NOT NULL AUTO_INCREMENT,
  `id_user` int(15) NOT NULL,
  `url` text NOT NULL,
  `aktivitasi` text NOT NULL,
  `tanggal` varchar(50) NOT NULL,
  `ip_address` text NOT NULL,
  `browser` varchar(150) NOT NULL,
  PRIMARY KEY (`id_histori`) USING BTREE,
  UNIQUE KEY `id_user` (`id_histori`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `instansi`;
CREATE TABLE `instansi` (
  `nama_instansi` varchar(100) NOT NULL,
  `alamat_lengkap` text NOT NULL,
  `telp` varchar(30) NOT NULL,
  `informasi` text NOT NULL,
  `keterangan_situs` text NOT NULL,
  `fax` varchar(30) NOT NULL,
  `npwp` varchar(40) NOT NULL,
  `logo` varchar(50) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `nip` varchar(30) NOT NULL,
  `nama_pejabat` varchar(100) NOT NULL,
  `favicon` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `jenis_arsip`;
CREATE TABLE `jenis_arsip` (
  `id_jenis` int(15) NOT NULL AUTO_INCREMENT,
  `jenis_arsip` varchar(50) NOT NULL,
  `create_id` varchar(50) NOT NULL,
  `create_date` date NOT NULL,
  PRIMARY KEY (`id_jenis`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `jenis_surat`;
CREATE TABLE `jenis_surat` (
  `id_jenis` int(20) NOT NULL AUTO_INCREMENT,
  `nama_jenis` varchar(50) NOT NULL,
  `id_user` varchar(12) DEFAULT NULL,
  `kode_surat` varchar(40) NOT NULL,
  `tanggal_create` date DEFAULT NULL,
  `parameter` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_jenis`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `login`;
CREATE TABLE `login` (
  `id_user` int(15) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `level` enum('admin','user','staff','') NOT NULL,
  `foto` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `log` varchar(40) DEFAULT NULL,
  `token` text,
  `statuslogin` tinyint(1) DEFAULT NULL,
  `active` enum('y','n') NOT NULL,
  PRIMARY KEY (`id_user`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `lokasi`;
CREATE TABLE `lokasi` (
  `id_lokasi` int(15) NOT NULL AUTO_INCREMENT,
  `nama_lokasi` varchar(80) NOT NULL,
  `tanggal` date NOT NULL,
  PRIMARY KEY (`id_lokasi`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id_menu` int(5) NOT NULL AUTO_INCREMENT,
  `id_parent` int(5) NOT NULL DEFAULT '0',
  `nama_menu` varchar(30) NOT NULL,
  `icon` varchar(30) NOT NULL,
  `link` varchar(30) NOT NULL,
  `aktif` enum('Ya','Tidak') NOT NULL DEFAULT 'Ya',
  `urutan` int(3) NOT NULL,
  `position` varchar(20) NOT NULL,
  `level` varchar(50) NOT NULL,
  PRIMARY KEY (`id_menu`) USING BTREE,
  KEY `id_menu` (`id_menu`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `m_satuan`;
CREATE TABLE `m_satuan` (
  `id_satuan` int(20) NOT NULL AUTO_INCREMENT,
  `nama_satuan` varchar(30) NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  PRIMARY KEY (`id_satuan`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `pegawai`;
CREATE TABLE `pegawai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sikd_satker_id` int(14) DEFAULT NULL,
  `nip` varchar(100) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `no_hp` varchar(100) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `tanggal_lahir` varchar(40) DEFAULT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `golongan` varchar(100) DEFAULT NULL,
  `golongan_tanggal` varchar(40) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `jabatan_tanggal` varchar(40) DEFAULT NULL,
  `kerja_tahun` varchar(50) DEFAULT NULL,
  `kerja_bulan` int(50) DEFAULT NULL,
  `latihan_jabatan` varchar(100) DEFAULT NULL,
  `latihan_jabatan_tanggal` varchar(40) DEFAULT NULL,
  `latihan_jabatan_jam` int(50) DEFAULT '0',
  `pendidikan` varchar(100) DEFAULT NULL,
  `pendidikan_lulus` varchar(50) DEFAULT NULL,
  `pendidikan_ijazah` varchar(100) DEFAULT NULL,
  `catatan_mutasi` text,
  `keterangan` text,
  `username` varchar(100) DEFAULT NULL,
  `username_update` varchar(100) DEFAULT NULL,
  `datetime_insert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `datetime_update` timestamp NULL DEFAULT NULL,
  `status_deleted` enum('0','1') DEFAULT '1',
  `pangkat` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `nip` (`nip`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `pengajuan_arsip`;
CREATE TABLE `pengajuan_arsip` (
  `id_pengajuan` int(15) NOT NULL AUTO_INCREMENT,
  `id_pejabat` varchar(60) NOT NULL,
  `id_satuan` varchar(50) NOT NULL,
  `nama_arsip` varchar(60) NOT NULL,
  `jumlah` varchar(50) NOT NULL,
  `satuan` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `tujuan` text NOT NULL,
  `file_arsip` varchar(50) DEFAULT NULL,
  `id_jenis` varchar(50) NOT NULL,
  `nonaktif` enum('n','y') NOT NULL,
  PRIMARY KEY (`id_pengajuan`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `pengajuan_surat_masuk`;
CREATE TABLE `pengajuan_surat_masuk` (
  `id_pengajuan_s` int(15) NOT NULL AUTO_INCREMENT,
  `no_agenda` varchar(15) NOT NULL,
  `jenis_surat` varchar(15) NOT NULL,
  `tanggal_kirim` datetime NOT NULL,
  `tanggal_terima` datetime NOT NULL,
  `no_surat` varchar(50) NOT NULL,
  `pengirim` varchar(50) NOT NULL,
  `perihal` varchar(50) NOT NULL,
  `nama_file` varchar(50) NOT NULL,
  PRIMARY KEY (`id_pengajuan_s`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `sikd_satker`;
CREATE TABLE `sikd_satker` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `sikd_satker_type` varchar(30) NOT NULL,
  `sikd_satker_id` varchar(30) DEFAULT NULL,
  `kode` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `singkatan` varchar(20) DEFAULT NULL,
  `sikd_bidang_id` varchar(30) NOT NULL,
  `kd_bidang_induk` varchar(10) NOT NULL,
  `rek_konsolidasi_id` varchar(30) DEFAULT NULL,
  `nip_ka_satker` varchar(18) DEFAULT NULL,
  `nm_ka_satker` varchar(100) DEFAULT NULL,
  `jab_ka_satker` varchar(200) DEFAULT NULL,
  `klasifikasi` varchar(20) DEFAULT NULL,
  `satker_pendapatan` char(1) NOT NULL,
  `sotk_lama` char(1) DEFAULT NULL,
  `npwp_satker` varchar(30) DEFAULT NULL,
  `kd_skpd_bmd` varchar(30) DEFAULT NULL,
  `created_by` varchar(20) NOT NULL DEFAULT '',
  `creation_date` varchar(20) NOT NULL,
  `last_updated_by` varchar(20) NOT NULL,
  `last_updated_date` varchar(20) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `sppd`;
CREATE TABLE `sppd` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `pimpinan` varchar(50) DEFAULT NULL COMMENT '//pimpinan menyatakan nama pimpinan nya siapa bisa jadi (gubernur, walikota, wakilwalikota)',
  `nip_pejabat` varchar(50) DEFAULT NULL,
  `nip_leader` varchar(50) DEFAULT NULL,
  `letter_code` varchar(50) DEFAULT NULL,
  `letter_subject` varchar(50) DEFAULT NULL,
  `letter_about` varchar(50) DEFAULT NULL,
  `letter_from` varchar(50) DEFAULT NULL,
  `letter_content` varchar(50) DEFAULT NULL,
  `letter_date` text,
  `code` varchar(30) DEFAULT NULL,
  `date` varchar(30) DEFAULT NULL,
  `bawahan` varchar(50) DEFAULT NULL,
  `atasan` varchar(50) DEFAULT NULL,
  `rate_travel` varchar(50) DEFAULT NULL COMMENT 'keterangan lama perjalanan',
  `pengikut_nip` text,
  `purpose` text,
  `transport` varchar(50) DEFAULT NULL,
  `place_from` varchar(50) DEFAULT NULL,
  `place_to` varchar(50) DEFAULT NULL,
  `length_journey` int(3) DEFAULT NULL,
  `date_go` varchar(40) DEFAULT NULL,
  `date_back` varchar(20) DEFAULT NULL,
  `government` varchar(50) DEFAULT NULL,
  `budget` double(16,2) DEFAULT '0.00',
  `budget_from` varchar(50) NOT NULL,
  `description` text,
  `result_date` date DEFAULT NULL,
  `result` text,
  `result_username` varchar(50) DEFAULT NULL,
  `file` longtext,
  `jenis_surat_id` int(10) DEFAULT NULL,
  `file_update` longtext,
  `status` enum('0','1','2') DEFAULT '0' COMMENT '0 : diinput  1 : dicetak 2 : selesai',
  `username` varchar(50) DEFAULT NULL,
  `username_update` varchar(50) DEFAULT NULL,
  `datetime_insert` varchar(50) DEFAULT NULL,
  `datetime_update` varchar(50) DEFAULT NULL,
  `basic` varchar(50) DEFAULT NULL COMMENT 'dasar perjalan dinas',
  `city` varchar(20) DEFAULT NULL COMMENT 'b',
  `rekening` varchar(50) DEFAULT NULL,
  `kabag` varchar(50) DEFAULT NULL,
  `kasubag` varchar(50) DEFAULT NULL,
  `pimpinan_spt` varchar(30) DEFAULT NULL,
  `kabag_spt` varchar(30) DEFAULT NULL,
  `kasubag_spt` varchar(30) DEFAULT NULL,
  `letter_code_spt` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `code` (`code`) USING BTREE,
  KEY `nip_leader` (`atasan`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

INSERT INTO `sppd` (`id`, `pimpinan`, `nip_pejabat`, `nip_leader`, `letter_code`, `letter_subject`, `letter_about`, `letter_from`, `letter_content`, `letter_date`, `code`, `date`, `bawahan`, `atasan`, `rate_travel`, `pengikut_nip`, `purpose`, `transport`, `place_from`, `place_to`, `length_journey`, `date_go`, `date_back`, `government`, `budget`, `budget_from`, `description`, `result_date`, `result`, `result_username`, `file`, `jenis_surat_id`, `file_update`, `status`, `username`, `username_update`, `datetime_insert`, `datetime_update`, `basic`, `city`, `rekening`, `kabag`, `kasubag`, `pimpinan_spt`, `kabag_spt`, `kasubag_spt`, `letter_code_spt`) VALUES
(20,	'195802281986012002',	NULL,	NULL,	'A12-/SPPD-12/4-SETDA',	NULL,	NULL,	NULL,	'Permendagri 102',	NULL,	NULL,	'2023-04-13',	NULL,	NULL,	NULL,	'195807171980032008',	'Studi Banding ke Bandung',	'Mobil Dinas',	'Padang',	'ff',	2,	'2021-06-30',	'2021-06-04',	'Trisakti UNiversitity',	NULL,	'2003211',	'Snack',	NULL,	NULL,	NULL,	NULL,	12,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	'asdadaasdadaasdada',	'padang',	'13131231',	'195802281986012002',	'195802281986012002',	NULL,	NULL,	NULL,	NULL),
(51,	'1958060519860811001',	NULL,	NULL,	'dsada',	NULL,	NULL,	NULL,	'dadasdad',	NULL,	'dsada',	'2023-04-13',	'195802281986012002',	'195807171980031014',	NULL,	'1958060519860811001',	'adsaa',	'adad',	'dadad',	'adadada',	12,	'2023-04-13',	'2023-04-19',	'RUMAH SAKIT UMUM',	NULL,	'dasdad',	'adadad',	NULL,	NULL,	NULL,	NULL,	12,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	'adsaad',	'Padang',	'asda',	'195808281986011003',	'195912251990031004',	NULL,	NULL,	NULL,	NULL),
(52,	'1958060519860811001',	NULL,	NULL,	'wqw',	NULL,	NULL,	NULL,	'kmsddasd',	NULL,	NULL,	'2023-04-15',	'1958060519860811001',	'195802281986012002',	NULL,	'195802281986012002,1958060519860811001,195807171980031014,195807171980032008,195808281986011003,195809281980032008',	'Melaksanan',	'pesawat',	'Medan',	'Gak tau',	3,	'2023-04-13',	'2023-04-13',	'DINAS PENDIDIKAN DAN KEBUDAYAAN',	NULL,	'matamu',	'olee',	NULL,	NULL,	NULL,	NULL,	12,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	'OKere',	'medan',	'03234234234234',	'195808281986011003',	'195912251990031004',	NULL,	NULL,	NULL,	NULL);

DROP TABLE IF EXISTS `tbl_disposisi`;
CREATE TABLE `tbl_disposisi` (
  `id_disposisi` int(10) NOT NULL AUTO_INCREMENT,
  `tujuan` varchar(250) NOT NULL,
  `isi_disposisi` mediumtext NOT NULL,
  `sifat` varchar(100) NOT NULL,
  `batas_waktu` varchar(100) NOT NULL,
  `catatan` varchar(250) NOT NULL,
  `id_surat` int(10) NOT NULL,
  `id_user` tinyint(2) NOT NULL,
  PRIMARY KEY (`id_disposisi`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `tbl_surat_keluar`;
CREATE TABLE `tbl_surat_keluar` (
  `id_surat` int(10) NOT NULL AUTO_INCREMENT,
  `id_jenis_surat` varchar(50) NOT NULL,
  `tujuan` varchar(250) NOT NULL,
  `no_surat` varchar(50) NOT NULL,
  `isi` mediumtext NOT NULL,
  `kode` varchar(30) NOT NULL,
  `tgl_surat` date NOT NULL,
  `tgl_catat` date NOT NULL,
  `file` varchar(250) NOT NULL,
  `keterangan` varchar(250) NOT NULL,
  `id_user` tinyint(2) NOT NULL,
  `no_agenda` varchar(50) NOT NULL,
  PRIMARY KEY (`id_surat`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `tbl_surat_masuk`;
CREATE TABLE `tbl_surat_masuk` (
  `id_surat` int(10) NOT NULL AUTO_INCREMENT,
  `no_agenda` varchar(30) NOT NULL,
  `no_surat` varchar(50) NOT NULL,
  `asal_surat` varchar(250) NOT NULL,
  `isi` mediumtext NOT NULL,
  `kode` varchar(30) NOT NULL,
  `indeks` varchar(30) NOT NULL,
  `tgl_surat` date NOT NULL,
  `tgl_diterima` date NOT NULL,
  `file` varchar(250) NOT NULL,
  `keterangan` varchar(250) NOT NULL,
  `id_user` tinyint(2) NOT NULL,
  `disposisi` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_surat`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `tmjabatan`;
CREATE TABLE `tmjabatan` (
  `Id` bigint(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(100) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Stat` varchar(100) DEFAULT NULL,
  `OtherString` longtext,
  PRIMARY KEY (`Id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;


-- 2025-08-05 09:09:03 UTC