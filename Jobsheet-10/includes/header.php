<?php
// Mulai session hanya jika belum aktif.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base = '/D-P-WEB/Jobsheet-10/';
// Cek apakah pengguna sudah login.
$sudahLogin = !empty($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SIMPUS-Mini<?php
        echo isset($page_title) ? ' | ' . htmlspecialchars($page_title) : '';
    ?></title>

          <link rel="stylesheet" href="/D-P-WEB/Jobsheet-10/assets/css/style.css">
</head>

<body>

<header>
    <h1>SIMPUS-Mini</h1>

    <button
        type="button"
        id="nav-toggle-btn"
        class="nav-toggle-label"
        aria-label="Menu"
        aria-expanded="false"
    >&#9776;</button>

    <nav>
        <ul>
            <li>
                <a href="<?php echo $base; ?>index.php">Beranda</a>
            </li>

            <li>
                <a href="<?php echo $base; ?>buku/list.php">Daftar Buku</a>
            </li>

            <?php if ($sudahLogin): ?>
                <li>
                    <a href="<?php echo $base; ?>buku/tambah.php">
                        Tambah Buku
                    </a>
                </li>

                <li>
                    <a href="<?php echo $base; ?>anggota/list.php">
                        Daftar Anggota
                    </a>
                </li>

                <li>
                    <a href="<?php echo $base; ?>anggota/tambah.php">
                        Tambah Anggota
                    </a>
                </li>

                <li>
                    <span>
                        Halo,
                        <?php echo htmlspecialchars($_SESSION['nama'] ?? 'Petugas'); ?>
                    </span>
                </li>

                <li>
                    <a href="<?php echo $base; ?>auth/logout.php">
                        Logout
                    </a>
                </li>

            <?php else: ?>
                <li>
                    <a href="<?php echo $base; ?>auth/login.php">
                        Login
                    </a>
                </li>

                <li>
                    <a href="<?php echo $base; ?>auth/register.php">
                        Register
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main>
