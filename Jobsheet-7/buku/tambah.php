<?php
$page_title = "Tambah Buku";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

        <section>
            <h2>Tambah Data Buku</h2>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo htmlspecialchars($flash['pesan']); ?></p>
            <?php endif; ?>

            <form id="form-tambah" method="post" action="proses_tambah.php">

                <label for="judul">Judul Buku</label>
                <input type="text" id="judul" name="judul" placeholder="Masukkan judul buku">

                <label for="penulis">Penulis</label>
                <input type="text" id="penulis" name="penulis" placeholder="Masukkan nama penulis">

                <label for="penerbit">Penerbit</label>
                <input type="text" id="penerbit" name="penerbit" placeholder="Masukkan nama penerbit">

                <label for="tahun">Tahun Terbit</label>
                <input type="number" id="tahun" name="tahun" placeholder="Contoh: 2024">

                <label for="stok">Stok</label>
                <input type="number" id="stok" name="stok" min="0" placeholder="Contoh: 5">

                <label for="kategori">Kategori</label>
                <select id="kategori" name="kategori">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="teknologi">Teknologi</option>
                    <option value="pemrograman">Pemrograman</option>
                    <option value="novel">Novel</option>
                    <option value="pendidikan">Pendidikan</option>
                </select>

                <button type="submit">Simpan</button>
                <a href="list.php">Batal</a>

            </form>
        </section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
