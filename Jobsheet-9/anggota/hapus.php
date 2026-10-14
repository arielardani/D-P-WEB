
<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

// Hapus hanya boleh dilakukan melalui POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Metode permintaan tidak diizinkan.');
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'ID anggota tidak valid.'
    ];

    header('Location: list.php');
    exit;
}

try {
    $stmt = $pdo->prepare(
        'DELETE FROM anggota WHERE id = :id'
    );

    $stmt->execute(['id' => $id]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['flash'] = [
            'type' => 'success',
            'pesan' => 'Data anggota berhasil dihapus.'
        ];
    } else {
        $_SESSION['flash'] = [
            'type' => 'error',
            'pesan' => 'Data anggota tidak ditemukan.'
        ];
    }

} catch (PDOException $e) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Data anggota gagal dihapus. Data mungkin masih digunakan oleh tabel lain.'
    ];
}

header('Location: list.php');
exit;
