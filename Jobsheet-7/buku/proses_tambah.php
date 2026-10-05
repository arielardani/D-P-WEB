<?php
session_start();

// 1. Ambil data dari form
$judul     = trim($_POST['judul'] ?? '');
$penulis   = trim($_POST['penulis'] ?? '');
$penerbit  = trim($_POST['penerbit'] ?? '');
$tahun     = $_POST['tahun'] ?? '';
$stok      = $_POST['stok'] ?? '';
$kategori  = trim($_POST['kategori'] ?? '');

// 2. Validasi di server
$errors = [];

if ($judul === '') {
    $errors[] = "Judul wajib diisi.";
}
if ($penulis === '') {
    $errors[] = "Penulis wajib diisi.";
}
if (!is_numeric($tahun) || $tahun < 1900 || $tahun > 2026) {
    $errors[] = "Tahun harus di antara 1900-2026.";
}
if (!is_numeric($stok) || $stok < 0) {
    $errors[] = "Stok tidak boleh negatif.";
}

// 3. Kalau ada error: simpan pesan, balik ke form
if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: tambah.php');
    exit;
}

// 4. Kalau valid: simpan ke session, lanjut ke daftar
if (!isset($_SESSION['buku'])) {
    $_SESSION['buku'] = [];
}

$_SESSION['buku'][] = [
    'judul'    => $judul,
    'penulis'  => $penulis,
    'penerbit' => $penerbit,
    'tahun'    => (int) $tahun,
    'stok'     => (int) $stok,
    'kategori' => $kategori,
];

$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Buku berhasil ditambahkan.'];
header('Location: list.php');
exit;
