<?php
$page_title = "Beranda";
include __DIR__ . '/includes/header.php';

$totalBuku = count($_SESSION['buku'] ?? []);
$totalAnggota = count($_SESSION['anggota'] ?? []);
?>

        <section>
            <h2>Dashboard</h2>
            <p>
                Selamat datang di Sistem Informasi Perpustakaan Mini
                (SIMPUS-Mini).
            </p>
        </section>

        <section>
            <article>
                <h3>Total Buku</h3>
                <p><?php echo $totalBuku; ?></p>
            </article>

            <article>
                <h3>Total Anggota</h3>
                <p><?php echo $totalAnggota; ?></p>
            </article>

            <article>
                <h3>Total Peminjaman</h3>
                <p>35</p>
            </article>
        </section>

        <section>
            <h2>Menu Cepat</h2>

            <p>
                <a href="buku/list.php">Kelola Data Buku</a>
            </p>

            <p>
                <a href="anggota/list.php">Kelola Data Anggota</a>
            </p>
        </section>

<?php include __DIR__ . '/includes/footer.php'; ?>
