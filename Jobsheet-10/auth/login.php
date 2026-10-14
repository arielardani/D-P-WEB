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
    <title>Login Petugas - SIMPUS-Mini</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <main class="container">
        <h1>Login Petugas</h1>

        <?php if ($pesan === 'terdaftar'): ?>
            <p>Registrasi berhasil. Silakan login.</p>
        <?php elseif ($pesan === 'gagal'): ?>
            <p>Username atau password salah.</p>
        <?php endif; ?>

        <form action="proses_login.php" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username"
                   maxlength="50" autocomplete="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                   autocomplete="current-password" required>

            <button type="submit">Login</button>
        </form>

        <p>Belum punya akun? <a href="register.php">Daftar petugas</a>.</p>
        <p><a href="../index.php">Kembali ke beranda</a></p>
    </main>
</body>
</html>