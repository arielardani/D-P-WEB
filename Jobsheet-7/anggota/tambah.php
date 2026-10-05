<?php
$page_title = "Tambah Anggota";
include __DIR__ . '/../includes/header.php';
?>

<section>

    <h2>Tambah Anggota</h2>

    <form method="post" action="proses_tambah.php">

        <p>
            <label for="nama">
                Nama Anggota
            </label>

            <input
                type="text"
                id="nama"
                name="nama"
                required>
        </p>

        <p>
            <label for="email">
                Email
            </label>

            <input
                type="email"
                id="email"
                name="email"
                required>
        </p>

        <button type="submit">
            Simpan
        </button>

    </form>

    <p>
        <a href="list.php">
            Kembali ke daftar anggota
        </a>
    </p>

</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>