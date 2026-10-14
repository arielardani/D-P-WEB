<?php
$page_title = "Daftar Buku";

include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
require_once __DIR__ . '/../includes/header.php';


$cari = trim($_GET['cari'] ?? '');

// Pengaturan pagination
$perHalaman = 5;
$halaman = filter_input(INPUT_GET, 'halaman', FILTER_VALIDATE_INT);

if (!$halaman || $halaman < 1) {
    $halaman = 1;
}

// Hitung jumlah data sesuai pencarian
if ($cari !== '') {
    $stmtJumlah = $pdo->prepare(
        "SELECT COUNT(*) FROM buku
         WHERE judul ILIKE :judul
            OR pengarang ILIKE :pengarang"
    );

    $kataKunci = '%' . $cari . '%';

    $stmtJumlah->execute([
        'judul' => $kataKunci,
        'pengarang' => $kataKunci
    ]);
} else {
    $stmtJumlah = $pdo->query(
        "SELECT COUNT(*) FROM buku"
    );
}

$totalBuku = (int) $stmtJumlah->fetchColumn();
$totalHalaman = (int) ceil($totalBuku / $perHalaman);

// Pastikan nomor halaman tidak melebihi halaman terakhir
if ($totalHalaman > 0 && $halaman > $totalHalaman) {
    $halaman = $totalHalaman;
}

$offset = ($halaman - 1) * $perHalaman;

// Ambil data untuk halaman yang sedang dibuka
if ($cari !== '') {
    $stmt = $pdo->prepare(
        "SELECT * FROM buku
         WHERE judul ILIKE :judul
            OR pengarang ILIKE :pengarang
         ORDER BY id DESC
         LIMIT :batas OFFSET :offset"
    );

    $stmt->bindValue(':judul', $kataKunci, PDO::PARAM_STR);
    $stmt->bindValue(':pengarang', $kataKunci, PDO::PARAM_STR);
    $stmt->bindValue(':batas', $perHalaman, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
} else {
    $stmt = $pdo->prepare(
        "SELECT * FROM buku
         ORDER BY id DESC
         LIMIT :batas OFFSET :offset"
    );

    $stmt->bindValue(':batas', $perHalaman, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
}

$daftarBuku = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<section>
    <h2>Daftar Buku</h2>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo $flash['type']; ?>">
            <?php echo $flash['pesan']; ?>
        </p>
    <?php endif; ?>


    <form class="search-box" method="get" action="list.php">
        <label for="cari">Cari Judul atau Pengarang</label>

        <input type="text" id="cari" name="cari" placeholder="Ketik judul atau pengarang..."
            value="<?= htmlspecialchars($cari, ENT_QUOTES, 'UTF-8') ?>">

        <button type="submit">Cari</button>

        <?php if ($cari !== ''): ?>
            <a href="list.php">Reset</a>
        <?php endif; ?>
    </form>

    <div class="table-responsive">
        <table>

            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Tahun</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                <?php if (empty($daftarBuku)): ?>

                    <tr>
                        <td colspan="5">
                            Belum ada data buku.
                            Silakan tambah lewat menu "Tambah Buku".
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($daftarBuku as $buku): ?>
                        <tr>
                            <td><?= htmlspecialchars($buku['judul'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($buku['pengarang'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= (int) $buku['tahun'] ?></td>
                            <td><?= (int) $buku['stok'] ?></td>

                            <td>
                                <a class="btn-edit" href="edit.php?id=<?= (int) $buku['id'] ?>">
                                    Edit
                                </a>

                                <form class="form-hapus" method="post" action="hapus.php">
                                    <input type="hidden" name="id" value="<?= (int) $buku['id'] ?>">
                                    <button type="submit" class="btn-hapus">Hapus</button>
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
                <a href="?<?= http_build_query([
                    'cari' => $cari,
                    'halaman' => $i
                ]) ?>" <?= $i === $halaman ? 'aria-current="page"' : '' ?> class="<?= $i === $halaman ? 'active' : '' ?>">
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