<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

$nama = trim($_POST['nama'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$konfirmasi = $_POST['konfirmasi'] ?? '';

if (
    $nama === '' ||
    $username === '' ||
    $password === '' ||
    strlen($password) < 8 ||
    $password !== $konfirmasi
) {
    header('Location: register.php?pesan=gagal');
    exit;
}

try {
    $cek = $pdo->prepare(
        'SELECT id FROM users WHERE username = :username'
    );
    $cek->execute(['username' => $username]);

    if ($cek->fetch()) {
        header('Location: register.php?pesan=username');
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare(
        'INSERT INTO users (nama, username, password, role)
         VALUES (:nama, :username, :password, :role)'
    );

    $stmt->execute([
        'nama' => $nama,
        'username' => $username,
        'password' => $hash,
        'role' => 'petugas'
    ]);

    header('Location: login.php?pesan=terdaftar');
    exit;
} catch (PDOException $e) {
    error_log($e->getMessage());
    header('Location: register.php?pesan=gagal');
    exit;
}