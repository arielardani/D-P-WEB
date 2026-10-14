
<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

// Penghapusan hanya boleh melalui POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Metode permintaan tidak diizinkan.');
}

// Ambil dan validasi ID buku
$id = filter_input(
    INPUT_POST,
    'id',
    FILTER_VALIDATE_INT
);

if (!$id || $id < 1) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'ID buku tidak valid.'
    ];

    header('Location: list.php');
    exit;
}

try {
    $stmt = $pdo->prepare(
        'DELETE FROM buku WHERE id = :id'
    );

    $stmt->execute(['id' => $id]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['flash'] = [
            'type' => 'success',
            'pesan' => 'Buku berhasil dihapus.'
        ];
    } else {
        $_SESSION['flash'] = [
            'type' => 'error',
            'pesan' => 'Buku tidak ditemukan.'
        ];
    }
} catch (PDOException $e) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Buku gagal dihapus. Periksa apakah data masih digunakan oleh tabel lain.'
    ];
}

header('Location: list.php');
exit;