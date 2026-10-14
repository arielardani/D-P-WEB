
<?php
session_start();
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';

// Proses hanya boleh melalui POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Metode permintaan tidak diizinkan.');
}

// Ambil dan validasi input
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nama = trim($_POST['nama'] ?? '');
$noAnggota = trim($_POST['no_anggota'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');

if (
    !$id || $id < 1 ||
    $nama === '' ||
    $noAnggota === ''
) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'ID, nama, dan nomor anggota wajib diisi dengan benar.'
    ];

    header('Location: list.php');
    exit;
}

try {
    // Pastikan data anggota memang ada
    $cek = $pdo->prepare(
        'SELECT id FROM anggota WHERE id = :id'
    );
    $cek->execute(['id' => $id]);

    if (!$cek->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'pesan' => 'Data anggota tidak ditemukan.'
        ];

        header('Location: list.php');
        exit;
    }

    // Cegah nomor anggota dipakai anggota lain
    $cekNomor = $pdo->prepare(
        'SELECT id FROM anggota
         WHERE no_anggota::text = :nomor
           AND id <> :id'
    );
    $cekNomor->execute([
        'nomor' => $noAnggota,
        'id' => $id
    ]);

    if ($cekNomor->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'pesan' => 'Nomor anggota sudah digunakan anggota lain.'
        ];

        header('Location: edit.php?id=' . $id);
        exit;
    }

    // Simpan perubahan
    $stmt = $pdo->prepare(
        'UPDATE anggota
         SET nama = :nama,
             no_anggota = :no_anggota,
             alamat = :alamat,
             no_hp = :no_hp
         WHERE id = :id'
    );

    $stmt->execute([
        'nama' => $nama,
        'no_anggota' => $noAnggota,
        'alamat' => $alamat !== '' ? $alamat : null,
        'no_hp' => $noHp !== '' ? $noHp : null,
        'id' => $id
    ]);

    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Data anggota berhasil diperbarui.'
    ];

    header('Location: list.php');
    exit;

} catch (PDOException $e) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Data anggota gagal diperbarui. Periksa kembali data yang dimasukkan.'
    ];

    header('Location: edit.php?id=' . $id);
    exit;
}
