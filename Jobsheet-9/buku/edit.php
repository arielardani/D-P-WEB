
<?php
require __DIR__ . '/../includes/koneksi.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    http_response_code(400);
    exit('ID buku tidak valid.');
}

$stmt = $pdo->prepare(
    "SELECT * FROM buku WHERE id = :id"
);
$stmt->execute(['id' => $id]);
$buku = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$buku) {
    http_response_code(404);
    exit('Data buku tidak ditemukan.');
}

$page_title = "Edit Buku";
include __DIR__ . '/../includes/header.php';

function h($value) {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}
?>

<section>
    <h2>Edit Buku</h2>

    <form method="post" action="proses_edit.php">
        <input
            type="hidden"
            name="id"
            value="<?php echo (int) $buku['id']; ?>"
        >

        <p>
            <label for="judul">Judul</label><br>
            <input
                type="text"
                id="judul"
                name="judul"
                value="<?php echo h($buku['judul']); ?>"
                required
            >
        </p>

        <p>
            <label for="pengarang">Pengarang</label><br>
            <input
                type="text"
                id="pengarang"
                name="pengarang"
                value="<?php echo h($buku['pengarang']); ?>"
                required
            >
        </p>

        <p>
            <label for="tahun">Tahun Terbit</label><br>
            <input
                type="number"
                id="tahun"
                name="tahun"
                min="1900"
                max="2026"
                value="<?php echo h($buku['tahun']); ?>"
                required
            >
        </p>

        <p>
            <label for="isbn">ISBN</label><br>
            <input
                type="text"
                id="isbn"
                name="isbn"
                value="<?php echo h($buku['isbn'] ?? ''); ?>"
            >
        </p>

        <p>
            <label for="stok">Stok</label><br>
            <input
                type="number"
                id="stok"
                name="stok"
                min="0"
                value="<?php echo h($buku['stok']); ?>"
                required
            >
        </p>

        <p>
            <label for="kategori">Kategori</label><br>
            <select id="kategori" name="kategori">
                <?php
                $kategoriList = [
                    'fiksi' => 'Fiksi',
                    'non-fiksi' => 'Non-Fiksi',
                    'referensi' => 'Referensi'
                ];

                foreach ($kategoriList as $nilai => $label):
                ?>
                    <option
                        value="<?php echo h($nilai); ?>"
                        <?php
                        echo $buku['kategori'] === $nilai
                            ? 'selected'
                            : '';
                        ?>
                    >
                        <?php echo h($label); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <button type="submit">Simpan Perubahan</button>
            <a href="list.php">Batal</a>
        </p>
    </form>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
