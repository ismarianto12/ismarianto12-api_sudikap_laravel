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

INSERT INTO `anggaran_perjalanan` (`id`, `kode_anggaran`, `nama_kegiatan`, `tahun_anggaran`, `pagu_anggaran`, `sisa_anggaran`, `status`, `created_at`, `updated_at`) VALUES
(1,	'MT-10',	'Kegiatan Anggaran',	2025,	11213132313.00,	121313.00,	'aktif',	'2025-08-08 15:19:58',	'2025-08-15 03:01:06'),
(2,	'TETING',	'SPPD TAhun 2023',	2025,	10231313.00,	10231313.00,	'aktif',	'2025-08-15 03:00:59',	'2025-08-15 03:00:59');

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

INSERT INTO `jenis_arsip` (`id_jenis`, `jenis_arsip`, `create_id`, `create_date`) VALUES
(7,	'Arsip Barang dan Jasa.',	'1',	'2020-02-01'),
(8,	'Arsip Bendahara',	'1',	'2019-11-08'),
(13,	'Arsip Surat Keluar',	'1',	'2020-11-26'),
(14,	'Arsip Surat Masuk Internal',	'1',	'2020-11-26'),
(15,	'Arsip Surat Masuk Eksternal',	'1',	'2020-11-26'),
(16,	'Arsip Surat Keputusan Rektor',	'1',	'2021-02-09'),
(17,	'SPPD',	'1',	'2021-05-03'),
(20,	'asda',	'1',	'2021-05-03'),
(23,	'Surat Jalan',	'29',	'2021-05-04'),
(24,	'Bendahara Pengeluaran',	'30',	'2021-05-18');

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

INSERT INTO `login` (`id_user`, `username`, `password`, `nama`, `level`, `foto`, `email`, `log`, `token`, `statuslogin`, `active`) VALUES
(24,	'admin12',	'$2y$10$epOvKXqOSb0mUyjVRtkfuuGUF0iPwgqN2jnSvRh6oIOOcHyGoR4pG',	'123',	'admin',	'foto1620061490.jpg',	'ysmasriki@yahoo.com',	'2025-08-05 13:50:49',	'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODA4MFwvYXBpXC92MVwvbG9naW4iLCJpYXQiOjE3NTUzNTY2NTMsIm5iZiI6MTc1NTM1NjY1MywianRpIjoiMTNCUm1zSHpFZFJaT2ZUSiIsInN1YiI6MjQsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.im0LUFNETl7awTDKvKgA_jlyu9xMZQO6oAZHE20ucmU',	1,	'y'),
(25,	'admin67',	'$2y$10$epOvKXqOSb0mUyjVRtkfuuGUF0iPwgqN2jnSvRh6oIOOcHyGoR4pG',	'123',	'admin',	'foto1620061494.jpg',	'ysmariki@yahoo.com',	'2021-05-04 00:35:08',	NULL,	NULL,	'y'),
(26,	'kacang',	'$2y$10$epOvKXqOSb0mUyjVRtkfuuGUF0iPwgqN2jnSvRh6oIOOcHyGoR4pG',	'123',	'admin',	'foto1620061862.jpg',	'ysmariki@yahoo.com',	NULL,	NULL,	NULL,	'y'),
(29,	'guegw10',	'$2y$10$epOvKXqOSb0mUyjVRtkfuuGUF0iPwgqN2jnSvRh6oIOOcHyGoR4pG',	'Guntur Wijaya, A.Md',	'user',	'foto1620062866.jpg',	'guntur.wijay@gmail.com',	'2021-05-30 00:52:25',	NULL,	NULL,	'y'),
(30,	'rahmiati',	'$2y$10$epOvKXqOSb0mUyjVRtkfuuGUF0iPwgqN2jnSvRh6oIOOcHyGoR4pG',	'kasubbag keuangan',	'admin',	'foto1621311379.png',	'wijayg@yahoo.co.id',	NULL,	NULL,	NULL,	'y'),
(31,	'admin123',	'$2y$10$dq4umhkzYqIAfU1c3eesQ.74pKG40s3gNSBC9oN1NkZEqo09KwLM2',	'ada',	'staff',	'default.png',	'adadasdsada@gmail.com',	'2025-08-05 13:35:20',	NULL,	0,	'y');

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
  `status_deleted` varchar(10) DEFAULT '1',
  `pangkat` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `nip` (`nip`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

INSERT INTO `pegawai` (`id`, `sikd_satker_id`, `nip`, `nama`, `no_hp`, `alamat`, `tanggal_lahir`, `tempat_lahir`, `golongan`, `golongan_tanggal`, `jabatan`, `jabatan_tanggal`, `kerja_tahun`, `kerja_bulan`, `latihan_jabatan`, `latihan_jabatan_tanggal`, `latihan_jabatan_jam`, `pendidikan`, `pendidikan_lulus`, `pendidikan_ijazah`, `catatan_mutasi`, `keterangan`, `username`, `username_update`, `datetime_insert`, `datetime_update`, `status_deleted`, `pangkat`) VALUES
(1,	NULL,	'195802281986012002',	'ALDIAZ NASHER ARIGHI',	'-',	'Singosari Malang Indonesia',	'0000-00-00',	'Indonesia',	'12',	'2011-04-05',	'-',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:46',	'2015-11-02 20:05:42',	'1',	NULL),
(2,	NULL,	'1958060519860811001',	'MIRZA RAMADHANY',	'-',	'Singosari Malang Indonesia',	'1958-06-05',	'Indonesia',	'15',	'2009-10-27',	'Pimpinan',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	'',	'',	NULL,	'admin',	'2023-04-13 09:43:27',	'2015-11-02 20:05:48',	'1',	NULL),
(3,	NULL,	'195807171980031014',	'ADI ROZAQ AL HA YU',	'-',	'Singosari Malang Indonesia',	'1958-07-17',	'Indonesia',	'9',	'2013-11-14',	'Staff',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:46',	'2015-11-02 20:05:48',	'1',	NULL),
(4,	NULL,	'195807171980032008',	'ADIKA SETIA BRATA',	'-',	'Singosari Malang Indonesia',	'1958-07-17',	'Indonesia',	'10',	'2001-03-29',	'Staff',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:46',	'2015-11-02 20:05:48',	'1',	NULL),
(5,	NULL,	'195808281986011003',	'ALVIN CANDRA WIJAYA',	'-',	'Singosari Malang Indonesia',	'1958-08-05',	'Indonesia',	'13',	'2015-09-30',	'Kepala Bagian UMUM',	'2009-02-26',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2023-04-13 09:43:37',	'2015-11-02 20:05:48',	'1',	NULL),
(6,	NULL,	'195809281980032008',	'ANDIKA SETYA RISWANTO',	'-',	'Singosari Malang Indonesia',	'1958-09-28',	'Indonesia',	'10',	'2001-03-29',	'Staff',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:46',	'2015-11-02 20:05:48',	'1',	NULL),
(7,	NULL,	'195810291986081001',	'ANDRE GINO KURNIAWAN',	'-',	'Singosari Malang Indonesia',	'1958-10-29',	'Indonesia',	'7',	'0000-00-00',	'Staff',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:46',	'2015-11-02 20:05:48',	'1',	NULL),
(8,	NULL,	'195811141986031005',	'ARGA SEPTANDIKA PUTRA',	'-',	'Singosari Malang Indonesia',	'1988-11-23',	'Indonesia',	'11',	'2010-07-08',	'Penyuluh kehutanan penyelia',	'2010-04-01',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:47',	'2015-11-02 20:05:48',	'1',	NULL),
(9,	NULL,	'195812241992111001',	'ok1',	'-',	'Singosari Malang Indonesia',	'1958-12-14',	'Indonesia',	'13',	'2012-09-20',	'Sekretaris',	'2014-06-12',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:47',	'2015-11-02 20:06:27',	'1',	NULL),
(10,	NULL,	'195812291982122003',	'ok2',	'-',	'Singosari Malang Indonesia',	'1958-12-29',	'Indonesia',	'10',	'2003-04-08',	'Staff',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:47',	'2015-11-02 20:05:48',	'1',	NULL),
(11,	NULL,	'195905071990031004',	'ok13',	'-',	'Singosari Malang Indonesia',	'1959-05-02',	'Indonesia',	'8',	'2010-05-01',	'Staff',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:47',	'2015-11-02 20:05:48',	'1',	NULL),
(12,	NULL,	'195909111983032008',	'ok4',	'-',	'Singosari Malang Indonesia',	'1959-09-11',	'Indonesia',	'12',	'2005-08-24',	'Kasi Monitoring dan Pelaporan',	'2009-02-26',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:47',	'2015-11-02 20:05:48',	'1',	NULL),
(13,	NULL,	'195912251990031004',	'DANANG DAIFULLAH DINAR MAUDY',	'-',	'Singosari Malang Indonesia',	'1959-12-25',	'Indonesia',	'8',	'0000-00-00',	'Kepala sub bagian',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2023-04-13 09:43:53',	'2015-11-02 20:05:48',	'1',	NULL),
(14,	NULL,	'196001011987091001',	'DENNY\'S ALFIAN',	'-',	'Singosari Malang Indonesia',	'0000-00-00',	'Indonesia',	'11',	'2013-09-17',	'-',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:47',	'2015-11-02 20:05:48',	'1',	NULL),
(15,	NULL,	'196003051987082001',	'DIMAS AJI PRAKOSA',	'-',	'Singosari Malang Indonesia',	'1960-03-05',	'Indonesia',	'13',	'2013-09-30',	'Kepala sub bagian',	'2013-01-12',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2023-04-13 09:43:49',	'2015-11-02 20:05:48',	'1',	NULL),
(16,	NULL,	'196003271986032003',	'FARID NANDA LUTHFIANTO',	'-',	'Singosari Malang Indonesia',	'1960-03-27',	'Indonesia',	'14',	'2005-07-29',	'Kabid Binus dan Kelembagaan',	'2009-02-26',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:47',	'2015-11-02 20:05:48',	'1',	NULL),
(17,	NULL,	'196004291990021002',	'FIRMAN MAULANA JA\'FAR',	'-',	'Singosari Malang Indonesia',	'1980-04-29',	'Indonesia',	'10',	'2010-05-01',	'Staff',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:47',	'2015-11-02 20:05:48',	'1',	NULL),
(18,	NULL,	'196005271987081001',	'GALIH RAMADHAN',	'-',	'Singosari Malang Indonesia',	'1993-01-04',	'Indonesia',	'12',	'2012-11-05',	'-',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:47',	'2015-11-02 20:05:48',	'1',	NULL),
(19,	NULL,	'196006071992031005',	'ok5',	'-',	'Singosari Malang Indonesia',	'0000-00-00',	'Indonesia',	'13',	'2014-04-01',	'-',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:47',	'2015-11-02 20:05:48',	'1',	NULL),
(20,	NULL,	'196109201992032004',	'ok6',	'-',	'Singosari Malang Indonesia',	'0000-00-00',	'Indonesia',	'10',	'2009-09-30',	'-',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:47',	'2015-11-02 20:05:48',	'1',	NULL),
(21,	NULL,	'196110151987081001',	'LERENOP SULAKSONO',	'-',	'Singosari Malang Indonesia',	'0000-00-00',	'Indonesia',	'9',	'2005-10-13',	'-',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:47',	'2015-11-02 20:05:48',	'1',	NULL),
(22,	NULL,	'196110302007011001',	'ok7',	'-',	'Singosari Malang Indonesia',	'1981-10-30',	'Indonesia',	'6',	'2011-03-10',	'Staff',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:47',	'2015-11-02 20:05:48',	'1',	NULL),
(23,	NULL,	'196201182007011002',	'MAULANA NUR HIDAYATULLAH',	'-',	'Singosari Malang Indonesia',	'1962-01-18',	'Indonesia',	'6',	'2011-03-10',	'Staff',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:47',	'2015-11-02 20:05:49',	'1',	NULL),
(24,	NULL,	'196212301980031005',	'DHANY',	'-',	'Singosari Malang Indonesia',	'0000-00-00',	'Indonesia',	'5',	'1996-02-05',	'Staff',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:47',	'2015-11-02 20:05:49',	'1',	NULL),
(25,	NULL,	'196303061992031005',	'ok8',	'-',	'Singosari Malang Indonesia',	'1963-03-06',	'Indonesia',	'11',	'0000-00-00',	'Kasi Sarana dan Prasarana Perlindungan',	'2013-06-01',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:47',	'2015-11-02 20:05:49',	'1',	NULL),
(26,	NULL,	'196303181988032009',	'RHESAL MAHADYANTO',	'-',	'Singosari Malang Indonesia',	'1989-04-24',	'Indonesia',	'12',	'2012-11-05',	'-',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:47',	'2015-11-02 20:05:49',	'1',	NULL),
(27,	NULL,	'196311101998022002',	'ok9',	'-',	'Singosari Malang Indonesia',	'1963-11-10',	'Indonesia',	'9',	'2014-04-14',	'Staff',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:47',	'2015-11-02 20:05:49',	'1',	NULL),
(28,	NULL,	'196401042007011010',	'ok10',	'-',	'Singosari Malang Indonesia',	'1966-01-04',	'Indonesia',	'4',	'2011-03-10',	'Staff',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:48',	'2015-11-02 20:05:49',	'1',	NULL),
(29,	NULL,	'196404241989032010',	'ok11',	'-',	'Singosari Malang Indonesia',	'1964-04-24',	'Indonesia',	'10',	'2009-04-01',	'Staff',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:48',	'2015-11-02 20:05:49',	'1',	NULL),
(30,	NULL,	'196408191987081002',	'ok12',	'-',	'Singosari Malang Indonesia',	'0000-00-00',	'Indonesia',	'10',	'2010-05-24',	'-',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:48',	'2015-11-02 20:05:49',	'1',	NULL),
(31,	NULL,	'196410011994021002',	'RIZKY BA YU VERNANDO',	'-',	'Singosari Malang Indonesia',	'1964-10-01',	'Indonesia',	'11',	'2014-08-27',	'Staff',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:48',	'2015-11-02 20:05:49',	'1',	NULL),
(32,	NULL,	'196412231994031001',	'ok13',	'-',	'Singosari Malang Indonesia',	'2010-12-29',	'Indonesia',	'10',	'2010-05-24',	'Penyuluh Kehutanan Pelaksana lanjutan',	'2010-04-01',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:48',	'2015-11-02 20:05:49',	'1',	NULL),
(33,	NULL,	'196701271995022001',	'ok14',	'-',	'Singosari Malang Indonesia',	'1967-01-27',	'Indonesia',	'13',	'2009-09-30',	'Kabid Produksi',	'2011-06-23',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:48',	'2015-11-02 20:05:49',	'1',	NULL),
(34,	NULL,	'196702041998031003',	'ASRORI HASAN',	'-',	'Singosari Malang Indonesia',	'0000-00-00',	'Indonesia',	'11',	'2013-09-17',	'-',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:48',	'2015-11-02 20:06:35',	'1',	NULL),
(35,	NULL,	'196703081993032008',	'ok15',	'-',	'Singosari Malang Indonesia',	'1967-03-08',	'Indonesia',	'13',	'2012-11-27',	'Kasubbag Keuangan DISHUTBUN',	'2010-07-29',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:48',	'2015-11-02 20:06:40',	'1',	NULL),
(36,	NULL,	'196809292008011004',	'KADARMAN',	'-',	'Singosari Malang Indonesia',	'1968-09-29',	'Indonesia',	'10',	'0001-12-11',	'Kasi Pembina SDM',	'2013-10-05',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:48',	'2015-11-02 20:06:49',	'1',	NULL),
(37,	NULL,	'196809292008011004',	'MUHAMMAD ALFADIN',	'-',	'Singosari Malang Indonesia',	'1963-02-06',	'Indonesia',	'9',	'2008-04-01',	'Staff Disbun',	'2000-04-01',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:48',	'2015-11-02 20:06:54',	'1',	NULL),
(38,	NULL,	'196810302000032004',	'MUHAMMAD ULIL ALBAB',	'-',	'Singosari Malang Indonesia',	'0000-00-00',	'Indonesia',	'',	'0000-00-00',	'Penyuluh Kehutanan Muda',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:48',	'2015-11-02 20:08:20',	'1',	NULL),
(39,	NULL,	'196901311998031007',	'MUHAMMAD APHEP ROSYADI',	'-',	'Singosari Malang Indonesia',	'1962-01-31',	'Indonesia',	'13',	'2014-10-01',	'Kabid Perlindungan',	'2013-06-01',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:48',	'2015-11-02 20:08:17',	'1',	NULL),
(40,	NULL,	'196902101998032004',	'VIENDY NURUL KUSUMAWAN',	'-',	'Singosari Malang Indonesia',	'0000-00-00',	'Indonesia',	'11',	'0000-00-00',	'Penyuluh Kehutanan',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:48',	'2015-11-02 20:07:53',	'1',	NULL),
(41,	NULL,	'196904261997031002',	'SUMPIL',	'-',	'Singosari Malang Indonesia',	'1969-04-26',	'Indonesia',	'13',	'2013-03-28',	'Kasi Bahan Tanaman, Pupuk, Alat dan Mesin',	'2009-02-26',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:48',	'2015-11-02 20:08:41',	'1',	NULL),
(42,	NULL,	'196906141998031010',	'MUHAMMAD NOVAL',	'-',	'Singosari Malang Indonesia',	'0000-00-00',	'Indonesia',	'11',	'2014-10-01',	'-',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:48',	'2015-11-02 20:08:48',	'1',	NULL),
(43,	NULL,	'196907191998031004',	'LAREDO',	'-',	'Singosari Malang Indonesia',	'0000-00-00',	'Indonesia',	'11',	'2014-10-01',	'-',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:48',	'2015-11-02 20:08:57',	'1',	NULL),
(44,	NULL,	'196909271998031006',	'RENDY',	'-',	'Singosari Malang Indonesia',	'1969-09-27',	'Indonesia',	'',	'0000-00-00',	'Penyuluh Kehutanan Muda',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:48',	'2015-11-02 20:09:16',	'1',	NULL),
(45,	NULL,	'197005022000032005',	'CHOLIK',	'-',	'Singosari Malang Indonesia',	'1989-11-10',	'Indonesia',	'10',	'0000-00-00',	'Penyuluh Kehutanan pertama',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:48',	'2015-11-02 20:09:18',	'1',	NULL),
(46,	NULL,	'197006011994031011',	'ARIF',	'-',	'Singosari Malang Indonesia',	'1970-06-01',	'Indonesia',	'11',	'2012-04-17',	'Staff',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:48',	'2015-11-02 20:09:27',	'1',	NULL),
(47,	NULL,	'197006281998031005',	'ARIF TAHU',	'-',	'Singosari Malang Indonesia',	'0000-00-00',	'Indonesia',	'11',	'2014-10-10',	'-',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:48',	'2015-11-02 20:09:29',	'1',	NULL),
(48,	NULL,	'197011262006042004',	'ADITH',	'-',	'Singosari Malang Indonesia',	'1985-08-06',	'Indonesia',	'11',	'0000-00-00',	'Penyuluh Kehutanan',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:49',	'2015-11-02 20:09:51',	'1',	NULL),
(49,	NULL,	'197022061998032007',	'FACHRUDIN',	'-',	'Singosari Malang Indonesia',	'1970-06-22',	'Indonesia',	'',	'0000-00-00',	'PENYULUH KEHUTANAN MUDA',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:49',	'2015-11-02 20:09:59',	'1',	NULL),
(50,	NULL,	'197106091988031009',	'ENAL',	'-',	'Singosari Malang Indonesia',	'1985-12-05',	'Indonesia',	'11',	'2014-10-01',	'Penyuluh Kehutanan Muda',	'2014-08-01',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:49',	'2015-11-02 20:10:15',	'1',	NULL),
(51,	NULL,	'197106161998031006',	'RADITIYA',	'-',	'Singosari Malang Indonesia',	'1971-06-16',	'Indonesia',	'',	'0000-00-00',	'Penyuluh Kehutanan Muda',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:49',	'2015-11-02 20:10:22',	'1',	NULL),
(52,	NULL,	'197209172000031005',	'PATRICK',	'-',	'Singosari Malang Indonesia',	'1975-09-17',	'Indonesia',	'12',	'2012-05-21',	'Kasi Rehabilitasi Hutan dan Lahan',	'2011-09-22',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:49',	'2015-11-02 20:10:25',	'1',	NULL),
(53,	NULL,	'197301081998031009',	'SANIY',	'-',	'Singosari Malang Indonesia',	'1973-01-08',	'Indonesia',	'13',	'2012-03-31',	'Kasi Binus dan Kelembagaan',	'2011-09-22',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:49',	'2015-11-02 20:10:53',	'1',	NULL),
(54,	NULL,	'197307052008011006',	'ROHMAN',	'-',	'Singosari Malang Indonesia',	'1973-07-05',	'Indonesia',	'2',	'2014-01-01',	'Staff',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:49',	'2015-11-02 20:11:07',	'1',	NULL),
(55,	NULL,	'197410182006011005',	'AHMAD ROHMAN',	'-',	'Singosari Malang Indonesia',	'1971-10-18',	'Indonesia',	'11',	'2013-04-16',	'Kasi Perlindungan Hutan dan Mata Air',	'2013-06-01',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:49',	'2015-11-02 20:11:21',	'1',	NULL),
(56,	NULL,	'197501252006041006',	'REZA KURNIAWAN',	'-',	'Singosari Malang Indonesia',	'0000-00-00',	'Indonesia',	'8',	'2014-04-01',	'-',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:49',	'2015-11-02 20:12:23',	'1',	NULL),
(57,	NULL,	'197506052007011023',	'ZAINAL',	'-',	'Singosari Malang Indonesia',	'1975-06-05',	'Indonesia',	'6',	'2011-03-10',	'Staff',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:49',	'2015-11-02 20:13:05',	'1',	NULL),
(58,	NULL,	'197508102006041016',	'ALIEF',	'-',	'Singosari Malang Indonesia',	'1975-08-10',	'Indonesia',	'11',	'2014-04-01',	'Kasubbag Program',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:49',	'2015-11-02 20:13:02',	'1',	NULL),
(59,	NULL,	'19751117200312003',	'ALIFIN',	'-',	'Singosari Malang Indonesia',	'1975-11-17',	'Indonesia',	'11',	'2012-01-17',	'Kasi Pengembangan Tanaman dan Aneka Usaha',	'2012-01-12',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:49',	'2015-11-02 20:13:17',	'1',	NULL),
(60,	NULL,	'197703072003122008',	'YOGI',	'-',	'Singosari Malang Indonesia',	'1977-03-07',	'Indonesia',	'11',	'2012-04-17',	'Kasubag Umum dan Kepegawaian',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:49',	'2015-11-02 20:13:19',	'1',	NULL),
(61,	NULL,	'197712272006041010',	'DIMAS',	'-',	'Singosari Malang Indonesia',	'1985-03-30',	'Indonesia',	'11',	'2014-10-01',	'Penyuluh Kehutanan Muda',	'2014-08-01',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:49',	'2015-11-02 20:13:28',	'1',	NULL),
(62,	NULL,	'197807052006041021',	'FAUDJI',	'-',	'Singosari Malang Indonesia',	'1978-07-05',	'Indonesia',	'11',	'2012-10-08',	'-',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:49',	'2015-11-02 20:13:30',	'1',	NULL),
(63,	NULL,	'197808072011011009',	'REZA FIRMANSYAH BUDIONO',	'-',	'Singosari Malang Indonesia',	'1985-05-17',	'Indonesia',	'9',	'0000-00-00',	'Penyuluh Kehutanan',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:49',	'2015-11-02 20:13:58',	'1',	NULL),
(64,	NULL,	'197912072000031001',	'MIFTA AGUG',	'-',	'Singosari Malang Indonesia',	'1979-12-07',	'Indonesia',	'10',	'2011-10-01',	'Staff',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:49',	'2015-11-02 20:14:06',	'1',	NULL),
(65,	NULL,	'198101102005012012',	'AGUNG RAMADHAN',	'-',	'Singosari Malang Indonesia',	'1981-01-10',	'Indonesia',	'11',	'2013-04-16',	'Staf Sub Bagian Program ',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:49',	'2015-11-02 20:14:13',	'1',	NULL),
(66,	NULL,	'198103302009031004',	'RISQI ARIS',	'-',	'Singosari Malang Indonesia',	'1981-03-30',	'Indonesia',	'8',	'2013-04-16',	'Staff',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:49',	'2015-11-02 20:14:30',	'1',	NULL),
(67,	NULL,	'198104202010011015',	'PUNKY PRIYO',	'-',	'Singosari Malang Indonesia',	'1990-03-29',	'Indonesia',	'8',	'2014-03-13',	'-',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:49',	'2015-11-02 20:14:50',	'1',	NULL),
(68,	NULL,	'198111062010011001',	'RICHARD',	'-',	'Singosari Malang Indonesia',	'1981-11-06',	'Indonesia',	'6',	'2014-03-13',	'Staff',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:49',	'2015-11-02 20:15:21',	'1',	NULL),
(69,	NULL,	'198203202004012010',	'THOMAS',	'-',	'Singosari Malang Indonesia',	'0000-00-00',	'Indonesia',	'10',	'2013-09-17',	'-',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:50',	'2015-11-02 20:15:39',	'1',	NULL),
(71,	NULL,	'198207072006041010',	'PUTRI',	'-',	'Singosari Malang Indonesia',	'1982-07-13',	'Indonesia',	'11',	'2014-05-19',	'Penyuluh Kehutanan Muda',	'2014-02-01',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:50',	'2015-11-02 20:16:04',	'1',	NULL),
(72,	NULL,	'198401132006041006',	'SAMED',	'-',	'Singosari Malang Indonesia',	'1984-02-27',	'Indonesia',	'9',	'2013-10-01',	'Penyuluh Kehutanan Pelaksana Lanjutan',	'2013-10-01',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:50',	'2015-11-02 20:16:16',	'1',	NULL),
(73,	NULL,	'198405112011011007',	'FATTAH',	'-',	'Singosari Malang Indonesia',	'1984-05-19',	'Indonesia',	'9',	'2012-11-01',	'-',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:50',	'2015-11-02 20:16:28',	'1',	NULL),
(74,	NULL,	'198406262010011028',	'ADITIYA NURYAN',	'-',	'Singosari Malang Indonesia',	'1999-05-29',	'Indonesia',	'10',	'2014-04-14',	'Penyuluh Kehutanan Pertama (III/b)',	'2015-02-28',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:50',	'2015-11-02 20:17:41',	'1',	NULL),
(75,	NULL,	'198704122011011009',	'ADITYA RAHMAN',	'-',	'Singosari Malang Indonesia',	'2011-05-27',	'Indonesia',	'5',	'2012-11-01',	'Penyuluh Kehutanan Pemula',	'2012-11-01',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:50',	'2015-11-02 20:17:54',	'1',	NULL),
(76,	NULL,	'198705222011011005',	'ok19',	'-',	'Singosari Malang Indonesia',	'2011-04-16',	'Indonesia',	'5',	'2012-10-30',	'-',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:50',	'2015-11-02 20:17:56',	'1',	NULL),
(77,	NULL,	'199005212011011004',	'JOJO BENZOAT',	'-',	'Singosari Malang Indonesia',	'2010-12-31',	'Indonesia',	'5',	'2012-11-01',	'-',	'0000-00-00',	'0',	0,	'-',	'0000-00-00',	0,	'UI',	'0',	'S3',	NULL,	NULL,	NULL,	NULL,	'2015-05-30 12:12:50',	'2015-11-02 20:18:05',	'1',	NULL),
(78,	NULL,	'199006092011012002',	'ZAKARIA',	'0',	'Sabang',	'2021-06-01',	'Indonesia',	'5',	NULL,	'-',	'2021-06-02',	'2021-06-02',	NULL,	'-',	'',	NULL,	'UI',	'2021-06-02',	'S3',	'-',	'-',	NULL,	NULL,	'2021-06-01 17:51:20',	'2015-11-02 20:18:26',	'1',	''),
(79,	NULL,	'1234567890123456',	'NAZARUDDIN, S.I.KOM',	'0',	'Singosari Malang Indonesia',	'2021-06-02',	'Indonesia',	'5',	NULL,	'WALIKOTA SABANG',	'2021-06-02',	'2021-06-02',	NULL,	'-',	'',	NULL,	'UI',	'2021-06-02',	'S3',	'-',	'-',	NULL,	NULL,	'2021-06-01 17:49:04',	'2015-11-02 20:18:33',	'1',	''),
(80,	NULL,	'34',	'das',	'234234',	'224',	'2021-05-21',	'Padang',	'IIIA',	NULL,	'asd',	'2021-05-18',	'2021-05-15',	NULL,	'asdad',	'2021-05-10',	NULL,	'UI',	'2019',	'2019',	'2019',	'saad',	NULL,	NULL,	'2021-05-30 13:11:45',	NULL,	'1',	'Eselone 1'),
(81,	110202,	'1231314',	'asdad',	'324242',	'sdad',	'2021-06-24',	'asdada',	'IIIA',	NULL,	'Juru Muda',	'2021-06-23',	'2021-06-10',	NULL,	'ad',	'2021-06-24',	NULL,	'UI',	'2021-06-24',	'2020',	'asda',	'adsad',	NULL,	NULL,	'2023-04-15 13:42:56',	NULL,	'1',	'Eselone 1'),
(82,	110101,	'9321039',	'00913013i09',	'032091230139',	'amdlksmkl',	'2022-07-07',	'asd',	'C11',	NULL,	'Juru Muda',	'2022-07-15',	'2022-07-21',	NULL,	'asd',	'2022-07-15',	NULL,	'asd',	'2022-07-13',	'2313',	'asd',	'asd',	NULL,	NULL,	'2023-04-15 13:42:40',	NULL,	'1',	'Letnan'),
(83,	110101,	'13132131',	'3131313',	'13131',	'31313',	'2025-08-06',	'adadad',	'dasda',	NULL,	'Juru Muda Tk. I',	'2025-08-21',	'2025-08-23',	NULL,	'',	'',	NULL,	'adasda',	'2025-08-14',	'131321',	'adasd',	'adad',	NULL,	NULL,	'2025-08-05 13:54:12',	NULL,	'1',	'adada'),
(84,	110101,	'1231312',	'TEROMPAET',	'83182305714',	'asdad',	'2025-08-29',	'ADS',	'1313',	NULL,	'Juru Muda',	'2025-08-15',	'2025-08-22',	NULL,	'REDFF',	'2025-08-13',	0,	'TEMPAT',	'2025-08-13',	'113123123-1231',	NULL,	NULL,	NULL,	NULL,	'2025-08-16 12:42:42',	NULL,	'1',	'NMad');

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

INSERT INTO `sikd_satker` (`id`, `sikd_satker_type`, `sikd_satker_id`, `kode`, `nama`, `singkatan`, `sikd_bidang_id`, `kd_bidang_induk`, `rek_konsolidasi_id`, `nip_ka_satker`, `nm_ka_satker`, `jab_ka_satker`, `klasifikasi`, `satker_pendapatan`, `sotk_lama`, `npwp_satker`, `kd_skpd_bmd`, `created_by`, `creation_date`, `last_updated_by`, `last_updated_date`) VALUES
(110101,	'SikdSkpd',	'',	'110101',	'DINAS PENDIDIKAN DAN KEBUDAYAAN',	'DINDIK',	'101',	'1101',	'1180101',	'196306101985121002',	'Drs. TARYONO, M.Si',	'KEPALA DINAS',	'',	'1',	'',	NULL,	'1.01.01.01',	'',	'0000-00-00 00:00:00',	'bpkad.pelaporan',	'2019-05-24 10:22:52'),
(110201,	'SikdSkpd',	'',	'110201',	'DINAS KESEHATAN',	'DINKES',	'102',	'1102',	'1180102',	'197412202001121004',	'DEDEN DENI,SE',	'Plt. KEPALA DINAS',	'',	'1',	'',	NULL,	'1.01.02.01',	'',	'0000-00-00 00:00:00',	'bpkad.susunanggaran',	'2018-09-19 09:56:59'),
(110202,	'SikdSkpd',	'110202',	'110202',	'RUMAH SAKIT UMUM',	'RSU',	'102',	'1102',	'1180103',	'197610152007012007',	'dr. ALLIN HENDALIN. M',	'Plt. DIREKTUR',	'',	'1',	'1',	NULL,	'1.01.02.02',	'',	'0000-00-00 00:00:00',	'bpkad.susunanggaran',	'2018-10-01 13:21:26'),
(110301,	'SikdSkpd',	'',	'110301',	'DINAS PEKERJAAN UMUM',	'DPU',	'103',	'1103',	'1180104',	'197504082001121003',	'ARIES KURNIAWAN, ST, MT \r\n',	'PlT.KEPALA DINAS',	'',	'0',	'',	NULL,	'1.01.03.01',	'',	'0000-00-00 00:00:00',	'bpkad.susunanggaran',	'2018-09-19 10:10:48'),
(110302,	'SikdSkpd',	NULL,	'110302',	'DINAS BANGUNAN DAN PENATAAN RUANG',	'DBPR',	'103',	'1103',	'1180105',	'196612301996031001',	'Ir.DENDI PRYANDANA, MT\r\n',	'Kepala Dinas',	'',	'0',	NULL,	NULL,	'1.01.03.02',	'',	'0000-00-00 00:00:00',	'bpkad.susunanggaran',	'2017-11-05 11:00:05'),
(110401,	'SikdSkpd',	'',	'110401',	'DINAS PERUMAHAN, KAWASAN PERMUKIMAN DAN PERTANAHAN',	'DPKPP',	'104',	'1104',	'1180106',	'196105291982121001',	'TEDDY MEIYADI,SE,MM\r\n',	'PLT.Kepala Dinas',	'',	'1',	'',	NULL,	'1.01.04.01',	'',	'0000-00-00 00:00:00',	'bpkad.susunanggaran',	'2018-09-19 10:11:44'),
(110501,	'SikdSkpd',	NULL,	'110501',	'DINAS PEMADAM KEBAKARAN DAN PENYELAMATAN',	'DPKP',	'105',	'1105',	'1180107',	'196209171985031014',	'Drs. UCI SANUSI, M.Pd\r\n\r\n',	'Kepala Dinas',	'',	'1',	NULL,	NULL,	'1.01.05.01',	'',	'0000-00-00 00:00:00',	'admin2an',	'2017-11-07 17:29:09'),
(110502,	'SikdSkpd',	'',	'110502',	'BADAN PENANGGULANGAN BENCANA DAERAH ',	'BPBD',	'105',	'1105',	'1180108',	'196711271997031002',	'Drs. H. CHAERUDIN, M.Si\r\n',	'Kepala Pelaksana',	'',	'0',	'',	NULL,	'1.01.05.02',	'',	'0000-00-00 00:00:00',	'bpkad.susunanggaran',	'2018-12-27 18:16:59'),
(110503,	'SikdSkpd',	'',	'110503',	'SATUAN POLISI PAMONG PRAJA ',	'SATPOL',	'105',	'1105',	'1180109',	'196108161986031012',	'MOHAMAD UTUH, S.Sos\r\n',	'Plt. KEPALA SATUAN POLISI PAMONG PRAJA',	'',	'0',	'',	NULL,	'1.01.05.03',	'',	'0000-00-00 00:00:00',	'bpkad.susunanggaran',	'2019-07-15 10:08:36'),
(110504,	'SikdSkpd',	'',	'110504',	'BADAN KESATUAN BANGSA DAN POLITIK',	'KESBANGPOL',	'105',	'1105',	'1180110',	'19610712198501100',	'DR. RAHMAT SALAM, M.Si\r\n',	'Plt. KEPALA BADAN',	'',	'0',	'',	NULL,	'1.01.05.04',	'',	'0000-00-00 00:00:00',	'bpkad.susunanggaran',	'2019-07-11 14:01:28'),
(110601,	'SikdSkpd',	'',	'110601',	'DINAS SOSIAL',	'DINSOS',	'106',	'1106',	'1180111',	'197205261992031002',	'WAHYUNOTO LUKMAN, S.IP, MM\r\n',	'KEPALA DINAS',	'',	'0',	'',	NULL,	'1.01.06.01',	'',	'0000-00-00 00:00:00',	'bpkad.susunanggaran',	'2018-09-19 10:12:59'),
(120101,	'SikdSkpd',	NULL,	'120101',	'DINAS KETENAGAKERJAAN',	'DISNAKER',	'201',	'1201',	'1180112',	'196103041986031010',	'H. PURNAMA WIJAYA S.Sos, M.Si\r\n',	'KEPALA DINAS',	'',	'1',	NULL,	NULL,	'1.02.01.01',	'',	'0000-00-00 00:00:00',	'admin',	'2017-01-06 16:28:49'),
(120201,	'SikdSkpd',	'',	'120201',	'DINAS PEMBERDAYAAN MASYARAKAT PEMBERDAYAAN PEREMPUAN PERLINDUNGAN ANAK DAN KELUARGA BERENCANA',	'DPMP3AKB',	'202',	'1202',	'1180113',	'196308191989012003',	'drg. Hj. KHAIRATI, M.Kes\r\n',	'Kepala Dinas',	'',	'0',	'',	NULL,	'1.02.02.01',	'',	'0000-00-00 00:00:00',	'bpkad.susunanggaran',	'2018-09-19 10:16:00'),
(120501,	'SikdSkpd',	'',	'120501',	'DINAS LINGKUNGAN HIDUP ',	'DLH',	'205',	'1205',	'1180114',	'196607281986031004',	'Drs. H. TOTO SUDARTO, M.Si',	'Kepala Dinas',	'',	'1',	'',	NULL,	'1.02.05.01',	'',	'0000-00-00 00:00:00',	'bpkad.susunanggaran',	'2018-09-19 10:15:32'),
(120601,	'SikdSkpd',	'',	'120601',	'DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL ',	'DISDUKCAPIL',	'206',	'1206',	'1180115',	'196412201985091001',	'Drs. H. DEDI BUDIAWAN, MM\r\n',	'Kepala Dinas',	'',	'0',	'',	NULL,	'1.02.06.01',	'',	'0000-00-00 00:00:00',	'bpkad.susunanggaran',	'2018-09-19 10:17:10'),
(120901,	'SikdSkpd',	'',	'120901',	'DINAS PERHUBUNGAN',	'DISHUB',	'209',	'1209',	'1180116',	'196203111985031012',	'Drs. H. SUKANTA\r\n',	'KEPALA DINAS',	'',	'1',	'',	NULL,	'1.02.09.01',	'',	'0000-00-00 00:00:00',	'bpkad.susunanggaran',	'2018-12-26 11:10:59'),
(121001,	'SikdSkpd',	'',	'121001',	'DINAS KOMUNIKASI DAN INFORMATIKA ',	'DISKOMINFO',	'210',	'1210',	'1180117',	'197411291993031003',	'Drs.Fuad, MPP\r\n',	'Plt.Kepala Dinas',	'',	'0',	'',	NULL,	'1.02.10.01',	'',	'0000-00-00 00:00:00',	'admin2an',	'2019-01-04 14:33:52'),
(121101,	'SikdSkpd',	NULL,	'121101',	'DINAS KOPERASI, USAHA KECIL DAN MENENGAH ',	'DKUKM',	'211',	'1211',	'1180118',	'196408151991032005',	'Drg. DAHLIA NADEAK, M.Kes\r\n',	'Plt.KEPALA DINAS',	'',	'0',	NULL,	NULL,	'1.02.11.01',	'',	'0000-00-00 00:00:00',	'bpkad.susunanggaran',	'2017-11-05 11:10:14'),
(121201,	'SikdSkpd',	NULL,	'121201',	'DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU',	'DPMPTSP',	'212',	'1212',	'1180119',	'197010061991031001',	'BAMBANG NOERTJAHJO, SE. Ak',	'Kepala Dinas',	'',	'1',	NULL,	NULL,	'1.02.12.01',	'',	'0000-00-00 00:00:00',	'bpkad.susunanggaran',	'2017-11-05 10:45:44'),
(121301,	'SikdSkpd',	NULL,	'121301',	'DINAS PEMUDA DAN OLAHRAGA ',	'DPO',	'213',	'1213',	'1180120',	'196503011997031002',	'Ir.H.E.WIWI MARTAWIJAYA, M.Si \r\n',	'KEPALA DINAS',	'',	'1',	NULL,	NULL,	'1.02.13.01',	'',	'0000-00-00 00:00:00',	'bpkad.susunanggaran',	'2017-11-07 22:44:47'),
(121701,	'SikdSkpd',	NULL,	'121701',	'DINAS PERPUSTAKAAN DAN ARSIP DAERAH',	'DPAD',	'217',	'1217',	'1180121',	'196311131985011001',	'Drs. H. DADANG RAHARJA, M.Si\r\n',	'Kepala Dinas',	'',	'0',	NULL,	NULL,	'1.02.17.01',	'',	'0000-00-00 00:00:00',	'admin',	'2017-01-08 20:33:51'),
(200201,	'SikdSkpd',	NULL,	'200201',	'DINAS PARIWISATA ',	'DISPAR',	'302',	'2002',	'1180122',	'197904122002121006',	'JUDIANTO, ST.MT ',	'Kepala Dinas',	'',	'0',	NULL,	NULL,	'2.00.02.01',	'',	'0000-00-00 00:00:00',	'bpkad.susunanggaran',	'2017-11-05 11:13:34'),
(200301,	'SikdSkpd',	'',	'200301',	'DINAS KETAHANAN PANGAN, PERTANIAN DAN PERIKANAN',	'DKPPP',	'203',	'1203',	'1180123',	'196302151996011001',	'Ir. H. NUR SELAMET, MM\r\n',	'Kepala Dinas',	'',	'0',	'',	NULL,	'2.00.03.01',	'',	'0000-00-00 00:00:00',	'admin2an',	'2019-01-10 14:37:48'),
(200401,	'SikdSkpd',	NULL,	'200401',	'DINAS PERINDUSTRIAN DAN PERDAGANGAN',	'DISPERINDAG',	'304',	'2004',	'1180124',	'197008192002122005',	'drg. MAYA MARDIANA, MARS\r\n',	'Kepala Dinas',	'',	'1',	NULL,	NULL,	'2.00.04.01',	'',	'0000-00-00 00:00:00',	'admin2an',	'2017-11-07 17:28:10'),
(300101,	'SikdSkpd',	NULL,	'300101',	'BADAN PERENCANAAN PEMBANGUNAN DAERAH',	'BAPEDA',	'401',	'301',	'1180125',	'196505211994031003',	'Ir. MOCHAMMAD TAHER ROCHMADI, M.Si\r\n',	'KEPALA BADAN',	'',	'0',	NULL,	NULL,	'3.00.01.01',	'',	'0000-00-00 00:00:00',	'admin',	'2017-01-10 16:26:06'),
(300201,	'SikdSkpd',	NULL,	'300201',	'BADAN PENDAPATAN  DAERAH ',	'BPD',	'402',	'302',	'1180126',	'196101241986031006',	'Drs. H. DADANG SOFYAN, MM\r\n',	'KEPALA BADAN',	'',	'1',	NULL,	NULL,	'3.00.02.01',	'',	'0000-00-00 00:00:00',	'admin',	'2017-01-08 20:41:58'),
(300202,	'SikdSkpd',	NULL,	'300202',	'BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH ',	'BPKAD',	'402',	'302',	'1180127',	'196308301984031004',	'Drs. H.WARMAN S. MM\r\n',	'KEPALA BADAN',	'',	'1',	NULL,	NULL,	'3.00.02.02',	'',	'0000-00-00 00:00:00',	'admin',	'2017-01-08 20:42:30'),
(300301,	'SikdSkpd',	'',	'300301',	'BADAN KEPEGAWAIAN, PENDIDIKAN DAN PELATIHAN',	'BKPP',	'403',	'303',	'1180128',	'196303091986031013',	'H. APENDI, S.Sos, M.Si\r\n',	'Kepala Badan',	'',	'0',	'',	NULL,	'3.00.04.02',	'',	'0000-00-00 00:00:00',	'admin2an',	'2019-01-10 14:36:33'),
(300501,	'SikdSkpd',	'',	'300501',	'SEKRETARIAT DPRD ',	'SEKWAN',	'405',	'305',	'1180129',	'196707231987031002',	'Drs. H. CHAERUL SOLEH, M.Si\r\n',	'Sekretaris DPRD',	'',	'0',	'',	NULL,	'3.00.05.01',	'',	'0000-00-00 00:00:00',	'bpkad.susunanggaran',	'2019-07-12 15:45:25'),
(300601,	'SikdSkpd',	'',	'300601',	'SEKRETARIAT DAERAH ',	'SEKDA',	'367416060794408',	'306',	'1180130',	'196404061985031014',	'Drs. H. MUHAMAD, M.Si\r\n',	'SEKRETARIS DAERAH',	'',	'0',	'0',	NULL,	'3.00.06.01',	'',	'0000-00-00 00:00:00',	'bpkad.susunanggaran',	'2018-10-23 11:47:06'),
(300701,	'SikdSkpd',	'',	'300701',	'INSPEKTORAT ',	'INSPEKTORAT',	'367416060794409',	'307',	'1180141',	'196109031991021001',	'H. Uus Kusnadi, SE, M.Si\r\n',	'INSPEKTUR',	'',	'0',	'',	NULL,	'3.00.07.01',	'',	'0000-00-00 00:00:00',	'bpkad.susunanggaran',	'2018-10-12 14:41:08'),
(300801,	'SikdSkpd',	'',	'300801',	'KECAMATAN CIPUTAT ',	'CIPUTAT',	'367416060794410',	'308',	'1180142',	'197510251994121001',	'Drs. H. ANDI D. PATABAI AP.M.Si\r\n',	'CAMAT CIPUTAT',	'',	'0',	'',	NULL,	'3.00.08.01',	'',	'0000-00-00 00:00:00',	'bpkad.susunanggaran',	'2018-12-21 09:24:34'),
(300802,	'SikdSkpd',	'',	'300802',	'KECAMATAN CIPUTAT TIMUR ',	'CIPUTAT TIMUR',	'367416060794410',	'308',	'1180143',	'196702151992031004',	'Drs. SUTANG SUPRIANTO, M.Si\r\n',	'CAMAT CIPUTAT TIMUR',	'',	'0',	'',	NULL,	'3.00.08.02',	'',	'0000-00-00 00:00:00',	'bpkad.susunanggaran',	'2019-06-27 10:58:10'),
(300803,	'SikdSkpd',	NULL,	'300803',	'KECAMATAN PAMULANG ',	'PAMULANG',	'367416060794410',	'308',	'1180144',	'196205101989021001',	'H. DEDEN JUARDI, S.Sos.,M.Si\r\n',	'CAMAT PAMULANG',	'',	'0',	NULL,	NULL,	'3.00.08.03',	'',	'0000-00-00 00:00:00',	'admin',	'2016-11-02 17:41:29'),
(300804,	'SikdSkpd',	NULL,	'300804',	'KECAMATAN SERPONG ',	'SERPONG',	'367416060794410',	'308',	'1180145',	'196509042005012005',	'MURSINAH, SH., M.Si\r\n',	'KECAMATAN SERPONG',	'',	'0',	NULL,	NULL,	'3.00.08.04',	'',	'0000-00-00 00:00:00',	'admin',	'2016-11-02 17:42:21'),
(300805,	'SikdSkpd',	NULL,	'300805',	'KECAMATAN SERPONG UTARA ',	'SERPONG UTARA',	'367416060794410',	'308',	'1180146',	'197407281994021002',	'BANI KHOSYATULLOH\r\n',	'CAMAT SERPONG UTARA',	'',	'0',	NULL,	NULL,	'3.00.08.05',	'',	'0000-00-00 00:00:00',	'admin2an',	'2016-11-14 18:46:06'),
(300806,	'SikdSkpd',	'',	'300806',	'KECAMATAN PONDOK AREN ',	'PONDOK AREN',	'367416060794410',	'308',	'1180147',	'196701032005011004',	'MAKUM SAGITA,S.Pd\r\n',	'CAMAT PONDOK AREN\r\n',	'',	'0',	'',	NULL,	'3.00.08.06',	'',	'0000-00-00 00:00:00',	'admin.user',	'2018-03-14 14:31:47'),
(300807,	'SikdSkpd',	'',	'300807',	'KECAMATAN SETU ',	'SETU',	'367416060794410',	'308',	'1180148',	'196303181988031006',	'H. HERU AGUS S, AP, M.Si\r\n',	'CAMAT SETU',	'',	'0',	'',	NULL,	'3.00.08.07',	'',	'0000-00-00 00:00:00',	'bpkad.susunanggaran',	'2018-09-19 10:20:26'),
(300901,	'SikdSkpd',	'',	'300901',	'DEWAN PERWAKILAN RAKYAT DAERAH',	'DPRD',	'367416060794411',	'309',	'1180149',	'196707231987031002',	'Drs. H. CHAERUL SOLEH, M.Si',	'Sekretaris DPRD',	'',	'0',	'',	NULL,	'3.00.09.01',	'',	'0000-00-00 00:00:00',	'bpkad.susunanggaran',	'2019-07-12 15:45:05'),
(300902,	'SikdSkpd',	'',	'300902',	'WALIKOTA DAN WAKIL WALIKOTA',	'KDH-WKDH',	'367416060794411',	'309',	'1180150',	'196201261986031005',	'H. M. SAHLAN, S.Sos',	'Kepala Bagian Keuangan',	'',	'0',	'',	NULL,	'3.00.09.02',	'',	'0000-00-00 00:00:00',	'bpkad.susunanggaran',	'2018-10-22 11:22:19'),
(90000,	'SikdSkpkd',	'',	'300202',	'SATUAN KERJA PEGELOLA KEUANGAN DAERAH',	'SKPKD',	'402',	'302',	'3110201',	'196308301984031004',	'Drs. H. Warman S. MM',	'Pejabat Pegelola Keuangan Daerah',	'0',	'1',	'',	NULL,	'3.00.02.02',	'5/20/2014 12:06',	'0000-00-00 00:00:00',	'admin2an',	'2019-02-11 18:45:08');

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
  `budget` varchar(100) DEFAULT '0.00',
  `budget_from` varchar(100) DEFAULT NULL,
  `description` text,
  `result_date` date DEFAULT NULL,
  `result` text,
  `result_username` varchar(50) DEFAULT NULL,
  `file` longtext,
  `jenis_surat_id` int(10) DEFAULT NULL,
  `file_update` longtext,
  `status` varchar(100) DEFAULT '0' COMMENT '0 : diinput  1 : dicetak 2 : selesai',
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
(52,	'1958060519860811001',	NULL,	NULL,	'wqw',	NULL,	NULL,	NULL,	'kmsddasd',	NULL,	NULL,	'2023-04-15',	'1958060519860811001',	'195802281986012002',	NULL,	'195802281986012002,1958060519860811001,195807171980031014,195807171980032008,195808281986011003,195809281980032008',	'Melaksanan',	'pesawat',	'Medan',	'Gak tau',	3,	'2023-04-13',	'2023-04-13',	'DINAS PENDIDIKAN DAN KEBUDAYAAN',	NULL,	'matamu',	'olee',	NULL,	NULL,	NULL,	NULL,	12,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	'OKere',	'medan',	'03234234234234',	'195808281986011003',	'195912251990031004',	NULL,	NULL,	NULL,	NULL),
(53,	'195802281986012002',	NULL,	NULL,	'asas',	'asdada',	'dadad',	'adadad',	'adsad',	'2025-08-16T08:26:27.148Z',	'asas',	'2025-08-16 08:26:27',	NULL,	NULL,	'0',	'195802281986012002,195808281986011003,195809281980032008',	'asdada',	'adad',	'1231313',	'adad',	12,	'2025-08-01',	'2025-08-12',	'DINAS KESEHATAN',	'sdad',	NULL,	'adsad',	NULL,	NULL,	NULL,	NULL,	1,	NULL,	'draft',	'admin12',	NULL,	'2025-08-16 08:26:27',	NULL,	'adasda',	'adad',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(54,	'195802281986012002',	NULL,	NULL,	'A23/1213NM/123213',	'asdada',	'313123',	'adadad',	'adasd',	'2025-08-16T08:33:06.107Z',	'A23/1213NM/123213',	'2025-08-16 08:33:06',	NULL,	NULL,	'0',	'195802281986012002,195807171980031014,195807171980032008',	'asdada',	'adad',	'dadad',	'adad',	28,	'2025-08-01',	'2025-08-28',	'DINAS KESEHATAN',	'sdad',	NULL,	'adasd',	NULL,	NULL,	NULL,	NULL,	1,	NULL,	'draft',	'admin12',	NULL,	'2025-08-16 08:33:06',	NULL,	'adada',	'adad',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(55,	'195802281986012002',	NULL,	NULL,	'DALAM KOTA',	'asdada',	'313123',	'131313',	'adadsa',	'2025-08-16T12:29:00.612Z',	'DALAM KOTA',	'2025-08-16 12:29:01',	NULL,	NULL,	'0',	'195802281986012002,1958060519860811001',	'asdada',	'adad',	'1231313',	'adad',	24,	'2025-08-01',	'2025-08-24',	'DINAS PENDIDIKAN DAN KEBUDAYAAN',	'sdad',	NULL,	'adadsa',	NULL,	NULL,	NULL,	NULL,	1,	NULL,	'draft',	'admin12',	NULL,	'2025-08-16 12:29:01',	NULL,	'adasd',	'adad',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL);

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


DROP TABLE IF EXISTS `transportation`;
CREATE TABLE `transportation` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `code` varchar(14) DEFAULT NULL,
  `name` varchar(15) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(14) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- 2025-08-16 15:59:26 UTC