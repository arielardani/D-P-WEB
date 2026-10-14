<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$pesan = $_GET['pesan'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Petugas - SIMPUS-Mini</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <main class="container">
        <h1>Registrasi Petugas</h1>

        <?php if ($pesan === 'username'): ?>
            <p>Username sudah digunakan. Silakan pilih username lain.</p>
        <?php elseif ($pesan === 'gagal'): ?>
            <p>Registrasi gagal. Silakan coba kembali.</p>
        <?php endif; ?>

        <form action="proses_register.php" method="POST">
            <label for="nama">Nama lengkap</label>
            <input type="text" id="nama" name="nama"
                   maxlength="255" required>

            <label for="username">Username</label>
            <input type="text" id="username" name="username"
                   maxlength="50" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                   minlength="8" required>

            <label for="konfirmasi">Konfirmasi password</label>
            <input type="password" id="konfirmasi" name="konfirmasi"
                   minlength="8" required>

            <button type="submit">Daftar</button>
        </form>

        <p>Sudah punya akun? <a href="login.php">Login di sini</a>.</p>
        <p><a href="../index.php">Kembali ke beranda</a></p>
    </main>
</body>
</html>