-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 13 Des 2021 pada 18.22
-- Versi server: 10.4.17-MariaDB
-- Versi PHP: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `digilib_smkmuh1_surakarta`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggota`
--

CREATE TABLE `anggota` (
  `nomor_anggota` varchar(25) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `jabatan` enum('guru','siswa') NOT NULL,
  `angkatan` varchar(4) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `anggota`
--

INSERT INTO `anggota` (`nomor_anggota`, `nama`, `jabatan`, `angkatan`, `password`) VALUES
('18106050038', 'Haha Hihi', 'siswa', '2018', '8d01bf96f24111b52102d5055cbc4654'),
('19101010001', 'Account Test', 'siswa', '2019', '84d53afcd0ec186e037dd48d8d57bfb5'),
('19106050038', 'Fikri', 'siswa', '2019', '202cb962ac59075b964b07152d234b70');

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku`
--

CREATE TABLE `buku` (
  `nomor_klasifikasi` varchar(7) NOT NULL,
  `isbn` varchar(13) NOT NULL,
  `nomor_rak` varchar(5) NOT NULL,
  `judul` varchar(50) NOT NULL,
  `penulis` varchar(50) NOT NULL,
  `penerbit` varchar(50) NOT NULL,
  `tahun_terbit` year(4) NOT NULL,
  `stok` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `buku`
--

INSERT INTO `buku` (`nomor_klasifikasi`, `isbn`, `nomor_rak`, `judul`, `penulis`, `penerbit`, `tahun_terbit`, `stok`) VALUES
('200CARp', '23', '200/2', 'Principled Pragmatism', 'Carl Fredrik Feddersen', 'Cappelen Damm Akademisk/NOASP (Nordic Open Access ', 2017, 2),
('200EKEs', '9786020303932', '200/1', 'Seperti Dendam, Rindu Harus Dibayar Tuntas', 'EkÊ¹a Kurniawan', 'PT Gramedia Pustaka Utama', 2014, 1),
('200SALp', '9786235764009', '200/1', 'Pandemi Segera Pergi!', 'Salsiyah', 'Gema Godam Grafika', 2021, 1),
('600Bc', '111', '600/1', 'B', 'C', 'D', 2000, 4),
('600TESt', '123', '600/1', 'Tes_buku', 'Tes_penulis', 'Tes_penerbit', 2000, 3);

--
-- Trigger `buku`
--
DELIMITER $$
CREATE TRIGGER `hapus_buku` AFTER DELETE ON `buku` FOR EACH ROW BEGIN
	UPDATE rak SET jumlah_buku = jumlah_buku - OLD.stok
    WHERE nomor_rak = OLD.nomor_rak;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tambah_buku` AFTER INSERT ON `buku` FOR EACH ROW BEGIN
	UPDATE rak SET jumlah_buku = jumlah_buku + NEW.stok
    WHERE nomor_rak = NEW.nomor_rak;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `petugas`
--

CREATE TABLE `petugas` (
  `nomor_petugas` varchar(25) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `petugas`
--

INSERT INTO `petugas` (`nomor_petugas`, `nama`, `password`) VALUES
('199901012001012001', 'Admin', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rak`
--

CREATE TABLE `rak` (
  `nomor_rak` varchar(5) NOT NULL,
  `nama_rak` varchar(50) NOT NULL,
  `jumlah_buku` int(3) NOT NULL,
  `kategori` enum('000 - Umum','100 - Filsafat dan Psikologi','200 - Agama','300 - Sosial','400 - Bahasa','500 - Sains dan Matematika','600 - Teknologi dan Teknik','700 - Seni dan Rekreasi','800 - Literatur dan Sastra','900 - Sejarah dan Geografi') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `rak`
--

INSERT INTO `rak` (`nomor_rak`, `nama_rak`, `jumlah_buku`, `kategori`) VALUES
('200/1', 'Agama', -1, '200 - Agama'),
('200/2', 'Agama Islam', 0, '200 - Agama'),
('600/1', 'Informatika', 7, '600 - Teknologi dan Teknik'),
('800/1', 'Karangan Fiksi', 0, '800 - Literatur dan Sastra');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_peminjaman` int(10) NOT NULL,
  `nomor_klasifikasi` varchar(7) NOT NULL,
  `nomor_anggota` varchar(25) NOT NULL,
  `nomor_petugas` varchar(25) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `durasi` int(4) NOT NULL,
  `tanggal_kembali` date NOT NULL,
  `keterlambatan` int(4) NOT NULL,
  `selesai` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_peminjaman`, `nomor_klasifikasi`, `nomor_anggota`, `nomor_petugas`, `tanggal_pinjam`, `durasi`, `tanggal_kembali`, `keterlambatan`, `selesai`) VALUES
(1, '200EKEs', '19106050038', '199901012001012001', '2021-11-01', 24, '2021-11-02', 0, 1),
(4, '600Bc', '19106050038', '199901012001012001', '2021-12-02', 3, '2021-12-11', 6, 1),
(5, '200EKEs', '19106050038', '199901012001012001', '2021-12-03', 3, '2021-12-11', 5, 1),
(11, '200CARp', '19106050038', '199901012001012001', '2021-12-11', 33, '2021-12-13', 0, 1),
(12, '200CARp', '19106050038', '199901012001012001', '2021-12-08', 3, '2021-12-13', 2, 1),
(14, '600TESt', '19101010001', '199901012001012001', '2021-12-13', 17, '2021-12-13', 0, 1),
(15, '200SALp', '18106050038', '199901012001012001', '2021-12-01', 3, '2021-12-04', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`nomor_anggota`);

--
-- Indeks untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`nomor_klasifikasi`),
  ADD KEY `nomor_rak` (`nomor_rak`);

--
-- Indeks untuk tabel `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`nomor_petugas`);

--
-- Indeks untuk tabel `rak`
--
ALTER TABLE `rak`
  ADD PRIMARY KEY (`nomor_rak`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD KEY `nomor_anggota` (`nomor_anggota`),
  ADD KEY `nomor_petugas` (`nomor_petugas`),
  ADD KEY `nomor_klasifikasi` (`nomor_klasifikasi`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_peminjaman` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD CONSTRAINT `buku_ibfk_1` FOREIGN KEY (`nomor_rak`) REFERENCES `rak` (`nomor_rak`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`nomor_anggota`) REFERENCES `anggota` (`nomor_anggota`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_3` FOREIGN KEY (`nomor_petugas`) REFERENCES `petugas` (`nomor_petugas`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_4` FOREIGN KEY (`nomor_klasifikasi`) REFERENCES `buku` (`nomor_klasifikasi`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
