
<?php
$page_title = "Daftar Anggota";

include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// Mengambil kata kunci pencarian
$cari = trim($_GET['cari'] ?? '');

// Pengaturan pagination
$perHalaman = 5;
$halaman = filter_input(INPUT_GET, 'halaman', FILTER_VALIDATE_INT);

if (!$halaman || $halaman < 1) {
    $halaman = 1;
}

// Menghitung jumlah anggota sesuai pencarian
if ($cari !== '') {
    $stmtJumlah = $pdo->prepare(
        "SELECT COUNT(*) FROM anggota
         WHERE nama ILIKE :nama
            OR no_anggota::text ILIKE :nomor"
    );

    $kataKunci = '%' . $cari . '%';

    $stmtJumlah->execute([
        'nama' => $kataKunci,
        'nomor' => $kataKunci
    ]);
} else {
    $stmtJumlah = $pdo->query(
        "SELECT COUNT(*) FROM anggota"
    );
}

$totalAnggota = (int) $stmtJumlah->fetchColumn();
$totalHalaman = (int) ceil($totalAnggota / $perHalaman);

// Memastikan nomor halaman valid
if ($totalHalaman > 0 && $halaman > $totalHalaman) {
    $halaman = $totalHalaman;
}

$offset = ($halaman - 1) * $perHalaman;

// Mengambil anggota untuk halaman yang dipilih
if ($cari !== '') {
    $stmt = $pdo->prepare(
        "SELECT * FROM anggota
         WHERE nama ILIKE :nama
            OR no_anggota::text ILIKE :nomor
         ORDER BY id DESC
         LIMIT :batas OFFSET :offset"
    );

    $stmt->bindValue(':nama', $kataKunci, PDO::PARAM_STR);
    $stmt->bindValue(':nomor', $kataKunci, PDO::PARAM_STR);
    $stmt->bindValue(':batas', $perHalaman, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
} else {
    $stmt = $pdo->prepare(
        "SELECT * FROM anggota
         ORDER BY id DESC
         LIMIT :batas OFFSET :offset"
    );

    $stmt->bindValue(':batas', $perHalaman, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
}

$daftarAnggota = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section>
    <h2>Daftar Anggota</h2>

    <?php if ($flash): ?>
        <p class="flash flash-<?php
            echo htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8');
        ?>">
            <?php
            echo htmlspecialchars($flash['pesan'], ENT_QUOTES, 'UTF-8');
            ?>
        </p>
    <?php endif; ?>

    <form class="search-box" method="get" action="list.php">
        <label for="cari">Cari Nama atau No. Anggota</label>

        <input
            type="text"
            id="cari"
            name="cari"
            placeholder="Ketik nama atau nomor anggota..."
            value="<?= htmlspecialchars($cari, ENT_QUOTES, 'UTF-8') ?>"
        >

        <button type="submit">Cari</button>

        <?php if ($cari !== ''): ?>
            <a href="list.php">Reset</a>
        <?php endif; ?>
    </form>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No. Anggota</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>No. HP</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($daftarAnggota)): ?>
                    <tr>
                        <td colspan="5">
                            <?php if ($cari !== ''): ?>
                                Tidak ada anggota yang cocok dengan pencarian.
                            <?php else: ?>
                                Belum ada data anggota.
                                Silakan tambah lewat menu "Tambah Anggota".
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($daftarAnggota as $anggota): ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars(
                                    (string) $anggota['no_anggota'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $anggota['nama'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $anggota['alamat'] ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $anggota['no_hp'] ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>

                            <td>
                                <a
                                    class="btn-edit"
                                    href="edit.php?id=<?= (int) $anggota['id'] ?>"
                                >
                                    Edit
                                </a>

                                <form
                                    class="form-hapus"
                                    method="post"
                                    action="hapus.php"
                                >
                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= (int) $anggota['id'] ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="btn-hapus"
                                    >
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalHalaman > 1): ?>
        <nav class="pagination" aria-label="Navigasi halaman">
            <?php if ($halaman > 1): ?>
                <a href="?<?= http_build_query([
                    'cari' => $cari,
                    'halaman' => $halaman - 1
                ]) ?>">
                    &laquo; Sebelumnya
                </a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalHalaman; $i++): ?>
                <a
                    href="?<?= http_build_query([
                        'cari' => $cari,
                        'halaman' => $i
                    ]) ?>"
                    <?= $i === $halaman ? 'aria-current="page"' : '' ?>
                    class="<?= $i === $halaman ? 'active' : '' ?>"
                >
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($halaman < $totalHalaman): ?>
                <a href="?<?= http_build_query([
                    'cari' => $cari,
                    'halaman' => $halaman + 1
                ]) ?>">
                    Selanjutnya &raquo;
                </a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
